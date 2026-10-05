#!/usr/bin/env bash

set -euo pipefail

deploy_root="${1:?Deployment root is required}"
release_id="${2:?Release ID is required}"
php_bin="/opt/cpanel/ea-php81/root/usr/bin/php"
php_args=(-d memory_limit=256M)
archive="$deploy_root/incoming/$release_id.tar.gz"
release="$deploy_root/releases/$release_id"

test -x "$php_bin"
test -f "$archive"
test -f "$deploy_root/shared/.env"

mkdir -p \
  "$deploy_root/shared/storage/app/public" \
  "$deploy_root/shared/storage/framework/cache/data" \
  "$deploy_root/shared/storage/framework/sessions" \
  "$deploy_root/shared/storage/framework/views" \
  "$deploy_root/shared/storage/logs" \
  "$release/bootstrap/cache"

ln -s "$deploy_root/shared/.env" "$release/.env"
ln -s "$deploy_root/shared/storage" "$release/storage"
ln -sfn "$deploy_root/shared/storage/app/public" "$release/public/storage"

chmod -R ug+rwX "$deploy_root/shared/storage" "$release/bootstrap/cache" || true

cd "$release"
"$php_bin" "${php_args[@]}" artisan migrate --force
"$php_bin" "${php_args[@]}" artisan optimize
"$php_bin" "${php_args[@]}" artisan storage:link --force || true

ln -sfn "$release" "$deploy_root/current.next"
mv -Tf "$deploy_root/current.next" "$deploy_root/current"

"$php_bin" "${php_args[@]}" artisan queue:restart || true

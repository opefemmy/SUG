#!/bin/bash
MIGRATIONS_DIR="sug-portal/database/migrations"
for file in "$MIGRATIONS_DIR"/*.php; do
    echo "FILE: $file"
    # Find Schema::create calls and print the table name and the block following it
    sed -n "/Schema::create\(['\"]/,/});/p" "$file" | sed 's/^[[:space:]]*//'
    echo "--------------------------------------------------------------------------------"
done

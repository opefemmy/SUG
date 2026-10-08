# Implementation Plan: Configurable Receipt Signers

## Goal
Replace hardcoded "SUG Treasurer" and "SUG President" signers in the receipt PDF with configurable settings managed via the Admin UI.

## Proposed Changes

### 1. Admin Interface Update
Modify `resources\views\admin\settings\index.blade.php` to add two new settings keys in the `sug` configuration group.

- **Key**: `sug_receipt_signer_treasurer`
  - Label: `Receipt Treasurer Signer`
  - Placeholder: `e.g. SUG Treasurer`
- **Key**: `sug_receipt_signer_president`
  - Label: `Receipt President Signer`
  - Placeholder: `e.g. SUG President`

### 2. PDF View Update
Modify `resources\views\receipts\pdf.blade.php` to dynamically fetch the signer names from the `SettingsService`.

- Replace `SUG Treasurer` with:
  `{{ \App\Services\SettingsService::get('sug_receipt_signer_treasurer', 'SUG Treasurer') }}`
- Replace `SUG President` with:
  `{{ \App\Services\SettingsService::get('sug_receipt_signer_president', 'SUG President') }}`

## Step-by-Step Execution

1. **Update Admin Settings View**
   - Locate the `$defaultKeys` array in `resources\views\admin\settings\index.blade.php`.
   - Add `sug_receipt_signer_treasurer` and `sug_receipt_signer_president` to the `sug` group.

2. **Update Receipt PDF View**
   - Locate the `.signature-section` in `resources\views\receipts\pdf.blade.php`.
   - Update the `.sig-line` divs to use the `SettingsService::get` method.

3. **Verification**
   - Access the Admin Settings page and verify the new fields appear under "SUG Configuration".
   - Save new values for the signers.
   - Generate a receipt PDF and verify the updated signer names are displayed.

## Critical Files
- `resources\views\admin\settings\index.blade.php`
- `resources\views\receipts\pdf.blade.php`
- `app\Services\SettingsService.php` (Reference only)

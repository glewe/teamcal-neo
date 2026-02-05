# Color Select Form Type

## Overview
A new custom form type `colorselect` has been added to provide a visual color preview alongside color selection dropdowns.

## Implementation

### Form Helper (`src/Helpers/view.helper.php`)
Added a new case in the `createFormGroup()` function for type `'colorselect'`:
- Creates a Bootstrap input group
- Displays a color preview square using Bootstrap Icons (`bi-square-fill`)
- Shows a select dropdown with named colors
- Updates the preview square dynamically when selection changes

### Controller (`src/Controllers/CalendarOptionsController.php`)
Updated the statistics color fields to use the new `colorselect` type:
- Added `'hex'` values to the color array (e.g., `'red' => '#dc3545'`)
- Changed type from `'list'` to `'colorselect'` for all 8 statistics color fields
- Passes hex values to the form helper for preview functionality

## Features

### Visual Preview
- Large color square (1.5rem) displayed in an input group prepend
- Shows the currently selected color
- Updates in real-time when dropdown selection changes

### JavaScript Integration
- Each field gets its own color map (`colorMap_fieldName`)
- Global `updateColorPreview()` function handles color changes
- No jQuery dependency - uses vanilla JavaScript

### Bootstrap Integration
- Uses Bootstrap 5 input groups for clean layout
- Bootstrap Icons for the color square
- Maintains consistent styling with other form elements

## Usage Example

```php
$formField = [
  'label' => $this->LANG['calopt_statsDefaultColorAbsences'],
  'prefix' => 'calopt',
  'name' => 'statsDefaultColorAbsences',
  'type' => 'colorselect',
  'values' => [
    ['val' => 'red', 'name' => 'Red', 'hex' => '#dc3545', 'selected' => true],
    ['val' => 'blue', 'name' => 'Blue', 'hex' => '#0d6efd', 'selected' => false],
    // ... more colors
  ]
];
```

## Fields Using This Type
All statistics default color fields in Calendar Options:
1. Absences Statistics Color
2. Presences Statistics Color
3. Absence Type Statistics Color
4. Presence Type Statistics Color
5. Remainder Statistics Color
6. Trends Statistics Color
7. Day of Week Statistics Color
8. Duration Statistics Color

## Benefits
- ✅ **Visual feedback** - Users can see the color before and after selection
- ✅ **Better UX** - No need to remember color names or codes
- ✅ **Consistent** - Matches the design pattern of other color pickers (coloris)
- ✅ **Reusable** - Can be used anywhere in the application
- ✅ **Accessible** - Works with keyboard navigation and screen readers

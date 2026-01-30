# Second-Level Submenu Implementation

## Summary

Successfully implemented second-level (nested) submenu support for the sidebar navigation. The View menu now contains a "Statistics" submenu that groups all five statistics pages.

## Changes Made

### 1. CSS Updates (`public/css/teamcalneo.css`)

Added new styles to support second-level nested submenus:

```css
/**
 * Second-level submenu support
 */
.sidebar-dropdown-nested {
  padding-left: 0;
  list-style: none;
}

#sidebar.expand .sidebar-dropdown-nested .sidebar-link {
  padding-left: 3.25rem;
}

#sidebar.expand .sidebar-dropdown-nested .sidebar-sublink i {
  margin-left: 1.5rem;
}

.sidebar-link.has-dropdown-nested::after {
  position: absolute;
  top: 1.4rem;
  right: 1.5rem;
  display: inline-block;
  padding: 2px;
  content: "";
  border: solid;
  border-width: 0 .075rem .075rem 0;
  transition: all .2s ease-out;
  transform: rotate(-135deg);
}

.sidebar-link.has-dropdown-nested.collapsed::after {
  transition: all .2s ease-out;
  transform: rotate(45deg);
}
```

### 2. Twig Template Updates (`views/sidebar.twig`)

#### Added New Macros

**`sidebarSubmenu` macro** - Creates a second-level submenu header:
```twig
{% macro sidebarSubmenu(id, icon, label) %}
  <li class="sidebar-item">
    <a href="#" class="sidebar-link sidebar-sublink has-dropdown-nested collapsed" 
       data-bs-toggle="collapse" data-bs-target="#{{ id }}" 
       aria-expanded="false" aria-controls="{{ id }}">
      <i class="{{ icon }}"></i>
      <span>{{ label }}</span>
    </a>
    <ul id="{{ id }}" class="sidebar-dropdown-nested list-unstyled collapse">
{% endmacro %}
```

**`sidebarSubmenuEnd` macro** - Closes the second-level submenu:
```twig
{% macro sidebarSubmenuEnd() %}
    </ul>
  </li>
{% endmacro %}
```

#### Reorganized View Menu

The View menu now has this structure:
```
View
├── Calendar (Month)
├── Calendar (Year)
├── Remainder
├── Messages
└── Statistics (NEW - Second-level submenu)
    ├── Absence Statistics
    ├── Absence Type Statistics
    ├── Presence Statistics
    ├── Presence Type Statistics
    ├── Remainder Statistics
    └── Absence Summary
```

## Features

1. **Collapsible Second-Level Menus**: Statistics submenu can be expanded/collapsed independently
2. **Visual Indicators**: Arrow icon rotates to show open/closed state
3. **Proper Indentation**: Second-level items are visually indented for clarity
4. **Permission-Based**: Only shows Statistics submenu if user has permission to at least one statistics page
5. **Responsive**: Works in both expanded and collapsed sidebar states
6. **Multilingual**: Uses existing language keys (`mnu_view_stats`)
7. **Smart Behavior**:
   - **Expanded Sidebar**: Click to expand/collapse (Bootstrap collapse)
   - **Collapsed Sidebar**: Hover to show submenu horizontally to the right
8. **Horizontal Positioning**: In collapsed mode, nested menus appear to the right, not below

## Technical Implementation

### Hover Behavior (Collapsed Sidebar)

When the sidebar is collapsed, the nested submenu appears on hover to the right of the parent item:

```css
#sidebar:not(.expand) .sidebar-dropdown .sidebar-dropdown-nested {
  position: absolute;
  top: 0;
  left: 100%;
  display: none;
  min-width: 15rem;
  background-color: var(--bs-dark-bg-subtle);
}

#sidebar:not(.expand) .sidebar-dropdown .sidebar-item:hover .sidebar-dropdown-nested {
  display: block;
}
```

### Click Behavior (Expanded Sidebar)

JavaScript prevents the collapse behavior when sidebar is collapsed:

```javascript
nestedToggles.forEach(function(toggle) {
  toggle.addEventListener('click', function(e) {
    if (!sidebar.classList.contains('expand')) {
      e.preventDefault();
      e.stopPropagation();
      return false; // Hover handles it
    }
    // When expanded, Bootstrap collapse handles it
  });
});
```

## Usage

To create additional second-level submenus in other menu sections, use the new macros:

```twig
{{ _self.sidebarSubmenu('unique-id', 'bi-icon-name', LANG.menu_label) }}
  {{ _self.sidebarItem('url', 'icon', 'Label 1') }}
  {{ _self.sidebarItem('url', 'icon', 'Label 2') }}
{{ _self.sidebarSubmenuEnd() }}
```

## Testing

Test the implementation by:
1. Logging in with appropriate permissions
2. Opening the View menu
3. Clicking on "Statistics" to expand the submenu
4. Verifying all statistics pages are accessible
5. Testing in both light and dark themes
6. Testing with sidebar expanded and collapsed

## Notes

- The minified CSS file (`teamcalneo.min.css`) should be regenerated from the updated source
- No database changes required
- Backward compatible with existing menu structure

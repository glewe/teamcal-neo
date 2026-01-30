# Test Plan for TeamCal Neo

Test sets and cases for TeamCal Neo

## Test Set 1: Installation

* ✅ **Open Installation Page**: 
  * Condition 1: APP_INSTALLED is set to 0 in config.app.php
  * Condition 2: installation.php exists in root folder
  * ✅ **Expected result**: Installation page is displayed

* ✅ **Installation Error Message 1**: 
  * Condition 1: APP_INSTALLED is set to 1 in config.app.php
  * Condition 2: installation.php does exists in root folder
  * ✅ **Expected result**: Proper error message is displayed

* ✅ **Installation Error Message 2**: 
  * Condition 1: APP_INSTALLED is set to 0 in config.app.php
  * Condition 2: installation.php does NOT exists in root folder
  * ✅ **Expected result**: Proper error message is displayed

* ✅ **Run Installation**: 
  * ✅ **DB Connection**: Enter DB credentials and ensure DB connection is working
  * ✅ **Setup with Sample Data**: Run setup with sample data. Check DB for sample data.

## Test Set 2: Entities

* ✅ **Absence Types**: 
  * ✅ **View**: View the absence type list
  * ✅ **Add**: Add a new absence type
  * ✅ **Edit**: Edit an existing absence type
    * ✅ **Select Icon**: Select an icon for the absence type
  * ✅ **Delete**: Delete an existing absence type

* ✅ **Holidays**: 
  * ✅ **View**: View the holidays list
  * ✅ **Add**: Add a new holiday
  * ✅ **Edit**: Edit an existing holiday
  * ✅ **Delete**: Delete an existing holiday

* ✅ **Regions**: 
  * ✅ **View**: View the region list
  * ✅ **Add**: Add a new region
  * ✅ **Edit**: Edit an existing region
  * ✅ **Delete**: Delete an existing region
  * ✅ **Calendar**: Region month edit (check holidays are colored and radio buttons are checked)
  * ✅ **ICS Import**

* ✅ **Patterns**: 
  * ✅ **View**: View the pattern list
  * ✅ **Add**: Add a new pattern
  * ✅ **Edit**: Edit an existing pattern
  * ✅ **Delete**: Delete an existing pattern

* ✅ **Framework Settings**: 
  * ✅ **View**: View the pattern list
  * ✅ **Edit**: Edit an existing pattern

* ✅ **Users**: 
  * ✅ **View**: View the list
    * ✅ **Search by text**
    * ✅ **Filter by group**
    * ✅ **Filter by role**
    * ✅ **Remove secret of selected**
    * ✅ **Reset password of selected**: Check mail out
    * ✅ **Archive selected**
    * ✅ **Restore selected**
    * ✅ **Activate selected**
  * ✅ **Add**: Add a new
  * ✅ **Edit**: Edit existing
  * ✅ **Delete**: Delete existing
  * ✅ **CSV Import**

* ✅ **Groups**: 
  * ✅ **View**: View the list
  * ✅ **Add**: Add a new
  * ✅ **Edit**: Edit existing
  * ✅ **Calendar**: Group month edit
  * ✅ **Delete**: Delete existing

* ✅ **Roles**: 
  * ✅ **View**: View the list
  * ✅ **Add**: Add a new
  * ✅ **Edit**: Edit existing
  * ✅ **Delete**: Delete existing

* ✅ **Attachments**: 
  * ✅ **View**: View the list
  * ✅ **Upload**: Add a new
  * ✅ **Download/View**: Edit existing
  * ✅ **Delete**: Delete existing

* ✅ **Messages**: 
  * ✅ **View**: View the list
  * ✅ **Add**: Add a new
  * ✅ **Confirm**: Edit existing
  * ✅ **Delete**: Delete existing

## Test Set 3: Administration

* ✅ **System Settings**: 
  * ✅ **View**: View the list
  * ✅ **Edit**: Add a new

* ✅ **Calendar Options**: 
  * ✅ **View**: View the list
  * ✅ **Edit**: Add a new

* ✅ **Database Management**: 
  * ✅ **Optimize Tables**
  * ✅ **Clean Up**
  * ✅ **Repair**
  * ✅ **Delete Records**
  * ✅ **Administration**
  * ✅ **Reset Database**
  * ✅ **Database Information**

* ✅ **System Log**: 
  * ✅ **View**
  * ✅ **Filter, Reset**
  * ✅ **Delete Period**
  * ✅ **Log Settings**

* ✅ **PHP Info**

* ✅ **Permissions**: 
  * ✅ **View**
  * ✅ **Edit**

## Test Set 4: Core Pages

* ✅ **Imprint**:
  * ✅ **View**

* ✅ **Data Privacy Policy**: 
  * ✅ **Config (in System Settings)**
  * ✅ **View**

* ✅ **About Page**: 
  * ✅ **View**
  * ✅ **Release notes**
  * ✅ **Version compare**

## Test Set 5: Calendar Features

* ✅ **Allowances**: 
  * ✅ **View**
  * ✅ **Update**

* ✅ **Declination**: 
  * ✅ **View**
  * ✅ **Update**

* ✅ **Calendar Month**: 
  * ✅ **View**
  * ✅ **Page menu button functions**
  * ✅ **Daynotes**
  * ✅ **Summary row**
  * ✅ **Mobile Device Support**

* ✅ **Calendar Month Edit**: 
  * ✅ **View**
  * ✅ **Page menu button functions**
  * ✅ **Daynotes**

* ✅ **Calendar Year**: 
  * ✅ **View**

* ✅ **Remainder**: 
  * ✅ **View**

* ✅ **Statistics Pages**: 
  * ✅ **Absence Statistics**
  * ✅ **Absence Type Statistics**
  * ✅ **Presence Statistics**
  * ✅ **Presence Type Statistics**: new page
  * ✅ **Remainder Statistics**
  * ✅ **Absence Summary**

* ✅ **Functional Tests**: 
  * ✅ **Enter absences**: Enter absences as normal user (decline, not available for group, etc.)

* 🚧 **Xxx Tests**: 
  * ⬜ **Xxx**:

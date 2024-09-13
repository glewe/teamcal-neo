<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * View Helper Functions
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */

//-----------------------------------------------------------------------------
/**
 * Creates an alert box with the specified data and optionally includes a script to auto-close the alert.
 *
 * @param array $data An associative array containing the alert data:
 *  - 'type': The type of alert (e.g., 'danger', 'success', 'warning').
 *  - 'title': The title of the alert.
 *  - 'subject': The subject of the alert.
 *  - 'text': The main text of the alert.
 *  - 'help' (optional): Additional help text to display in the alert.
 *
 * @return string The HTML string for the alert box, including a script for auto-closing if applicable.
 * @global array $LANG Language array for localization.
 *
 * @global object $C Configuration object to read settings.
 */
function createAlertBox($data) {
  global $C, $LANG;

  $html = '
    <div class="alert alert-dismissible alert-' . $data['type'] . ' fade show" role="alert">
      <button type="button" class="btn-close float-end" data-bs-dismiss="alert" title="' . $LANG['close_this_message'] . '"></button>
      <h5>' . $data['title'] . '</h5>
      <hr>
      <p><strong>' . $data['subject'] . '</strong></p>
      <p>' . $data['text'] . '</p>
      ' . (isset($data['help']) ? "<p><i>" . $data['help'] . "</i></p>" : "") . '
    </div>';

  if (
    $data['type'] === 'danger' && $C->read('alertAutocloseDanger') ||
    $data['type'] === 'success' && $C->read('alertAutocloseSuccess') ||
    $data['type'] === 'warning' && $C->read('alertAutocloseWarning')
  ) {
    $delay = (int)$C->read('alertAutocloseDelay');
    $html .= '
      <script>
        setTimeout(function() {
          $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
            $(".alert-dismissible").alert("close");
          });
        }, ' . $delay . ');
      </script>';
  }
  return $html;
}

//-----------------------------------------------------------------------------
/**
 * Creates a Font Awesome icon listbox.
 *
 * This function generates an HTML select element populated with Font Awesome icons.
 *
 * @param string $tabIndex The tabindex attribute for the select element. Default is "-1".
 * @param string $selected The icon that should be selected by default. Default is an empty string.
 *
 * @return string The HTML string for the Font Awesome icon listbox.
 * @global array $faIcons An array of available Font Awesome icons.
 *
 */
function createFaIconListbox($tabIndex = "-1", $selected = "") {
  global $faIcons;
  $listbox = '<select id="faIcon" class="form-select" name="sel_faIcon" tabindex="' . $tabIndex . '">';
  foreach ($faIcons as $faIcon) {
    if ($faIcon == $selected) {
      $sel = ' selected="selected"';
    } else {
      $sel = "";
    }
    $listbox .= '<option value="' . $faIcon . '"' . $sel . '>' . proper($faIcon) . '</option>';
  }
  $listbox .= '</select>';
  return $listbox;
}

//-----------------------------------------------------------------------------
/**
 * Creates a form group with various input types based on the provided data.
 *
 * @param array $data An associative array containing the form group data:
 *  - 'prefix': The prefix for the form group.
 *  - 'name': The name of the form group.
 *  - 'type': The type of the form group (e.g., 'check', 'color', 'date', 'info', 'list', 'password', 'radio', 'text', 'textarea', 'ckeditor').
 *  - 'value': The value of the form group.
 *  - 'maxlength' (optional): The maximum length for text inputs.
 *  - 'placeholder' (optional): The placeholder text for text inputs.
 *  - 'rows' (optional): The number of rows for textarea inputs.
 *  - 'action' (optional): An associative array containing action button data:
 *    - 'name': The name of the action button.
 *    - 'target': The target URL for the action button.
 *  - 'disabled' (optional): A boolean indicating if the form group should be disabled.
 *  - 'mandatory' (optional): A boolean indicating if the form group is mandatory.
 *  - 'error' (optional): An error message to display.
 *  - 'values' (optional): An array of values for select and radio inputs.
 *  - 'imagelist' (optional): A boolean indicating if the select list should display images.
 *  - 'imagedir' (optional): The directory for images in the select list.
 *
 * @param int $colsleft The number of columns for the left part of the form group.
 * @param int $colsright The number of columns for the right part of the form group.
 * @param int $tabindex The tabindex attribute for the form group inputs.
 *
 * @return string The HTML string for the form group.
 * @global array $LANG Language array for localization.
 *
 */
function createFormGroup($data, $colsleft, $colsright, $tabindex) {
  global $LANG;
  $langIdx1 = $data['prefix'] . '_' . $data['name'];
  $langIdx2 = $data['prefix'] . '_' . $data['name'] . '_comment';
  $button = '';
  if (isset($data['action']) && !empty($data['action'])) {
    $name = 'btn_' . $data['action']['name'];
    $target = $data['action']['target'];
    $button = '<button type="button" class="btn btn-primary btn-sm" style="margin-top: 8px;" tabindex="' . ($tabindex + 1) . '" name="' . $name . '" onclick="window.location=\'' . $target . '\';">' . $LANG[$name] . '</button>';
  }

  $disabled = '';
  if (isset($data['disabled']) && $data['disabled']) {
    $disabled = ' disabled="disabled"';
  }

  $mandatory = '';
  if (isset($data['mandatory']) && $data['mandatory']) {
    $mandatory = '<i class="text-danger">*</i> ';
  }

  $error = '';
  if (isset($data["error"]) && strlen($data["error"])) {
    $error = '<br><div class="alert alert-dismissible alert-danger fade show"><button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>' . $data['error'] . '</div>';
  }

  switch ($data['type']) {
    /**
     * Checkbox
     */
    case 'check':
      $formGroup = '
        <div class="form-group row" id="form-group-' . $data['name'] . '">
          <label for="' . $data['name'] . '" class="col-lg-' . $colsleft . ' control-label">
          ' . $mandatory . $LANG[$langIdx1] . '<br>
          <span class="text-normal">' . $LANG[$langIdx2] . '</span>
          </label>
          <div class="col-lg-' . $colsright . '">
          <div class="form-check">
              <input class="form-check-input" type="checkbox" id="' . $data['name'] . '" name="chk_' . $data['name'] . '" value="chk_' . $data['name'] . '"' . ((intval($data['value'])) ? " checked" : "") . ' tabindex="' . $tabindex . '"' . $disabled . '>
              <label class="form-check-label">' . $LANG[$langIdx1] . '</label>
          </div>
          ' . $button . $error . '</div>
        </div>
        <div class="divider"><hr></div>';
      break;

    /**
     * Color selection text field
     */
    case 'color':
      $formGroup = '
        <div class="form-group row" id="form-group-' . $data['name'] . '">
          <label for="' . $data['name'] . '" class="col-lg-' . $colsleft . ' control-label">
          ' . $mandatory . $LANG[$langIdx1] . '<br>
          <span class="text-normal">' . $LANG[$langIdx2] . '</span>
          </label>
          <div class="col-lg-' . $colsright . '">
          <input id="' . $data['name'] . '" class="form-control" tabindex="' . $tabindex . '" name="txt_' . $data['name'] . '" type="text" maxlength="6" value="' . $data['value'] . '"' . $disabled . '>
          ' . $button . $error . '</div>
          <script>$(function() { $( "#' . $data['name'] . '" ).ColorPicker({ onSubmit: function(hsb, hex, rgb, el) { $(el).val(hex.toUpperCase()); $(el).ColorPickerHide(); }, onBeforeShow: function () { $(this).ColorPickerSetColor(this.value); } }) .bind(\'keyup\', function(){ $(this).ColorPickerSetColor(this.value); }); });</script>
        </div>
        <div class="divider"><hr></div>';
      break;

    /**
     * Date selection text field
     */
    case 'date':
      $formGroup = '
        <div class="form-group row" id="form-group-' . $data['name'] . '">
          <label for="' . $data['name'] . '" class="col-lg-' . $colsleft . ' control-label">
          ' . $mandatory . $LANG[$langIdx1] . '<br>
          <span class="text-normal">' . $LANG[$langIdx2] . '</span>
          </label>
          <div class="col-lg-' . $colsright . '">
          <input id="' . $data['name'] . '" class="form-control" tabindex="' . $tabindex . '" name="txt_' . $data['name'] . '" type="text" maxlength="10" value="' . $data['value'] . '"' . $disabled . '>
          ' . $button . $error . '</div>
          <script>$(function() { $( "#' . $data['name'] . '" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });</script>
        </div>
        <div class="divider"><hr></div>';
      break;

    /**
     * Info field
     */
    case 'info':
      $formGroup = '
        <div class="form-group row" id="form-group-' . $data['name'] . '">
          <label for="' . $data['name'] . '" class="col-lg-' . $colsleft . ' control-label">
          ' . $mandatory . $LANG[$langIdx1] . '<br>
          <span class="text-normal">' . $LANG[$langIdx2] . '</span>
          </label>
          <div class="col-lg-' . $colsright . '">
            <div id="' . $data['name'] . '">' . $data['value'] . '</div>
          </div>
        </div>
        <div class="divider"><hr></div>';
      break;

    /**
     * Info field (wide)
     */
    case 'infoWide':
      $formGroup = '
        <div class="form-group row" id="form-group-' . $data['name'] . '">
          <label for="' . $data['name'] . '" class="col-lg-12 control-label">
          ' . $mandatory . $LANG[$langIdx1] . '<br>
          <span class="text-normal">' . $LANG[$langIdx2] . '</span>
          </label>
        </div>
        <div class="divider"><hr></div>';
      break;

    /**
     * Single select list
     */
    case 'list':
      $style = '';
      $formGroup = '
        <div class="form-group row" id="form-group-' . $data['name'] . '">
          <label for="' . $data['name'] . '" class="col-lg-' . $colsleft . ' control-label">
          ' . $mandatory . $LANG[$langIdx1] . '<br>
          <span class="text-normal">' . $LANG[$langIdx2] . '</span>
          </label>
          <div class="col-lg-' . $colsright . '">
          <select id="' . $data['name'] . '" class="form-select" name="sel_' . $data['name'] . '" tabindex="' . $tabindex . '"' . $disabled . '>' . "\r\n";
      foreach ($data['values'] as $val) {
        if (isset($data['imagelist']) && $data['imagelist'] && isset($data['imagedir'])) {
          $style = $style = 'style="background-image: url(\'' . $data['imagedir'] . '/' . $val['val'] . '\'); background-size: 16px 16px; background-repeat: no-repeat; padding-left: 20px;"';
        }
        $formGroup .= '<option ' . $style . ' value="' . $val['val'] . '"' . (($val['selected']) ? " selected=\"selected\"" : "") . '>' . $val['name'] . '</option>' . "\r\n";
      }
      $formGroup .= '</select>
          ' . $button . $error . '</div>
        </div>
        <div class="divider"><hr></div>';
      break;

    /**
     * Multi select list
     */
    case 'listmulti':
      $formGroup = '
        <div class="form-group row" id="form-group-' . $data['name'] . '">
          <label for="' . $data['name'] . '" class="col-lg-' . $colsleft . ' control-label">
          ' . $mandatory . $LANG[$langIdx1] . '<br>
          <span class="text-normal">' . $LANG[$langIdx2] . '</span>
          </label>
          <div class="col-lg-' . $colsright . '">
          <select id="' . $data['name'] . '" class="form-select" name="sel_' . $data['name'] . '[]" tabindex="' . $tabindex . '" multiple="multiple" size="10"' . $disabled . '>' . "\r\n";
      foreach ($data['values'] as $val) {
        $formGroup .= '<option value="' . $val['val'] . '"' . (($val['selected']) ? " selected=\"selected\"" : "") . '>' . $val['name'] . '</option>' . "\r\n";
      }
      $formGroup .= '</select>
          ' . $button . $error . '</div>
        </div>
        <div class="divider"><hr></div>';
      break;

    /**
     * Password text field
     */
    case 'password':
      $formGroup = '
        <div class="form-group row" id="form-group-' . $data['name'] . '">
          <label for="' . $data['name'] . '" class="col-lg-' . $colsleft . ' control-label">
          ' . $mandatory . $LANG[$langIdx1] . '<br>
          <span class="text-normal">' . $LANG[$langIdx2] . '</span>
          </label>
          <div class="col-lg-' . $colsright . '">
          <input id="' . $data['name'] . '" class="form-control" tabindex="' . $tabindex . '" name="txt_' . $data['name'] . '" type="password" maxlength="' . $data['maxlength'] . '" value="' . $data['value'] . '" placeholder="' . $LANG['enter_password'] . '" autocomplete="new-password"' . $disabled . '>
          ' . $button . $error . '</div>
        </div>
        <div class="divider"><hr></div>';
      break;

    /**
     * Radio box
     */
    case 'radio':
      $formGroup = '
        <div class="form-group row" id="form-group-' . $data['name'] . '">
          <label class="col-lg-' . $colsleft . ' control-label">
          ' . $mandatory . $LANG[$langIdx1] . '<br>
          <span class="text-normal">' . $LANG[$langIdx2] . '</span>
          </label>
          <div class="col-lg-' . $colsright . '">';
      foreach ($data['values'] as $val) {
        $langIdx3 = $data['prefix'] . '_' . $data['name'] . '_' . $val;
        $formGroup .= '<div class="radio">';
        $formGroup .= '<label><input name="opt_' . $data['name'] . '" value="' . $val . '" tabindex="' . $tabindex . '" type="radio"' . (($val == $data['value']) ? " checked" : "") . $disabled . '>' . $LANG[$langIdx3] . '</label>';
        $formGroup .= '</div>';
      }
      $formGroup .= $button . $error . '</div>
        </div>
        <div class="divider"><hr></div>';
      break;

    /**
     * Securimage
     */
    case 'securimage':
      $langIdx3 = $data['prefix'] . '_' . $data['name'] . '_new';
      $formGroup = '
        <div class="form-group row" id="form-group-' . $data['name'] . '">
          <label for="' . $data['name'] . '" class="col-lg-' . $colsleft . ' control-label">
          ' . $mandatory . $LANG[$langIdx1] . '<br>
          <span class="text-normal">' . $LANG[$langIdx2] . '</span>
          </label>
          <div class="col-lg-' . $colsright . '">
          <img id="captcha" src="addons/securimage/securimage_show.php" alt="CAPTCHA Image"><br>
          [<a href="#" onclick="document.getElementById(\'captcha\').src = \'addons/securimage/securimage_show.php?\' + Math.random(); return false">' . $LANG[$langIdx3] . '</a>]
          <input id="' . $data['name'] . '" class="form-control" tabindex="' . $tabindex . '" name="txt_' . $data['name'] . '" placeholder="Code"  value="' . $data['value'] . '" type="text" maxlength="' . $data['maxlength'] . '"' . $disabled . '>
          ' . $button . $error . '</div>
        </div>
        <div class="divider"><hr></div>';
      break;

    /**
     * Text field
     */
    case 'text':
      $formGroup = '
        <div class="form-group row" id="form-group-' . $data['name'] . '">
          <label for="' . $data['name'] . '" class="col-lg-' . $colsleft . ' control-label">
          ' . $mandatory . $LANG[$langIdx1] . '<br>
          <span class="text-normal">' . $LANG[$langIdx2] . '</span>
          </label>
          <div class="col-lg-' . $colsright . '">
          <input id="' . $data['name'] . '" class="form-control" tabindex="' . $tabindex . '" name="txt_' . $data['name'] . '" type="text" maxlength="' . $data['maxlength'] . '" value="' . $data['value'] . '" placeholder="' . $data['placeholder'] . '"' . $disabled . '>
          ' . $button . $error . '</div>
        </div>
        <div class="divider"><hr></div>';
      break;

    /**
     * Text Long
     * Textbox will appear underneath the label in full width.
     */
    case 'textlong':
      $formGroup = '
        <div class="form-group row" id="form-group-' . $data['name'] . '">
          <label for="' . $data['name'] . '" class="col-lg-12 control-label">
          ' . $mandatory . $LANG[$langIdx1] . '<br>
          <span class="text-normal">' . $LANG[$langIdx2] . '</span>
          </label>
          <div class="col-lg-12">
          <input id="' . $data['name'] . '" class="form-control" tabindex="' . $tabindex . '" name="txt_' . $data['name'] . '" type="text" maxlength="' . $data['maxlength'] . '" value="' . $data['value'] . '" placeholder="' . $data['placeholder'] . '"' . $disabled . '>
          ' . $button . $error . '</div>
        </div>
        <div class="divider"><hr></div>';
      break;

    /**
     * Textarea
     */
    case 'textarea':
      $formGroup = '
        <div class="form-group row" id="form-group-' . $data['name'] . '">
          <label for="' . $data['name'] . '" class="col-lg-' . $colsleft . ' control-label">
          ' . $mandatory . $LANG[$langIdx1] . '<br>
          <span class="text-normal">' . $LANG[$langIdx2] . '</span>
          </label>
          <div class="col-lg-' . $colsright . '">
          <textarea id="' . $data['name'] . '" class="form-control" tabindex="' . $tabindex . '" name="txt_' . $data['name'] . '" rows="' . $data['rows'] . '" placeholder="' . $data['placeholder'] . '" ' . $disabled . '>' . $data['value'] . '</textarea>
          ' . $button . $error . '</div>
        </div>
        <div class="divider"><hr></div>';
      break;

    /**
     * Textarea CKEditor
     */
    case 'ckeditor':
      $formGroup = '
        <div class="form-group" id="form-group-' . $data['name'] . '">
          <label for="' . $data['name'] . '" class="col-lg-12 control-label">
          ' . $mandatory . $LANG[$langIdx1] . '<br>
          <span class="text-normal">' . $LANG[$langIdx2] . '</span>
          </label>
          <div class="col-lg-12">
          <textarea id="' . $data['name'] . '" class="form-control" tabindex="' . $tabindex . '" name="txt_' . $data['name'] . '" rows="' . $data['rows'] . '"' . $disabled . '>' . $data['value'] . '</textarea>
          <script>CKEDITOR.replace( "' . $data['name'] . '" );</script>
          ' . $button . $error . '</div>
        </div>
        <div class="divider"><hr></div>';
      break;

    /**
     * Default
     */
    default:
      $formGroup = '
        <div class="form-group row" id="form-group-unknown">
          <label for="form-group-unknown" class="col-lg-' . $colsleft . ' control-label">
          Unknown<br>
          <span class="text-normal">Form group could not be built.</span>
          </label>
          <div class="col-lg-' . $colsright . '"></div>
        </div>
        <div class="divider"><hr></div>';
      break;
  }
  return $formGroup;
}

//-----------------------------------------------------------------------------
/**
 * Creates the top part of a modal dialog
 *
 * @param string $id ID of the modal dialog
 * @param string $title Title of the modal dialog
 */
function createModalTop($id, $title, $size = '') {
  switch ($size) {
    case 'sm':
      $size = 'modal-sm';
      break;
    case 'lg':
      $size = 'modal-lg';
      break;
    case 'xl':
      $size = 'modal-xl';
      break;
    default:
      $size = '';
  }
  return '
    <div class="modal fade" id="' . $id . '" tabindex="-1" role="dialog" aria-labelledby="' . $id . 'Label" aria-hidden="true">
      <div class="modal-dialog ' . $size . '" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">' . $title . '</h5>
          <button id="' . $id . 'Label" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-start">';
}

//-----------------------------------------------------------------------------
/**
 * Creates the bottom part of a modal dialog
 *
 * @param array $data Array of parameters defining the form-group type and content
 */
function createModalBottom($buttonID = '', $buttonColor = '', $buttonText = '') {
  global $LANG;
  $modalbottom = '
    </div>
    <div class="modal-footer">';

  if (strlen($buttonID)) {
    $modalbottom .= '        <button type="submit" class="btn btn-' . $buttonColor . '" name="' . $buttonID . '" style="margin-top: 4px;">' . $buttonText . '</button>';
  }
  $modalbottom .= '        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $LANG['btn_cancel'] . '</button>
        </div>
      </div>
      </div>
    </div>';
  return $modalbottom;
}

//-----------------------------------------------------------------------------
/**
 * Creates the tabs for the top of dialog pages
 *
 * @param array $tabs Array of tab details
 */
function createPageTabs($tabs) {
  global $LANG;
  $tabsHtml = '<ul class="nav nav-tabs card-header-tabs" id="dialogTabs" role="tablist">';
  foreach ($tabs as $tab) {
    if ($tab['active']) {
      $tabsHtml .= '<li class="nav-item" role="presentation"><a class="nav-link active" id="solid-tab" href="' . $tab['href'] . '" data-bs-toggle="tab" role="tab" aria-controls="solid" aria-selected="true">' . $tab['label'] . '</a></li>';
    } else {
      $tabsHtml .= '<li class="nav-item" role="presentation"><a class="nav-link" id="solid-tab" href="' . $tab['href'] . '" data-bs-toggle="tab" role="tab" aria-controls="solid" aria-selected="false">' . $tab['label'] . '</a></li>';
    }
  }
  $tabsHtml .= '</ul>';
  return $tabsHtml;
}

//-----------------------------------------------------------------------------
/**
 * Creates a pattern table (showing weekdays and absences)
 *
 * @param int $patternId ID of the pattern record
 */
function createPatternTable($patternId) {
  global $A, $C, $LANG;
  $PTN = new Patterns();
  $PTN->get($patternId);
  $html = '
  <table class="table table-bordered month mb-0">
    <tr>
      <th class="m-weekday text-center" scope="col">' . $LANG['weekdayShort'][1] . '</th>
      <th class="m-weekday text-center" scope="col">' . $LANG['weekdayShort'][2] . '</th>
      <th class="m-weekday text-center" scope="col">' . $LANG['weekdayShort'][3] . '</th>
      <th class="m-weekday text-center" scope="col">' . $LANG['weekdayShort'][4] . '</th>
      <th class="m-weekday text-center" scope="col">' . $LANG['weekdayShort'][5] . '</th>
      <th class="m-weekday text-center" scope="col" style="color:#000000;background-color:#fcfc9a;">' . $LANG['weekdayShort'][6] . '</th>
      <th class="m-weekday text-center" scope="col" style="color:#000000;background-color:#fcfc9a;">' . $LANG['weekdayShort'][7] . '</th>
    </tr>
    <tr>
  ';

  for ($i = 1; $i <= 7; $i++) {
    $prop = 'abs' . $i;
    $absId = $PTN->$prop;
    if ($A->getBgTrans($absId)) {
      $bgStyle = "";
    } else {
      $bgStyle = "background-color: #" . ($A->getBgColor($absId) ? $A->getBgColor($absId) : 'ffffff') . ";";
    }
    $style = 'color: #' . $A->getColor($absId) . ';' . $bgStyle;
    if ($C->read('symbolAsIcon')) {
      $icon = $A->getSymbol($absId);
    } else {
      $icon = '<span class="' . $A->getIcon($absId) . '"></span>';
    }

    $html .= '
    <td class="text-center" style="' . $style . '">
      <span data-bs-custom-class="dark" data-bs-placement="top" data-bs-toggle="tooltip" title="' . $A->getName($absId) . '">' . $icon . '
    </td>';
  }

  $html .= '
    </tr>
  </table>';

  return $html;
}

//-----------------------------------------------------------------------------
/**
 * Creates the Bootstrap toast
 *
 * @param array $data Array of toast details
 */
function createToast($data) {
  global $LANG;
  $classColor = '';
  if (strlen($data['color'])) {
    $classColor = 'text-bg-' . $data['color'];
  }

  return '
  <div id="' . $data['id'] . '" class="toast ' . $classColor . '" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="6000">
    <div class="toast-header">
      <i class="' . $data['icon'] . ' me-2"></i>
      <strong class="me-auto">' . $data['title'] . '</strong>
      <small>' . date("Y-m-d H:m", time()) . '</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      ' . $data['message'] . '
    </div>
  </div>';
}

//-----------------------------------------------------------------------------
/**
 * Returns a tooltip span element with a Font Awesome icon
 *
 * @param string $type BS color code (info,success,warning,danger) (Default: info)
 * @param string $icon Font Awesome icon to use (Default: question-circle)
 * @param string $position Tooltip position (top,right,bottom,left) (Default: top)
 * @param string $text Tooltip text (HTML allowed)
 *
 * @return string
 */
function iconTooltip($text = 'Tooltip text', $title = '', $position = 'top', $type = 'info', $icon = 'question-circle') {
  if (strlen($title)) {
    $ttText = " < div class='text-bold' style = 'padding-top: 4px; padding-bottom: 4px' > " . $title . "</div > ";
  }
  $ttText .= "<div class='text-normal' > " . $text . "</div > ";
  return '<span data-placement="' . $position . '" data-type="' . $type . ' fas fa - ' . $icon . ' text - ' . $type . '" data-bs-toggle="tooltip" title="' . $ttText . '"></span>';
}

<?php
if (!defined('VALID_ROOT')) {
  exit('');
}

/**
 * XML
 *
 * This class provides methods and properties for XML handling.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Application Framework
 * @since 3.0.0
 */
class XML {
  public $header;
  public $startTag;
  public $endTag;
  public $body;

  //---------------------------------------------------------------------------
  /**
   * Constructor. Creates the start and end tag.
   *
   * @param string $tablename Name of MySQL table to parse
   */
  public function __construct($tablename) {
    $this->startTag = "<Table Name=\"" . $tablename . "\">";
    $this->endTag = "</Table>\n";
  }

  //---------------------------------------------------------------------------
  /**
   * Adds an element to the XML ouput based on a given MySQL query result handle
   *
   * @param array $row Single MySQL query result row
   * @param integer MySQL query result handle
   */
  public function addElement($row, $rows) {
    $out = "\t<DataRow>\n";
    for ($i = 0; $i < mysql_num_fields($rows); $i++) {
      $meta = mysql_fetch_field($rows, $i);
      if ($meta->name == "password") {
        $out = $out . "\t\t<DataField Name=\"" . $meta->name . "\" Type=\"" . $meta->type . "\">********</DataField>\n";
      } else {
        $out = $out . "\t\t<DataField Name=\"" . $meta->name . "\" Type=\"" . $meta->type . "\">" . htmlspecialchars($row[$i]) . "</DataField>\n";
      }
    }
    $out = $out . "\t</DataRow>\n";
    $this->body = $this->body . $out;
  }

  //---------------------------------------------------------------------------
  /**
   * Returns the XML text
   *
   * @return string XML text
   */
  public function getXMLDocument() {
    return $this->header . $this->startTag . "\n" . $this->body . $this->endTag;
  }
}

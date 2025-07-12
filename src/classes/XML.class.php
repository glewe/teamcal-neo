<?php

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
 * @since 3.0.0
 */
class XML {
  public string $header = '';
  public string $startTag = '';
  public string $endTag = '';
  public string $body = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor. Creates the start and end tag.
   *
   * @param string $tablename Name of MySQL table to parse
   */
  public function __construct(string $tablename) {
    $this->startTag = "<Table Name=\"" . htmlspecialchars($tablename) . "\">";
    $this->endTag = "</Table>\n";
  }

  //---------------------------------------------------------------------------
  /**
   * Adds an element to the XML output based on a given row and field metadata
   *
   * @param array $row Associative array representing a single row
   * @param array $fields Array of field metadata (each item: ['name' => ..., 'type' => ...])
   */
  public function addElement(array $row, array $fields): void {
    $out = "\t<DataRow>\n";
    foreach ($fields as $field) {
      $name = $field['name'];
      $type = $field['type'] ?? '';
      $value = isset($row[$name]) ? $row[$name] : '';
      if ($name === 'password') {
        $out .= "\t\t<DataField Name=\"$name\" Type=\"$type\">********</DataField>\n";
      } else {
        $out .= "\t\t<DataField Name=\"$name\" Type=\"$type\">" . htmlspecialchars((string)$value) . "</DataField>\n";
      }
    }
    $out .= "\t</DataRow>\n";
    $this->body .= $out;
  }

  //---------------------------------------------------------------------------
  /**
   * Returns the XML text
   *
   * @return string XML text
   */
  public function getXMLDocument(): string {
    return $this->header . $this->startTag . "\n" . $this->body . $this->endTag;
  }
}

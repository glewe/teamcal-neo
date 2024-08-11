<?php
require_once 'PDODb.php';

/**
 * AbsenceGroup
 *
 * This class provides methods and properties for attachments.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class Attachment {
  private $db = '';
  private $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   *
   * Initializes the Attachment class by setting up the database connection
   * and defining the table name for attachments.
   */
  public function __construct() {
    global $CONF;
    $this->db = new PDODb([
      'driver' => $CONF['db_driver'],
      'host' => $CONF['db_server'],
      'port' => $CONF['db_port'],
      'dbname' => $CONF['db_name'],
      'username' => $CONF['db_user'],
      'password' => $CONF['db_password'],
      'charset' => $CONF['db_charset'],
    ]);
    $this->table = $CONF['db_table_attachments'];
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a record
   *
   * This method inserts a new record into the attachments table with the given filename and uploader.
   * If the insertion is successful, it returns the ID of the newly inserted record.
   * Otherwise, it returns the result of the insertion attempt.
   *
   * @param string $filename File name
   * @param string $uploader Uploader username
   * @return mixed Returns the ID of the newly inserted record on success, or the result of the insertion attempt on failure.
   */
  public function create($filename, $uploader) {
    $data = [
      'id' => null,
      'filename' => $filename,
      'uploader' => $uploader
    ];
    $result = $this->db->insert($this->table, $data)->run();
    if ($result) {
      return $this->db->lastInsertId();
    } else {
      return $result;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes all records
   *
   * This method deletes all records from the attachments table.
   *
   * @return boolean Query result
   */
  public function deleteAll() {
    return $this->db->delete($this->table)->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a record by filename
   *
   * This method deletes a record from the attachments table based on the provided filename.
   * If the filename is not provided, it returns false.
   *
   * @param string $filename File name to delete
   * @return boolean Query result
   */
  public function delete($filename) {
    if (isset($filename)) {
      return $this->db->delete($this->table)
        ->where('filename', '=', $filename)
        ->run();
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a record by ID
   *
   * This method deletes a record from the attachments table based on the provided ID.
   * If the ID is not provided, it returns false.
   *
   * @param string $id Record ID to delete
   * @return boolean Query result
   */
  public function deleteById($id) {
    if (isset($filename)) {
      return $this->db->delete($this->table)
        ->where('id', '=', $id)
        ->run();
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Retrieves all records
   *
   * This method retrieves all records from the attachments table,
   * ordered by the filename in ascending order.
   *
   * @return array List of all records
   */
  public function getAll() {
    return $this->db->select($this->table)->orderBy('filename', 'asc')->run();
  }

  //---------------------------------------------------------------------------
  /**
   * Retrieves the ID of a record by filename
   *
   * This method retrieves the ID of a record from the attachments table based on the provided filename.
   * If the filename is found, it returns the ID of the record.
   * Otherwise, it returns false.
   *
   * @param string $filename File name to find
   * @return mixed Returns the ID of the record on success, or false if the filename is not found.
   */
  public function getId($filename) {
    $row = $this->db->select($this->table, [ 'id' ])->where('filename', '=', $filename)->first()->run();
    if ($row) {
      return $row['id'];
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Retrieves the uploader of a record by filename
   *
   * This method retrieves the uploader of a record from the attachments table based on the provided filename.
   * If the filename is found, it returns the uploader of the record.
   * Otherwise, it returns false.
   *
   * @param string $filename File name to find
   * @return mixed Returns the uploader of the record on success, or false if the filename is not found.
   */
  public function getUploader($filename) {
    $row = $this->db->select($this->table, [ 'uploader' ])->where('filename', '=', $filename)->first()->run();
    if ($row) {
      return $row['uploader'];
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Retrieves the uploader of a record by ID
   *
   * This method retrieves the uploader of a record from the attachments table based on the provided ID.
   * If the ID is found, it returns the uploader of the record.
   * Otherwise, it returns false.
   *
   * @param string $id Record ID to find
   * @return mixed Returns the uploader of the record on success, or false if the ID is not found.
   */
  public function getUploaderById($id) {
    $row = $this->db->select($this->table, [ 'uploader' ])->where('id', '=', $id)->first()->run();
    if ($row) {
      return $row['uploader'];
    } else {
      return false;
    }
  }
}

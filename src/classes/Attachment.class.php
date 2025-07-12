<?php

/**
 * Attachment
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
  private $db = null;
  private $table = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $CONF, $DB;
    $this->db = $DB->db;
    $this->table = $CONF['db_table_attachments'];
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a record
   *
   * @param string $filename File name
   * @param string $uploader Uploader username
   * @return boolean Query result
   */
  public function create(string $filename, string $uploader): string|bool {
    // Check if the file already exists to avoid duplicate entry error
    $query = $this->db->prepare('SELECT id FROM ' . $this->table . ' WHERE filename = :filename');
    $query->bindParam(':filename', $filename);
    $query->execute();
    if ($row = $query->fetch()) {
      // File already exists, return its ID
      return $row['id'];
    }
    // Insert new record
    $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (`filename`, `uploader`) VALUES (:filename, :uploader)');
    $query->bindParam(':filename', $filename);
    $query->bindParam(':uploader', $uploader);
    $result = $query->execute();
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
   * @return boolean Query result
   */
  public function deleteAll(): bool {
    $query = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table);
    $result = $query->execute();
    if ($result && $query->fetchColumn()) {
      $query = $this->db->prepare('TRUNCATE TABLE ' . $this->table);
      return $query->execute();
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a record by filetype/filename
   *
   * @param string $filename File name to delete
   * @return boolean Query result
   */
  public function delete(string $filename): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE filename = :filename');
    $query->bindParam(':filename', $filename);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes a record by ID
   *
   * @return boolean Query result
   */
  public function deleteById(string $id): bool {
    $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $id);
    return $query->execute();
  }

  //---------------------------------------------------------------------------
  /**
   * Reads all records into an array
   *
   * @return array Array with records
   */
  public function getAll(): array {
    $records = array();
    $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY filename ASC');
    if ($query->execute()) {
      while ($row = $query->fetch()) {
        $records[] = $row;
      }
    }
    return $records;
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the record ID of a given file
   *
   * @param string $filename File name to find
   * @return string Record ID
   */
  public function getId(string $filename): string|bool {
    $query = $this->db->prepare('SELECT id FROM ' . $this->table . ' WHERE filename = :filename');
    $query->bindParam(':filename', $filename);
    if ($query->execute() && $row = $query->fetch()) {
      return $row['id'];
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the uploader of a given file
   *
   * @param string $filename File name to find
   * @return string Uploader
   */
  public function getUploader(string $filename): string|bool {
    $query = $this->db->prepare('SELECT uploader FROM ' . $this->table . ' WHERE filename = :filename');
    $query->bindParam(':filename', $filename);
    if ($query->execute() && $row = $query->fetch()) {
      return $row['uploader'];
    } else {
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the uploader of a given file
   *
   * @param string $filename File name to find
   * @return string Uploader
   */
  public function getUploaderById(string $fileid): string|bool {
    $query = $this->db->prepare('SELECT uploader FROM ' . $this->table . ' WHERE id = :id');
    $query->bindParam(':id', $fileid);
    if ($query->execute() && $row = $query->fetch()) {
      return $row['uploader'];
    } else {
      return false;
    }
  }
}

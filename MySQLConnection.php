<?php

include_once 'DbConnectionInterface.php';

class MySQLConnection implements DbConnectionInterface {
  
  private $db_host;
  private $db_user;
  private $db_pass;
  private $db_name;

  private $conn     = false;
  private $result   = array(); 

  public function __construct($db_host = '127.0.0.1', $db_user = 'root', $db_pass = 'root', $db_name = 'guest_records') {
    $this->db_host = $db_host;
    $this->db_user = $db_user;
    $this->db_pass = $db_pass;
    $this->db_name = $db_name;
  }

  public function connect() {
    if (!$this->conn) {
      $conn = @mysqli_connect($this->db_host, $this->db_user, $this->db_pass);
      
      if ($conn) {
        $db = @mysqli_select_db($conn, $this->db_name);

        if ($db) {
          $this->conn = $conn;
        }
      } else {
        die('Could not connect: ' . mysqli_error($conn));
      }
    }
    return $this->conn;
  }

  public function disconnect() {
    if ($this->conn) {
      if(@mysqli_close()) {
        $this->conn = false;
      }	
    }
    return $this->conn;
  }

  public function select($table, $rows = '*', $where = null, $order = null) {
    $q = 'SELECT '.$rows.' FROM '.$table;

    if ($where != null)
      $q .= ' WHERE '.$where;
      
    if ($order != null)
      $q .= ' ORDER BY '.$order;
      
    if ($this->tableExists($table)) {
      $query = @mysqli_query($this->conn, $q);
      
      if ($query) {
        $this->numResults = mysqli_num_rows($query);

        for ($i = 0; $i < $this->numResults; $i++) {
          $r = mysqli_fetch_array($query);
          $key = array_keys($r); 
          
          for ($x = 0; $x < count($key); $x++) {
            // Sanitizes keys so only alphavalues are allowed
            if (!is_int($key[$x])) {
              if(mysqli_num_rows($query) > 1)
                $this->result[$i][$key[$x]] = $r[$key[$x]];
              else if(mysqli_num_rows($query) < 1)
                $this->result = null;
              else
                $this->result[$key[$x]] = $r[$key[$x]];
            }
          }
        }            
        return true; 
      }
    }
    return false; 
  }
  
  public function insert($table, $values, $rows = null) {

    if ($this->tableExists($table)) {
      $insert = 'INSERT INTO '.$table;
   
      if ($rows != null) {
        $insert .= ' ('.$rows.')';
      }

      for ($i = 0; $i < count($values); $i++) {
        if (is_string($values[$i])) {
          $values[$i] = "'".$values[$i]."'";
        }
      }
      $values = implode(',',$values);
      $insert .= ' VALUES ('.$values.')';

      $ins = @mysqli_query($this->conn, $insert);

      if ($ins) {
        return true;
      }
    }
    return false;
  }
    
  public function delete($table, $where = null) {
    if ($this->tableExists($table)) {
      if ($where == null) {
        $delete = 'DELETE '.$table;
      } else {
        $delete = 'DELETE FROM '.$table.' WHERE '.$where;
      }
      $del = @mysqli_query($this->conn, $delete);

      if ($del) {
        return true;
      }
    }
    return false;
  }
  
  public function update($table, $rows, $where) {
    if ($this->tableExists($table)) {
      for ($i = 0; $i < count($where); $i++) {
        if ($i%2 != 0) {
          if (is_string($where[$i])) {
            if (($i+1) != null)
              $where[$i] = '"'.$where[$i].'" AND ';
            else
              $where[$i] = '"'.$where[$i].'"';
          }
        }
      }
      $where = implode('=', $where);
          
          
      $update = 'UPDATE '.$table.' SET ';
      $keys = array_keys($rows);

      for ($i = 0; $i < count($rows); $i++) {
        if (is_string($rows[$keys[$i]])) {
          $update .= $keys[$i].'="'.$rows[$keys[$i]].'"';
        } else {
          $update .= $keys[$i].'='.$rows[$keys[$i]];
        }
          
        // Parse to add commas
        if ($i != count($rows)-1) {
          $update .= ',';
        }
      }
      $update .= ' WHERE '.$where;
        
      $query = @mysqli_query($this->conn, $update);

      if ($query) {
        return true;
      }
    }
    return false;
  }
 
  private function tableExists($table) {
    $tablesInDb = @mysqli_query($this->conn, 'SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"');
    if ($tablesInDb) {
      if (mysqli_num_rows($tablesInDb) == 1) {
        return true;
      }
    }
    return false;
  }
}

?>

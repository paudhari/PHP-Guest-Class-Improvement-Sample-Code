<?php

interface DbConnectionInterface {
  // connect to db
  public function connect();
  // disconnect from db
  public function disconnect();
}

?>

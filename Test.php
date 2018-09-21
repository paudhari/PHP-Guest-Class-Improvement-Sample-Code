<?php

include_once 'Guest.php';
include_once 'MySQLConnection.php';
include_once 'DbConnectionInterface.php';

$mysql = new MySQLConnection();
$guest = new Guest($mysql);

$guest->addGuest(array('name'=> 'Test User', 'address' => 'My Address', 'phone' => '+1-541-754-3010', 'email' => 'test@user.com'));



$_REQUEST['guestArray'] = array(
  array('name'=> 'Test User1', 'address' => 'My Address1', 'phone' => '+1-541-754-3011', 'email' => 'test1@user.com'),
  array('name'=> 'Test User2', 'address' => 'My Address2', 'phone' => '+1-541-754-3012', 'email' => 'test2@user.com'),
  array('name'=> 'Test User3', 'address' => 'My Address3', 'phone' => '+1-541-754-3013', 'email' => 'test3@user.com')
);
$guest->addGuests();

?>

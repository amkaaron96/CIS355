<?php

require "../crud2/database.php";
require "customers.class.php";
$cust = new Customers();

if(isset($_POST["name"])) $cust->name = $_POST["name"];
if(isset($_POST["email"])) $cust->email = $_POST["email"];
if(isset($_POST["mobile"])) $cust->mobile = $_POST["mobile"];

if(isset($_GET["fun"])) $fun = $_GET["fun"];
else $fun = 0;

if(isset($_GET["id"])){
	$id = $_GET["id"];
}else{
	$id = -1;
}

switch ($fun) {
    case 1: // create
        $cust->create_record();
        break;
    case 2: // read
        $cust->read_record();
        break;
    case 3: // update
        $cust->update_record();
        break;
    case 4: // delete
        $cust->delete_record($id);
        break;
    case 11: // insert database record from create_record()
        $cust->insert_record();
        break;
    case 44: // delete database record from delete_record()
        $cust->delete_db($id);
        break;
    case 0: // list
        $cust->list_records();
        break;
    default: // list
        $cust->list_records();
}
?>
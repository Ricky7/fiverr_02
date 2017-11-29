<?php
require_once "class/DB.php";
require_once "class/Operation.php";

$opt = new Operation($db);

if(isset($_POST["x_id"]) && isset($_POST["table"]) && isset($_POST["idf"]))
{
	try {
        $opt->delete($_POST['x_id'], $_POST["table"], $_POST['idf']);
	  } catch (Exception $e) {
	  	die($e->getMessage());
	  }
	
}

if(isset($_POST["x_id"]) && isset($_POST["table"]) && isset($_POST["idEve"]))
{
	try {
        $opt->delGoalSale($_POST['x_id'], $_POST["table"], $_POST['idEve']);
	  } catch (Exception $e) {
	  	die($e->getMessage());
	  }
}

?>
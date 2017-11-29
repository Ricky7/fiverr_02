<?php
require_once "class/DB.php";
require_once "class/Operation.php";

$opt = new Operation($db);

if(isset($_POST["x_id"]) && isset($_POST["table"]))
{
	if($_POST['table'] == 'event')
	{
		try {
		    $opt->event_fetch_single($_POST['x_id']);
		  } catch (Exception $e) {
		  	die($e->getMessage());
		  }
	}

	if($_POST['table'] == 'executive')
	{
		try {
		    $opt->exec_fetch_single($_POST['x_id']);
		  } catch (Exception $e) {
		  	die($e->getMessage());
		  }
	}

	if($_POST['table'] == 'businessline')
	{
		try {
		    $opt->buss_fetch_single($_POST['x_id']);
		  } catch (Exception $e) {
		  	die($e->getMessage());
		  }
	}

	if($_POST['table'] == 'prospect')
	{
		try {
		    $opt->pros_fetch_single($_POST['x_id']);
		  } catch (Exception $e) {
		  	die($e->getMessage());
		  }
	}

	if($_POST['table'] == 'goalsale')
	{
		try {
		    $opt->goal_fetch_single($_POST['x_id'], $_POST['idEve']);
		  } catch (Exception $e) {
		  	die($e->getMessage());
		  }
	}

	if($_POST['table'] == 'compare')
	{
		try {
		    $opt->compare_fetch_single($_POST['x_id'], $_POST['idEve']);
		  } catch (Exception $e) {
		  	die($e->getMessage());
		  }
	}

	if($_POST['table'] == 'salesreal')
	{
		try {
		    $opt->salesreal_fetch_single($_POST['x_id'], $_POST['idEve'], $_POST['date']);
		  } catch (Exception $e) {
		  	die($e->getMessage());
		  }
	}
}
?>
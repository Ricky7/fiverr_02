<?php
require_once "class/DB.php";
require_once "class/Operation.php";

$opt = new Operation($db);

if(isset($_POST["operation"]) && isset($_POST["table"]))
{
	
	if($_POST["operation"] == "Add" && $_POST["table"] == "event")
	{

		$statement = $db->prepare("INSERT INTO event (name, createdate, eventdateIni, eventdateFin, salesdateIni, salesdateFin, active) VALUES (:name, NOW(), :eventdateIni, :eventdateFin, :salesdateIni, :salesdateFin, :active)");
		$result = $statement->execute(
			array(
				':name'	=>	$_POST["name"],
				':eventdateIni'	=>	$_POST["event_start"],
				':eventdateFin'	=>	$_POST["event_finish"],
				':salesdateIni'	=>	$_POST["sale_start"],
				':salesdateFin'	=>	$_POST["sale_finish"],
				':active'	=>	$_POST["status"]
			)
		);
		if(!empty($result))
		{
			echo 'Data Inserted';
		}
	}
	if($_POST["operation"] == "Edit" && $_POST["table"] == "event")
	{
		$statement = $db->prepare("UPDATE event SET name = :name, eventdateIni = :eventdateIni, eventdateFin = :eventdateFin,  active = :active WHERE idEvent = :idEvent");
		$result = $statement->execute(
			array(
				':name'	=>	$_POST["name"],
				':eventdateIni'	=>	$_POST["event_start"],
				':eventdateFin'		=>	$_POST["event_finish"],
				':active'		=>	$_POST["status"],
				':idEvent'			=>	$_POST["x_id"]
			)
		);
		if(!empty($result))
		{
			echo 'Data Updated';
		}
	}

	if($_POST["operation"] == "Add" && $_POST["table"] == "executive")
	{

		$statement = $db->prepare("INSERT INTO executive (name, createdate, active) VALUES (:name, NOW(), :active)");
		$result = $statement->execute(
			array(
				':name'	=>	$_POST["name"],
				':active'	=>	$_POST["status"]
			)
		);
		if(!empty($result))
		{
			echo 'Data Inserted';
		}
	}
	if($_POST["operation"] == "Edit" && $_POST["table"] == "executive")
	{
		$statement = $db->prepare("UPDATE executive SET name = :name, active = :active WHERE idEjecutive = :idEjecutive");
		$result = $statement->execute(
			array(
				':name'	=>	$_POST["name"],
				':active'		=>	$_POST["status"],
				':idEjecutive'	=>	$_POST["x_id"]
			)
		);
		if(!empty($result))
		{
			echo 'Data Updated';
		}
	}

	if($_POST["operation"] == "Add" && $_POST["table"] == "businessline")
	{

		$statement = $db->prepare("INSERT INTO businessline (name, createdate, active) VALUES (:name, NOW(), :active)");
		$result = $statement->execute(
			array(
				':name'	=>	$_POST["name"],
				':active'	=>	$_POST["status"]
			)
		);
		if(!empty($result))
		{
			echo 'Data Inserted';
		}
	}
	if($_POST["operation"] == "Edit" && $_POST["table"] == "businessline")
	{
		$statement = $db->prepare("UPDATE businessline SET name = :name, active = :active WHERE idBusinessline = :idBusinessline");
		$result = $statement->execute(
			array(
				':name'	=>	$_POST["name"],
				':active'		=>	$_POST["status"],
				':idBusinessline'	=>	$_POST["x_id"]
			)
		);
		if(!empty($result))
		{
			echo 'Data Updated';
		}
	}

	if($_POST["operation"] == "Add" && $_POST["table"] == "prospect")
	{

		$statement = $db->prepare("INSERT INTO prospect (name, company, telephone, whatsapp, facebook, email, webpage, idBusinessline, businesslineother, createdate, active) VALUES (:name, :company, :telephone, :whatsapp, :facebook, :email, :webpage, :idBusinessline, :businesslineother, NOW(), :active)");
		$result = $statement->execute(
			array(
				':name'	=>	$_POST["name"],
				':company'	=>	$_POST["comp"],
				':telephone'	=>	$_POST["telp"],
				':whatsapp'	=>	$_POST["w_app"],
				':facebook'	=>	$_POST["fb"],
				':email'	=>	$_POST["email"],
				':webpage'	=>	$_POST["web"],
				':idBusinessline'	=>	$_POST["b_line"],
				':businesslineother'	=>	$_POST["b_other"],
				':active'	=>	$_POST["status"]
			)
		);
		$lastId = $db->lastInsertId();
		if(!empty($result))
		{
			$statement2 = $db->prepare("INSERT INTO prospectexecutiveevent (idProspect, idEjecutive, idEvent, createdate) VALUES (:idProspect, :idEjecutive, :idEvent, NOW())");
			$result2 = $statement2->execute(
				array(
					':idProspect'	=>	$lastId,
					':idEjecutive'	=>	$_POST["exe"],
					':idEvent'	=>	$_POST["eve"]
				)
			);
			if(!empty($result2))
			{
				echo 'Data Inserted';
			}
		}
	}
	if($_POST["operation"] == "Edit" && $_POST["table"] == "prospect")
	{
		$statement = $db->prepare("UPDATE prospect SET name = :name, company = :company, telephone = :telephone, whatsapp = :whatsapp, facebook = :facebook, email = :email, webpage = :webpage, idBusinessline = :idBusinessline, businesslineother = :businesslineother, active = :active WHERE idProspect = :idProspect");
		$result = $statement->execute(
			array(
				':name'	=>	$_POST["name"],
				':company'	=>	$_POST["comp"],
				':telephone'	=>	$_POST["telp"],
				':whatsapp'	=>	$_POST["w_app"],
				':facebook'	=>	$_POST["fb"],
				':email'	=>	$_POST["email"],
				':webpage'	=>	$_POST["web"],
				':idBusinessline'	=>	$_POST["b_line"],
				':businesslineother'	=>	$_POST["b_other"],
				':active'		=>	$_POST["status"],
				':idProspect'	=>	$_POST["x_id"]
			)
		);
		if(!empty($result))
		{
			echo 'Data Updated';
		}
	}
	
} 


?>
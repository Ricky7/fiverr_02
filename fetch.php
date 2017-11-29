<?php
require_once "class/DB.php";
require_once "class/Operation.php";

$opt = new Operation($db);

if($_POST['op_id'] == 'event') 
{
	$query = '';
	$output = array();
	$query .= "SELECT * FROM event ";
	if(isset($_POST["search"]["value"]))
	{
		$query .= 'WHERE name LIKE "%'.$_POST["search"]["value"].'%" ';
	}
	if(isset($_POST["order"]))
	{
		$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	}
	else
	{
		$query .= 'ORDER BY idEvent DESC ';
	}
	if($_POST["length"] != -1)
	{
		$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}
	$statement = $db->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$data = array();
	$filtered_rows = $statement->rowCount();
	foreach($result as $row)
	{
		$sub_array = array();
		$sub_array[] = $row["idEvent"];
		$sub_array[] = $row["name"];
		$sub_array[] = $row["createdate"];
		$sub_array[] = $row["eventdateIni"];
		$sub_array[] = $row["eventdateFin"];
		$sub_array[] = $row["salesdateIni"];
		$sub_array[] = $row["salesdateFin"];
		if($row['active'] == 1){
			$status = 'Active';
		} else {
			$status	= 'Inactive';
		}
		$sub_array[] = $status;
		$sub_array[] = '<a href="goalsale.php?id='.$row['idEvent'].'" class="btn btn-info btn-xs">GoalSale</a>';
		$sub_array[] = '<button type="button" name="update" id="'.$row["idEvent"].'" class="btn btn-warning btn-xs update">Update</button>';
		$sub_array[] = '<button type="button" name="delete" id="'.$row["idEvent"].'" class="btn btn-danger btn-xs delete">Delete</button>';
		$data[] = $sub_array;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"		=> 	$filtered_rows,
		"recordsFiltered"	=>	$opt->total_records($_POST['op_id']),
		"data"				=>	$data
	);
	echo json_encode($output);
}

if($_POST['op_id'] == 'executive') 
{
	$query = '';
	$output = array();
	$query .= "SELECT * FROM executive ";
	if(isset($_POST["search"]["value"]))
	{
		$query .= 'WHERE name LIKE "%'.$_POST["search"]["value"].'%" ';
	}
	if(isset($_POST["order"]))
	{
		$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	}
	else
	{
		$query .= 'ORDER BY idEjecutive DESC ';
	}
	if($_POST["length"] != -1)
	{
		$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}
	$statement = $db->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$data = array();
	$filtered_rows = $statement->rowCount();
	foreach($result as $row)
	{
		$sub_array = array();
		$sub_array[] = $row["idEjecutive"];
		$sub_array[] = $row["name"];
		$sub_array[] = $row["createdate"];
		if($row['active'] == 1){
			$status = 'Active';
		} else {
			$status	= 'Inactive';
		}
		$sub_array[] = $status;
		$sub_array[] = '<button type="button" name="update" id="'.$row["idEjecutive"].'" class="btn btn-warning btn-xs update">Update</button>';
		$sub_array[] = '<button type="button" name="delete" id="'.$row["idEjecutive"].'" class="btn btn-danger btn-xs delete">Delete</button>';
		$data[] = $sub_array;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"		=> 	$filtered_rows,
		"recordsFiltered"	=>	$opt->total_records($_POST['op_id']),
		"data"				=>	$data
	);
	echo json_encode($output);
}

if($_POST['op_id'] == 'businessline') 
{
	$query = '';
	$output = array();
	$query .= "SELECT * FROM businessline ";
	if(isset($_POST["search"]["value"]))
	{
		$query .= 'WHERE name LIKE "%'.$_POST["search"]["value"].'%" ';
	}
	if(isset($_POST["order"]))
	{
		$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	}
	else
	{
		$query .= 'ORDER BY idBusinessline DESC ';
	}
	if($_POST["length"] != -1)
	{
		$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}
	$statement = $db->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$data = array();
	$filtered_rows = $statement->rowCount();
	foreach($result as $row)
	{
		$sub_array = array();
		$sub_array[] = $row["idBusinessline"];
		$sub_array[] = $row["name"];
		$sub_array[] = $row["createdate"];
		if($row['active'] == 1){
			$status = 'Active';
		} else {
			$status	= 'Inactive';
		}
		$sub_array[] = $status;
		$sub_array[] = '<button type="button" name="update" id="'.$row["idBusinessline"].'" class="btn btn-warning btn-xs update">Update</button>';
		$sub_array[] = '<button type="button" name="delete" id="'.$row["idBusinessline"].'" class="btn btn-danger btn-xs delete">Delete</button>';
		$data[] = $sub_array;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"		=> 	$filtered_rows,
		"recordsFiltered"	=>	$opt->total_records($_POST['op_id']),
		"data"				=>	$data
	);
	echo json_encode($output);
}

if($_POST['op_id'] == 'prospect') 
{
	$query = '';
	$output = array();
	$query .= "SELECT * FROM prospect INNER JOIN prospectexecutiveevent ON (prospect.idProspect=prospectexecutiveevent.idProspect) ";
	if(isset($_POST["search"]["value"]))
	{
		$query .= 'WHERE prospect.name LIKE "%'.$_POST["search"]["value"].'%" ';
	}
	if(isset($_POST["order"]))
	{
		$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	}
	else
	{
		$query .= 'ORDER BY prospect.idProspect DESC ';
	}
	if($_POST["length"] != -1)
	{
		$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}
	$statement = $db->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$data = array();
	$filtered_rows = $statement->rowCount();
	foreach($result as $row)
	{
		$sub_array = array();
		$sub_array[] = $row["idProspect"];
		$sub_array[] = $row["name"];
		$sub_array[] = $row["company"];
		$sub_array[] = $row["telephone"];
		$sub_array[] = $row["whatsapp"];
		$sub_array[] = $row["facebook"];
		$sub_array[] = $row["email"];
		$sub_array[] = $row["webpage"];
		$buss_data = $opt->getBusinesslineByID($row["idBusinessline"]);
		$sub_array[] = $buss_data['name'];
		$eve_data = $opt->getEventByID($row["idEvent"]);
		$sub_array[] = $eve_data['name'];
		$exe_data = $opt->getExecutiveByID($row["idEjecutive"]);
		$sub_array[] = $exe_data['name'];
		$sub_array[] = $row["businesslineother"];
		$sub_array[] = $row["createdate"];
		if($row['active'] == 1){
			$status = 'Active';
		} else {
			$status	= 'Inactive';
		}
		$sub_array[] = $status;
		$sub_array[] = '<button type="button" name="update" id="'.$row["idProspect"].'" class="btn btn-warning btn-xs update">Update</button>';
		$sub_array[] = '<button type="button" name="delete" id="'.$row["idProspect"].'" class="btn btn-danger btn-xs delete">Delete</button>';
		$data[] = $sub_array;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"		=> 	$filtered_rows,
		"recordsFiltered"	=>	$opt->total_records($_POST['op_id']),
		"data"				=>	$data
	);
	echo json_encode($output);
}


?>
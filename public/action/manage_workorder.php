<?php
session_start();
require("global/db.php");
header("Location: ../home.php");
$userID = $_SESSION['userID'];
$command = $_POST['instruct'];
//0 = Add
//1 = Edit
//2 = Delete


//Workorder Creation
if ($command == 0) {
	$payID = $_POST['payID'];
        $woNum = $_POST['woNum'];
	$pickup = $_POST['pAddr'];
	$dropoff = $_POST['dAddr'];
	$start = $_POST['start'];
	$dead = $_POST['dead'];
	$price = $_POST['price'];
	$completed = (int) $_POST['completed'];

	$sql = "insert into Workorder (company_id, payload_id, workorder_no, pickup_address, dropoff_address, start_time, deadline, completed, contract_price) values ($userID, $payID, '$woNum', '$pickup', '$dropoff', '$start', '$dead', $completed, '$price');";

	if ($link->query($sql) === false) {
		$_SESSION['flash'] = "Could not create new workorder";
	}

//Edit workorder
} else if ($command == 1) {

	$payID = $_POST['payID'];
        $woNum = $_POST['woNum'];
	$pickup = $_POST['pAddr'];
	$dropoff = $_POST['dAddr'];
	$start = $_POST['start'];
	$dead = $_POST['dead'];
	$completed = (int) $_POST['completed'];
	$price = $_POST['price'];

	//Check if workorder has been accepted query --> Cannot edit?
	$sql = "select * from Workorder where company_id, payload_id, workorder_no in (select * from AcceptedOrders);";
	$result = $link->query($sql);

	if ($result->num_rows > 0) {
		$_SESSION['flash'] = "Edit failed: Cannot edit accepted workorder";
	} else {
		$sql = "update Workorder set pickup_address = '$pickup', dropoff_address = '$dropoff', start_time = '$start', deadline = '$dead', completed = $completed, contract_price = '$price' where company_id = $userID and payload_id = $payID and workorder_no = $woNum;";	
		if ($link->query($sql) === false) 
			$_SESSION['flash'] = "Edit failed: Could not edit";
	}

//Deletion workorder	
} else {

	//Check if workorder has been accepted query --> Cannot delete?
	$sql = "select * from Workorder where company_id, payload_id, workorder_no in (select * from AcceptedOrders);";
	$result = $link->query($sql);

	if ($result->num_rows > 0) {
		$_SESSION['flash'] = "Deletion failed: Cannot delete accepted workorder";
	} else {
		$woNum = $_POST['workID'];
		$payID = $_POST['payID'];
		$sql = "delete from Workorder where company_id = $userID and payload_id = $payID and workorder_no = $woNum;";	
		
		if ($link->query($sql) === false) 
			$_SESSION['flash'] = "Deletion failed: Could not delete";
	}
}

$link->close();
?>

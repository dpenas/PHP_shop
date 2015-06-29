<?php

session_start();
include_once("config.php");

$return_url = $_POST["return_url"];

if(isset($_POST["type"]) && $_POST["type"]=='checkout') {
	$firstName = filter_var($_POST["firstName"], FILTER_SANITIZE_STRING);
	$lastName = filter_var($_POST["lastName"], FILTER_SANITIZE_STRING);
	$email = filter_var($_POST["email"], FILTER_SANITIZE_STRING);
	$address = filter_var($_POST["address"], FILTER_SANITIZE_STRING);
	$deliverAddress = filter_var($_POST["addressDeliver"], FILTER_SANITIZE_STRING);

	$query = "INSERT INTO prodOrders (billingAddress, firstName, lastName, email, deliverAddress, discount) 
		VALUES ('" . $address . "', '" . $firstName . "', '" . $lastName . "', '" . $email . "', '" . $deliverAddress . 
			"', '" . $_SESSION["discount"] . "')";
	
	
	$mysqli->query($query);
	$prodOrderID = $mysqli->insert_id;

	foreach($_SESSION["products"] as $product) {
		$mysqli->query("INSERT INTO productOrder (productID, prodOrderID, quantity) 
			VALUES ('" . $product["id"] . "', '" . $prodOrderID . "', '" . $product["quantity"] . "')");
	}
	session_destroy();
	echo '<div style="color: #444;  margin: 0;  padding-left: 30%;  font-size: 51px;  line-height: 44px;  letter-spacing: -2px;  font-weight: bold;"> Your order has been sent</div>';

} else {
	session_destroy();
	header('Location:'.$return_url);
}
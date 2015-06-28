<?php

session_start();
include_once("config.php");

$return_url = base64_decode($_POST["return_url"]);

if(isset($_POST["type"]) && $_POST["type"]=='discount') {

	$discountCode = filter_var($_POST["discount"], FILTER_SANITIZE_STRING);
	$result = $mysqli->query("SELECT value FROM discount WHERE code='$discountCode' AND NOW() BETWEEN startDate AND endDate LIMIT 1");
	$value = $result->fetch_object()->value;
	$_SESSION["discount"] = $value;
	header('Location:'.$return_url);

} else {
	session_destroy();
	header('Location:'.$return_url);
}
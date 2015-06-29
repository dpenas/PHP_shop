<?php
session_start();
include_once("config.php");

if(isset($_GET["emptycart"]) && $_GET["emptycart"] == 1) {
	$return_url = base64_decode($_GET["return_url"]);
	session_destroy();
	header('Location:'.$return_url);
}


if(isset($_POST["type"]) && $_POST["type"]=='add') {
	$id 	= filter_var($_POST["id"], FILTER_SANITIZE_STRING);
	$quantity 	= filter_var($_POST["quantity"], FILTER_SANITIZE_NUMBER_INT);
	$return_url 	= base64_decode($_POST["return_url"]);

	$results = $mysqli->query("SELECT id, name, price FROM product WHERE id = '$id' LIMIT 1");
	$obj = $results->fetch_object();
	
	if ($results) {
		
		$prodAdd = array(array('id' => $obj->id, 'name' => $obj->name, 'quantity' => $quantity, 'price' => $obj->price));
		
		if(isset($_SESSION["products"])) {
			$found = false; 
			
			foreach ($_SESSION["products"] as $item) {
				if($item["id"] == $id) { 
					$product[] = array('id' => $item["id"], 'name' => $item["name"], 'quantity' => $quantity, 'price' => $item["price"]);
					$found = true;
				}else{
					$product[] = array('id' => $item["id"], 'name' => $item["name"], 'quantity' => $item["quantity"], 'price' => $item["price"]);
				}
			}

			$_SESSION["products"] = ($found) ? $product : array_merge($product, $prodAdd);
			
		} else{

			$_SESSION["products"] = $prodAdd;
		}
		
	}
	
	header('Location:'.$return_url);
}

if(isset($_GET["prodDelete"]) && isset($_GET["return_url"]) && isset($_SESSION["products"])) {

	$id 	= $_GET["prodDelete"];
	$return_url 	= base64_decode($_GET["return_url"]);

	
	foreach ($_SESSION["products"] as $item) {

		if($item["id"] != $id) {
			$product[] = array('id' => $id, 'name' => $item["name"], 'quantity' => $item["quantity"], 'price' => $item["price"]);
		}
		
		$_SESSION["products"] = $product;
	}
	
	
	header('Location:'.$return_url);
}
?>
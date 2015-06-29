<?php
session_start();
include_once("config.php");

if(isset($_GET["emptycart"]) && $_GET["emptycart"] == 1) {
	$return_url = base64_decode($_GET["return_url"]);
	session_destroy();
	header('Location:'.$return_url);
}


if(isset($_POST["type"]) && $_POST["type"]=='add') {
	$product_id 	= filter_var($_POST["product_id"], FILTER_SANITIZE_STRING);
	$product_qty 	= filter_var($_POST["product_qty"], FILTER_SANITIZE_NUMBER_INT);
	$return_url 	= base64_decode($_POST["return_url"]);

	$results = $mysqli->query("SELECT id, product_name, price FROM product WHERE id = '$product_id' LIMIT 1");
	$obj = $results->fetch_object();
	
	if ($results) {
		
		$new_product = array(array('id' => $obj->id, 'name' => $obj->product_name, 'qty' => $product_qty, 'price' => $obj->price));
		
		if(isset($_SESSION["products"])) {
			$found = false; 
			
			foreach ($_SESSION["products"] as $item) {
				if($item["id"] == $product_id) { 
					$product[] = array('id' => $item["id"], 'name'=>$item["name"], 'qty'=>$product_qty, 'price'=>$item["price"]);
					$found = true;
				}else{
					$product[] = array('id' => $item["id"], 'name'=>$item["name"], 'qty'=>$item["qty"], 'price'=>$item["price"]);
				}
			}
			
			if($found == false)
			{
				
				$_SESSION["products"] = array_merge($product, $new_product);
			}else{
				
				$_SESSION["products"] = $product;
			}
			
		}else{

			$_SESSION["products"] = $new_product;
		}
		
	}
	
	header('Location:'.$return_url);
}

if(isset($_GET["removep"]) && isset($_GET["return_url"]) && isset($_SESSION["products"])) {

	$product_id 	= $_GET["removep"];
	$return_url 	= base64_decode($_GET["return_url"]);

	
	foreach ($_SESSION["products"] as $item) {

		if($item["id"] != $product_id) {
			$product[] = array('name'=>$item["name"], 'qty'=>$item["qty"], 'price'=>$item["price"]);
		}
		
		$_SESSION["products"] = $product;
	}
	
	
	header('Location:'.$return_url);
}
?>
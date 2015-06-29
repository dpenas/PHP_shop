<?php
	session_start();
	include_once("config.php");
	$current_url = base64_encode('http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>View shopping cart</title>
		<link href="style/style.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div id="products-wrapper">
			<div class="cart">
			 	<?php
				if(isset($_SESSION["products"])){
				    $total = 0;
					$itemCount = 0;
					foreach ($_SESSION["products"] as $item) {
			           $id = $item["id"];
					   $results = $mysqli->query("SELECT id, name, descr, price FROM product WHERE id = '$id' LIMIT 1");
					   $obj = $results->fetch_object();
					   
						echo '<span class="float-right"><a href="cart_update.php?prodDelete='.$item["id"].'&return_url='.$current_url.'">&times;</a></span>';
						echo '<div class="price">'.$currency.$obj->price.'</div>';
			            echo '<div class="product-info">';
						echo '<h3>'.$obj->name.'</h3> ';
			            echo '<div class="p-quantity">Quantity : '.$item["quantity"].'</div>';
			            echo '<div>'.$obj->descr.'</div>';
						echo '</div>';
						$subtotal = ($item["price"] * $item["quantity"]);
						$total = ($total + $subtotal);
						if ($_SESSION["discount"]) {
							$total = $total * (1 - (0.01 * $_SESSION["discount"]));
						}

						echo '<input type="hidden" name="item_name['.$itemCount.']" value="'.$obj->name.'" />';
						echo '<input type="hidden" name="item_descr['.$itemCount.']" value="'.$obj->descr.'" />';
						echo '<input type="hidden" name="item_quantity['.$itemCount.']" value="'.$item["quantity"].'" />';
						$itemCount++;
						
			        }
			    	echo '<form method="post" action="apply_discount.php">';
			    	echo '<label> Discount code: </label> <input type="text" name="discount">';
			    	echo '<span><button>OK</button></span>';
			    	echo '<input type="hidden" name="type" value="discount" />';
			    	echo '<input type="hidden" name="return_url" value="'.$current_url.'" />';
			    	echo '</form>';
					echo '<span class="check-out-txt">';
					echo '<strong>Total : '.$currency.$total.'</strong>  ';
					echo '</span>';
					
			    }else{
					echo 'Your Cart is empty';
				}
				
			    ?>
		    </div>
		</div>
		<?php
			echo "<div id='address-wrapper'><div id='billing'>";
			echo "<h2>Billing Address</h2>";
			echo "<form action='deliver.php' method='post'>";
			echo "<label> First name: </label> <input type='text' name='firstName' required><br>";
			echo "<label> Last name: </label> <input type='text' name='lastName' required><br>";
			echo "<label> E-mail: </label> <input type='email' name='email' required><br>";
			echo "<label> Address: </label> <input type='text' name='address' required><br>";
			echo "<h2>Deliver Address</h2>";
			echo "<label> Address: </label> <input type='text' name='addressDeliver' required><br>";
			echo "<div class='acceptButton'><button class='btn'>Accept</button></div>";
			echo '<input type="hidden" name="return_url" value="'.$current_url.'" />';
			echo '<input type="hidden" name="type" value="checkout" />';
			echo "</form></div></div>";
		?>
	</body>
</html>

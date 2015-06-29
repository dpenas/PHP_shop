<?php
session_start();
include_once("config.php");

function limitWords($word) {
    $word = wordwrap($word, 200);
    $word = explode("\n", $word);
    return $word[0] . '...';
}

?>
<html>
    <head>
        <title>Cart</title>
        <link href="style/style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="products-wrapper">
            <div class="products">
            
            <?php

        	$current_url = base64_encode('http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
            
        	$products = $mysqli->query("SELECT * FROM product ORDER BY id DESC");
            if ($products) {
                while($product = $products->fetch_object()) {
        			echo '<div class="product">';
                    echo '<form method="post" action="cart_update.php">';
                    echo '<div class="product-content"><h3>'.$product->product_name.'</h3>';
                    echo '<div align="center">'.limitWords($product->product_desc).'</div>';
        			echo '<div id="price">Price: ' . $product->price . $currency . '</div>';
                    echo '<div id="quantity">Quantity: <input type="text" name="product_qty" value="1" size="3" /></div>';
        			echo '<div id="buyButton" class="margin-top-small"><button class="btn add-to-cart">Buy</button></div>';
        			echo '</div>';
                    echo '<input type="hidden" name="product_id" value="'.$product->id.'" />';
                    echo '<input type="hidden" name="type" value="add" />';
        			echo '<input type="hidden" name="return_url" value="'.$current_url.'" />';
                    echo '</form>';
                    echo '</div>';
                }
            }

            ?>
            </div>
            
            <div class="shopping-cart">
                <h2>Your Shopping Cart</h2>

                <?php
                if(isset($_SESSION["products"])) {
                    $total = 0;
                    foreach ($_SESSION["products"] as $item) {
                        echo '<li class="cart-itm">';
                        echo '<span class="remove-itm"><a href="cart_update.php?removep='.$item["id"].'&return_url='.$current_url.'">&times;</a></span>';
                        echo '<h3>'.$item["name"].'</h3>';
                        echo '<div class="p-qty">Quantity: '.$item["qty"].'</div>';
                        echo '<div class="p-price">Price: '.$currency.$item["price"].'</div>';
                        echo '</li>';
                        $subtotal = ($item["price"]*$item["qty"]);
                        $total = ($total + $subtotal);
                    }
                    echo '<span class="check-out-txt"><strong>Total : '.$currency.$total.'</strong> <a href="checkout.php">Checkout</a></span>';
                	echo '<span class="empty-cart"><a href="cart_update.php?emptycart=1&return_url='.$current_url.'">Delete cart</a></span>';
                }else{
                    echo 'Your Cart is empty';
                }
                ?>
            </div>
            
        </div>

    </body>
</html>

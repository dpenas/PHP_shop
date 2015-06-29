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
                    echo '<div class="product-content"><h3>'.$product->name.'</h3>';
                    echo '<div align="center">'.limitWords($product->descr).'</div>';
        			echo '<div id="price">price: ' . $product->price . $currency . '</div>';
                    echo '<div id="quantity">Quantity: <input type="text" name="quantity" value="1" size="3" /></div>';
        			echo '<div id="buyButton" class="margin-top-small"><button class="btn add-to-cart">Buy</button></div>';
        			echo '</div>';
                    echo '<input type="hidden" name="id" value="'.$product->id.'" />';
                    echo '<input type="hidden" name="type" value="add" />';
        			echo '<input type="hidden" name="return_url" value="'.$current_url.'" />';
                    echo '</form>';
                    echo '</div>';
                }
            }

            ?>
            </div>
            
            <div class="shopping-cart">
                <h2>Shopping Cart</h2>

                <?php
                if(isset($_SESSION["products"])) {
                    $total = 0;
                    foreach ($_SESSION["products"] as $item) {
                        echo '<span class="float-right"><a href="cart_update.php?prodDelete='.$item["id"].'&return_url='.$current_url.'">&times;</a></span>';
                        echo '<h3>'.$item["name"].'</h3>';
                        echo '<div class="p-quantity">Quantity: '.$item["quantity"].'</div>';
                        echo '<div class="price">price: '.$currency.$item["price"].'</div>';
                        $itemprice = ($item["price"] * $item["quantity"]);
                        $total = ($total + $itemprice);
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

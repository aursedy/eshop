<?php 
   session_start();
?>
<style type="">
      
    .catalog-item {
      width: 200px;
      background-color: beige;
      height: 300px;
      margin: 5px;
    }

    .catalog-img {
      font-size: 100px;
    }

    .cart-button, .catalog-buy-button {
      margin: 5px;
      padding: 5px;
      border: 1px solid yellow;
      background-color: yellowgreen;
      text-align: center;
    }

    #catalog-items {
      display: flex;
    }
    ul{
      background-color: yellowgreen;
      display: flex;
      list-style-type: none;
      margin : 0;
    }

    ul a{
      display: block;
      text-decoration: none;
      padding: 15px;
    }

    a{
      display: block;
      text-decoration: none;
    }

    a:hover{
      background-color: darkolivegreen;
      color: white;
    }

    .cart-item {
      justify-content: space-between;
      display: flex;
      margin: 5px;
      border: 1px solid yellowgreen;
      padding: 5px;
    }

    .cart-quantity {
      margin: 10px;
    }

    .cart-price {
      margin: 10px;

    }

    .cart-control {
      display: flex;
    }

    #cart-total-price {
      font-weight: bold;
    }


</style>
<?php include("nav.php")?>

<section>
  <h2>Shopping cart</h2>
    <?php

    if(!isset($_GET['action'])){
  $_GET['action'] = null;
}

function getBy($att, $value, $array)
{
    foreach ($array as $key => $val) {
        if ($val[$att] === $value) {
            return $key;
        }
    }
    return null;
}

if ($_GET["action"] == "add" && !empty($_GET["id"])) {
    addToCart($_GET["id"]);
    //header("Location: /");
}

if ($_GET["action"] == "remove" && !empty($_GET["id"])) {
    removeFromCart($_GET["id"]);
    //header("Location: /");

}

if ($_GET["action"] == "delete" && !empty($_GET["id"])) {
    deleteFromCart($_GET["id"]);
    //header("Location: /");
}

function addToCart($productId)
{
    if (!array_key_exists($productId, $_SESSION["cart"])) {
        $_SESSION["cart"][$productId]["quantity"] = 1;
    } else {
        $_SESSION["cart"][$productId]["quantity"]++;
    }
}

function removeFromCart($productId)
{
    if (array_key_exists($productId, $_SESSION["cart"])) {
        if ($_SESSION["cart"][$productId]["quantity"] <= 1) {
            unset($_SESSION["cart"][$productId]);
        } else {
            $_SESSION["cart"][$productId]["quantity"]--;
        }
    }
}

function deleteFromCart($productId)
{
    unset($_SESSION["cart"][$productId]);
}

    $totalPrice = 0;
    foreach ($_SESSION["cart"] as $key => $value) {

        $item = $_SESSION['catalog'] [getBy("id", $key, $_SESSION['catalog'] )];
        $totalPrice = $totalPrice + ($value["quantity"] * $item["price"]);
        echo '
<div class="cart-item">
<div class="cart-img">
' . $item["img"] . '
</div>
<div>
' . $item["name"] . '
</div>
<div class="cart-control">
<div class="cart-price">
' . $item["price"] . '
</div>
<div class="cart-quantity">
' . ($value["quantity"]) . '
</div>
<div class="cart-quantity">
' . ($value["quantity"] * $item["price"]) '
</div>';

    }

    echo "<div id='cart-total-price'>Total price: $totalPrice</div>";

    ?>
</section>
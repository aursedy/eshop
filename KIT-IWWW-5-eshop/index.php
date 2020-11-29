<?php
session_start();
ob_start();

$banana = array(
    "id" => 1,
    "img" => "&#127820",
    "name" => "banana",
    "price" => "29",
);
$apple = array(
    "id" => 2,
    "img" => "&#127823",
    "name" => "apple",
    "price" => "39",
);
$pepper = array(
    "id" => 3,
    "img" => "&#127817",
    "name" => "watermelon",
    "price" => "59",
);
$potato = array(
    "id" => 4,
    "img" => "&#129364",
    "name" => "potato",
    "price" => "19",
);
$_SESSION['catalog'] = array($banana, $apple, $pepper, $potato);

if(!empty($_SESSION["loginUziv"]) AND !empty($_SESSION["hesloUziv"])){
    $_SESSION["isLogged"]=true;
}else{
     $_SESSION["isLogged"]=false;
}

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
        addDB($productId);
    } else {
        $_SESSION["cart"][$productId]["quantity"]++;
        addDB($productId);
    }
}

function addDB($productId){
  try {
      $conn = new PDO("mysql:host=$servername;dbname=eshop_db", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stm =$conn->prepare("INSERT INTO Produkt_uzivatele(Id_uzivatele,Id_produkt) VALUES(:Id_uzivatele,:Id_produkt);");
      $stm->bindParam(':Id_uzivatele',$_SESSION["id"]);
      $stm->bindParam(':Id_produkt',$productId);

      $stm->execute();

    } catch(PDOException $e) {
      echo "New data couldn't be recorded: " . $e->getMessage();
    }

    }
?>

<html>
<head>
  <title>ESHOP</title>
  <style>
    body {

    }

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
</head>
<body>
<?php include('nav.php')?>

<section id="catalog-items">

    <?php


    foreach ($_SESSION['catalog']  as $item) {
        echo '
<div class="catalog-item">
<div class="catalog-img">
' . $item["img"] . '
</div>
<h3>
' . $item["name"] . '
</h3>
<div>
' . $item["price"] . '
</div>
<a href="?action=add&id=' . $item["id"] . '" class="catalog-buy-button">
Buy
</a>
</div>';

    }

    ?>
</section>
</body>
</html>
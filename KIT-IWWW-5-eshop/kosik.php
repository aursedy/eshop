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

function removeDB($productId){
   try {
      $conn = new PDO("mysql:host=$servername;dbname=eshop_db", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stm =$conn->prepare("DELETE FROM Produkt_uzivatele WHERE Id_uzivatele= :uzivatele,Id_produkt= :produkt); LIMIT 0,0");
      $stm->bindParam(':uzivatele',$_SESSION["id"]);
      $stm->bindParam(':produkt',$productId);

      $stm->execute();

    } catch(PDOException $e) {
      echo "New data couldn't be recorded: " . $e->getMessage();
    }

    }
}

function deleteFromCart($productId)
{
    unset($_SESSION["cart"][$productId]);
    deleteDB($productId);
}

function 
deleteDB($productId){
  try {
      $conn = new PDO("mysql:host=$servername;dbname=eshop_db", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stm =$conn->prepare("DELETE FROM Produkt_uzivatele WHERE Id_uzivatele= :uzivatele,Id_produkt= :produkt);");
      $stm->bindParam(':uzivatele',$_SESSION["id"]);
      $stm->bindParam(':produkt',$productId);

      $stm->execute();

    } catch(PDOException $e) {
      echo "New data couldn't be recorded: " . $e->getMessage();
    }

    }
}

    $totalPrice = 0;

      try {
          $conn = new PDO("mysql:host=$servername;dbname=eshop_db", $username, $password);
          // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $result =$conn->query("SELECT * FROM Produkt_uzivatele WHERE Login = '$_SESSION['loginUziv']' AND Heslo = '$_SESSION['hesloUziv']' ");

          while($row = $result->fetch()){

            $item = $_SESSION['catalog'] [getBy("id", $row['Id_produkt'], $_SESSION['catalog'] )];
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
' . ($value["quantity"] * $item["price"]) . '
</div>
<a href="?action=add&id=' . $item["id"] . '" class="cart-button">
+
</a>
<a href="?action=remove&id=' . $item["id"] . '" class="cart-button">
-
</a>
<a href="?action=delete&id=' . $item["id"] . '" class="cart-button">
x
</a>
</div>
</div>';

    }

    echo "<div id='cart-total-price'>Total price: $totalPrice</div>";

          }else{
            $zprava = "Uzivatel neexistuje !";
            $file = "login.php";
          }

        }catch(PDOException $e) {
         echo "ERROR: " . $e->getMessage();
        }



        

    ?>
</section>
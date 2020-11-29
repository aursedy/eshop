<?php 
   session_start();
?>
<style type="text/css">
  form{
  background-color: white;
  padding: 10px;
  width: 400px;
  height: 200px;
  margin: auto;
  margin-top: 10px;
  margin-bottom: 10px;
  box-shadow: 0 15px 20px 0;  
}

.row{ 
  display: flex;
  justify-content: space-between;
  margin: 10px;
}

.error{
  color : red;
}

input , textarea{
  padding: 6px;
  width: 180px;
}
</style>
<?php
$existujeUzivatel = false;
$login = $heslo = "";
$logErr = $hesErr = $zprava = "";
$file = "";

$_SESSION["id"]=0;


if($_SERVER["REQUEST_METHOD"]=="POST"){
  $servername = "localhost";
  $username = "root";
  $password = "root";

    if(empty($_POST["login"])){
      $logErr = "uživatelské jméno je povinné !";
    }else{
      $login = test_input($_POST["login"]);
    }
    

    if(empty($_POST["heslo"])){
        $hesErr = "heslo je povinné !";
    }else{
      $heslo = test_input($_POST["heslo"]);
    }


      if(!empty($_POST["heslo"]) AND !empty($_POST["login"])){
        try {
          $conn = new PDO("mysql:host=$servername;dbname=eshop_db", $username, $password);
          // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $result =$conn->query("SELECT * FROM Uzivatele WHERE Login = '$login' AND Heslo = '$heslo' ");

          if(!empty($row = $result->fetch())){
            $existujeUzivate = true;
            $zprava = "Zkuste znovu!";
            $file = "index.php";
            $_SESSION["isLogged"]= true;

            $_SESSION["loginUziv"]= $row['Login'];
            $_SESSION["hesloUziv"]= $row['Heslo'];
            $_SESSION["id"] = $row['Id_uzivatele'];

          }else{
            $zprava = "Uzivatel neexistuje !";
            $file = "login.php";
          }

        }catch(PDOException $e) {
         echo "ERROR: " . $e->getMessage();
        }
      }
    
}
 function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
 }
?>

<article>
    <form class="form" action="<?php echo htmlspecialchars($file);?>" method="post">

        <div class="row">
        <label>Uzivatelské jmeno(Login):</label>
        <input type="text" name="login">
        </div>

        <span class="error"><?php echo $logErr;?></span>

        <div class="row">
        <label>Heslo:</label>
        <input type="password" name="heslo">
        </div>

        <span class="error"><?php echo $hesErr;?></span>

        <div class="row">
        <label></label>
        <input type="submit" name="submit" value="Přihlasit">
        </div>

        <span class="error"><?php echo $zprava;?></span>
        
    </form>
</article>


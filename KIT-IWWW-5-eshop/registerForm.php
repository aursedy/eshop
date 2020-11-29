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
  $servername = "localhost";
  $username = "root";
  $password = "root";

  $login = $heslo= "";
  $loginErr = $hesloErr = "";

  if($_SERVER["REQUEST_METHOD"]=="POST"){

    if(empty($_POST["login"])){
      $loginErr = "uživatelské jméno je povinné !";
    }else{
      $login = test_input($_POST["login"]);

      if (!preg_match("/^[a-zA-Z-' ]*$/",$login)) {
      $loginErr = "Jen pismeno a bilí znaci jsou povoleni !";
      }
    }
    

    if(empty($_POST["heslo"])){
        $hesloErr = "heslo je povinné !";
    }else{
      $heslo = test_input($_POST["heslo"]);
    }
      
    //ukladám udaje do databaze
    try {
      $conn = new PDO("mysql:host=$servername;dbname=eshop_db", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stm =$conn->prepare("INSERT INTO Uzivatele(Login,Heslo) VALUES(:Login,:Heslo);");
      $stm->bindParam(':Login',$login);
      $stm->bindParam(':Heslo',$heslo);

      $stm->execute();

    } catch(PDOException $e) {
      echo "New data couldn't be recorded: " . $e->getMessage();
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
    <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

        <div class="row">
        <label>Uzivatelské jmeno(Login):</label>
        <input type="text" name="login" value="<?php echo $login ;?>">
        </div>
    
        <span class="error"><?php echo $loginErr;?></span>

        <div class="row">
        <label>Heslo:</label>
        <input type="password" name="heslo" value="<?php echo $heslo ;?>">
        </div>

        <span class="error"><?php echo $hesloErr;?></span>
          
        <div class="row">
        <label></label>
        <input type="submit" name="submit" value="Registrovat">
        </div>
    </form>
</article>
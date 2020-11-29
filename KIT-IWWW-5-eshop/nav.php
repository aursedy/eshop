  <?php 
   session_start();
  ?>
  <style>
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

    a:hover{
      background-color: darkolivegreen;
      color: white;
    }

  </style>
  <section>
    <ul>
      <li><a href="index.php">Home</a></li>
       <?php 
       if ($_SESSION['isLogged']) {     
         echo'<li><a href="kosik.php">Kosik</a></li>'; 
         echo'<li><a href="objednavky.php">Objednavky</a></li>';
         echo '<li><a href="logout.php">Logout</a></li>';
       }else{
         echo '<li><a href="login.php">Login</a></li>';
         echo '<li><a href="register.php">Register</a></li>';
       }

       ?>
    </ul>
  </section>

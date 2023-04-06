<?php
/*
* By Stefan Schumacher
*/

include ('./template/header.php');?>

<title>Register</title>
</head>

<body>
<?php include ('./template/navbar.php');

require_once("dbConnection.php");

if(isset($_POST["submit"])){
    $stmt = $mysql->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $count = $stmt->rowCount();

    if($count == 0){
        if($_POST["pw"] == $_POST["pw2"]){
            $stmt = $mysql->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $hash = password_hash($_POST["pw"], PASSWORD_BCRYPT);            
            $stmt->execute([$_POST['username'], $_POST['email'], $hash]);
            echo "<div class='container'><hr><h1>Dein Account wurde angelegt</h1><hr><br><br>";
            echo "<a href='login.php'>Zum Login</a></div>";
            include ('./template/footer.php');
            exit;
            } else {
                echo'<div class="alert alert-danger" role="alert">
                Die Passwörter stimmen nicht überein!
              </div>';

                //echo '<script>alert("Die Passwörter stimmen nicht überein!")</script>';                
            }
        }else {
            echo'<div class="alert alert-danger" role="alert">
            Der Username ist bereits vergeben
          </div>';
            //echo '<script>alert("Der Username ist bereits vergeben")</script>';  
        }
           
}
?>
                



<div class="container">
<hr>
<h1>Geben Sie hier Ihre Daten ein um ein neues Konto zu registrieren: </h1>
<hr>
<br>
<form action="register.php" method="post">
<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text" id="basic-addon1">Username</span>
</div>
<input type="text" class="form-control" name="username" placeholder="" required><br>
</div>
<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text" id="basic-addon1">E-Mail</span>
</div>
<input type="email" class="form-control" name="email" placeholder="" required><br>
</div>
<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text" id="basic-addon1">Passwort</span>
</div>
<input type="password" class="form-control" name="pw" placeholder="" required><br>
</div>
<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text" id="basic-addon1">Passwort wiederholen</span>
</div>
<input type="password" class="form-control" name="pw2" placeholder="" required><br>
</div>
<br>
<button class="btn btn-primary" type="submit" name="submit">Register</button><br>   
</form>
<br>
<hr>
<h4>Bereits registriert?</h4>
<a href="login.php">Login</a>
<br>
</div>
<?php include ('./template/footer.php');?>
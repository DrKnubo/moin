<?php
/*
* By Stefan Schumacher
*/

include ('./template/header.php');?>
<title>Login</title>
</head>
<body>
<?php include ('./template/navbar.php');?>
<div class="container">
<hr>
<h1>Geben Sie hier Ihre Zugangsdaten ein:</h1>
<hr>
<br>
<?php 

require_once("dbConnection.php");

if (isset($_COOKIE["login_cookie"])){
    $stmt = $mysql->prepare("SELECT * FROM users WHERE rememberToken = ?");
    $stmt->execute([$_COOKIE["login_cookie"]]);

    if($stmt->rowCount()==1){
        $row = $stmt->fetch();

        session_start();
        $_SESSION["username"] = $row["username"];
        $_SESSION["uId"] = $row["id"];
        header("Location: usersPage.php");        
    } else {
        setcookie("login_cookie", "", time() -1);
    }
}

if (isset($_POST["submit"])){ 
    $stmt = $mysql->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST["username"]]);
    $count = $stmt->rowCount();
    if ($count == 1){
        $row = $stmt->fetch();
        if (password_verify($_POST["pw"], $row["password"])){
            if(isset($_POST["rememberme"])){
                $token = bin2hex((random_bytes(16)));

                $stmt = $mysql->prepare("UPDATE users SET rememberToken = ? WHERE username = ?");
                $stmt->execute([$token, $_POST["username"]]);

                setcookie("login_cookie", $token, time()+ (3600*24*360));
            }

            session_start();
            $_SESSION["username"] = $row["username"];
            $_SESSION["uId"] = $row["id"];
            header("Location: usersPage.php");
        }
        else {
            echo "Login fehlgeschlagen";
        }        
    } else {
        echo"Login fehlgeschlagen";
    }
}

?>

<form action="login.php" method="post">
<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text" id="basic-addon1">Username</span>
</div>
<input type="text" class="form-control" name="username" placeholder="" required><br>
</div>
<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text" id="basic-addon1">Passwort</span>
</div>
<input type="password" class="form-control" name ="pw" placeholder="" required><br>
</div>

<div class="input-group mb-3">
<div class="input-group-prepend">
<div class="input-group-text">
<input type="checkbox" name="rememberme" aria-label="Checkbox for following text input"> &nbsp; Angemeldet bleiben
</div>
</div>
</div>
<br>
<button class="btn btn-primary" type="submit" name="submit">Login</button><br>   
</form>
<br>
<hr>
<h4>Neu hier?</h4>
<a href="register.php">Account anlegen</a>
</div>
<?php include ('./template/footer.php');?>
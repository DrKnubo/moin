<?php
/*
* By Stefan Schumacher
*/

include ('./template/header.php');?>
<title>Hauptseite</title>
</head>   
<body>
<?php include ('./template/navbarUserMainPage.php');
session_start();
if(!isset($_SESSION["username"])){
    header("Location: login.php");
    exit;
}
require_once("dbConnection.php");
$stmt = $mysql->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$userInhalt = $stmt->fetch();
$profstmt = $mysql->prepare("SELECT * FROM profiles WHERE user_id = ?");
$profstmt->execute([$_SESSION['uId']]);
$profileInhalt = $profstmt->fetch();

$name = $_SESSION["username"];
?>

<div class="container">
<hr>
<?php echo "<h1>Willkommen auf deiner Seite $name !</h1>"; ?>
<hr>
<br>
<?php 
$profcount = $profstmt->rowCount();
if ($profcount != 1)
{
    echo "<p>Dein Profil ist leer. <br> Du kannst es oben oder <a href='profil.php'>hier</a> bearbeiten bzw. löschen!</p>";
    echo "<p>Den Logout findest du oben oder <a href='logout.php'>hier</a> !</p>";
    include ('./template/footer.php');
    exit;

}
?>
<br>
<h2>Deine Profildaten lauten:</h2>
<hr>
<?php 
echo ('<p>Vorname: ' . $profileInhalt["first_name"] . '</p>');
echo ('<p>Nachname: ' . $profileInhalt["last_name"] . '</p>');
echo ('<p>Geboren am: ' . $profileInhalt["birth_date"] . '</p>'); 
echo ('<p>email: ' . $userInhalt["email"] . '</p>');
?>

<?php
     
     $picstmt = $mysql->prepare('SELECT picture_path FROM profiles WHERE user_id = ?');
     $picstmt->execute([$_SESSION['uId']]);
     $zwimage = $picstmt->fetchAll();
     $image = $zwimage[0];   
     ?>     
      
     <img src=<?=$image['picture_path'] ?> title=<?=$profileInhalt['first_name'] ?> width='200' height='200'> 
     <?php echo ('<p>Bild Pfad in Textform: ' . $profileInhalt["picture_path"] . '</p>'); ?>
  
     <hr>  
<br><br>

<p>Du kannst dein Profil oben oder <a href='profil.php'>hier</a> bearbeiten bzw. löschen!</p>
<br>
<p>Den Logout findest du oben oder <a href='logout.php'>hier</a> !</p>
<br><br>
<hr>		
<br>
</div>
<?php include ('./template/footer.php');?>
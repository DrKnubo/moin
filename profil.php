<?php
/*
* By Stefan Schumacher
*/
include ('./template/header.php');?>

<title>Profil</title>
</head>	
<body>
<?php include ('./template/navbarUserMainPage.php'); ?>

<div class="container">
	
<?php require_once("dbConnection.php"); 
session_start();

if(!isset($_SESSION["username"])){
    header("Location: login.php");
    exit;
}
	
$stmt = $mysql->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$_SESSION["username"]]);
$inhalt = $stmt->fetch();
$userEiDi = $inhalt[0];
$_SESSION["uId"] = $inhalt[0];

$stmt = $mysql->prepare("SELECT id FROM profiles WHERE user_id = ?");
$stmt->execute([$userEiDi]);    
$count = $stmt->rowCount();    

$stmtPic = $mysql->prepare("SELECT picture_path FROM profiles WHERE user_id = ?");
$stmtPic->execute([$userEiDi]);    
$countPic = $stmtPic->rowCount();  

if(isset($_POST["btProfilAbschicken"])){
	$stmt = $mysql->prepare("INSERT INTO profiles (id, user_id, first_name, last_name, picture_path, birth_date, created) VALUES (NULL, ?, ?, ?, ?, ? , current_timestamp());");
	$stmt->execute([$userEiDi, $_POST["tfvName"], $_POST["tfnName"], 'empty' , $_POST["tfbDate"]]);
	echo "<h4><b>Profil erstellt!</b></h4>";
	echo "<br><br><h4><b>Bild zufügen!</b></h4>";


echo'<form action="profil.php" method="post" enctype="multipart/form-data">
<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text">&nbsp;User Bild&nbsp;</span>
</div>
<div class="custom-file">
<input type="file" class="custom-file-input" name="imgUploadFile">
</div>
<input type="submit" id="submitIMG" name="submitIMG" value="Bild hochladen">
</div>';
include ('./template/footer.php');
exit;		
}


if(isset($_POST["btUpdate"])){
	$stmt = $mysql->prepare("UPDATE profiles SET first_name = ?, last_name = ?, birth_date = ?, created = current_timestamp() WHERE user_id = ?");
	$stmt->execute([$_POST["tfvName"] , $_POST["tfnName"], $_POST["tfbDate"], $_SESSION["uId"]]);

echo "<h3>Profil upgedatet</h3>";  
echo "<a href = 'usersPage.php'>zurueck</a>";
include ('./template/footer.php');
exit;		
}

if(isset($_POST["btDelete"])){
	$stmt = $mysql->prepare("DELETE FROM profiles WHERE user_id = ?");
	$stmt->execute([$_SESSION["uId"]]);

echo "<h3>Profil gelöscht</h3>";  
echo "<a href = 'usersPage.php'>zurueck</a>";
include ('./template/footer.php');
exit;		
}

if(isset($_POST['submitIMG'])) {	
$stmt = "UPDATE profiles SET picture_path = ? WHERE user_id = ?";
$statement = $mysql->prepare($stmt); 

	// File name
	$filename = $_FILES['imgUploadFile']['name'];
	
	// Location
	$target_file = './uploads/'.$filename;
	
	// file extension
	$file_extension = pathinfo(
		$target_file, PATHINFO_EXTENSION);
			
	$file_extension = strtolower($file_extension);
	
	// Valid image extension
	$valid_extension = array("png","jpeg","jpg","bmp");
	
	if(in_array($file_extension, $valid_extension)) {

		// Upload file
		if(move_uploaded_file(
			$_FILES['imgUploadFile']['tmp_name'],
			$target_file)
		) { 
			// Execute query
			$statement->execute(array($target_file, $_SESSION['uId']));
			header("Location: usersPage.php");
		}
	}
}
  

if($count != 0 && $countPic != 0){
	echo"<h4><b>Profil bereits komplett!</b></h4>";
	echo"<br><br>";	
	echo"<h4>Sie können Ihr Profil löschen</h4>";
	echo"<br><form action='profil.php' method='post'><input type='submit' id='btDelete' name='btDelete' value='Profil löschen'><br></form>";	
	echo"<br><br>";	
	echo"<h4>Sie können Ihr Profil bearbeiten</h4>";
	echo'
	<form action="profil.php" method="post">	
	<div class="input-group mb-3">
	<div class="input-group-prepend">
	<span class="input-group-text" id="basic-addon1">Vorname</span>
	</div>
	<input type="text" class="form-control" name="tfvName" placeholder="" required><br>
	</div>
	<div class="input-group mb-3">
	<div class="input-group-prepend">
	<span class="input-group-text" id="basic-addon1">Nachname</span>
	</div>
	<input type="text" class="form-control" name="tfnName" placeholder="" required><br>
	</div>
	<div class="input-group mb-3">
	<div class="input-group-prepend">
	<span class="input-group-text" id="basic-addon1">Geburtsdatum</span>
	</div>
	<input type="date" class="form-control" name="tfbDate" placeholder="" required><br>
	</div>
	<br>';
	
	echo"<form action='profil.php' method='post'><input type='submit' id='btUpdate' name='btUpdate' value='Update'></form>";
	echo"<br><br>";
	echo"<h4>Sie können Ihr Bild ändern</h4>";
	echo'<form action="profil.php" method="post" enctype="multipart/form-data">
	<div class="input-group mb-3">
	<div class="input-group-prepend">
	<span class="input-group-text">&nbsp;User Bild&nbsp;</span>
	</div>
	<div class="custom-file">
	<input type="file" class="custom-file-input" name="imgUploadFile">
	</div>
	<input type="submit" id="submitIMG" name="submitIMG" value="Bild hochladen">
	</div>';		
	
	echo "<br><h4> Oder hier <a href = 'usersPage.php'>zurück</a></h4>";
	include ('./template/footer.php');
	exit;
} else if ($count != 0 && $countPic == 0){
	echo"<br><br>";
	echo"<h4>Sie haben noch kein Bild hochgeladen!</h4>";
	echo"<br><br>";
	echo'<form action="profil.php" method="post" enctype="multipart/form-data">
	<div class="input-group mb-3">
	<div class="input-group-prepend">
	<span class="input-group-text">&nbsp;User Bild&nbsp;</span>
	</div>
	<div class="custom-file">
	<input type="file" class="custom-file-input" name="imgUploadFile">
	</div>
	<input type="submit" id="submitIMG" name="submitIMG" value="Bild hochladen">
	</div>';		
	
	echo "<br><p> oder hier <a href = 'usersPage.php'>zurueck</a></p>";
	include ('./template/footer.php');
	exit;
}
else {
	echo'<h3>Ihre Daten</h3>
	<form action="profil.php" method="post">	
	<div class="input-group mb-3">
	<div class="input-group-prepend">
	<span class="input-group-text" id="basic-addon1">Vorname</span>
	</div>
	<input type="text" class="form-control" name="tfvName" placeholder="" required><br>
	</div>
	<div class="input-group mb-3">
	<div class="input-group-prepend">
	<span class="input-group-text" id="basic-addon1">Nachname</span>
	</div>
	<input type="text" class="form-control" name="tfnName" placeholder="" required><br>
	</div>
	<div class="input-group mb-3">
	<div class="input-group-prepend">
	<span class="input-group-text" id="basic-addon1">Geburtsdatum</span>
	</div>
	<input type="date" class="form-control" name="tfbDate" placeholder="" required><br>
	</div>
	<br>
	<br>
	<input type="submit" id="btProfilAbschicken" name="btProfilAbschicken" value="Absenden">
	</form>
	</div>';
	include ('./template/footer.php');
}
?>
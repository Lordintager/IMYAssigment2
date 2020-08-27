<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$target_dir = "gallery/";
	$targetFile;//The directory of the file to ve uploaded
	$file; // File to be uploaded

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false

	//check if an image is uploaded

	if (isset($_FILES["picToUpload"])){
		$file = $_FILES["picToUpload"];
		$targetFile = $target_dir.basename($file["name"]);


		// print_r($targetFile);

		if ($file["type"] == "image/jpeg"|| $file["type"] == "image/jpg" && $file["size"] < 1024) {
			if(move_uploaded_file($file["tmp_name"], $targetFile)){
				echo "File Uploaded";

				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);

				if($row = mysqli_fetch_array($res)){

					$imageAdd = "INSERT INTO tbgallery (user_id, filename) VALUES ('".$row['user_id']."','".$file["name"]."')";
					if ($mysqli->query($imageAdd)) {
						echo "Image saved to profile";
						# code...
					}
					else
					{
						echo "Could not connect to profile";
					}
				}
				else{
					echo "No user";
				}


				// $imageAdd = "INSERT INTO tbgallery"
			}
			else{
				echo "Upload Failed";
			}
			# code...
		}
		else{
			echo "Stuff is not right";
		}
		
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Name Surname">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$getImages;
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					$getImages = "SELECT * FROM tbgallery WHERE user_id = '" . $row["user_id"] ."'";

					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";

				
					echo 	"<form method='POST' action='login.php' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type = 'hidden' name='loginEmail' value='".$email."'>
									<input type = 'hidden' name='loginPass' value='".$pass."'>

									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
						  	</form>";

						  	
					echo "<div class='row imageGallery'>";

					$images = $mysqli->query($getImages);

					print_r($images);
					
					if ($images->num_rows > 0) {
					  // output data of each row
					  	while($imageRow = $images->fetch_assoc()) {
					    	echo "<div class='col-3' style='background-image: url(gallery/". $imageRow['filename'].")'></div>";
					  	}
					  }

					echo "</div>";
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
	</div>
</body>
</html>
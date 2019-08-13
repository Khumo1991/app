<!DOCTYPE html>
<html>
<head>
	<title>Employee Details</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" typr="text/css" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/js/bootstrap.js">


</head>
<body>
	<div>
		<?php 
		session_start();
		$_SESSION['message'] = '';
		$mysqli = new mysqli('localhost','root','','employeedetails');

		if (isset($_POST['apply'])) {
			$file = $_FILES['profilephoto'];

			$fileName = $_FILES['file']['name'];
			$fileTmpName = $_FILES['file']['tmp_name'];
			$fileSize = $_FILES['file']['size'];
			$fileError = $_FILES['file']['size'];
			$fileType = $_FILES['file']['type'];

			$fileExt = explode('.', $fileName);
			$fileActualExt = strtolower(end($fileExt));

			$allowed = array('jpg', 'jpeg', 'png', 'pdf', 'doc');

			if (in_array($fileActualExt, $allowed)){
				if ($fileError === 0){
					if ($fileSize < 2000){
						$fileNameNew = uniqid('', true).".".$fileActualExt;
						$fileDestinatioin = 'image/'.$fileNameNew;
						move_uploaded_file($fileTmpName, $fileDestinatioin);
						header("Location: empdetails.php?uploadsucess");

					}
					else{
						echo "Your file is too big!";
					}

				}
				else{
					echo "There was an error uploading your file";
				}

			}
			else{
				echo "You can not upload files of this type.";
			}
			//echo "Application Successful";

			if ($_SERVER['REQUEST_METHOD'] == 'POST'){

			$firstname = $mysqli->real_escape_string($_POST['firstname']);
			$lastname = $mysqli->real_escape_string($_POST['lastname']);
			$gender = $mysqli->real_escape_string($_POST['gender']);
			$dob = $mysqli->real_escape_string($_POST['dob']);
			$email = $mysqli->real_escape_string($_POST['email']);
			$occupation = $mysqli->real_escape_string($_POST['occupation']);
			$salaryrange = $mysqli->real_escape_string($_POST['salary']);
			$profilephoto_path = $mysqli->real_escape_string('image/'.$_FILES['profilephoto']['name']);
			$resume_path = $mysqli->real_escape_string('resume/'.$_FILES['resume']['name']); 

			//make sure file type is image
			if (preg_match("!image!", $_FILES['profilephoto']['type'])){
				//copy image to image/ folder
				if (copy($_FILES['profilephoto'][tmp_name], $profilephoto_path)){
					$_SESSION['firstname'] = $firstname;
					$_SESSION['profilephoto'] = $profilephoto_path;

					$sql = "INSERT INTO employee (firstname,lastname,gender,birthday,email,occupation,salaryRange,profilephoto,resumeCV) " . "VALUES ('$firstname','$lastname','$gender','$dob','$email','$occupation','$salaryrange','$profilephoto_path','$resume_path')";

					//if the query is successful, redirect to application.php page, done
					if ($mysqli->query($sql) == true) {
						$_SESSION['message'] = 'Application Successful! Added $firstname $lastname to the database!';
						header("location: empdetails.php");
					}
					else {
						$_SESSION['message'] = "Application could not be processed.";
					}
				}
				else {
					$_SESSION['message'] = "Photo Upload failed!";
				}
			}
			else{
				$_SESSION['message'] = "Please only upload GIF, JPG, or PNG images.";

			}


			//make sure file type is document
			if (preg_match("!resume!", $_FILES['resume']['type'])){
				if (copy($_FILES['resume'][tmp_name], $resume_path)){
					$_SESSION['resume'] = $resume_path;

					$sql = "INSERT INTO employee (firstname,lastname,gender,birthday,email,occupation,salaryRange,profilephoto,resumeCV) " . "VALUES ('$firstname','$lastname','$gender','$dob','$email','$occupation','$salaryrange','$profilephoto_path','$resume_path')";


					//if the query is successful, redirect to application.php page, done
					if ($mysqli->query($sql) == true) {
						$_SESSION['message'] = 'Application Successful! Added $firstname $lastname to the database!';
						header("location: empdetails.php");
					}
					else {
						$_SESSION['message'] = "Application could not be processed.";
					}
				}
				else {
					$_SESSION['message'] = "Resume Upload failed!";
				}
			}
			else{
				$_SESSION['message'] = "Please only upload PDF, DOCX files.";

			}
		}
	}

		 ?>
	</div>
	<div id="frm">
		<form action="empdetails.php" method="POST" enctype="multipart/form-data">
			<div class="alert alert-error"><?= $_SESSION['message'] ?></div>
			<div class="container">
			<h1>Application Form</h1>
			<p>
				<label>First Name:</label>
				<input type="text" id="firstname" name="firstname" required="true" class="input-field" placeholder="John">
			</p>
			<p>
				<label>Last Name:</label>
				<input type="text" id="lastname" name="lastname" required="true" class="input-field" placeholder="Smith">
			</p>
			<p>
				<label>Gender:</label>
				<input type="radio" id="gender" name="gender" value="male">Male
				<input type="radio" id="gender" name="gender" value="female">Female<br>
			</p>
			<p>
				<label>Date of Birth:</label>
				<input type="date" id="dob" name="dob" required="true">
			</p>
			<p>
				<label>Email:</label>
				<input type="email" id="email" name="email" required="true" class="input-field" placeholder="john.smith@gmail.com">
			</p>
			<p>
				<label>Occupation:</label>
				<select name="occupation" required="true" class="input-field" placeholder="Occupation">
					<option value="Student">Student</option>
					<option value="Engineer">Engineer</option>
					<option value="Doctor">Doctor</option>
					<option value="Graphic Designer">Graphic Designer</option>
					<option value="Advertising/Marketing">Advertising/Marketing</option>
					<option value="Quantity Surveyor">Quantity Surveyor</option>
					<option value="Electrician">Electrician</option>
					<option value="Physiotherapist">Physiotherapist</option>
					<option value="Pharmacist">Pharmacist</option>
					<option value="Project Manager">Project Manager</option>
					<option value="Others">Others</option>
				</select>
			</p>
			<p>
				<label>Salary Range:</label>
				<select name="salary">
					<option value="0 - 2500 BWP">0 - 2500 BWP</option>
					<option value="2501 - 5000 BWP">2501 - 5000 BWP</option>
					<option value="5001 - 10000 BWP">5001 - 10000 BWP</option>
					<option value="10001 - 25000 BWP">10001 - 25000 BWP</option>
					<option value="25001 - more BWP">25001 - more BWP</option>
				</select>
			</p> 
			<label>Uploading Files</label>
			<p>
				<input type="file" name="profilephoto">
			</p>
			<p>
				<input type="file" name="resume">
			</p>
			<p>
				<input type="submit" name="apply" id="btnapply" value="APPLY">
			</p></div>
		
		</form>
		
	</div>

</body>
</html>
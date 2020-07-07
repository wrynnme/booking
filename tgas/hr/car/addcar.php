<?php
require_once('./../../../config.php');
@session_start();
// CHECK LOGIN
if (!isset($_SESSION['emp_id'])) {
	hd("/booking/logout");
} elseif (strtolower($_SESSION['emp_position']) != "hr approval" && strtolower($_SESSION['emp_position']) != "administrator" || $_SESSION['emp_factory'] != "1") {
	hd("/booking/");
}
include './../../header.php';
?>
<?php
if (isset($_POST['done'])) {
	$license_plate    = $_POST['inputlicense_plate'];
	$driver_name      = $_POST['intputdriver_name'];
	$driver_tel       = $_POST['inputdriver_tel'];
	$car_brand        = $_POST['inputcar_brand'];
	$car_color        = $_POST['inputcar_color'];
	$car_factory      = 1;
	$car_status       = $_POST['inputcar_status'];
	$car_descriptions = $_POST['inputcar_descriptions'];
	$file             = $_FILES['file'];
	$fileName         = $_FILES['file']['name'];
	$fileTmpName      = $_FILES['file']['tmp_name'];
	$fileSize         = $_FILES['file']['size'];
	$fileError        = $_FILES['file']['error'];
	$fileType         = $_FILES['file']['type'];
	$fileExt          = explode('.', $fileName);
	$fileActualExt    = strtolower(end($fileExt));
	$allowed          = array('jpg', 'jpeg', 'png', 'pdf');
	if ($license_plate == "" || $car_factory == "" || $car_status == "") {
		sc("Please fill out the form. !!");
	} else {
		$qy1 = $con->query("SELECT * FROM `car` WHERE license_plate ='$license_plate'");
		$ck1 = $qy1->num_rows;
		if ($ck1 != 0) {
			sc("This car was already. Please try agian. !!");
		} else {
			if (in_array($fileActualExt, $allowed)) {
				if ($fileError === 0) {
					if ($fileSize < 5e+7) {
						$fileNameNew = uniqid('', true) . "." . $fileActualExt;
						$fileDestination = '../../.././images/uploads/car/' . $fileNameNew;
						move_uploaded_file($fileTmpName, $fileDestination);
						$con->query("INSERT INTO car VALUEs (NULL, '$license_plate', '$driver_name', '$driver_tel', '$car_brand', '$car_color', '$fileNameNew', '$car_factory', '$car_status', NULL)");
						ao("Add car successfully. !", "/booking/TGAS/HR/car/");
						// header("Location: index.php?uploadsuccess");
					} else {
						sc("Your file is too big.!");
					}
				} else {
					sc("There was an error upload file.!");
				}
			} else {
				$con->query("INSERT INTO car VALUEs (NULL, '$license_plate', '$driver_name', '$driver_tel', '$car_brand', '$car_color', NULL , '$car_factory', '$car_status', NULL)");
				ao("Add car without photo successfully. !", "/booking/TGAS/HR/car/");
				// header("Location: index.php?uploadsuccess");
				// sc("You cannot upload files of this type.!");
			}
		}
	}
}
?>
<html>

<head>
	<meta charset="utf-8">
	<title></title>
	<style media="screen">
		/* Downloaded from https://www.codeseek.co/ */
		.inputDnD .form-control-file {
			position: relative;
			width: 100%;
			/*height: 100%;*/
			min-height: 6em;
			outline: none;
			visibility: hidden;
			cursor: pointer;
			background-color: #c61c23;
			box-shadow: 0 0 5px solid currentColor;
		}

		.inputDnD .form-control-file:before {
			content: attr(data-title);
			position: absolute;
			top: 0.5em;
			left: 0;
			width: 100%;
			min-height: 6em;
			line-height: 2em;
			padding-top: 1.5em;
			opacity: 1;
			visibility: visible;
			text-align: center;
			border: 0.25em dashed currentColor;
			-webkit-transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
			transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
			overflow: hidden;
		}

		.inputDnD .form-control-file:hover:before {
			border-style: solid;
			box-shadow: inset 0px 0px 0px 0.25em currentColor;
		}

		body {
			background-color: #f7f7f9;
		}
	</style>
	<script language="JavaScript">
		/* Downloaded from https://www.codeseek.co/ */
		"use strict";

		function readUrl(input) {

			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					var imgData = e.target.result;
					var imgName = input.files[0].name;
					input.setAttribute("data-title", imgName);
					console.log(e.target.result);
				};
				reader.readAsDataURL(input.files[0]);
			}
		}
	</script>
</head>

<body>
	<br>
	<div class="container">
		<h2>Add Car</h2>
		<br>
		<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
			<div class="form-group row">
				<label for="inputlicense_plate" class="col-sm-3 col-form-label"><b>License Plate : </b></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="inputlicense_plate" id="inputlicense_plate" placeholder="กข-1234">
				</div>
			</div>
			<div class="form-group row">
				<label for="intputdriver_name" class="col-sm-3 col-form-label"><b>Driver name : </b></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="intputdriver_name" id="intputdriver_name" placeholder="">
				</div>
			</div>
			<div class="form-group row">
				<label for="inputdriver_tel" class="col-sm-3 col-form-label"><b>Driver telephone number : </b></label>
				<div class="col-sm-9">
					<input type="number" size="10" class="form-control" name="inputdriver_tel" id="inputdriver_tel" placeholder="08xxxxxxxx">
					<small id="emailHelp" class="form-text text-muted">Don't fill in more than 10 characters.</small>
				</div>
			</div>
			<div class="form-group row">
				<label for="inputcar_brand" class="col-sm-3 col-form-label"><b>Car brand : </b></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="inputcar_brand" id="inputcar_brand" placeholder="">
				</div>
			</div>
			<div class="form-group row">
				<label for="inputcar_color" class="col-sm-3 col-form-label"><b>Car color : </b></label>
				<div class="col-sm-9">
					<input class="form-control" type="color" value="#563d7c" id="inputcar_color" name="inputcar_color" style="height: 38px;">
				</div>
			</div>

			<div class="form-group row">
				<label for="inputcar_status" class="col-sm-3 col-form-label"><b>Car status : </b></label>
				<div class="col-sm-9">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-primary">
							<input type="radio" name="inputcar_status" id="inputcar_status1" autocomplete="off" value="1"> Enable
						</label>
						<label class="btn btn-primary">
							<input type="radio" name="inputcar_status" id="inputcar_status2" autocomplete="off" value="2"> Disable
						</label>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-9 ml-sm-auto">
					<button type="button" class="btn btn-primary btn-block" name="" onclick="document.getElementById('image_name').click()">Add Image</button>
					<div class="form-group inputDnD">
						<label class="sr-only" for="image_name">File Upload</label>
						<input type="file" class="form-control-file text-primary font-weight-bold" name="file" id="image_name" accept="image/*" onchange="readUrl(this)" data-title="Drag and drop a file">
					</div>
				</div>
			</div>
			<button type="submit" name="done" class="btn btn-primary">Submit</button>
			<button type="reset" name="reset" class="btn btn-danger">Reset</button>
		</form>
	</div>
</body>

</html>
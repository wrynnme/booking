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
	<div class="container">
		<?php
		$qr = $con->query("SELECT * FROM `car` WHERE `car_id` = '$_GET[car_id]'");
		$r = $qr->fetch_array(MYSQLI_ASSOC);
		if (isset($_POST['cancle'])) {
			hd("/car/");
		}
		if (isset($_POST['delpt'])) {
			$con->query("UPDATE `car` SET `car_options` = NULL WHERE `car`.`car_id` = '$_GET[car_id]'");
			sc("The photo is meeting room has dealt. !");
		}
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
			// al("$license_plate"." "."$driver_name"." "."$driver_tel"." "."$car_brand"." "."$car_color"." "."$car_factory"." "."$car_status");
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
				if ($car_status != $r['car_status'] && $license_plate == $r['license_plate'] && $car_factory == $r['car_factory']) {
					$con->query("UPDATE `car` SET `car_status` = '$car_status' WHERE `car_id` = '$_GET[car_id]'");
					ao("Updated successfully. !!", '/booking/TGAS/HR/car/');
				} elseif ($license_plate != $r['license_plate'] || $car_factory != $r['car_factory']) {
					$qy1 = $con->query("SELECT * FROM `car` WHERE `license_plate` = '$license_plate ' AND `car_factory` = '$car_factory'");
					$ck1 = $qy1->num_rows;
					if ($ck1 != 0) {
						sc("This car was already. Please try again. !!");
					} else {
						$con->query("UPDATE `car` SET `license_plate` = '$license_plate', `driver_name` = '$driver_name', `driver_tel` = '$driver_tel', `car_brand` = '$car_brand', `car_color` = '$car_color', `car_factory` = '$car_factory', `car_status` = '$car_status' WHERE `car_id` = '$_GET[car_id]'");
						ao("Updated successfully. !!", '/booking/TGAS/HR/car/');
					}
				} elseif ($car_status == $r['car_status'] && $license_plate == $r['license_plate'] && $car_factory == $r['car_factory'] && $driver_name == $r['driver_name'] && $driver_tel == $r['driver_tel'] && $car_brand == $r['car_brand'] && $car_color == $r['car_color'] && $file == '') {
					ao("not update. !", "/booking/TGAS/HR/car/");
				} elseif ($file != '') {
					if (in_array($fileActualExt, $allowed)) {
						if ($fileError === 0) {
							if ($fileSize < 5.243e+6) {
								$fileNameNew = uniqid('', true) . "." . $fileActualExt;
								$fileDestination = '../../.././images/uploads/car/' . $fileNameNew;
								move_uploaded_file($fileTmpName, $fileDestination);
								$con->query("UPDATE `car` SET `license_plate` = '$license_plate', `driver_name` = '$driver_name', `driver_tel` = '$driver_tel', `car_brand` = '$car_brand', `car_color` = '$car_color', `car_options` = '$fileNameNew', `car_factory` = '$car_factory', `car_status` = '$car_status' WHERE `car_id` = '$_GET[car_id]'");
								ao("Add photo car successfully. !", "/booking/TGAS/HR/car/");
								header("Location: index.php?uploadsuccess");
							} else {
								sc("Your file is too big.!");
							}
						} else {
							sc("There was an error upload file.!");
						}
					} else {
						sc("You cannot upload files of this type.!");
					}
				} elseif ($driver_name != "" || $r['driver_name'] == "" || $driver_tel != "" || $r['driver_tel'] == "" || $car_brand != "" || $r['car_brand'] == "" || $car_color != "" || $r['car_color'] == "") {
					$con->query("UPDATE `car` SET `driver_name` = '$driver_name', `driver_tel` = '$driver_tel', `car_brand` = '$car_brand', `car_color` = '$car_color' WHERE `car_id` = '$_GET[car_id]'");
					ao("Updated successfully. !", "/booking/TGAS/HR/car/");
				}
			}
		}
		?>
		<br>
		<h2>Edit Car <?php echo $r['license_plate'] ?></h2>
		<br>
		<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
			<div class="form-group row">
				<label for="inputlicense_plate" class="col-sm-3 col-form-label "><b>License plate : </b></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="inputlicense_plate" id="inputlicense_plate" value="<?php echo $r['license_plate'] ?>">
				</div>
			</div>
			<div class="form-group row">
				<label for="intputdriver_name" class="col-sm-3 col-form-label "><b>Driver name : </b></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="intputdriver_name" id="intputdriver_name" value="<?php echo $r['driver_name'] ?>">
				</div>
			</div>
			<div class="form-group row">
				<label for="inputdriver_tel" class="col-sm-3 col-form-label "><b>Driver telephone : </b></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="inputdriver_tel" id="inputdriver_tel" value="<?php echo $r['driver_tel'] ?>">
				</div>
			</div>
			<div class="form-group row">
				<label for="inputcar_brand" class="col-sm-3 col-form-label "><b>Car brand : </b></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="inputcar_brand" id="inputcar_brand" value="<?php echo $r['car_brand'] ?>">
				</div>
			</div>
			<div class="form-group row">
				<label for="inputcar_color" class="col-sm-3 col-form-label "><b>Car color : </b></label>
				<div class="col-sm-9">
					<?php $a = $r['car_color']; ?>
					<input class="form-control" type="color" value="<?php echo $r['car_color'] ?>" id="inputcar_color" name="inputcar_color" style="height: 38px;">
				</div>
			</div>

			<div class="form-group row">
				<label for="inputcar_status" class="col-sm-3 col-form-label"><b>Car status : </b></label>
				<div class="col-sm-9">
					<div class="btn-group" data-toggle="buttons">
						<?php
						if ($r['car_status'] == '1') {
							echo '<label class="btn btn-primary active">';
							echo '<input type="radio" name="inputcar_status" id="inputcar_status1" autocomplete="off" value="1" checked> Enable';
							echo '</label>';
							echo '<label class="btn btn-primary">';
							echo '<input type="radio" name="inputcar_status" id="inputcar_status2" autocomplete="off" value="2"> Disable';
							echo '</label>';
						} elseif ($r['car_status'] == '2') {
							echo '<label class="btn btn-primary">';
							echo '<input type="radio" name="inputcar_status" id="inputcar_status1" autocomplete="off" value="1"> Enable';
							echo '</label>';
							echo '<label class="btn btn-primary active">';
							echo '<input type="radio" name="inputcar_status" id="inputcar_status2" autocomplete="off" value="2" checked> Disable';
							echo '</label>';
						} ?>
					</div>
				</div>
			</div>
			<?php
			if ($r['car_options'] == NULL || $r['car_options'] == "") { ?>
				<div class="form-group row">
					<div class="col-sm-6 offset-sm-3">
						<button type="button" class="btn btn-primary btn-block" name="" onclick="document.getElementById('image_name').click()">Add Image</button>
						<div class="form-group inputDnD">
							<label class="sr-only" for="image_name">File Upload</label>
							<input type="file" class="form-control-file text-primary font-weight-bold" name="file" id="image_name" accept="image/*" onchange="readUrl(this)" data-title="Drag and drop a file">
						</div>
					</div>
				</div>
			<?php } else { ?>
				<div class="form-group row"><label for="file" class="col-sm-3 col-form-label"><b>Car :</b></label>
					<div class="col-sm-9">
						<img src="/booking/images/uploads/car/<?php echo $r['car_options'] ?>" alt="" class="img-fluid">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-9 ml-md-auto">
						<button type="submit" name="delpt" class="btn btn-outline-warning btn-block">DELETE PHOTO</button>
					</div>
				</div>
			<?php } ?>
			<button type="submit" name="done" class="btn btn-sm btn-primary">Submit</button>
		</form>
	</div>
</body>

</html>
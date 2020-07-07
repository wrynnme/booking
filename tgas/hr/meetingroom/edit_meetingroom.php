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
		$qr = $con->query("SELECT * FROM `meeting_room` WHERE `mtr_id` = '$_GET[mtr_id]'");
		$r = $qr->fetch_array(MYSQLI_ASSOC);
		if (isset($_POST['cancle'])) {
			hd("/meetingroom/");
		}
		if (isset($_POST['delpt'])) {
			$con->query("UPDATE `meeting_room` SET `mtr_options` = NULL WHERE `meeting_room`.`mtr_id` = '$_GET[mtr_id]'");
			sc("The photo is meeting room has dealt. !");
		}
		?>
		<?php
		if (isset($_POST['done'])) {
			$nm = $_POST['inputMTR'];
			$mtr_tel = $_POST['inputMTR_tel'];
			$ft = 1;
			$st = $_POST['inputMTR_status'];
			$file          = $_FILES['file'];
			$fileName      = $_FILES['file']['name'];
			$fileTmpName   = $_FILES['file']['tmp_name'];
			$fileSize      = $_FILES['file']['size'];
			$fileError     = $_FILES['file']['error'];
			$fileType      = $_FILES['file']['type'];
			$fileExt       = explode('.', $fileName);
			$fileActualExt = strtolower(end($fileExt));
			$allowed       = array('jpg', 'jpeg', 'png', 'pdf');
			if ($nm == "" || $ft == "" || $st == "") {
				sc("Please fill out the form. !");
			} else {
				if ($st != $r['mtr_status'] && $nm == $r['mtr_number'] && $ft == $r['mtr_factory']) {
					$con->query("UPDATE meeting_room SET mtr_status = '$st' WHERE mtr_id = '$_GET[mtr_id]'");
					ao("Updated status this room successfully. !", "/booking/tgas/hr/meetingroom/");
				} elseif ($nm != $r['mtr_number'] || $ft != $r['mtr_factory']) {
					$qy1 = $con->query("SELECT * FROM `meeting_room` WHERE `mtr_number` = '$nm' AND `mtr_factory` = '$ft'");
					$ck1 = $qy1->num_rows;
					if ($ck1 != 0) {
						sc("This room was already. Please try again. !");
					} else {
						$con->query("UPDATE `meeting_room` SET `mtr_number` = '$nm', `mtr_tel` = '$mtr_tel', `mtr_factory` = '$ft', `mtr_status` = '$st', `mtr_options` = '$fileNameNew' WHERE `mtr_id` = '$_GET[mtr_id]'");
						ao("Updated this room successfully. !", "/booking/tgas/hr/meetingroom/");
					}
				} elseif ($st == $r['mtr_status'] && $nm == $r['mtr_number'] && $ft == $r['mtr_factory'] && $file == '' && $mtr_tel == $r['mtr_tel']) {
					ao("not update. !", "/booking/TGAS/HR/meetingroom/");
				} elseif ($mtr_tel != $r['mtr_tel'] && $mtr_tel != '') {
					$con->query("UPDATE meeting_room SET mtr_tel = '$mtr_tel' WHERE mtr_id = '$_GET[mtr_id]'");
					ao("Updated this room successfully. !", "/booking/tgas/hr/meetingroom/");
				} elseif ($file != '') {
					if (in_array($fileActualExt, $allowed)) {
						if ($fileError === 0) {
							if ($fileSize < 5.243e+6) {
								$fileNameNew = uniqid('', true) . "." . $fileActualExt;
								$fileDestination = '../../.././images/uploads/mtr/' . $fileNameNew;
								move_uploaded_file($fileTmpName, $fileDestination);
								$con->query("UPDATE `meeting_room` SET `mtr_number` = '$nm', `mtr_tel` = '$mtr_tel', `mtr_factory` = '$ft', `mtr_status` = '$st', `mtr_options` = '$fileNameNew' WHERE `mtr_id` = '$_GET[mtr_id]'");
								ao("Add photo meeting room successfully. !", "/booking/TGAS/HR/meetingroom/");
								// header("Location: index.php?uploadsuccess");
							} else {
								sc("Your file is too big.!");
							}
						} else {
							sc("There was an error upload file.!");
						}
					} else {
						sc("You cannot upload files of this type.!");
					}
				}
			}
		}
		?>
		<br>
		<h2>Edit Meeting Room <?php echo $r['mtr_number'] ?></h2>
		<br>
		<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
			<div class="form-group row">
				<label for="inputMTR" class="col-sm-3 col-form-label "><b>Meeting room Number : </b></label>
				<div class="col-sm-9">
					<input type="number" class="form-control" name="inputMTR" id="inputMTR" value="<?php echo $r['mtr_number'] ?>">
				</div>
			</div>
			<div class="form-group row">
				<label for="inputMTR_tel" class="col-sm-3 col-form-label "><b>Telephone number : </b></label>
				<div class="col-sm-9">
					<input type="number" class="form-control" name="inputMTR_tel" id="inputMTR_tel" value="<?php echo $r['mtr_tel'] ?>">
				</div>
			</div>

			<div class="form-group row">
				<label for="inputMTR_status" class="col-sm-3 col-form-label"><b>Meeting room Status : </b></label>
				<div class="col-sm-9">
					<div class="btn-group" data-toggle="buttons">
						<?php
						if ($r['mtr_status'] == '1') {
							echo '<label class="btn btn-primary active">';
							echo '<input type="radio" name="inputMTR_status" id="inputMTR_status1" autocomplete="off" value="1" checked> Enable';
							echo '</label>';
							echo '<label class="btn btn-primary">';
							echo '<input type="radio" name="inputMTR_status" id="inputMTR_status2" autocomplete="off" value="2"> Disable';
							echo '</label>';
						} elseif ($r['mtr_status'] == '2') {
							echo '<label class="btn btn-primary">';
							echo '<input type="radio" name="inputMTR_status" id="inputMTR_status1" autocomplete="off" value="1"> Enable';
							echo '</label>';
							echo '<label class="btn btn-primary active">';
							echo '<input type="radio" name="inputMTR_status" id="inputMTR_status2" autocomplete="off" value="2" checked> Disable';
							echo '</label>';
						} ?>
					</div>
				</div>
			</div>
			<?php
			if ($r['mtr_options'] == NULL || $r['mtr_options'] == "") { ?>
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
				<div class="form-group row">
					<label for="file" class="col-auto mr-auto col-form-label"><b>Meeting room :</b></label>
					<div class="col-sm-9">
						<img src="/booking/images/uploads/mtr/<?php echo $r['mtr_options'] ?>" alt="" class="img-fluid">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-9 ml-md-auto">
						<button type="submit" name="delpt" class="btn btn-outline-warning btn-block">DELETE PHOTO</button>
					</div>
				</div>
			<?php } ?>
			<button type="submit" name="done" class="btn btn-primary">Submit</button>
		</form>
	</div>
</body>

</html>
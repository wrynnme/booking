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
		$qr = $con->query("SELECT * FROM `interpreter` WHERE `itp_id` = '$_GET[itp_id]'");
		$r = $qr->fetch_array(MYSQLI_ASSOC);
		if (isset($_POST['cancle'])) {
			hd("/itp/");
		}
		if (isset($_POST['delpt'])) {
			$con->query("UPDATE `interpreter` SET `itp_options` = NULL WHERE `interpreter`.`itp_id` = '$_GET[itp_id]'");
			sc("The photo is meeting room has dealt. !");
		}
		?>
		<?php
		if (isset($_POST['done'])) {
			$itp_name         = $_POST['inputitp_name'];
			$itp_tel          = $_POST['inputitp_tel'];
			$itp_email        = $_POST['inputitp_email'];
			$itp_dept         = $_POST['inputitp_dept'];
			$itp_factory      = 1;
			$itp_status       = $_POST['inputitp_status'];
			$itp_descriptions = $_POST['inputitp_descriptions'];
			$file             = $_FILES['file'];
			$fileName         = $_FILES['file']['name'];
			$fileTmpName      = $_FILES['file']['tmp_name'];
			$fileSize         = $_FILES['file']['size'];
			$fileError        = $_FILES['file']['error'];
			$fileType         = $_FILES['file']['type'];
			$fileExt          = explode('.', $fileName);
			$fileActualExt    = strtolower(end($fileExt));
			$allowed          = array('jpg', 'jpeg', 'png', 'pdf');
			if ($itp_name == "" || $itp_factory == "" || $itp_status == "") {
				sc("Please fill out the form. !!");
			} else {
				if ($itp_status != $r['itp_status'] && $itp_name == $r['itp_name'] && $itp_factory == $r['itp_factory']) {
					$con->query("UPDATE `interpreter` SET `itp_status` = '$itp_status' WHERE `itp_id` = '$_GET[itp_id]'");
					ao("Updated status interpreter successfully. !!", '/booking/TGAS/HR/interpreter/');
				} elseif ($itp_name != $r['itp_name'] || $itp_factory != $r['itp_factory']) {
					$qy1 = $con->query("SELECT * FROM `interpreter` WHERE `itp_name` = '$itp_name ' AND `itp_factory` = '$itp_factory'");
					$ck1 = $qy1->num_rows;
					if ($ck1 != 0) {
						sc("This interpreter was already. Please try again. !!");
					} else {
						$con->query("UPDATE `interpreter` SET `itp_name` = '$itp_name', `itp_tel` = '$itp_tel', `itp_email` = '$itp_email', `itp_dept` = '$itp_dept', `itp_factory` = '$itp_factory', `itp_status` = '$itp_status' WHERE `itp_id` = '$_GET[itp_id]'");
						ao("Updated interpreter successfully. !!", '/booking/TGAS/HR/interpreter/');
					}
				} elseif ($r['itp_tel'] == '' || $itp_tel != $r['itp_tel'] || $r['itp_email'] == '' || $itp_email != $r['itp_email'] || $r['itp_dept'] == '' || $itp_dept != $r['itp_dept']) {
					$con->query("UPDATE `interpreter` SET `itp_tel` = '$itp_tel', `itp_email` = '$itp_email', `itp_dept` = '$itp_dept' WHERE `itp_id` = '$_GET[itp_id]' ");
					ao("Updated successfully. !!", '/booking/TGAS/HR/interpreter/');
				} elseif ($itp_status == $r['itp_status'] && $itp_name == $r['itp_name'] && $itp_factory == $r['itp_factory'] && $file == '' && $itp_email == $r['itp_email'] && $itp_dept == $r['itp_dept']) {
					ao("not update. !", "/booking/TGAS/HR/interpreter/");
				} elseif ($file != '') {
					if (in_array($fileActualExt, $allowed)) {
						if ($fileError === 0) {
							if ($fileSize < 5.243e+6) {
								$fileNameNew = uniqid('', true) . "." . $fileActualExt;
								$fileDestination = '../../.././images/uploads/itp/' . $fileNameNew;
								move_uploaded_file($fileTmpName, $fileDestination);
								$con->query("UPDATE `interpreter` SET `itp_name` = '$itp_name', `itp_tel` = '$itp_tel', `itp_email` = '$itp_email', `itp_dept` = '$itp_dept', `itp_factory` = '$itp_factory', `itp_status` = '$itp_status', `itp_options` = '$fileNameNew' WHERE `itp_id` = '$_GET[itp_id]'");
								ao("Add photo interpreter successfully. !", "/booking/TGAS/HR/interpreter/");
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
		<br><br>
		<h2>Edit interpreter <?php echo $r['itp_name'] ?></h2>
		<br>
		<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
			<div class="form-group row">
				<label for="inputitp_name" class="col-sm-3 col-form-label "><b>Interpreter name : </b></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="inputitp_name" id="inputitp_name" value="<?php echo $r['itp_name'] ?>">
				</div>
			</div>
			<div class="form-group row">
				<label for="inputitp_dept" class="col-sm-3 col-form-label"><b>Department : </b></label>
				<div class="col-sm-9">
					<input type="text" size="10" class="form-control" name="inputitp_dept" id="inputitp_dept" value="<?php echo $r['itp_dept'] ?>">
				</div>
			</div>
			<div class="form-group row">
				<label for="inputitp_email" class="col-sm-3 col-form-label"><b>Email : </b></label>
				<div class="col-sm-9">
					<input type="email" size="10" class="form-control" name="inputitp_email" id="inputitp_email" value="<?php echo $r['itp_email'] ?>">
				</div>
			</div>
			<div class="form-group row">
				<label for="inputitp_tel" class="col-sm-3 col-form-label "><b>Interpreter telephone : </b></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="inputitp_tel" id="inputitp_tel" value="<?php echo $r['itp_tel'] ?>">
				</div>
			</div>

			<div class="form-group row">
				<label for="inputitp_status" class="col-sm-3 col-form-label"><b>Interpreter Status : </b></label>
				<div class="col-sm-9">
					<div class="btn-group" data-toggle="buttons">
						<?php
						if ($r['itp_status'] == '1') {
							echo '<label class="btn btn-primary active">';
							echo '<input type="radio" name="inputitp_status" id="inputcar_status1" autocomplete="off" value="1" checked> Enable';
							echo '</label>';
							echo '<label class="btn btn-primary">';
							echo '<input type="radio" name="inputitp_status" id="inputcar_status2" autocomplete="off" value="2"> Disable';
							echo '</label>';
						} elseif ($r['itp_status'] == '2') {
							echo '<label class="btn btn-primary">';
							echo '<input type="radio" name="inputitp_status" id="inputcar_status1" autocomplete="off" value="1"> Enable';
							echo '</label>';
							echo '<label class="btn btn-primary active">';
							echo '<input type="radio" name="inputitp_status" id="inputcar_status2" autocomplete="off" value="2" checked> Disable';
							echo '</label>';
						}
						?>
					</div>
				</div>
			</div>
			<?php
			if ($r['itp_options'] == NULL || $r['itp_options'] == "") { ?>
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
				<div class="form-group row"><label for="file" class="col-sm-3 col-form-label"><b>Interpreter :</b></label>
					<div class="col-sm-9">
						<img src="/booking/images/uploads/itp/<?php echo $r['itp_options'] ?>" alt="" class="img-fluid">
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
<?php
	require_once('./../../../config.php');
	@session_start();
	// CHECK LOGIN
	if (!isset($_SESSION['emp_id'])) {
		hd("/booking/logout");
	}elseif (strtolower($_SESSION['emp_position']) != "hr approval" && strtolower($_SESSION['emp_position']) != "administrator" || $_SESSION['emp_factory'] != "1") {
		hd("/booking/");
	}
	include './../../header.php';
?>
<?php
	if(isset($_POST['cancel'])){
		hd("employee.php");
	}
?>
<?php
	if(isset($_POST['done'])){
		$mtr_number    = $_POST['inputMTR'];
		$mtr_tel       = $_POST['inputMTR_tel'];
		$mtr_factory   = 1;
		$mtr_status    = $_POST['inputMTR_status'];
		$file          = $_FILES['file'];
		$fileName      = $_FILES['file']['name'];
		$fileTmpName   = $_FILES['file']['tmp_name'];
		$fileSize      = $_FILES['file']['size'];
		$fileError     = $_FILES['file']['error'];
		$fileType      = $_FILES['file']['type'];
		$fileExt       = explode('.', $fileName);
		$fileActualExt = strtolower(end($fileExt));
		$allowed       = array('jpg', 'jpeg', 'png', 'pdf');
		// al("$mtr_number"." "."$mtr_factory"." "."$mtr_status");
		if ($mtr_number == "") {
			sc("Please fill out the form. !!");
		}elseif ($mtr_factory == "") {
			sc("Please select factory. !!");
		}elseif ($mtr_status == "") {
			sc("Please select status of room. !!");
		}else{
			$ck1 = mysql_num_rows(mysql_query("SELECT * FROM meeting_room WHERE mtr_number = '$mtr_number' AND mtr_factory = '$mtr_factory'"));
			if ($ck1 != 0) {
				sc("This room was already. Please try again. !!");
			}else{
				if (in_array($fileActualExt, $allowed)) {
					if ($fileError === 0) {
						if ($fileSize < 5.243e+6) {
							$fileNameNew = uniqid('', true).".".$fileActualExt;
							$fileDestination = '../../.././images/uploads/mtr/'.$fileNameNew;
							move_uploaded_file($fileTmpName, $fileDestination);
							mysql_query("INSERT INTO meeting_room VALUEs (NULL, '$mtr_number', '$mtr_tel', '$fileNameNew', '$mtr_factory', '$mtr_status', NULL)");
							ao("Add meeting room successfully. !","/booking/TGAS/HR/meetingroom/index.php");
						}else{
							sc("Your file is too big.!");
						}
					}else{
						sc("There was an error upload file.!");
					}
				}else{
					mysql_query("INSERT INTO meeting_room VALUEs (NULL, '$mtr_number', '$mtr_tel', NULL, '$mtr_factory', '$mtr_status', NULL)");
					ao("Add meeting room without photo successfully. !","/booking/TGAS/HR/meetingroom/index.php");
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
					reader.onload = function (e) {
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
		<br>
			<h2>Add Meeting room</h2>
			<br>
			<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
				<div class="form-group row">
					<label for="inputMTR" class="col-sm-3 col-form-label ">
						<b>Meeting room Number : </b>
					</label>
					<div class="col-sm-9">
						<input type="number" class="form-control has-danger" name="inputMTR" id="inputMTR" placeholder="Number room" required="" autofocus="">
					</div>
				</div>
				<div class="form-group row">
					<label for="inputMTR_tel" class="col-sm-3 col-form-label ">
						<b>Telephone number : </b>
					</label>
					<div class="col-sm-9">
						<input type="number" class="form-control has-danger" name="inputMTR_tel" id="inputMTR_tel">
					</div>
				</div>
				<!-- <div class="form-group row">
					<label for="inputMTR_factory" class="col-sm-3 col-form-label"><b>Meeting room of the factory : </b></label>
					<div class="col-sm-9">
						<div class="btn-group" data-toggle="buttons">
							<label class="btn btn-primary">
								<input type="radio" name="inputMTR_factory" id="inputMTR_factory1" autocomplete="off" value="1"> TGAS
							</label>
							<label class="btn btn-primary">
								<input type="radio" name="inputMTR_factory" id="inputMTR_factory2" autocomplete="off" value="2"> TGT
							</label>
							<label class="btn btn-primary">
								<input type="radio" name="inputMTR_factory" id="inputMTR_factory3" autocomplete="off" value="3"> TGRT
							</label>
						</div>
					</div>
				</div> -->
				<div class="form-group row">
					<label for="inputMTR_status" class="col-sm-3 col-form-label"><b>Meeting room Status : </b></label>
					<div class="col-sm-9">
						<div class="btn-group" data-toggle="buttons">
							<label class="btn btn-primary">
								<input type="radio" name="inputMTR_status" id="inputMTR_status1" autocomplete="off" value="1"> Enable
							</label>
							<label class="btn btn-primary">
								<input type="radio" name="inputMTR_status" id="inputMTR_status2" autocomplete="off" value="2"> Disable
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

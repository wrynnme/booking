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
 		if($itp_name == "" || $itp_factory == "" || $itp_status == ""){
 			sc("Please fill out the form. !!");
 		}else{
			$ck1 = mysql_num_rows(mysql_query("SELECT * FROM `interpreter` WHERE itp_id ='$itp_id'"));
			if($ck1!=0){
				sc("This interpreter was already. Please try agian. !!");
			}else{
				if (in_array($fileActualExt, $allowed)) {
					if ($fileError === 0) {
						if ($fileSize < 5.243e+6) {
							$fileNameNew = uniqid('', true).".".$fileActualExt;
							$fileDestination = './../../../images/uploads/itp/'.$fileNameNew;
							move_uploaded_file($fileTmpName, $fileDestination);
							mysql_query("INSERT INTO `interpreter` VALUEs ('', '$itp_name', '$itp_tel', '$itp_email', '$itp_dept','$fileNameNew', '$itp_factory', '$itp_status', NULL)");
							ao("Add interpreter successfully. !","/booking/TGAS/HR/interpreter/");
							// header("Location: index.php?uploadsuccess");
						}else{
							sc("Your file is too big.!");
						}
					}else{
						sc("There was an error upload file.!");
					}
				}else{
					mysql_query("INSERT INTO `interpreter` VALUEs ('', '$itp_name', '$itp_tel', '$itp_email', '$itp_dept', NULL, '$itp_factory', '$itp_status', NULL)");
					ao("Add interpreter without photo successfully. !","/booking/TGAS/HR/interpreter/");
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
	<br>
		<div class="container">
			<h2>Add Interpreter</h2>
			<br>
			<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
				<div class="form-group row">
					<label for="inputitp_name" class="col-sm-3 col-form-label"><b>Interpreter name : </b></label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="inputitp_name" id="inputitp_name" placeholder="">
					</div>
				</div>
				<div class="form-group row">
					<label for="inputitp_tel" class="col-sm-3 col-form-label"><b>Department : </b></label>
					<div class="col-sm-9">
						<input type="text" size="10" class="form-control" name="inputitp_dept" id="inputitp_dept">
					</div>
				</div>
				<div class="form-group row">
					<label for="inputitp_tel" class="col-sm-3 col-form-label"><b>Email : </b></label>
					<div class="col-sm-9">
						<input type="email" size="10" class="form-control" name="inputitp_email" id="inputitp_email">
					</div>
				</div>
				<div class="form-group row">
					<label for="inputitp_tel" class="col-sm-3 col-form-label"><b>Telephone number : </b></label>
					<div class="col-sm-9">
						<input type="number" size="10" class="form-control" name="inputitp_tel" id="inputitp_tel">
						<small id="emailHelp" class="form-text text-muted">Don't fill in more than 10 characters.</small>
					</div>
				</div>
				<!-- <div class="form-group row">
					<label for="inputitp_factory" class="col-sm-3 col-form-label"><b>Interpreter of the factory : </b></label>
					<div class="col-sm-9">
						<div class="btn-group" data-toggle="buttons">
							<label class="btn btn-primary">
								<input type="radio" name="inputitp_factory" id="inputitp_factory1" autocomplete="off" value="1"> TGAS
							</label>
							<label class="btn btn-primary">
								<input type="radio" name="inputitp_factory" id="inputitp_factory2" autocomplete="off" value="2"> TGT
							</label>
							<label class="btn btn-primary">
								<input type="radio" name="inputitp_factory" id="inputitp_factory3" autocomplete="off" value="3"> TGRT
							</label>
						</div>
					</div>
				</div> -->
				<div class="form-group row">
					<label for="inputitp_status" class="col-sm-3 col-form-label"><b>Interpreter status : </b></label>
					<div class="col-sm-9">
						<div class="btn-group" data-toggle="buttons">
							<label class="btn btn-primary">
								<input type="radio" name="inputitp_status" id="inputitp_status1" autocomplete="off" value="1"> Enable
							</label>
							<label class="btn btn-primary">
								<input type="radio" name="inputitp_status" id="inputitp_status2" autocomplete="off" value="2"> Disable
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

<?php
	require_once('./../../../config.php');
	@session_start();
	// CHECK LOGIN
	if (!isset($_SESSION['emp_id'])) {
		hd("/booking/logout");
	}elseif (strtolower($_SESSION['emp_position']) != "user" && strtolower($_SESSION['emp_position']) != "administrator" || $_SESSION['emp_factory'] != "1") {
		hd("/booking/");
	}
	include './../../header.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title></title>
		<style media="screen">
			.form-control:disabled, .form-control[disabled]{
				background: #f5f5f5;
				opacity: 1;
			}
			.form-control{
				background: none;
			}
		</style>
	</head>
	<body>
		<?php 
			$r = mysql_fetch_array(mysql_query("SELECT * FROM bk_mtr,employee,meeting_room WHERE meeting_room.mtr_id = bk_mtr.mtr_id AND employee.emp_id = bk_mtr.bkemp_id AND bk_mtr.bk_factory = 1 AND bk_mtr.bkmtr_id = '$_GET[bkmtr_id]'"));
			$sdate=date_create($r['bktime_start']);
			$edate=date_create($r['bktime_end']);
		?>
		<br><br>
		<div class="container" id="show">
			<h4>Your booking meeting room in <b><?php echo $r['mtr_number']; ?></b> Request by <b><?php echo $r['emp_fname']." ".$r['emp_lname']; ?></b></h4>
			<hr>
			<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
				<div class="row">
					<div class="col-md">
						<label for="bk_name" class="col col-form-label text-right"><b>Name : </b></label>
					</div>
					<div class="col-md-3">
						<input type="text" class="col form-control form-check" name="bk_name" value="<?=$r[bk_name]?>" disabled>
					</div>
					<div class="col-md-2">
						<label for="bk_dept" class="col col-form-label text-right"><b>Department : </b></label>
					</div>
					<div class="col-md-4">
						<input type="text" class="col form-control form-check" name="bk_dept" value="<?=$r[bk_dept]?>" disabled>
					</div>
				</div>
				<div class="row">
					<div class="col-md">
						<label for="bk_tel" class="col col-form-label text-right"><b>Telephone number : </b></label>
					</div>
					<div class="col-md-3">
						<input type="number" class="col form-control form-check" name="bk_tel" value="<?=$r[bk_tel]?>" disabled>
					</div>
					<div class="col-md-2">
						<label for="bk_email" class="col col-form-label text-right"><b>Email : </b></label>
					</div>
					<div class="col-md-4">
						<input type="email" class="col form-control form-check" name="bk_email" value="<?=$r[bk_email]?>" disabled>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="bk_position" class="col col-form-label text-right"><b>Date Start : </b></label>
					</div>
					<div class="col-md-4">
						<input type="text" class="col form-control form-check" name="bk_dept" value="<?=date_format($sdate,'l d F Y H:i')?>" disabled>
					</div>
					<div class="col-md-2">
						<label for="bk_department" class="col col-form-label text-right"><b>Date End : </b></label>
					</div>
					<div class="col-md-4">
						<input type="text" class="col form-control form-check" name="bk_dept" value="<?=date_format($edate,'l d F Y H:i')?>" disabled>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="mtr_id" class="col col-form-label text-right"><b>Meeting room : </b></label>
					</div>
					<div class="col-md-10">
						<input type="mtr_id" class="col form-control form-check" name="mtr_id" value="<?=$r[mtr_number]?>" disabled>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="bk_item_request" class="col col-form-label text-right"><b>Item Request: </b></label>
					</div>
					<div class="col-md-10">
						<input type="bk_item_request" class="col form-control form-check" name="bk_item_request" value="<?=$r[bk_item_request]?>" disabled>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="bk_objective" class="col col-form-label text-right"><b>Objective : </b></label>
					</div>
					<div class="col-md-10">
						<input type="bk_objective" class="col form-control form-check" name="bk_objective" value="<?=$r[bk_objective]?>" disabled>
					</div>
				</div>
				<hr>
				<a href="./" class="btn btn-outline-success">Continue</a>
			</form>
		</div>
	</body>
</html>
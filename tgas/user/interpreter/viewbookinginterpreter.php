<?php
require_once('./../../../config.php');
@session_start();
// CHECK LOGIN
if (!isset($_SESSION['emp_id'])) {
	hd("/booking/logout");
} elseif (strtolower($_SESSION['emp_position']) != "user" && strtolower($_SESSION['emp_position']) != "administrator" || $_SESSION['emp_factory'] != "1") {
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
		.form-control:disabled,
		.form-control[disabled] {
			background: #f5f5f5;
			opacity: 1;
		}

		.form-control {
			background: none;
		}
	</style>
</head>

<body>
	<?php
	$qsss = $con->query("SELECT * FROM bk_interpreter WHERE bk_interpreter.bkitp_id = $_GET[bkitp_id]");
	$sss = $qsss->fetch_array(MYSQLI_ASSOC);
	if ($sss['car_id'] != "") {
		$qr = $con->query("SELECT * FROM bk_interpreter,car WHERE bk_interpreter.car_id = car.car_id AND bk_factory = 1 AND bkitp_id = '$_GET[bkitp_id]'");
		$r = $qr->fetch_array(MYSQLI_ASSOC);
	} else {
		$qr = $con->query("SELECT * FROM bk_interpreter WHERE bk_factory = 1 AND bkitp_id = '$_GET[bkitp_id]'");
		$r = $qr->fetch_array(MYSQLI_ASSOC);
	}
	$sdate = date_create($r['bktime_start']);
	$edate = date_create($r['bktime_end']);
	$sdate = date_create($r['bktime_start']);
	$edate = date_create($r['bktime_end']);
	?>
	<br><br>
	<div class="container" id="show">
		<h4>Your booking interpreter <b><?php echo $r['itp_name']; ?></b> Request by <b><?php echo $r['emp_fname'] . " " . $r['emp_lname']; ?></b></h4>
		<hr>
		<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
			<div class="row">
				<div class="col-md-3">
					<label for="itp_id" class="col col-form-label text-right"><b>Interpreter name : </b></label>
				</div>
				<div class="col-md-9">
					<?php if ($r['itp_name'] == "") { ?>
						<input type="text" class="col form-control form-check bg-danger" name="itp_id" disabled value="<?php echo $r['itp_name'] ?>">
					<?php } else { ?>
						<input type="text" class="col form-control form-check bg-success" name="itp_id" disabled value="<?php echo $r['itp_name'] ?>">
					<?php } ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md">
					<label for="bk_name" class="col col-form-label text-right"><b>Name : </b></label>
				</div>
				<div class="col-md-3">
					<input type="text" class="col form-control form-check" name="bk_name" value="<?php echo $r['bk_name'] ?>" disabled>
				</div>
				<div class="col-md-2">
					<label for="bk_dept" class="col col-form-label text-right"><b>Department : </b></label>
				</div>
				<div class="col-md-4">
					<input type="text" class="col form-control form-check" name="bk_dept" value="<?php echo $r['bk_dept'] ?>" disabled>
				</div>
			</div>
			<div class="row">
				<div class="col-md">
					<label for="bk_tel" class="col col-form-label text-right"><b>Telephone number : </b></label>
				</div>
				<div class="col-md-3">
					<input type="number" class="col form-control form-check" name="bk_tel" value="<?php echo $r['bk_tel'] ?>" maxlength="10">
				</div>
				<div class="col-md-2">
					<label for="bk_email" class="col col-form-label text-right"><b>Email : </b></label>
				</div>
				<div class="col-md-4">
					<input type="email" class="col form-control form-check" name="bk_email" value="<?php echo $r['bk_email'] ?>" disabled>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<label for="bktime_start" class="col col-form-label text-right"><b>Date Start : </b></label>
				</div>
				<div class="col-md-4">
					<input type="text" class="col form-control form-check" name="bktime_start" disabled value="<?php echo date_format($sdate, 'l d F Y H:i') ?>">
				</div>
				<div class="col-md-2">
					<label for="bktime_end" class="col col-form-label text-right"><b>Date End : </b></label>
				</div>
				<div class="col-md-4">
					<input type="text" class="col form-control form-check" name="bktime_end" disabled value="<?php echo date_format($edate, 'l d F Y H:i') ?>">
				</div>
			</div>
			<hr />
			<a href="./" class="btn btn-outline-success">Continue</a>
		</form>
	</div>
</body>

</html>
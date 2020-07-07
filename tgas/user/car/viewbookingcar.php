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
	$qsss = $con->query("SELECT * FROM bk_car WHERE bk_car.bkcar_id = $_GET[bkcar_id]");
	$sss = $qsss->fetch_array(MYSQLI_ASSOC);
	if ($sss['car_id'] != "") {
		$qr = $con->query("SELECT * FROM bk_car,car WHERE bk_car.car_id = car.car_id AND bk_factory = 1 AND bkcar_id = '$_GET[bkcar_id]'");
		$r = $qr->fetch_array(MYSQLI_ASSOC);
	} else {
		$qr = $con->query("SELECT * FROM bk_car WHERE bk_factory = 1 AND bkcar_id = '$_GET[bkcar_id]'");
		$r = $qr->fetch_array(MYSQLI_ASSOC);
	}
	$sdate = date_create($r['bktime_start']);
	$edate = date_create($r['bktime_end']);
	?>
	<br><br>
	<div class="container" id="show">
		<h4></b> Request car by <b><?php echo $r['bk_name'] ?></b></h4>
		<hr>
		<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" id="">
			<div class="row">
				<div class="col-md-3">
					<label for="license_plate" class="col col-form-label text-right"><b>License plate : </b></label>
				</div>
				<div class="col-md-9">
					<?php if ($r['license_plate'] == "") { ?>
						<input type="text" class="col form-control form-check" name="itp_id" disabled value="Waiting HR Design License plate">
					<?php } else { ?>
						<input type="text" class="col form-control form-check" name="itp_id" disabled value="<?php echo $r['license_plate'] . " " . $r['car_brand'] ?>">
					<?php } ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md">
					<label for="bk_name" class="col col-form-label text-right"><b>Name : </b></label>
				</div>
				<div class="col-md-3">
					<input type="text" class="col form-control form-check" name="bk_name" disabled value="<?php echo $r['bk_name'] ?>">
				</div>
				<div class="col-md-2">
					<label for="bk_idcode" class="col col-form-label text-right"><b>ID Code : </b></label>
				</div>
				<div class="col-md-4">
					<input type="text" class="col form-control form-check" name="bk_idcode" disabled value="<?php echo $r['bk_idcode'] ?>">
				</div>
			</div>
			<div class="row">
				<div class="col-md">
					<label for="bk_tel" class="col col-form-label text-right"><b>Telephone number : </b></label>
				</div>
				<div class="col-md-3">
					<input type="number" class="col form-control form-check" name="bk_tel" disabled value="<?php echo $r['bk_tel'] ?>">
				</div>
				<div class="col-md-2">
					<label for="bk_email" class="col col-form-label text-right"><b>Email : </b></label>
				</div>
				<div class="col-md-4">
					<input type="email" class="col form-control form-check" name="bk_email" disabled value="<?php echo $r['bk_email'] ?>">
				</div>
			</div>
			<div class="row">
				<div class="col-md">
					<label for="bk_position" class="col col-form-label text-right"><b>Position : </b></label>
				</div>
				<div class="col-md-3">
					<input type="text" class="col form-control form-check" name="bk_position" disabled value="<?php echo $r['bk_position'] ?>">
				</div>
				<div class="col-md-2">
					<label for="bk_dept" class="col col-form-label text-right"><b>Department : </b></label>
				</div>
				<div class="col-md-4">
					<input type="text" class="col form-control form-check" name="bk_dept" disabled value="<?php echo $r['bk_dept'] ?>">
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
			<div class="row">
				<div class="col-md">
					<label for="bk_destination" class="col col-form-label text-right"><b>Destination or Contact Name : </b></label>
				</div>
				<div class="col-md-10">
					<input type="text" class="col form-control form-check" name="bk_destination" disabled value="<?php echo $r['bk_destination'] ?>">
				</div>
			</div>
			<div class="row">
				<div class="col-md">
					<label for="bk_purpose" class="col col-form-label text-right"><b>Purpose reque : </b></label>
				</div>
				<div class="col-md-10">
					<input type="text" class="col form-control form-check" name="bk_purpose" disabled value="<?php echo $r['bk_purpose'] ?>">
				</div>
			</div>
			<hr>
			<a href="./" class="btn btn-outline-success">Continue</a>
		</form>
	</div>
</body>

</html>
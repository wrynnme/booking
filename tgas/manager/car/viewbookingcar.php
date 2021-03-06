<?php
require_once('./../../../config.php');
@session_start();
// CHECK LOGIN
if (!isset($_SESSION['emp_id'])) {
	hd("/booking/logout");
} elseif (strtolower($_SESSION['emp_position']) != "manager" && strtolower($_SESSION['emp_position']) != "administrator" || $_SESSION['emp_factory'] != "1") {
	hd("/booking/");
}
include './../../header.php';
$qdet = $con->query("SELECT * FROM bk_car WHERE bkcar_id = '$_GET[bkcar_id]'");
$det = $qdet->num_rows;
if ($det < 1) {
	ao("This booked car has deleted", '/booking/tgas/manager/car');
}
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
	<?php
	if (isset($_POST['approved'])) {
		if ($r['bk_mgr_ap'] == 1) {
			ao("Not update a booked car.", '/booking/TGAS/manager/car/');
		} else {
			$bk_mgr_ap = 1;
			$sdate = $r['bktime_start'];
			$edate = $r['bktime_end'];
			$con->query("UPDATE `bk_car` SET `bk_mgr_ap`= '$bk_mgr_ap' , `bk_hr_ap` = NULL WHERE `bkcar_id` = '$_GET[bkcar_id]'");
			ao("Approve a booked car.", '/booking/TGAS/manager/car/');

			//	mail
			$qe = $con->query("SELECT * FROM `employee` WHERE emp_id = $_SESSION[emp_id]");
			$qehr = $con->query("SELECT * FROM `employee` WHERE emp_position = 'hr approval'");
			$qemgr = $con->query("SELECT * FROM `employee` WHERE (SELECT employee.emp_dept FROM `employee` WHERE emp_id = $_SESSION[emp_id]) = emp_dept AND emp_position = 'manager'");
			$qlink = $con->query("SELECT * FROM `bk_car` WHERE bkcar_id = '$_GET[bkcar_id]'");
			$e = $qe->fetch_array(MYSQLI_ASSOC);
			$ehr = $qehr->fetch_array(MYSQLI_ASSOC);
			$emgr = $qemgr->fetch_array(MYSQLI_ASSOC);
			$link = $qlink->fetch_array(MYSQLI_ASSOC);
			$lii = $link['bkmtr_id'];

			// ini_set("SMTP","localhost");

			$strTo        = "$ehr[emp_fname] $ehr[emp_lname] <$ehr[emp_email]>";
			$strSubject   = "=?UTF-8?B?" . base64_encode("Manager has approve a booked car.") . "?=";
			$Subject      = "Manager has approve a booked car.";
			$strHeader   .= "MIME-Version: 1.0' . \r\n";
			$strHeader   .= "Content-type: text/html; charset=utf-8\r\n";
			$strHeader   .= "From: Booking Management System <$_SESSION[emp_email]>\r\n";
			$strHeader   .= "Cc:<$_SESSION[emp_email]>,<$r[bk_email]>\r\n";
			$headmessages = "Requester name : <b>$r[bk_name]</b> <br> ID Code : <b>$r[bk_idcode]</b> <br> Telephone number : <b>$r[bk_tel]</b> <br> Email : <b>$r[bk_email]</b> <br> Position : <b>$r[bk_position]</b> <br> Department : <b>$r[bk_dept]</b> <br> Time start : <b>$r[bktime_start]</b> <br> Time end : <b>$edate</b> <br> Purpose : <b>$r[bk_purpose]</b> <br><br> <a target='_blank' href='http://192.168.222.223/booking/tgas/hr/car/viewbookingcar.php?bkcar_id=$lii'>Click here to approval page</a> <br>";
			include './../../mail.php';
			$flgSend = mail($strTo, $strSubject, $strMessage, $strHeader);
			if ($flgSend) {
				al_g("Send email successfully");
			} else {
				al_r("Cannot send email!");
			}
		}
	}
	if (isset($_POST['disapproves'])) {
		if ($r['bk_mgr_ap'] == 2) {
			ao("Not update a booked car.", '/booking/TGAS/manager/car/');
		} else {
			$bk_mgr_ap = 2;
			$sdate = $r['bktime_start'];
			$edate = $r['bktime_end'];
			$con->query("UPDATE `bk_car` SET `bk_mgr_ap`= '$bk_mgr_ap' , `bk_hr_ap` = '2' WHERE `bkcar_id` = '$_GET[bkcar_id]'");
			ao("Disapproves a booked car.", '/booking/TGAS/manager/car/');

			// mail
			$qe = $con->query("SELECT * FROM `employee` WHERE emp_id = $_SESSION[emp_id]");
			$qlink = $con->query("SELECT * FROM `bk_car` WHERE bkcar_id = '$_GET[bkcar_id]'");
			$e = $qr->fetch_array(MYSQLI_ASSOC);
			$link = $qlink->fetch_array(MYSQLI_ASSOC);
			$lii = $link['bkmtr_id'];

			// ini_set("SMTP","localhost");

			$strTo        = "$r[bk_name] <$r[bk_email]>";
			$strSubject   = "=?UTF-8?B?" . base64_encode("Manager has disapproves a booked car.") . "?=";
			$Subject      = "Manager has disapproves a booked car.";
			$strHeader   .= "MIME-Version: 1.0' . \r\n";
			$strHeader   .= "Content-type: text/html; charset=utf-8\r\n";
			$strHeader  .= "From: Booking Management System <$_SESSION[emp_email]>\r\n";
			$strHeader   .= "Cc: User Requester <$e[emp_email]>\r\n";
			$headmessages = "Requester name : <b>$r[bk_name]</b> <br> ID Code : <b>$r[bk_idcode]</b> <br> Telephone number : <b>$r[bk_tel]</b> <br> Email : <b>$r[bk_email]</b> <br> Position : <b>$r[bk_position]</b> <br> Department : <b>$r[bk_dept]</b> <br> Time start : <b>$r[bktime_start]</b> <br> Time end : <b>$edate</b> <br> Purpose : <b>$r[bk_purpose]</b> <br><br> <a target='_blank' href='http://192.168.222.223/booking/tgas/user/car/viewbookingcar.php?bkcar_id=$lii'>Click here to check a booked</a> <br>";
			include './../../mail.php';
			$flgSend = mail($strTo, $strSubject, $strMessage, $strHeader);
			if ($flgSend) {
				al_g("Send email successfully");
			} else {
				al_r("Cannot send email!");
			}
		}
	}
	?>
	<br><br>
	<div class="container" id="show">
		<h4>Approval booked car</b> Request by <b><?php echo $r['bk_name'] ?></b></h4>
		<hr>
		<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="bk_name" class="col-form-label">Requester Name</label>
					<input type="text" class="form-control" name="bk_name" id="bk_name" placeholder="<?php echo $r['bk_name'] ?>" disabled>
				</div>
				<div class="form-group col-md-6">
					<label for="bk_idcode" class="col-form-label">ID Code</label>
					<input type="text" class="form-control" name="bk_idcode" id="bk_idcode" placeholder="<?php echo $r['bk_idcode'] ?>" disabled>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="bk_tel" class="col-form-label">Telephone Number</label>
					<input type="text" class="form-control" name="bk_tel" id="bk_tel" placeholder="<?php echo $r['bk_tel'] ?>" disabled>
				</div>
				<div class="form-group col-md-6">
					<label for="bk_email" class="col-form-label">Email Address</label>
					<input type="email" class="form-control" name="bk_email" id="bk_email" placeholder="<?php echo $r['bk_email'] ?>" disabled>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="bk_position" class="col-form-label">Position</label>
					<input type="text" class="form-control" name="bk_position" id="bk_position" placeholder="<?php echo $r['bk_position'] ?>" disabled>
				</div>
				<div class="form-group col-md-6">
					<label for="bk_dept" class="col-form-label">Department</label>
					<input type="email" class="form-control" name="bk_dept" id="bk_dept" placeholder="<?php echo $r['bk_dept'] ?>" disabled>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="bktime_start" class="col-form-label">Date Start</label>
					<input type="text" class="form-control" name="bktime_start" id="bktime_start" placeholder="<?php echo date_format($sdate, 'l d F Y H:i') ?>" disabled>
				</div>
				<div class="form-group col-md-6">
					<label for="bktime_end" class="col-form-label">Date End</label>
					<input type="text" class="form-control" name="bktime_end" id="bktime_end" placeholder="<?php echo date_format($edate, 'l d F Y H:i') ?>" disabled>
				</div>
			</div>

			<div class="form-group">
				<label for="bk_destination" class="col-form-label">Desination</label>
				<textarea name="bk_destination" class="form-control form-check" id="bk_destination" cols="100" rows="3" maxlength="200" placeholder="<?php echo $r['bk_destination'] ?>" disabled></textarea>
			</div>

			<div class="form-group">
				<label for="bk_purpose" class="col-form-label">Purpose</label>
				<textarea name="bk_purpose" class="form-control form-check" id="bk_purpose" cols="100" rows="3" maxlength="200" placeholder="<?php echo $r['bk_purpose'] ?>" disabled></textarea>
			</div>

			<div class="form-group">
				<label for="license_plate" class="col-form-label">License Plate</label>
				<?php
				if ($r['car_id'] == "") { ?>
					<input type="text" class="form-control" name="license_plate" id="license_plate" placeholder="Waiting HR Design License plate" disabled>
				<?php	} else { ?>
					<input type="text" class="form-control" name="license_plate" id="license_plate" placeholder="<?php echo $r['license_plate'] . " " . $r['car_brand'] ?>" disabled>
				<?php	} ?>
			</div>

			<hr>

			<div id="approval" class="text-center">
				<div class="form-group">
					<p><label for="ap" class="col-form-label h3">Manager Department</label></p>
					<?php
					if ($r['bk_mgr_ap'] == 1) { ?>
						<div class="btn-group" role="group" aria-label="Basic example">
							<button type="submit" name="approved" class="btn btn-md btn-success">Approve</button>
							<button type="submit" name="disapproves" class="btn btn-md btn-outline-danger">Disapproves</button>
						</div>
					<?php	} elseif ($r['bk_mgr_ap'] == 2) { ?>
						<div class="btn-group" role="group" aria-label="Basic example">
							<button type="submit" name="approved" class="btn btn-md btn-outline-success">Approve</button>
							<button type="submit" name="disapproves" class="btn btn-md btn-danger">Disapproves</button>
						</div>
					<?php	} else { ?>
						<div class="btn-group" role="group" aria-label="Basic example">
							<button type="submit" name="approved" class="btn btn-md btn-outline-success">Approve</button>
							<button type="submit" name="disapproves" class="btn btn-md btn-outline-danger">Disapproves</button>
						</div>
					<?php	}
					?>
				</div>
			</div>
			<hr>

		</form>
	</div>
</body>

</html>
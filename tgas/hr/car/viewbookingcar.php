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
		if ($r['bk_hr_ap'] == 1) {
			ao("Not update a booked car.", '/booking/TGAS/hr/car/');
		} elseif ($r['bk_mgr_ap'] != 1) {
			ao("Manager Department has not approved booked car.", '/booking/TGAS/hr/car/');
		} else {
			$bk_hr_ap = 1;
			$car_id = $_POST['car_id'];
			$sdate = $r['bktime_start'];
			$edate = $r['bktime_end'];
			$ss = $con->query("SELECT * FROM `bk_car` WHERE car_id = '$car_id' AND bk_hr_ap = 1 AND (bktime_start BETWEEN '$sdate' AND '$edate') OR car_id = '$car_id' AND bk_hr_ap = 1 AND (bktime_end BETWEEN '$sdate' AND '$edate') OR car_id = '$car_id' AND bk_hr_ap = 1 AND ('$sdate' BETWEEN bktime_start AND bktime_end) OR car_id = '$car_id' AND bk_hr_ap = 1 AND ('$edate' BETWEEN bktime_start AND bktime_end)");
			if ($row = $ss->fetch_array(MYSQLI_ASSOC)) {
				ao("This car was approved already in " . $row['bktime_start'] . " - " . $row['bktime_end'] . ". Please check time to approved.", '/booking/tgas/hr/car/');
			} else {
				if ($car_id == "") {
					sc("Please select car before approve.");
				} else {
					$con->query("UPDATE `bk_car` SET `car_id` = '$car_id', `bk_hr_ap`= '$bk_hr_ap' WHERE `bkcar_id` = '$_GET[bkcar_id]'");
					ao("Approve a booked car.", '/booking/tgas/hr/car/');

					//	mail
					$qemgr = $con->query("SELECT * FROM `employee` WHERE (SELECT employee.emp_dept FROM `employee` WHERE emp_id = (SELECT bk_car.bkemp_id FROM bk_car WHERE bkcar_id = '$_GET[bkcar_id]')) = emp_dept AND emp_position = 'manager'");
					$qcar = $con->query("SELECT car.license_plate FROM bk_car,car WHERE bk_car.car_id = car.car_id AND bk_car.bkcar_id = '$_GET[bkcar_id]'");
					$qlink = $con->query("SELECT * FROM `bk_car` WHERE bkcar_id = '$_GET[bkcar_id]'");
					$emgr = $qemgr->fetch_array(MYSQLI_ASSOC);
					$car = $qcar->fetch_array(MYSQLI_ASSOC);
					$link = $qlink->fetch_array(MYSQLI_ASSOC);
					$lii = $link['bkcar_id'];

					// ini_set("SMTP","localhost");

					$strTo        = "$r[bk_name] <$r[bk_email]>";
					$strSubject   = "=?UTF-8?B?" . base64_encode("HR Approval has approved a booked car.") . "?=";
					$Subject      = "HR Approval has approved a booked car.";
					$strHeader   .= "MIME-Version: 1.0' . \r\n";
					$strHeader   .= "Content-type: text/html; charset=utf-8\r\n";
					$strHeader  .= "From: Booking Management System <$_SESSION[emp_email]>\r\n";
					$strHeader   .= "Cc:<$emgr[emp_email]>\r\n";
					$headmessages = "License Plate : <b>$car[license_plate]</b> <br> Requester name : <b>$r[bk_name]</b> <br> ID Code : <b>$r[bk_idcode]</b> <br> Telephone number : <b>$r[bk_tel]</b> <br> Email : <b>$r[bk_email]</b> <br> Position : <b>$r[bk_position]</b> <br> Department : <b>$r[bk_dept]</b>  <br> Time start : <b>$r[bktime_start]</b> <br> Time end : <b>$r[bktime_end]</b> <br> Desination : <b>$r[bk_destination]</b> <br> Purpose : <b>$r[bk_purpose]</b> <br><br> <a target='_blank' href='http://192.168.222.223/booking/tgas/user/car/viewbookingcar.php?bkcar_id=$lii'>Click here to check status</a> <br>";
					include './../../mail.php';
					$flgSend = mail($strTo, $strSubject, $strMessage, $strHeader);
					if ($flgSend) {
						al_g("Send email successfully");
					} else {
						al_r("Cannot send email!");
					}
				}
			}
		}
	}
	if (isset($_POST['disapproves'])) {
		if ($r['bk_hr_ap'] == 2) {
			ao("Not update a booked car.", '/booking/TGAS/hr/car/');
		} else {
			$bk_hr_ap = 2;
			$sdate = $r['bktime_start'];
			$edate = $r['bktime_end'];
			$con->query("UPDATE `bk_car` SET `bk_hr_ap`= '$bk_hr_ap' , `bk_mgr_ap` = '2' , `car_id` = NULL WHERE `bkcar_id` = '$_GET[bkcar_id]'");
			ao("Disapproves a booked car.", '/booking/tgas/hr/car/');

			// mail
			$qemgr = $con->query("SELECT * FROM `employee` WHERE (SELECT employee.emp_dept FROM `employee` WHERE emp_id = (SELECT bk_car.bkemp_id FROM bk_car WHERE bkcar_id = '$_GET[bkcar_id]')) = emp_dept AND emp_position = 'manager'");
			$qe = $con->query("SELECT * FROM `employee` WHERE emp_id = $_SESSION[emp_id]");
			$qlink = $con->query("SELECT * FROM `bk_car` WHERE bkcar_id = '$_GET[bkcar_id]'");
			$emgr = $qemgr->fetch_array(MYSQLI_ASSOC);
			$e = $qe->fetch_array(MYSQLI_ASSOC);
			$link = $qlink->fetch_array(MYSQLI_ASSOC);
			$lii = $link['bkcar_id'];

			// ini_set("SMTP","localhost");

			$strTo        = "$r[bk_name] <$r[bk_email]>";
			$strSubject   = "=?UTF-8?B?" . base64_encode("HR Approval has disapproves a booked car.") . "?=";
			$Subject      = "HR Approval has disapproves a booked car.";
			$strHeader   .= "MIME-Version: 1.0' . \r\n";
			$strHeader   .= "Content-type: text/html; charset=utf-8\r\n";
			$strHeader  .= "From: Booking Management System <$_SESSION[emp_email]>\r\n";
			$strHeader   .= "Cc: User Requester <$emgr[emp_email]>\r\n";
			$headmessages = "Requester name : <b>$r[bk_name]</b> <br> ID Code : <b>$r[bk_idcode]</b> <br> Telephone number : <b>$r[bk_tel]</b> <br> Email : <b>$r[bk_email]</b> <br> Position : <b>$r[bk_position]</b> <br> Department : <b>$r[bk_dept]</b>  <br> Time start : <b>$r[bktime_start]</b> <br> Time end : <b>$r[bktime_end]</b> <br> Desination : <b>$r[bk_destination]</b> <br> Purpose : <b>$r[bk_purpose]</b> <br><br> <a target='_blank' href='http://192.168.222.223/booking/tgas/manager/car/viewbookingcar.php?bkcar_id=$lii'>Click here to approval page</a> <br>";
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
		<h4>Approve Car in <b><?php if ($r['license_plate'] != "") {
														echo $r['license_plate'];
													} else {
														echo "--";
													} ?></b> Request by <b><?php echo $r['bk_name']; ?></b></h4>
		<hr>
		<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
			<div class="form-group">
				<label for="car_id" class="col-form-label">License Plate</label>
				<?php if ($r['car_id'] == "") { ?>
					<select id="car_id" name="car_id" class="form-control" style="background: #fff;">
						<option value="">Choose Car</option>
						<?php $sss = $con->query("SELECT * FROM `car` WHERE car_factory = '1'");
						while ($rr = $sss->fetch_array(MYSQLI_ASSOC)) { ?>
							<option value="<?php echo $rr['car_id']; ?>"><?php echo $rr['license_plate'] . " " . $rr['car_brand'] ?></option>
						<?php } ?>
					</select>
				<?php } else {
					$qrr = $con->query("SELECT * FROM bk_car,car WHERE bk_car.car_id = car.car_id AND bkcar_id = '$_GET[bkcar_id]'");
					$rr = $qrr->fetch_array(MYSQLI_ASSOC);
				?>
					<input type="text" class="form-control" name="car_id" id="car_id" value="<?php echo $rr['license_plate'] ?>" disabled>
				<?php } ?>
			</div>
			<div id="approval" class="text-center" style="display: none;">
				<div class="form-group">
					<p><label for="ap" class="col-form-label h3">HR Approval</label></p>
					<?php
					if ($r['bk_hr_ap'] == 1) { ?>
						<div class="btn-group" role="group" aria-label="Basic example">
							<button type="submit" name="approved" class="btn btn-md btn-success">Approve</button>
							<button type="submit" name="disapproves" class="btn btn-md btn-outline-danger">Disapproves</button>
						</div>
					<?php	} elseif ($r['bk_hr_ap'] == 2) { ?>
						<div class="btn-group" role="group" aria-label="Basic example">
							<button type="submit" name="approved" class="btn btn-md btn-outline-success">Approve</button>
							<button type="submit" name="disapproves" class="btn btn-md btn-danger">Disapproves</button>
						</div>
					<?php	} else { ?>
						<div class="btn-group" role="group" aria-label="Basic example">
							<button type="submit" name="approved" class="btn btn-md btn-outline-success">Approve</button>
							<button type="submit" name="disapproves" class="btn btn-md btn-outline-danger">Disapproves</button>
						</div>
					<?php	} ?>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="bk_name" class="col-form-label">Name</label>
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
					<label for="bk_email" class="col-form-label">Email</label>
					<input type="text" class="form-control" name="bk_email" id="bk_email" placeholder="<?php echo $r['bk_email'] ?>" disabled>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="bk_position" class="col-form-label">Position</label>
					<input type="text" class="form-control" name="bk_position" id="bk_position" placeholder="<?php echo $r['bk_position'] ?>" disabled>
				</div>
				<div class="form-group col-md-6">
					<label for="bk_dept" class="col-form-label">Department</label>
					<input type="text" class="form-control" name="bk_dept" id="bk_dept" placeholder="<?php echo $r['bk_dept'] ?>" disabled>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="bktime_start" class="col-form-label">Date start</label>
					<input type="text" class="form-control" name="bktime_start" id="bktime_start" placeholder="<?php echo date_format($sdate, 'l d F Y H:i') ?>" disabled>
				</div>
				<div class="form-group col-md-6">
					<label for="bktime_end" class="col-form-label">Date end</label>
					<input type="text" class="form-control" name="bktime_end" id="bktime_end" placeholder="<?php echo date_format($edate, 'l d F Y H:i') ?>" disabled>
				</div>
			</div>

			<div class="form-group">
				<label for="bk_destination" class="col-form-label">Destination or Contact Name</label>
				<textarea name="bk_destination" class="form-control form-check" id="bk_destination" cols="100" rows="3" maxlength="200" placeholder="<?php echo $r['bk_destination'] ?>" disabled></textarea>
			</div>

			<div class="form-group">
				<label for="bk_purpose" class="col-form-label">Purpose reque</label>
				<textarea name="bk_purpose" class="form-control form-check" id="bk_purpose" cols="100" rows="3" maxlength="200" placeholder="<?php echo $r['bk_purpose'] ?>" disabled></textarea>
			</div>
			<hr>
			<!-- <button type="submit" id="done" name="done" class="btn btn-block btn-lg btn-success">Continue</button> -->
		</form>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript">
		$(function() {
			if ($("#car_id").val() != "") {
				document.getElementById('approval').style.display = '';
			}
			$("#car_id").change(function() {
				document.getElementById('approval').style.display = '';
			});
			$("#app").click(function() {
				var txt = $("#bk_hr_ap1").val();
				$("#done").text("Approve").attr('class', 'btn btn-block btn-lg btn-warning');

			});
			$("#dapp").click(function() {
				var txt = $("#bk_hr_ap2").val();
				$("#done").text("Disapproves").attr('class', 'btn btn-block btn-lg btn-danger');
			});
		});
	</script>
</body>

</html>
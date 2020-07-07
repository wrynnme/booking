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
$qdet = $con->query("SELECT * FROM bk_interpreter WHERE bkitp_id = '$_GET[bkitp_id]'");
$det = $qdet->num_rows;
if ($det < 1) {
	ao("This booked interpreter has deleted", '/booking/tgas/manager/interpreter');
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
	$qsss = $con->query("SELECT * FROM bk_interpreter WHERE bk_interpreter.bkitp_id = $_GET[bkitp_id]");
	$sss = $qsss->fetch_array(MYSQLI_ASSOC);
	if ($sss['itp_id'] != "") {
		$qr = $con->query("SELECT * FROM bk_interpreter,interpreter WHERE bk_interpreter.itp_id = interpreter.itp_id AND bk_factory = 1 AND bkitp_id = '$_GET[bkitp_id]'");
		$r = $qr->fetch_array(MYSQLI_ASSOC);
	} else {
		$qr = $con->query("SELECT * FROM bk_interpreter WHERE bk_factory = 1 AND bkitp_id = '$_GET[bkitp_id]'");
		$r = $qr->fetch_array(MYSQLI_ASSOC);
	}
	$sdate = date_create($r['bktime_start']);
	$edate = date_create($r['bktime_end']);
	?>
	<?php
	if (isset($_POST['approved'])) {
		if ($r['bk_mgr_ap'] == 1) {
			ao("Not update a booked interpreter.", '/booking/TGAS/manager/interpreter/');
		} else {
			$bk_mgr_ap = 1;
			$sdate = $r['bktime_start'];
			$edate = $r['bktime_end'];
			$con->query("UPDATE `bk_interpreter` SET `bk_mgr_ap`= '$bk_mgr_ap' , `bk_hr_ap` = NULL WHERE `bkitp_id` = '$_GET[bkitp_id]'");
			ao("Approve a booked interpreter.", '/booking/TGAS/manager/interpreter/');

			//	mail
			$qe = $con->query("SELECT * FROM `employee` WHERE emp_id = $_SESSION[emp_id]");
			$qehr = $con->query("SELECT * FROM `employee` WHERE emp_position = 'hr approval'");
			$qemgr = $con->query("SELECT * FROM `employee` WHERE (SELECT employee.emp_dept FROM `employee` WHERE emp_id = $_SESSION[emp_id]) = emp_dept AND emp_position = 'manager'");
			$qlink = $con->query("SELECT * FROM `bk_interpreter` WHERE bkitp_id = '$_GET[bkitp_id]'");
			$e = $qe->fetch_array(MYSQLI_ASSOC);
			$ehr = $qehr->fetch_array(MYSQLI_ASSOC);
			$emgr = $qemgr->fetch_array(MYSQLI_ASSOC);
			$link = $qlink->fetch_array(MYSQLI_ASSOC);
			$lii = $link['bkmtr_id'];

			// ini_set("SMTP","localhost");

			$strTo        = "$ehr[emp_fname] $ehr[emp_lname] <$ehr[emp_email]>";
			$strSubject   = "=?UTF-8?B?" . base64_encode("Manager has approve a booked interpreter.") . "?=";
			$Subject      = "Manager has approve a booked interpreter.";
			$strHeader   .= "MIME-Version: 1.0' . \r\n";
			$strHeader   .= "Content-type: text/html; charset=utf-8\r\n";
			$strHeader   .= "From: Booking Management System <$_SESSION[emp_email]>\r\n";
			$strHeader   .= "Cc:<$_SESSION[emp_email]>,<$r[bk_email]>\r\n";
			$headmessages = "Requester name : <b>$r[bk_name]</b> <br> Department : <b>$r[bk_dept]</b> <br> Telephone number : <b>$r[bk_tel]</b> <br> Email : <b>$r[bk_email]</b> <br> Time start : <b>$r[bktime_start]</b> <br> Time end : <b>$edate</b> <br> Objective : <b>$r[bk_objective]</b><br><br> <a target='_blank' href='http://192.168.222.223/booking/tgas/hr/interpreter/viewbookinginterpreter.php?bkitp_id=$lii'>Click here to check a booked</a> <br>";
			include './../../mail.php';
			$flgSend = mail($strTo, $strSubject, $strMessage, $strHeader);
			if ($flgSend) {
				alr("Send email successfully");
			} else {
				alr("Cannot send email!");
			}
		}
	}
	if (isset($_POST['disapproves'])) {
		if ($r['bk_mgr_ap'] == 2) {
			ao("Not update a booked interpreter.", '/booking/TGAS/manager/interpreter/');
		} else {
			$bk_mgr_ap = 2;
			$sdate = $r['bktime_start'];
			$edate = $r['bktime_end'];
			$con->query("UPDATE `bk_interpreter` SET `bk_mgr_ap`= '$bk_mgr_ap' , `bk_hr_ap` = '2' WHERE `bkitp_id` = '$_GET[bkitp_id]'");
			ao("Disapproves a booked interpreter.", '/booking/TGAS/manager/interpreter/');

			// mail
			$qe = $con->query("SELECT * FROM `employee` WHERE emp_id = $_SESSION[emp_id]");
			$qlink = $con->query("SELECT * FROM `bk_interpreter` WHERE bkitp_id = '$_GET[bkitp_id]'");
			$e = $qe->fetch_array(MYSQLI_ASSOC);
			$link = $qlink->fetch_array(MYSQLI_ASSOC);
			$lii = $link['bkmtr_id'];

			// ini_set("SMTP","localhost");

			$strTo        = "$r[bk_name] <$r[bk_email]>";
			$strSubject   = "=?UTF-8?B?" . base64_encode("Manager has disapproves a booked interpreter.") . "?=";
			$Subject      = "Manager has disapproves a booked interpreter.";
			$strHeader   .= "MIME-Version: 1.0' . \r\n";
			$strHeader   .= "Content-type: text/html; charset=utf-8\r\n";
			$strHeader  .= "From: Booking Management System <$_SESSION[emp_email]>\r\n";
			$strHeader   .= "Cc: User Requester <$e[emp_email]>\r\n";
			$headmessages = "Requester name : <b>$r[bk_name]</b> <br> Department : <b>$r[bk_dept]</b> <br> Telephone number : <b>$r[bk_tel]</b> <br> Objective : <b>$r[bk_objective]</b> <br> Email : <b>$r[bk_email]</b> <br> Time start : <b>$r[bktime_start]</b> <br> Time end : <b>$edate</b> <br><br> <a target='_blank' href='http://192.168.222.223/booking/tgas/user/interpreter/viewbookinginterpreter.php?bkitp_id=$lii'>Click here to check a booked</a> <br>";
			include './../../mail.php';
			$flgSend = mail($strTo, $strSubject, $strMessage, $strHeader);
			if ($flgSend) {
				alr("Send email successfully");
			} else {
				alr("Cannot send email!");
			}
		}
	}
	?>
	<br><br>
	<div class="container" id="show">
		<h4>Approval booked interpreter</b> Request by <b><?php echo $r['bk_name'] ?></b></h4>
		<hr>
		<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="bk_name" class="col-form-label">Requester Name</label>
					<input type="text" class="form-control" name="bk_name" id="bk_name" placeholder="<?php echo $r['bk_name'] ?>" disabled>
				</div>
				<div class="form-group col-md-6">
					<label for="bk_dept" class="col-form-label">Department</label>
					<input type="text" class="form-control" name="bk_dept" id="bk_dept" placeholder="<?php echo $r['bk_dept'] ?>" disabled>
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
					<label for="bktime_start" class="col-form-label">Date Start</label>
					<input type="text" class="form-control" name="bktime_start" id="bktime_start" placeholder="<?php echo date_format($sdate, 'l d F Y H:i') ?>" disabled>
				</div>
				<div class="form-group col-md-6">
					<label for="bktime_end" class="col-form-label">Date End</label>
					<input type="text" class="form-control" name="bktime_end" id="bktime_end" placeholder="<?php echo date_format($edate, 'l d F Y H:i') ?>" disabled>
				</div>
			</div>
			<div class="form-group">
				<label for="bk_objective" class="col-form-label">Objective</label>
				<input type="text" class="form-control" name="bk_objective" id="bk_objective" placeholder="<?php echo $r['bk_objective'] ?>" disabled>
			</div>
			<div class="form-group">
				<label for="itp_name" class="col-form-label">Intepreter Name</label>
				<?php
				if ($r['itp_id'] == "") { ?>
					<input type="text" class="form-control bg-danger" name="itp_name" id="itp_name" placeholder="<?php echo $r['itp_name'] ?>" disabled>
				<?php	} else { ?>
					<input type="text" class="form-control" name="itp_name" id="itp_name" placeholder="<?php echo $r['itp_name'] ?>" disabled>
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
					<?php	}	?>
				</div>
			</div>
			<hr>
			<!-- <button type="submit" name="done" class="btn btn-outline-success">Continue</button> -->
		</form>
	</div>
</body>

</html>
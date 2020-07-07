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
		if ($r['bk_hr_ap'] == 1) {
			ao("Not update a booked interpreter.", '/booking/TGAS/hr/interpreter/');
		} elseif ($r['bk_mgr_ap'] != 1) {
			ao("Manager Department has not approved booked interpreter.", '/booking/TGAS/hr/interpreter/');
		} else {
			$bk_hr_ap = 1;
			$itp_id = $_POST['itp_id'];
			$sdate = $r['bktime_start'];
			$edate = $r['bktime_end'];
			$ss = $con->query("SELECT * FROM `bk_interpreter` WHERE itp_id = '$itp_id' AND bk_hr_ap = 1 AND (bktime_start BETWEEN '$sdate' AND '$edate') OR itp_id = '$itp_id' AND bk_hr_ap = 1 AND (bktime_end BETWEEN '$sdate' AND '$edate') OR itp_id = '$itp_id' AND bk_hr_ap = 1 AND ('$sdate' BETWEEN bktime_start AND bktime_end) OR itp_id = '$itp_id' AND bk_hr_ap = 1 AND ('$edate' BETWEEN bktime_start AND bktime_end)");
			if ($row = $ss->fetch_array(MYSQLI_ASSOC)) {
				ao("This interpreter was approved already in " . $row['bktime_start'] . " - " . $row['bktime_end'] . ". Please check time to approved.", '/booking/tgas/hr/interpreter/');
			} else {
				if ($itp_id == "") {
					sc("Please select interpreter before approve.");
				} else {
					$con->query("UPDATE `bk_interpreter` SET `itp_id` = '$itp_id', `bk_hr_ap`= '$bk_hr_ap' WHERE `bkitp_id` = '$_GET[bkitp_id]'");
					ao("Approve a booked interpreter.", '/booking/tgas/hr/interpreter/');

					//	mail
					$qemgr = $con->query("SELECT * FROM `employee` WHERE (SELECT employee.emp_dept FROM `employee` WHERE emp_id = (SELECT bk_interpreter.bkemp_id FROM bk_interpreter WHERE bkitp_id = '$_GET[bkitp_id]')) = emp_dept AND emp_position = 'manager'");
					$qitp = $con->query("SELECT interpreter.itp_name FROM bk_interpreter,interpreter WHERE bk_interpreter.itp_id = interpreter.itp_id AND bk_interpreter.bkitp_id = '$_GET[bkitp_id]'");
					$qlink = $con->query("SELECT * FROM `bk_interpreter` WHERE bkitp_id = '$_GET[bkitp_id]'");
					$emgr = $qemgr->fetch_array(MYSQLI_ASSOC);
					$itp = $qitp->fetch_array(MYSQLI_ASSOC);
					$link = $qlink->fetch_array(MYSQLI_ASSOC);
					$lii = $link['bkitp_id'];

					// ini_set("SMTP","localhost");

					$strTo        = "$r[bk_name] <$r[bk_email]>";
					$strSubject   = "=?UTF-8?B?" . base64_encode("HR Approval has approved a booked interpreter.") . "?=";
					$Subject      = "HR Approval has approved a booked interpreter.";
					$strHeader   .= "MIME-Version: 1.0' . \r\n";
					$strHeader   .= "Content-type: text/html; charset=utf-8\r\n";
					$strHeader   .= "From: Booking Management System <$_SESSION[emp_email]>\r\n";
					$strHeader   .= "Cc:<$emgr[emp_email]>\r\n";
					$headmessages = "Interpreter Name : <b>$itp[itp_name]</b> <br> Requester name : <b>$r[bk_name]</b> <br> Department : <b>$r[bk_dept]</b> <br> Date Start : <b>$r[bktime_start]</b> <br> Date End : <b>$r[bktime_end]</b> <br> Telephone number : <b>$r[bk_tel]</b> <br> Email requestor : <b> $r[bk_email] </b> <br> Objective : <b>$r[bk_objective]</b> <br><br> <a target='_blank' href='http://192.168.222.223/booking/tgas/user/interpreter/viewbookinginterpreter.php?bkitp_id=$lii'>Click here to check status</a> <br>";
					include './../../mail.php';
					$flgSend = mail($strTo, $strSubject, $strMessage, $strHeader);
					if ($flgSend) {
						alr("Send email successfully");
					} else {
						alr("Cannot send email!");
					}
				}
			}
		}
	}
	if (isset($_POST['disapproves'])) {
		if ($r['bk_hr_ap'] == 2) {
			ao("Not update a booked meeting room.", '/booking/TGAS/hr/meetingroom/');
		} else {
			$bk_hr_ap = 2;
			$sdate = $r['bktime_start'];
			$edate = $r['bktime_end'];
			$con->query("UPDATE `bk_interpreter` SET `bk_hr_ap`= '$bk_hr_ap' , `bk_mgr_ap` = '2' , `itp_id` = NULL WHERE `bkitp_id` = '$_GET[bkitp_id]'");
			ao("Disapproves a booked interpreter.", '/booking/tgas/hr/interpreter/');

			// mail
			$qemgr = $con->query("SELECT * FROM `employee` WHERE (SELECT employee.emp_dept FROM `employee` WHERE emp_id = (SELECT bk_interpreter.bkemp_id FROM bk_interpreter WHERE bkitp_id = '$_GET[bkitp_id]')) = emp_dept AND emp_position = 'manager'");
			$qitp = $con->query("SELECT * FROM `employee` WHERE emp_id = $_SESSION[emp_id]");
			$qlink = $con->query("SELECT * FROM `bk_interpreter` WHERE bkitp_id = '$_GET[bkitp_id]'");
			$emgr = $qemgr->fetch_array(MYSQLI_ASSOC);
			$itp = $qitp->fetch_array(MYSQLI_ASSOC);
			$link = $qlink->fetch_array(MYSQLI_ASSOC);
			$lii = $link['bkitp_id'];

			// ini_set("SMTP","localhost");

			$strTo        = "$r[bk_name] <$r[bk_email]>";
			$strSubject   = "=?UTF-8?B?" . base64_encode("HR Approval has disapproves a booked interpreter.") . "?=";
			$Subject      = "HR Approval has disapproves a booked interpreter.";
			$strHeader   .= "MIME-Version: 1.0' . \r\n";
			$strHeader   .= "Content-type: text/html; charset=utf-8\r\n";
			$strHeader   .= "From: Booking Management System <$_SESSION[emp_email]>\r\n";
			$strHeader   .= "Cc: User Requester <$emgr[emp_email]>\r\n";
			$headmessages = "Requester name : <b>$r[bk_name]</b> <br> Department : <b>$r[bk_dept]</b> <br> Date Start : <b>$r[bktime_start]</b> <br> Date End : <b>$r[bktime_end]</b> <br> Telephone number : <b>$r[bk_tel]</b> <br> Email requestor : <b> $r[bk_email] </b> <br> Objective : <b>$r[bk_objective]</b> <br><br> <a target='_blank' href='http://192.168.222.223/booking/tgas/manager/interpreter/viewbookinginterpreter.php?bkitp_id=$lii'>Click here to approval page</a> <br>";
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
		<h4>Approve Interpreter in <b><?php if ($r['itp_name'] != "") {
																		echo $r['itp_name'];
																	} else {
																		echo "--";
																	} ?></b> Request by <b><?php echo $r['bk_name']; ?></b></h4>
		<hr>
		<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
			<div class="form-group">
				<label for="itp_id" class="col-form-label">Intepreter Name</label>
				<?php if ($r['itp_id'] == "") { ?>
					<select id="itp_id" name="itp_id" class="form-control" style="background: #fff;">
						<option value="">Choose Interpreter</option>
						<?php $sss = $con->query("SELECT * FROM `interpreter` WHERE itp_factory = '1'");
						while ($rr = $sss->fetch_array(MYSQLI_ASSOC)) { ?>
							<option value="<?php echo $rr['itp_id']; ?>"><?php echo $rr['itp_name']; ?></option>
						<?php } ?>
					</select>
				<?php } else {
					$qrr = $con->query("SELECT * FROM bk_interpreter,interpreter WHERE bk_interpreter.itp_id = interpreter.itp_id AND bkitp_id = '$_GET[bkitp_id]'");
					$rr = $qrr->fetch_array(MYSQLI_ASSOC) ?>
					<input type="text" class="form-control" name="itp_id" id="itp_id" value="<?php echo $rr['itp_name'] ?>" disabled>
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
					<?php	} elseif ($r[bk_hr_ap] == 2) { ?>
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
			<hr>
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
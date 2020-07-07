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
	$query1 = $con->query("SELECT * FROM bk_mtr,employee,meeting_room WHERE meeting_room.mtr_id = bk_mtr.mtr_id AND employee.emp_id = bk_mtr.bkemp_id AND bk_mtr.bk_factory = 1 AND bk_mtr.bkmtr_id = '$_GET[bkmtr_id]'");
	$r = $query1->num_rows;
	$sdate = date_create($r['bktime_start']);
	$edate = date_create($r['bktime_end']);
	?>
	<?php
	if (isset($_POST['approved'])) {
		if ($r['bk_hr_ap'] == 1) {
			ao("Not update a booked meeting room.", '/booking/TGAS/hr/meetingroom/');
		} elseif ($r['bk_mgr_ap'] != 1) {
			ao("Manager Department has not approved booked meeting room.", '/booking/TGAS/hr/meetingroom/');
		} else {
			$bk_hr_ap = 1;
			$sdate = $r['bktime_start'];
			$edate = $r['bktime_end'];
			$ss = $con->query("SELECT * FROM `bk_mtr` WHERE mtr_id = $r[mtr_id] AND bk_hr_ap = 1 AND (bktime_start BETWEEN '$sdate' AND '$edate') OR mtr_id = $r[mtr_id] AND bk_hr_ap = 1 AND (bktime_end BETWEEN '$sdate' AND '$edate') OR mtr_id = $r[mtr_id] AND bk_hr_ap = 1 AND ('$sdate' BETWEEN bktime_start AND bktime_end) OR mtr_id = $r[mtr_id] AND bk_hr_ap = 1 AND ('$edate' BETWEEN bktime_start AND bktime_end)");
			if ($row = $ss->fetch_array(MYSQLI_ASSOC)) {
				ao("This room was approved already in " . $row['bktime_start'] . " - " . $row['bktime_end'] . ". Please check time to approved.", '/booking/tgt/hr/meetingroom/');
				exit();
			} else {
				$sa = $con->query("UPDATE `bk_mtr` SET `bk_hr_ap`= '$bk_hr_ap' WHERE `bkmtr_id` = '$_GET[bkmtr_id]'");
				$dee = $con->query("SELECT * FROM `bk_mtr` WHERE mtr_id = $r[mtr_id] AND bk_hr_ap IS NULL AND (bktime_start BETWEEN '$sdate' AND '$edate') OR mtr_id = $r[mtr_id] AND bk_hr_ap IS NULL AND (bktime_end BETWEEN '$sdate' AND '$edate') OR mtr_id = $r[mtr_id] AND bk_hr_ap IS NULL AND ('$sdate' BETWEEN bktime_start AND bktime_end) OR mtr_id = $r[mtr_id] AND bk_hr_ap IS NULL AND ('$edate' BETWEEN bktime_start AND bktime_end)");
				while ($dele = $dee->fetch_array(MYSQLI_ASSOC)) {

					$qemgr =  $con->query("SELECT * FROM `employee` WHERE (SELECT employee.emp_dept FROM `employee` WHERE emp_id = (SELECT bk_mtr.bkemp_id FROM bk_mtr WHERE bkmtr_id = '$_GET[bkmtr_id]')) = emp_dept AND emp_position = 'manager'");
					$emgr = $qemgr->fetch_array(MYSQLI_ASSOC);
					$qlink = $con->query("SELECT * FROM `bk_mtr` WHERE bkmtr_id = '$_GET[bkmtr_id]'");
					$link = $qlink->fetch_array(MYSQLI_ASSOC);
					$lii = $link['bkmtr_id'];

					// ini_set("SMTP","localhost");

					$strTo        = "$dele[bk_name] <$dele[bk_email]>";
					$strSubject   = "=?UTF-8?B?" . base64_encode("Your booked meeting room has delete.") . "?=";
					$Subject      = "Your booked meeting room has delete.";
					$strHeader   .= "MIME-Version: 1.0' . \r\n";
					$strHeader   .= "Content-type: text/html; charset=utf-8\r\n";
					$strHeader   .= "From: Booking Management System <$_SESSION[emp_email]>\r\n";
					$strHeader   .= "Cc:<$emgr[emp_email]>\r\n";

					$headmessages = "Meeting room : <b>$r[mtr_number]</b> <br> Requester name : <b>$r[bk_name]</b> <br> Department : <b>$r[bk_dept]</b> <br> Telephone number : <b>$r[bk_tel]</b> <br> Email : <b>$r[bk_email]</b> <br> Time start : <b>$r[bktime_start]</b> <br> Time end : <b>$r[bktime_end]</b> <br> Objective : <b>$r[bk_objective]</b><br><br>";
					$headmessages .= "Because booking meeting room has approved by <b>$r[bk_name] $r[bk_dept]</b><br>Time start : <b>$r[bktime_start]</b> <br> Time end : <b>$r[bktime_end]</b>";
					include './../../mail.php';
					$flgSend = mail($strTo, $strSubject, $strMessage, $strHeader);
					if ($flgSend) {
						al_g("Send email successfully");
						$del = $con->query("DELETE FROM `bk_mtr` WHERE mtr_id = $r[mtr_id] AND bk_hr_ap IS NULL AND (bktime_start BETWEEN '$sdate' AND '$edate') OR mtr_id = $r[mtr_id] AND bk_hr_ap IS NULL AND (bktime_end BETWEEN '$sdate' AND '$edate') OR mtr_id = $r[mtr_id] AND bk_hr_ap IS NULL AND ('$sdate' BETWEEN bktime_start AND bktime_end) OR mtr_id = $r[mtr_id] AND bk_hr_ap IS NULL AND ('$edate' BETWEEN bktime_start AND bktime_end)");
						if (!$del) {
							alr('Cannot delete this time booked repeatedly.');
							exit();
						}
					} else {
						al_r("Cannot send email!");
					}
				}
				ao("Approve a booked meeting room.", '/booking/tgt/hr/meetingroom/');

				//	mail
				$qemgr = $con->query("SELECT * FROM `employee` WHERE (SELECT employee.emp_dept FROM `employee` WHERE emp_id = (SELECT bk_mtr.bkemp_id FROM bk_mtr WHERE bkmtr_id = '$_GET[bkmtr_id]')) = emp_dept AND emp_position = 'manager'");
				$emgr = $qemgr->fetch_array(MYSQLI_ASSOC);
				$qlink = $con->query("SELECT * FROM `bk_mtr` WHERE bkmtr_id = '$_GET[bkmtr_id]'");
				$link = $qlink->fetch_array(MYSQLI_ASSOC);
				$lii = $link['bkmtr_id'];

				// ini_set("SMTP","localhost");

				$strTo        = "$r[bk_name] <$r[bk_email]>";
				$strSubject   = "=?UTF-8?B?" . base64_encode("HR Approval has approved a booked meeting room.") . "?=";
				$Subject      = "HR Approval has approved a booked meeting room.";
				$strHeader   .= "MIME-Version: 1.0' . \r\n";
				$strHeader   .= "Content-type: text/html; charset=utf-8\r\n";
				$strHeader   .= "From: Booking Management System <$_SESSION[emp_email]>\r\n";
				$strHeader   .= "Cc:<$emgr[emp_email]>\r\n";
				$headmessages = "Meeting room : <b>$r[mtr_number]</b> <br> Requester name : <b>$r[bk_name]</b> <br> Department : <b>$r[bk_dept]</b> <br> Telephone number : <b>$r[bk_tel]</b> <br> Email : <b>$r[bk_email]</b> <br> Time start : <b>$r[bktime_start]</b> <br> Time end : <b>$edate</b> <br> Objective : <b>$r[bk_objective]</b> <br><br> <a target='_blank' href='http://192.168.222.223/booking/tgt/user/meetingroom/viewbookingmtr.php?bkmtr_id=$lii'>Click here to check statue</a> <br>";
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
	if (isset($_POST['disapproves'])) {
		if ($r['bk_hr_ap'] == 2) {
			ao("Not update a booked meeting room.", '/booking/TGAS/hr/meetingroom/');
		} else {
			$bk_hr_ap = 2;
			$sdate = $r['bktime_start'];
			$edate = $r['bktime_end'];
			$con->query("UPDATE `bk_mtr` SET `bk_hr_ap`= '$bk_hr_ap' , `bk_mgr_ap` = '2' WHERE `bkmtr_id` = '$_GET[bkmtr_id]'");
			ao("Disapproves a booked meeting room.", '/booking/tgt/hr/meetingroom/');

			// mail
			$qemgr = $con->query("SELECT * FROM `employee` WHERE (SELECT employee.emp_dept FROM `employee` WHERE emp_id = (SELECT bk_mtr.bkemp_id FROM bk_mtr WHERE bkmtr_id = '$_GET[bkmtr_id]')) = emp_dept AND emp_position = 'manager'");
			$emgr = $qemgr->fetch_array(MYSQLI_ASSOC);
			$qe = $con->query("SELECT * FROM `employee` WHERE emp_id = $_SESSION[emp_id]");
			$e = $qe->fetch_array(MYSQLI_ASSOC);
			$qlink = $con->query("SELECT * FROM `bk_mtr` WHERE bkmtr_id = '$_GET[bkmtr_id]'");
			$link = $qlink->fetch_array(MYSQLI_ASSOC);
			$lii = $link['bkmtr_id'];

			// ini_set("SMTP","localhost");

			$strTo        = "$r[bk_name] <$r[bk_email]>";
			$strSubject   = "=?UTF-8?B?" . base64_encode("HR Approval has disapproves a booked meeting room.") . "?=";
			$Subject      = "HR Approval has disapproves a booked meeting room.";
			$strHeader   .= "MIME-Version: 1.0' . \r\n";
			$strHeader   .= "Content-type: text/html; charset=utf-8\r\n";
			$strHeader   .= "From: Booking Management System <$_SESSION[emp_email]>\r\n";
			$strHeader   .= "Cc: User Requester <$emgr[emp_email]>\r\n";
			$headmessages = "Meeting room : <b>$r[mtr_number]</b> <br> Requester name : <b>$r[bk_name]</b> <br> Department : <b>$r[bk_dept]</b> <br> Telephone number : <b>$r[bk_tel]</b> <br> Email : <b>$r[bk_email]</b> <br> Time start : <b>$r[bktime_start]</b> <br> Time end : <b>$edate</b> <br> Objective : <b>$r[bk_objective]</b> <br><br> <a target='_blank' href='http://192.168.222.223/booking/tgt/user/meetingroom/viewbookingmtr.php?bkmtr_id=$lii'>Click here to check a booked</a> <br>";
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
		<h4>Approval Meeting room in <b><?php echo $r['mtr_number'] ?></b> Request by <b><?php echo $r['bk_name'] ?></b></h4>
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
					<label for="bktime_start" class="col-form-label">Date start</label>
					<input type="text" class="form-control" name="bktime_start" id="bktime_start" placeholder="<?php echo date_format($sdate, 'l d F Y H:i') ?>" disabled>
				</div>
				<div class="form-group col-md-6">
					<label for="bktime_end" class="col-form-label">Date end</label>
					<input type="text" class="form-control" name="bktime_end" id="bktime_end" placeholder="<?php echo date_format($edate, 'l d F Y H:i') ?>" disabled>
				</div>
			</div>

			<div class="form-group">
				<label for="bk_item_request" class="col-form-label">Item request</label>
				<textarea name="bk_item_request" class="form-control form-check" id="bk_item_request" cols="100" rows="3" maxlength="200" placeholder="<?php echo $r['bk_item_request'] ?>" disabled></textarea>
			</div>

			<div class="form-group">
				<label for="bk_objective" class="col-form-label">Objective</label>
				<input type="text" class="form-control" name="bk_objective" id="bk_objective" placeholder="<?php echo $r['bk_objective'] ?>" disabled>
			</div>

			<hr>

			<div id="approval" class="text-center">
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
					<?php	}
					?>
				</div>
			</div>
			<hr>
		</form>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript">
		$(function() {
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
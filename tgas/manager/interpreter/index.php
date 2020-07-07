<?php
require_once('./../../../config.php');
@session_start();
@$ps = $_GET['ps'];
@$search = $_GET['search'];
// CHECK LOGIN
if (!isset($_SESSION['emp_id'])) {
	hd("/booking/logout");
} elseif (strtolower($_SESSION['emp_position']) != "manager" && strtolower($_SESSION['emp_position']) != "administrator" || $_SESSION['emp_factory'] != "1") {
	hd("/booking/");
}
include './../../header.php';
?>
<?php
if (isset($_GET['del2'])) {
	$qto = $con->query("SELECT * FROM `bk_interpreter`,`meeting_room` WHERE `bk_interpreter`.`itp_id` = `meeting_room`.`itp_id` AND `bkitp_id` = '" . $_GET['del2'] . "' ");
	$qcc = $con->query("SELECT * FROM `employee`,`bk_interpreter` WHERE `employee`.`emp_id` = `bk_interpreter`.`bkemp_id` AND `bk_interpreter`.`bkitp_id` = '" . $_GET['del2'] . "' ");
	$to = $qto->fetch_array(MYSQLI_ASSOC);
	$cc = $qcc->fetch_array(MYSQLI_ASSOC);

	// ini_set("SMTP","localhost");
	// ini_set("sendmail_from","$bk_email");

	$strTo       = "$to[bk_name] <$to[bk_email]>";
	$strSubject  = "=?UTF-8?B?" . base64_encode("Your booked interpreter has deleted.") . "?=";
	$Subject     = "Your booked interpreter has deleted.";
	$strHeader  .= "MIME-Version: 1.0' . \r\n";
	$strHeader  .= "Content-type: text/html; charset=utf-8\r\n";
	$strHeader  .= "From: Booking Management System <$_SESSION[emp_email]>\r\n";
	$strHeader  .= "Cc: User ID Requester<$cc[emp_email]>\r\n";

	$headmessages = "Requester name : <b>$to[bk_name]</b> <br> Department : <b>$to[bk_dept]</b> <br> Telephone number : <b>$to[bk_tel]</b> <br> Email : <b>$to[bk_email]</b> <br> Time start : <b>$to[bktime_start]</b> <br> Time end : <b>$edate</b>";

	include './../../mail.php';

	$flgSend = mail($strTo, $strSubject, $strMessage, $strHeader);
	if ($flgSend) {
		al_g("Email Sending.");
		$con->query("DELETE FROM `bk_interpreter` WHERE `bkitp_id` = '" . $_GET['del2'] . "'");
	} else {
		al_r("Cannot send Mail!");
	}
	sc("Delete Successful !");
}
?>
<html>

<head>
	<meta charset="utf-8">
	<title></title>
	<script language="JavaScript">
		function fncHideap() { //Command to Hide
			document.getElementById('ap').style.display = 'none';
			document.getElementById('btnShowap').setAttribute("onClick", "JavaScript:fncShowap();");
			document.getElementById('btnShowap').setAttribute("value", "Show Approval !");
			document.getElementById('btnShowap').setAttribute("class", "btn btn-secondary btn-block");
		}

		function fncHideinterpreter() { //Command to Hide
			var getidinterpreter = document.getElementById('interpreter').style.display = 'none';
			document.getElementById('btnShowinterpreter').setAttribute("onClick", "JavaScript:fncShowinterpreter();");
			document.getElementById('btnShowinterpreter').setAttribute("value", "Show Interpreter !");
			document.getElementById('btnShowinterpreter').setAttribute("class", "btn btn-secondary btn-block");
		}

		function fncShowap() { //Command to Show
			document.getElementById('ap').style.display = '';
			document.getElementById('btnShowap').setAttribute("onClick", "JavaScript:fncHideap();");
			document.getElementById('btnShowap').setAttribute("value", "Hide Approval !");
			document.getElementById('btnShowap').setAttribute("class", "btn btn-outline-secondary btn-block");
			if (document.getElementById('interpreter').style.display == '') {
				fncHideinterpreter();
			}
		}

		function fncShowinterpreter() { //Command to Show
			var getidinterpreter = document.getElementById('interpreter').style.display = '';
			document.getElementById('btnShowinterpreter').setAttribute("onClick", "JavaScript:fncHideinterpreter();");
			document.getElementById('btnShowinterpreter').setAttribute("value", "Hide Interpreter !");
			document.getElementById('btnShowinterpreter').setAttribute("class", "btn btn-outline-secondary btn-block");
			if (document.getElementById('ap').style.display == '') {
				fncHideap();
			}
		}
	</script>
</head>

<body>
	<div id="book" class="text-center align-middle" style="position: absolute; height: 100%; width: 45px;line-height: 1000%;background: #4267b2">
	</div>
	<div class="container">
		<form action="" method="get" name="fs" id="fs">
			<br>
			<div class="form-group">
				<div class="row row justify-content-between">
					<div class="col-md-3">
						<h2 class="">
							<font class="">Interpreter</font>
						</h2>
					</div>
					<div class="col-md-5">
						<a href="/booking/tgas/bookinginterpreter.php" class="btn btn-danger btn-block"> Booking Interpreter </a>
					</div>
				</div>
				<br>
				<div class="row justify-content-end">
					<div class="col-md-3 ">
						<input class="btn btn-outline-secondary btn-block" name="btnShowap" id="btnShowap" type="button" value="Hide Approval !" onClick="JavaScript:fncHideap();">
					</div>
					<div class="col-md-3">
						<input class="btn btn-secondary btn-block" name="btnShowinterpreter" id="btnShowinterpreter" type="button" value="Show Interpreter !" onClick="JavaScript:fncShowinterpreter();">
					</div>
				</div>
			</div>
		</form>

		<form id="interpreter" style="display:none;">
			<table class="table table-hover">
				<thead>
					<tr class="text-center">
						<th class="text-center">Number</th>
						<th class="text-center">Interpreter name</th>
						<th class="text-center">Interpreter telephone</th>
						<th class="text-center">Factory</th>
						<th class="text-center">Status</th>
					</tr>
				</thead>
				<?php
				$x = 1;
				$row = 10;
				$query = $con->query("SELECT * FROM `interpreter` WHERE `itp_factory` = 1");
				$total_data = $query->num_rows;
				$total_page = ceil($total_data / $row);
				$page = @$_GET['page'];
				if ($page < 1) {
					$page = 1;
				}
				if ($page > $total_page) {
					$page = $total_page;
				}
				$start = ($page - 1) * $row;
				$sql = $con->query("SELECT * FROM `interpreter` WHERE `itp_factory` = 1 ORDER BY `itp_status` DESC , `itp_factory` LIMIT $start,$row");
				?>
				<tbody>
					<?php while ($r = $sql->fetch_array(MYSQLI_ASSOC)) { ?>
						<tr class="text-center">
							<td><?php echo $x;
									$x++; ?></td>
							<td><a href="/booking/tgas/viewinterpreter.php?itp_id=<?php echo $r['itp_id'] ?>"><?php echo $r['itp_name']; ?></a></td>
							<td><a href="/booking/tgas/viewinterpreter.php?itp_id=<?php echo $r['itp_id'] ?>"><?php echo $r['itp_tel'] ?></a></td>
							<td><a href="/booking/tgas/viewinterpreter.php?itp_id=<?php echo $r['itp_id'] ?>">
									<?php if ($r['itp_factory'] == 1) {
										echo "TGAS";
									} elseif ($r['itp_factory'] == 2) {
										echo "TGT";
									} elseif ($r['itp_factory'] == 3) {
										echo "TGRT";
									} else {
										echo "Data not found";
									} ?></a>
							</td>
							<td><a href="/booking/tgas/viewinterpreter.php?itp_id=<?php echo $r['itp_id'] ?>">
									<?php
									if ($r['itp_status'] == 1) {
										echo "Enable";
									} elseif ($r['itp_status'] == 2) {
										echo "Disabled or Repair";
									} else {
										echo "Status is not set";
									}
									?></a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="text-center" id="numpage">
				<a <?php if ($page != 1) { ?> href="?page=<?php echo $page - 1;
																									echo "&ps=" . $ps . "&search=" . $search;
																								} ?>"><img src="/booking/images/pre.png" width="1.55%" style="margin-bottom: 3px; margin-right:5px;"></a>
				<?php
				for ($i = 1; $i <= $total_page; $i++) {
					echo " <a href=?page=$i&ps=$ps&search=$search>$i</a> ";
				}
				?>
				<a <?php if ($page != $total_page) { ?> href="?page=<?php echo $page + 1;
																														echo "&ps=" . $ps . "&search=" . $search;
																													} ?>"><img src="/booking/images/nex.png" width="1.55%" style="margin-bottom: 3px; margin-left:5px;"></a>
			</div>
		</form>
		<form id="ap">
			<div class="text-right">
				<span class="badge badge-pill badge-info">Manager approved and wait for HR approval</span>
				<span class="badge badge-pill badge-warning sr-only">HR approved but wait for Manager approve</span>
				<span class="badge badge-pill badge-success">Booking successfully by HR Approval</span>
				<span class="badge badge-pill badge-danger">Manager or HR disapproves</span>
			</div>
			<br>
			<table class="table table-hover">
				<thead>
					<tr class="text-centert">
						<th class="text-center">Number</th>
						<th class="text-center">Time Start</th>
						<th class="text-center">Time End</th>
						<th class="text-center">Requester name</th>
						<th class="text-center">Delete</th>
					</tr>
				</thead>
				<?php
				$x = 1;
				$row = 10;
				$sql = $con->query("SELECT * FROM bk_interpreter,employee WHERE bk_interpreter.bkemp_id = employee.emp_id AND bk_interpreter.bk_factory = 1 AND bk_interpreter.bktime_end >= '$now' AND employee.emp_dept = (SELECT employee.emp_dept FROM employee WHERE emp_id = '$_SESSION[emp_id]') ORDER BY bk_interpreter.bkitp_id,bk_interpreter.bktime_start ASC  LIMIT $start,$row");
				$total_data = $sql->num_rows;
				$total_page = ceil($total_data / $row);
				$page = @$_GET['page'];
				if ($page < 1) {
					$page = 1;
				}
				if ($page > $total_page) {
					$page = $total_page;
				}
				$start = ($page - 1) * $row;
				$now = date('Y-m-d');

				// เพิ่ม AND bk_interpreter.bktime_end >= '$now' ลงไปในเงื่อนไขสุดท้าย ถ้าต้องการให้แสดงเฉพาะวันนี้เป็นต้นไป //เรียงตาม เลขห้อง เวลาเริ่ม
				?>
				<tbody>
					<?php while ($r = $sql->fetch_array(MYSQLI_ASSOC)) {
						// al($r['itp_name']);
						if ($r['bk_mgr_ap'] == '1') {
							if ($r['bk_mgr_ap'] == '1' && $r['bk_hr_ap'] == '1') {	?>
								<tr class="text-center bg-success text-white">
								<?php } elseif ($r['bk_mgr_ap'] == '1' && $r['bk_hr_ap'] == '2') {	?>
								<tr class="text-center bg-danger text-white">
								<?php } else { ?>
								<tr class="text-center bg-info text-white">
								<?php } ?>
							<?php } elseif ($r['bk_mgr_ap'] == '2') { ?>
								<tr class="text-center bg-danger text-white">
								<?php } elseif ($r['bk_mgr_ap'] == NULL && $r['bk_hr_ap'] == '1') { ?>
								<tr class="text-center bg-warning text-white">
								<?php } elseif ($r['bk_mgr_ap'] == NULL && $r['bk_hr_ap'] == '2') { ?>
								<tr class="text-center bg-danger text-white">
								<?php } else { ?>
								<tr class="text-center">
								<?php } ?>
								<td class="text-center">
									<a href="./viewbookinginterpreter.php?bkitp_id=<?php echo $r['bkitp_id'] ?>">
										<?php echo $x;
										$x++; ?>
									</a>
								</td>
								<?php
								$sdate = date_create($r['bktime_start']);
								$edate = date_create($r['bktime_end']);
								?>
								<td class="text-center">
									<a href="./viewbookinginterpreter.php?bkitp_id=<?php echo $r['bkitp_id'] ?>">
										<?php echo date_format($sdate, 'l d F Y H:i') ?>
									</a>
								</td>
								<td class="text-center">
									<a href="./viewbookinginterpreter.php?bkitp_id=<?php echo $r['bkitp_id'] ?>">
										<?php echo date_format($edate, 'l d F Y H:i') ?>
									</a>
								</td>
								<td class="text-center">
									<a href="./viewbookingitp.php?bkitp_id=<?php echo $r['bkitp_id'] ?>">
										<?php echo $r['bk_name'] ?>
									</a>
								</td>
								<td>
									<font>
										<a href="?del2=<?php echo $r['bkitp_id']; ?>" onClick="return confirm('Are your sure to delete?')"> <img src="/booking/images/delete.png" width="25px" style="margin-bottom:3px;"> </a>
									</font>
								</td>
								</tr>
							<?php } ?>
				</tbody>
			</table>
			<div class="text-center" id="numpage">
				<a <?php if ($page != 1) { ?> href="?page=<?php echo $page - 1;
																									echo "&ps=" . $ps . "&search=" . $search;
																								} ?>"><img src="/booking/images/pre.png" width="1.55%" style="margin-bottom: 3px; margin-right:5px;"></a>
				<?php
				for ($i = 1; $i <= $total_page; $i++) {
					echo " <a href=?page=$i&ps=$ps&search=$search>$i</a> ";
				}
				?>
				<a <?php if ($page != $total_page) { ?> href="?page=<?php echo $page + 1;
																														echo "&ps=" . $ps . "&search=" . $search;
																													} ?>"><img src="/booking/images/nex.png" width="1.55%" style="margin-bottom: 3px; margin-left:5px;"></a>
			</div>
		</form>
		<?php include './../../allitp.php'; ?>
	</div>
</body>

</html>
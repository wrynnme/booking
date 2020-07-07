<?php
require_once('./../../../config.php');
@session_start();
$ps = $_GET['ps'];
$search = $_GET['search'];
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
	$qto = $con->query("SELECT * FROM `bk_car`,`car` WHERE `bk_car`.`car_id` = `car`.`car_id` AND `bkcar_id` = '" . $_GET['del2'] . "' ");
	$qcc = $con->query("SELECT * FROM `employee`,`bk_car` WHERE `employee`.`emp_id` = `bk_car`.`bkemp_id` AND `bk_car`.`bkcar_id` = '" . $_GET['del2'] . "' ");
	$to = $qto->fetch_array(MYSQLI_ASSOC);
	$cc = $qcc->fetch_array(MYSQLI_ASSOC);

	// ini_set("SMTP","localhost");
	// ini_set("sendmail_from","$bk_email");

	$strTo       = "$to[bk_name] <$to[bk_email]>";
	$strSubject  = "=?UTF-8?B?" . base64_encode("Your booked car has deleted.") . "?=";
	$Subject     = "Your booked car has deleted.";
	$strHeader  .= "MIME-Version: 1.0' . \r\n";
	$strHeader  .= "Content-type: text/html; charset=utf-8\r\n";
	$strHeader  .= "From: Booking Management System <$_SESSION[emp_email]>\r\n";
	$strHeader  .= "Cc: User ID Requester<$cc[emp_email]>\r\n";

	$headmessages = "Requester name : <b>$to[bk_name]</b> <br> ID Code : <b>$to[bk_idcode]</b> <br> Telephone number : <b>$to[bk_tel]</b> <br> Email : <b>$to[bk_email]</b> <br> Position : <b>$to[bk_position]</b> <br> Department : <b>$to[bk_dept]</b> <br> Time start : <b>$to[bktime_start]</b> <br> Time end : <b>$edate</b> <br> Purpose : <b>$to[bk_purpose]</b>";

	include './../../mail.php';

	$flgSend = mail($strTo, $strSubject, $strMessage, $strHeader);
	if ($flgSend) {
		al_g("Email Sending.");
		$con->query("DELETE FROM `bk_car` WHERE `bkcar_id` = '" . $_GET['del2'] . "'");
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

		function fncHidecar() { //Command to Hide
			var getidcar = document.getElementById('car').style.display = 'none';
			document.getElementById('btnShowcar').setAttribute("onClick", "JavaScript:fncShowcar();");
			document.getElementById('btnShowcar').setAttribute("value", "Show Car !");
			document.getElementById('btnShowcar').setAttribute("class", "btn btn-secondary btn-block");
		}

		function fncShowap() { //Command to Show
			document.getElementById('ap').style.display = '';
			document.getElementById('btnShowap').setAttribute("onClick", "JavaScript:fncHideap();");
			document.getElementById('btnShowap').setAttribute("value", "Hide Approval !");
			document.getElementById('btnShowap').setAttribute("class", "btn btn-outline-secondary btn-block");
			if (document.getElementById('car').style.display == '') {
				fncHidecar();
			}
		}

		function fncShowcar() { //Command to Show
			var getidcar = document.getElementById('car').style.display = '';
			document.getElementById('btnShowcar').setAttribute("onClick", "JavaScript:fncHidecar();");
			document.getElementById('btnShowcar').setAttribute("value", "Hide Car !");
			document.getElementById('btnShowcar').setAttribute("class", "btn btn-outline-secondary btn-block");
			if (document.getElementById('ap').style.display == '') {
				fncHideap();
			}
		}
	</script>
</head>

<body>
	<div id="book" class="text-center align-middle" style="position: absolute; height: 100%; width: 45px;line-height: 1000%;background: #4267b2">
	</div>
	<div class="container"><br>
		<form action="" method="get" name="fs" id="fs">
			<div class="form-group">
				<div class="row row justify-content-between">
					<div class="col-md-3">
						<h2 class="">
							<font class="">Company Car</font>
						</h2>
					</div>
					<div class="col-md-5">
						<a href="/booking/tgas/bookingcar.php" class="btn btn-danger btn-block"> Booking Car </a>
					</div>
				</div>
				<br>
				<div class="row justify-content-end">

					<div class="col-md-3 ">
						<input class="btn btn-outline-secondary btn-block" name="btnShowap" id="btnShowap" type="button" value="Hide Approval !" onClick="JavaScript:fncHideap();">
					</div>
					<div class="col-md-3">
						<input class="btn btn-secondary btn-block" name="btnShowcar" id="btnShowcar" type="button" value="Show Car !" onClick="JavaScript:fncShowcar();">
					</div>
				</div>
			</div>
		</form>

		<form id="car" style="display:none;">
			<table class="table table-hover">
				<thead>
					<tr class="text-center">
						<th class="text-center">Number</th>
						<th class="text-center">License Plate</th>
						<th class="text-center">Driver name</th>
						<th class="text-center">Driver tel</th>
						<th class="text-center">Brand</th>
						<th class="text-center">Color</th>
						<th class="text-center">Factory</th>
						<th class="text-center">Status</th>
					</tr>
				</thead>
				<?php
				$x = 1;
				$row = 10;
				$query = $con->query("SELECT * FROM `car` WHERE `car_factory` = 1");
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
				$sql = $con->query("SELECT * FROM `car` WHERE `car_factory` = 1 ORDER BY `car_status` DESC , `car_factory` LIMIT $start,$row");
				?>
				<tbody>
					<?php while ($r = $sql->fetch_array(MYSQLI_ASSOC)) { ?>
						<tr class="text-center">
							<td><?php echo $x;
									$x++; ?></td>
							<td><a href="/booking/tgas/viewcar.php?car_id=<?php echo $r['car_id'] ?>"><?php echo $r['license_plate']; ?></a></td>
							<td><a href="/booking/tgas/viewcar.php?car_id=<?php echo $r['car_id'] ?>"><?php echo $r['driver_name'] ?></a></td>
							<td><a href="/booking/tgas/viewcar.php?car_id=<?php echo $r['car_id'] ?>"><?php echo $r['driver_tel'] ?></a></td>
							<td><a href="/booking/tgas/viewcar.php?car_id=<?php echo $r['car_id'] ?>"><?php echo $r['car_brand'] ?></a></td>
							<td><a href="/booking/tgas/viewcar.php?car_id=<?php echo $r['car_id'] ?>"><input class="form-control" type="color" value="<?php echo $r['car_color'] ?>" id="inputcar_color" name="inputcar_color" style="height: 38px;" disabled></a></td>
							<td>
								<?php if ($r['car_factory'] == 1) {
									echo "TGAS";
								} elseif ($r['car_factory'] == 2) {
									echo "TGT";
								} elseif ($r['car_factory'] == 3) {
									echo "TGRT";
								} else {
									echo "Data not found";
								} ?>
							</td>
							<td>
								<?php
								if ($r['car_status'] == 1) {
									echo "Enable";
								} elseif ($r['car_status'] == 2) {
									echo "Disabled or Repair";
								} else {
									echo "Status is not set";
								}
								?>
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
				<span class="badge badge-pill badge-info">Manager approved and wait for HR Approval</span>
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
				$sql = $con->query("SELECT * FROM bk_car,employee WHERE bk_car.bkemp_id = employee.emp_id AND bk_car.bk_factory = 1 AND bk_car.bktime_end >= '$now' AND employee.emp_dept = (SELECT employee.emp_dept FROM employee WHERE emp_id = '$_SESSION[emp_id]') ORDER BY bk_car.bkcar_id,bk_car.bktime_start ASC  LIMIT $start,$row");
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

				// เพิ่ม AND bk_car.bktime_end >= '$now' ลงไปในเงื่อนไขสุดท้าย ถ้าต้องการให้แสดงเฉพาะวันนี้เป็นต้นไป //เรียงตาม เลขห้อง เวลาเริ่ม
				?>
				<tbody>
					<?php while ($r = $sql->fetch_array(MYSQLI_ASSOC)) {
						// al($r['license_plate']);
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
									<a href="./viewbookingcar.php?bkcar_id=<?php echo $r['bkcar_id'] ?>">
										<?php echo $x;
										$x++; ?>
									</a>
								</td>
								<?php
								$sdate = date_create($r['bktime_start']);
								$edate = date_create($r['bktime_end']);
								?>
								<td class="text-center">
									<a href="./viewbookingcar.php?bkcar_id=<?php echo $r['bkcar_id'] ?>">
										<?php echo date_format($sdate, 'l d F Y H:i') ?>
									</a>
								</td>
								<td class="text-center">
									<a href="./viewbookingcar.php?bkcar_id=<?php echo $r['bkcar_id'] ?>">
										<?php echo date_format($edate, 'l d F Y H:i') ?>
									</a>
								</td>
								<td class="text-center">
									<a href="./viewbookingcar.php?bkcar_id=<?php echo $r['bkcar_id'] ?>">
										<?php echo $r['bk_name'] ?>
									</a>
								</td>
								<td>
									<font>
										<a href="?del2=<?php echo $r['bkcar_id']; ?>" onClick="return confirm('Are your sure to delete?')"> <img src="/booking/images/delete.png" width="25px" style="margin-bottom:3px;"> </a>
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
		<?php include './../../allcar.php'; ?>
	</div>
</body>

</html>
<?php
require_once('./../config.php');
@session_start();
// CHECK LOGIN
if (strtolower($_SESSION['emp_position']) == "administrator") {
	$_SESSION['emp_factory'] = '1';
}
if (!isset($_SESSION['emp_id'])) {
	hd("/booking/logout");
} elseif ($_SESSION['emp_factory'] != "1") {
	hd("/booking/");
}
include './header.php';
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
</head>

<body>
	<?php
	$qss = $con->query("SELECT * FROM interpreter WHERE itp_id = $_GET[itp_id]");
	$ss = $qss->fetch_array(MYSQLI_ASSOC);
	?>
	<div class="container">
		<br><br>
		<h2><?php echo $ss['itp_name'] ?></h2>
		<hr>
		<form action="">
			<div class="row">
				<div class="col-md-auto">
					<img src="/booking/images/uploads/itp/<?php echo $ss['itp_options'] ?>" alt="" class="" width="300px" height="auto">
				</div>
				<div class="col">
					<table>
						<th>
							<tr>
								<td class="text-right"><label for="itp_name" class="font-weight-bold h4">Interpreter Name : </label></td>
								<td></td>
								<td><label name="itp_name" id="itp_name" class="h4"><?php echo $ss['itp_name'] ?></label></td>
							</tr>
							<tr>
								<td class="text-right"><label for="itp_name" class="font-weight-bold h4">Department : </label></td>
								<td></td>
								<td><label name="itp_name" id="itp_name" class="h4"><?php echo $ss['itp_dept'] ?></label></td>
							</tr>
							<tr>
								<td class="text-right"><label for="itp_name" class="font-weight-bold h4">Telephone : </label></td>
								<td></td>
								<td><label name="itp_name" id="itp_name" class="h4"><?php echo $ss['itp_tel'] ?></label></td>
							</tr>
							<tr>
								<td class="text-right"><label for="itp_name" class="font-weight-bold h4">Email : </label></td>
								<td></td>
								<td><label name="itp_name" id="itp_name" class="h4"><?php echo $ss['itp_email'] ?></label></td>
							</tr>
						</th>
						</th>
						</th>
					</table>
				</div>
			</div>
		</form>
		<hr>
		<?php
		$sql = $con->query("SELECT * FROM bk_interpreter,interpreter WHERE bk_interpreter.itp_id = interpreter.itp_id AND interpreter.itp_id = '$_GET[itp_id]'");
		$sqli = $sql->fetch_array(MYSQLI_ASSOC);
		?>
		<h2>Schedule interpreter's <?php echo $sqli['itp_name']; ?></h2>
		<form action="">
			<div class="text-right">
				<span class="badge badge-pill badge-info">Manager approved and wait for HR approval</span>
				<span class="badge badge-pill badge-warning sr-only">HR approved but wait for Manager approve</span>
				<span class="badge badge-pill badge-success">Booking successfully by HR Approval</span>
				<span class="badge badge-pill badge-danger">Manager or HR disapproves</span>
			</div>
			<br>
			<table class="table">
				<thead>
					<tr class="">
						<th class="text-center">Interpreter Name</th>
						<th class="text-center">Time Start</th>
						<th class="text-center">Time End</th>
						<th class="text-center">Requester by</th>
						<th class="text-center">MGR AP</th>
						<th class="text-center">HR AP</th>
					</tr>
				</thead>
				<?php
				$sql = $con->query("SELECT * FROM bk_interpreter,interpreter,employee WHERE bk_interpreter.itp_id = interpreter.itp_id AND bk_interpreter.bkemp_id = employee.emp_id AND interpreter.itp_id = '$_GET[itp_id]' ORDER BY `bk_interpreter`.`bktime_start`, `bk_interpreter`.`bktime_end` DESC");
				while ($r = $sql->fetch_array(MYSQLI_ASSOC)) {
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
							<?php
							$sdate = date_create($r['bktime_start']);
							$edate = date_create($r['bktime_end']);
							?>
							<td class="text-center"><?php echo $r['itp_name'] ?></td>
							<td class="text-center"><?php echo date_format($sdate, 'l d F Y H:i') ?></td>
							<td class="text-center"><?php echo date_format($edate, 'l d F Y H:i') ?></td>
							<td class="text-center"><a href="/booking/tgas/profile.php?emp_id=<?php echo $r['emp_id'] ?>"><?php echo $r['emp_fname'] . " " . $r['emp_lname'] ?></a></td>
							<td class="text-center"><?php if ($r['bk_mgr_ap'] == '1') {
																				echo "Approve";
																			} elseif ($r['bk_mgr_ap'] == '2') {
																				echo "Disapproves";
																			} ?></td>
							<td class="text-center"><?php if ($r['bk_hr_ap'] == '1') {
																				echo "Approve";
																			} elseif ($r['bk_hr_ap'] == '2') {
																				echo "Disapproves";
																			} ?></td>
							</tr>
						<?php } ?>
			</table>
		</form>
	</div>
</body>

</html>
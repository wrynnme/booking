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
	$qss = $con->query("SELECT * FROM car WHERE car_id = $_GET[car_id]");
	$ss = $qss->fetch_array(MYSQLI_ASSOC);

	$qft = $con->query("SELECT ft_name FROM factory WHERE ft_id = $ss[car_factory]");
	$qst = $con->query("SELECT st_name FROM status WHERE st_id = $ss[car_status]");
	$ft = $qft->fetch_array(MYSQLI_ASSOC);
	$st = $qst->fetch_array(MYSQLI_ASSOC);
	?>
	<div class="container">
		<br><br>
		<h2><?php echo $ss['license_plate']; ?></h2>
		<hr>
		<form action="">
			<div class="row">
				<div class="col-md-auto">
					<img src="/booking/images/uploads/car/<?php echo $ss['car_options']; ?>" alt="" class="" width="300px" height="auto">
				</div>
				<div class="col">
					<table>
						<th>
							<tr>
								<td class="text-right"><label for="license_plate" class="font-weight-bold h4">License Plate : </label></td>
								<td></td>
								<td><label name="license_plate" id="license_plate" class="h4"><?php echo $ss['license_plate'] ?></label></td>
							</tr>
							<tr>
								<td class="text-right"><label for="driver_name" class="font-weight-bold h4">Driver Name : </label></td>
								<td></td>
								<td><label name="driver_name" id="driver_name" class="h4"><?php echo $ss['driver_name'] ?></label></td>
							</tr>
							<tr>
								<td class="text-right"><label for="driver_tel" class="font-weight-bold h4">Driver Tel : </label></td>
								<td></td>
								<td><label name="driver_tel" id="driver_tel" class="h4"><?php echo $ss['driver_tel'] ?></label></td>
							</tr>
							<tr>
								<td class="text-right"><label for="car_brand" class="font-weight-bold h4">Car brand: </label></td>
								<td></td>
								<td><label name="car_brand" id="car_brand" class="h4"><?php echo $ss['car_brand'] ?></label></td>
							</tr>
							<tr>
								<td class="text-right"><label for="car_color" class="font-weight-bold h4">Color: </label></td>
								<td></td>
								<td><label name="car_color" id="car_color" class="h4"><input type="color" value="<?php echo $ss['car_color'] ?>" disabled></label></td>
							</tr>
							<tr>
								<td class="text-right"><label for="mtr_factory" class="font-weight-bold h4">Factory : </label></td>
								<td></td>
								<td><label name="mtr_factory" id="mtr_factory" class="h4"><?php echo $ft['ft_name'] ?></label></td>
							</tr>
							<tr>
								<td class="text-right"><label for="mtr_status" class="font-weight-bold h4">Status : </label></td>
								<td></td>
								<td><label name="mtr_status" id="mtr_status" class="h4"><?php echo $st['st_name'] ?></label></td>
							</tr>
						</th>
					</table>
				</div>
			</div>
		</form>
		<hr>
		<?php
		$sql = $con->query("SELECT bk_car.*,car.license_plate FROM bk_car,car WHERE bk_car.car_id = car.car_id AND car.car_id = '$_GET[car_id]'");
		$sqli = $sql->fetch_array(MYSQLI_ASSOC);
		?>
		<h2>Schedule car's <?php echo $sqli['license_plate']; ?></h2>
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
						<th class="text-center">Car</th>
						<th class="text-center">Time Start</th>
						<th class="text-center">Time End</th>
						<th class="text-center">Requester by</th>
						<th class="text-center">MGR AP</th>
						<th class="text-center">HR AP</th>
					</tr>
				</thead>
				<?php
				$sql = $con->query("SELECT * FROM bk_car,car,employee WHERE bk_car.car_id = car.car_id AND bk_car.bkemp_id = employee.emp_id AND car.car_id = '$_GET[car_id]' ORDER BY `bk_car`.`bktime_start`, `bk_car`.`bktime_end` DESC");

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
							<td class="text-center"><?php echo $r['license_plate'] ?></td>
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
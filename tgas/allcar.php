<?php
if (isset($_GET['select'])) {
	if ($_GET['select'] == 'year') {
		$tt = $con->query("SELECT * FROM bk_car,employee,car WHERE bk_car.bk_factory = 1 AND bk_car.car_id = car.car_id AND bk_car.bkemp_id = employee.emp_id AND YEAR(bktime_end) = '$nowyear' AND bk_car.bk_mgr_ap = 1 ORDER BY `bk_car`.`bktime_start`, `bk_car`.`bktime_end` DESC");
	} elseif ($_GET['select'] == 'month') {
		$tt = $con->query("SELECT * FROM bk_car,employee,car WHERE bk_car.bk_factory = 1 AND bk_car.car_id = car.car_id AND bk_car.bkemp_id = employee.emp_id AND YEAR(bktime_end) = '$nowyear' AND MONTH(bktime_end) = '$nowmonth' AND bk_car.bk_mgr_ap = 1 ORDER BY `bk_car`.`bktime_start`, `bk_car`.`bktime_end` DESC");
	} elseif ($_GET['select'] == 'day') {
		$tt = $con->query("SELECT * FROM bk_car,employee,car WHERE bk_car.bk_factory = 1 AND bk_car.car_id = car.car_id AND bk_car.bkemp_id = employee.emp_id AND YEAR(bktime_end) = '$nowyear' AND MONTH(bktime_end) = '$nowmonth' AND DAY(bktime_end) = '$nowday' AND bk_car.bk_mgr_ap = 1 ORDER BY `bk_car`.`bktime_start`, `bk_car`.`bktime_end` DESC");
	}
} else {
	$tt = $con->query("SELECT * FROM bk_car,employee,car WHERE bk_car.bk_factory = 1 AND bk_car.car_id = car.car_id AND bk_car.bkemp_id = employee.emp_id AND YEAR(bktime_end) = '$nowyear' AND MONTH(bktime_end) = '$nowmonth' AND bk_car.bk_mgr_ap = 1 ORDER BY `bk_car`.`bktime_start`, `bk_car`.`bktime_end` DESC");
}
?>
<form action="view" style="border:1px;">
	<div class="text-right">
		<div class="btn-group" role="group" aria-label="Basic example">
			<a href="?select=day" onClick="return true" class="btn btn-outline-info"> Day</a>
			<a href="?select=month" onClick="return true" class="btn btn-outline-warning"> Month</a>
			<a href="?select=year" onClick="return true" class="btn btn-outline-danger"> Year</a>
		</div>
	</div>
	<br>
	<div class="text-right">
		<span class="badge badge-pill badge-info">Manager approved and wait for HR approval</span>
		<span class="badge badge-pill badge-warning sr-only">HR approved but wait for Manager approve</span>
		<span class="badge badge-pill badge-success">Booking successfully by HR Approval</span>
		<span class="badge badge-pill badge-danger sr-only">Manager or HR disapproves</span>
	</div>
	<br>
	<table class="table table-hover">
		<thead>
			<tr>
				<th class="text-center">Name</th>
				<th class="text-center">Department</th>
				<th class="text-center">License Plate</th>
				<th class="text-center">Time start</th>
				<th class="text-center">Time end</th>
				<th class="text-center">MGR AP</th>
				<th class="text-center">HR AP</th>
			</tr>
		</thead>
		<?php
		while ($r = $tt->fetch_array(MYSQLI_ASSOC)) {
			$sdate = date_create($r['bktime_start']);
			$edate = date_create($r['bktime_end']);
			if ($r['bk_mgr_ap'] == '1') {
				if ($r['bk_mgr_ap'] == '1' && $r['bk_hr_ap'] == '1') { ?>
					<tr class="text-center bg-success text-white">
					<?php } elseif ($r['bk_mgr_ap'] == '1' && $r['bk_hr_ap'] == '2') { ?>
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
					<td class="text-center"><?php echo $r['bk_name'] ?></td>
					<td class="text-center"><?php echo $r['bk_dept'] ?></td>
					<td class="text-center"><a href="/booking/tgas/viewcar.php?car_id=<?php echo $r['car_id'] ?>"><?php echo $r['license_plate'] . " " . $r['car_brand'] ?></a></td>
					<td class="text-center"><?php echo date_format($sdate, 'l d F Y H:i') ?></td>
					<td class="text-center"><?php echo date_format($edate, 'l d F Y H:i') ?></td>
					<td class="text-center"><?php if ($r['bk_mgr_ap'] == '1') {
																		echo "Approved";
																	} elseif ($r['bk_mgr_ap'] == '2') {
																		echo "Disapproves";
																	} ?></td>
					<td class="text-center"><?php if ($r['bk_hr_ap'] == '1') {
																		echo "Approved";
																	} elseif ($r['bk_hr_ap'] == '2') {
																		echo "Disapproves";
																	} ?></td>
					</tr>
				<?php } ?>
	</table>
</form>
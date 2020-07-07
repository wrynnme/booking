<?php
	require_once('./../../../config.php');
	@session_start();
	// CHECK LOGIN
	if (!isset($_SESSION['emp_id'])) {
		hd("/booking/logout");
	}elseif (strtolower($_SESSION['emp_position']) != "hr approval" && strtolower($_SESSION['emp_position']) != "administrator" && $_SESSION['emp_factory'] != "1") {
		hd("/booking/");
	}
	include './../../header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	
</head>
<body>
	<div class="container">
	<br>
		<div class="card-deck">
			<div class="card">
				<a href="/booking/TGAS/HR/report/mtr/index.php">
					<img class="card-img-top" src="/booking/images/mtr.png" alt="Meeting room image">
				</a>
				<div class="card-body">
					<a href="/booking/TGAS/HR/report/mtr/index.php">
						<h4 class="card-title">Meeting room</h4>
						<p class="card-text">Report meeting room.</p>
						<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
					</a>
				</div>
			</div>
			<div class="card">
				<a href="/booking/TGAS/HR/report/car/index.php">
					<img class="card-img-top" src="/booking/images/car.png" alt="Car image">
				</a>
				<div class="card-body">
					<a href="/booking/TGAS/HR/report/car/index.php">
						<h4 class="card-title">Car</h4>
						<p class="card-text">Report car.</p>
						<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
					</a>
				</div>
			</div>
			<div class="card">
					<a href="/booking/TGAS/HR/report/itp/index.php">
						<img class="card-img-top" src="/booking/images/itp.png" alt="Interpreter image">
					</a>
				<div class="card-body">
					<a href="/booking/TGAS/HR/report/itp/index.php">
						<h4 class="card-title">Interpreter</h4>
						<p class="card-text">Report interpreter.</p>
						<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
					</a>	
				</div>
			</div>
		</div>
	</div>
</body>
</html>
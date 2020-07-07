<?php
@session_start();
// al(strtolower($_SERVER["REQUEST_URI"]));
if (strtolower($_SESSION['emp_position']) == "administrator") {
	$_SESSION['emp_factory'] = '1';
}
$pt = $_SESSION['emp_position'];
$ft = $_SESSION['emp_factory'];
if ($ft = '1') {
	$ft = "tgas";
} elseif ($ft = '2') {
	$ft = "tgt";
} elseif ($ft = '3') {
	$ft = "tgrt";
}

@$bkmtr_id = $_GET['bkmtr_id'];
@$mtr_id   = $_GET['mtr_id'];
@$bkcar_id = $_GET['bkcar_id'];
@$car_id   = $_GET['car_id'];
@$bkitp_id = $_GET['bkitp_id'];
@$itp_id   = $_GET['itp_id'];
if (strtolower($pt) == 'administrator') {
	if (strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/hr/' || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/hr/meetingroom/' || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/hr/car/' || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/hr/interpreter/' || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/meetingroom/viewbookingmtr.php?bkmtr_id=$bkmtr_id" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/car/viewbookingcar.php?bkcar_id=$bkcar_id" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/interpreter/viewbookinginterpreter.php?bkitp_id=$bkitp_id" || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/hr/car/addcar.php'  || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/hr/meetingroom/addmeetingroom.php' || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/hr/interpreter/addinterpreter.php' || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/interpreter/edit_interpreter.php?itp_id=$itp_id" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/car/edit_car.php?car_id=$car_id" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/meetingroom/edit_meetingroom.php?mtr_id=$mtr_id" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/meetingroom/?select=year" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/meetingroom/?select=month" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/meetingroom/?select=day" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/car/?select=year" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/car/?select=month" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/car/?select=day" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/interpreter/?select=year" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/interpreter/?select=month" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/hr/interpreter/?select=day") {
		// $_SESSION['emp_position'] = 'hr approval';
		$pt = 'hr approval';
		$old_session = 'Administrator';
	} elseif (strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/manager/' || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/manager/meetingroom/' || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/manager/car/' || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/manager/interpreter/' || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/manager/meetingroom/viewbookingmtr.php?bkmtr_id=$bkmtr_id" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/manager/car/viewbookingcar.php?bkcar_id=$bkcar_id" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/manager/interpreter/viewbookinginterpreter.php?bkitp_id=$bkitp_id" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/manager/meetingroom/?select=year" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/manager/meetingroom/?select=month" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/manager/meetingroom/?select=day" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/manager/car/?select=year" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/manager/car/?select=month" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/manager/car/?select=day" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/manager/interpreter/?select=year" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/manager/interpreter/?select=month" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/manager/interpreter/?select=day") {
		// $_SESSION['emp_position'] = 'manager';
		$pt = 'manager';
		$old_session = 'Administrator';
	} elseif (strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/user/' || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/user/meetingroom/' || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/user/car/' || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/user/interpreter/' || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/user/meetingroom/viewbookingmtr.php?bkmtr_id=$bkmtr_id" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/user/car/viewbookingcar.php?bkcar_id=$bkcar_id" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/user/interpreter/viewbookinginterpreter.php?bkitp_id=$bkitp_id" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/user/meetingroom/?select=year" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/user/meetingroom/?select=month" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/user/meetingroom/?select=day" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/user/car/?select=year" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/user/car/?select=month" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/user/car/?select=day" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/user/interpreter/?select=year" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/user/interpreter/?select=month" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/user/interpreter/?select=day") {
		// $_SESSION['emp_position'] = 'user';
		$pt = 'user';
		$old_session = 'Administrator';
	} elseif (strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas' || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/profile.php?emp_id=' . $_SESSION['emp_id'] || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/changepass.php?emp_id=' . $_SESSION['emp_id'] || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/bookingmtr.php' || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/bookingcar.php' || strtolower($_SERVER["REQUEST_URI"]) == '/booking/tgas/bookinginterpreter.php' || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/viewmtr.php?mtr_id=$mtr_id" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/viewcar.php?car_id=$car_id" || strtolower($_SERVER["REQUEST_URI"]) == "/booking/tgas/viewinterpreter.php?itp_id=$itp_id") {
		// $_SESSION['emp_position'] = 'hr approval';
		$pt = 'hr approval';
		$old_session = 'Administrator';
	}
}
if (strtolower($pt) == 'hr approval') {
	$pt = 'hr';
	$old_session = '';
}
?>
<nav class="navbar navbar-dark bg-dark navbar-expand-lg sticky-top">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleCenteredNav" aria-controls="navbarsExampleCenteredNav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse justify-content-lg-around" id="navbarsExampleCenteredNav">
		<ul class="navbar-nav">
			<a class="navbar-brand" href="/booking/" style="margin: 0px; padding: 0px;">
				<img src="/booking/images/toyoda-gosei-logoo.png" width="55" height="45" class="d-inline-block align-top" alt="">
			</a>
			<li class="nav-item active">
				<a class="nav-link" href="/booking/" onClick="fff();"><img src="/booking/images/tg33.png" width="" height="27" class="d-inline-block align-top" alt=""><!-- <font class="" style="font-size: 18px;"><b>TOYODA GOSEI</b></font> --><span class="sr-only">(current)</span>
					<?php if ($old_session == 'Administrator') {
						$_SESSION['emp_position'] = 'Administrator';
					} ?>
				</a>
			</li>
			<li class="nav-item active" style="border-left: 1.2px solid #9a9da0;">
				<a class="nav-link" href="/booking/<?php echo $ft ?>/<?php echo strtolower($pt) ?>/meetingroom">Meeting room</a>
			</li>
			<li class="nav-item active" style="border-left: 1.2px solid #9a9da0;">
				<a class="nav-link" href="/booking/<?php echo $ft ?>/<?php echo strtolower($pt) ?>/car">Company Car</a>
			</li>
			<li class="nav-item active" style="border-left: 1.2px solid #9a9da0;">
				<a class="nav-link" href="/booking/<?php echo $ft ?>/<?php echo strtolower($pt) ?>/interpreter">Interpreter</a>
			</li>
			<li class="nav-item" style="border-left: 1.2px solid #9a9da0;">
				<a class="nav-link" href="/booking/<?php echo $ft ?>/profile.php?emp_id=<?php echo $_SESSION['emp_id'] ?>">
					<font color="white">
						<?php echo $_SESSION['emp_fname'] . " " . $_SESSION['emp_lname']; ?>
					</font>
				</a>
			</li>
			<li class="nav-item">
				<font class="nav-link" color="">
					(<?php echo ($_SESSION['emp_position']); ?>)
				</font>
			</li>
			<?php if (strtolower($_SESSION['emp_position']) == "hr approval") { ?>
				<li class="nav-item active">
					<a class="nav-link" href="/booking/<?php echo $ft ?>/hr/report/">Report</a>
				</li>
			<?php } ?>
			<li class="nav-item" style="border-left: 1.2px solid #9a9da0;">
				<a class="nav-link" href="/booking/logout/" onClick="return confirm('Do you want to logout.?')">
					<font color="red">Logout</font>
				</a>
			</li>
		</ul>
	</div>
</nav>
<link rel="stylesheet" href="/booking/dist/css/bootstrap.css">
<style media="screen">
	a {
		color: #000;
		text-decoration: none;
	}

	a:hover {
		color: #626262;
		text-decoration: none;
	}

	.dropdown-item:focus,
	.dropdown-item:hover {
		background-color: rgba(0, 0, 0, 0.08);
	}

	nav {
		box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
	}

	body {
		background-color: #f7f7f9;
	}

	.nav-link:hover {
		/*background: #545454;*/
		border-radius: 5px;
	}
</style>
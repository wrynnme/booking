<?php
@session_start();
$host = "localhost";
$user = "root";
$pass = '';
$db = "booking";
@$ft = $_SESSION['emp_factory'];

switch ($ft) {
	case '1':
		$ft = "tgas";
		break;
	case '2':
		$ft = "tgt";
		break;
	case '3':
		$ft = "tgrt";
		break;
	default:
		break;
}
@$st = $_SESSION['emp_status'];
@$pt = $_SESSION['emp_position'];
@$dt = $_SESSION['emp_dept'];
if (!isset($_SESSION['emp_id'])) {
	if ($_SERVER["REQUEST_URI"] != '/booking/login/') {
		hd("/booking/login");
	}
}

$con  = new mysqli($host, $user, $pass, $db);
$con->set_charset("utf8");
if ($con->errno) {
	echo $con->errno;
	exit;
}
header('Content-Type: text/html; charset=UTF-8');

date_default_timezone_set("Asia/Bangkok");
$now = date('Y-m-d');
$nowday = date('d');
$nowmonth = date('m');
$nowyear = date('Y');
function date_th($ddd)
{
	$day_th = date('d', strtotime($ddd));
	$month = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
	$month_th = $month[date('n', strtotime($ddd))];
	$year_th = date('Y', strtotime($ddd)) + 543;
	$date_th = $day_th . " " . $month_th . " " . $year_th;
	return $date_th;
}
function month_th($mm)
{
	$month = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
	$month_th = $month[$mm];
	return $month_th;
}
function month_en($mm)
{
	$month = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	$month_en = $month[$mm];
	return $month_en;
}
function al_g($name)
{
?>
	<style>
		.alert_g {
			width: 70%;
			margin: auto;
			padding: 14px;
			margin-bottom: 5px;
			border-radius: 4px 4px 4px 4px;
			margin-bottom: 20px;
			color: #4F3;
			background: white;
			font-size: 18px;
			border: solid 2px #4F3;
		}
	</style>
	<br />
	<div class="alert_g" align="center">
		<?php echo $name ?>
	</div>
<?php
}
function al_r($name)
{
?>
	<style>
		.alert_r {
			width: 70%;
			margin: auto;
			padding: 14px;
			margin-bottom: 5px;
			border-radius: 4px 4px 4px 4px;
			margin-bottom: 20px;
			color: #F33;
			background: white;
			font-size: 18px;
			border: solid 2px #F33;
		}
	</style>
	<br />
	<div class="alert_r" align="center">
		<?php echo $name ?>
	</div>
<?php
}
function sc($name)
{
	echo "<script>alert('$name');window.history.back();</script>";
}
function alr($name)
{
	echo "<script>alert('$name');</script>";
}
function ao($name, $link)
{
	echo "<script>alert('$name');window.location.href='$link';</script>";
}
function hd($name)
{
	header("location:$name");
}
?>

<link rel="icon" href="/booking/favicon.ico" type="image/x-icon">
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
<title>BOOKING | TOYODA GOSEI</title>
<link rel="stylesheet" href="/booking/dist/css/bootstrap.css">

<script src="/booking/dist/js/bootstrap.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>


<style>
	body {
		background-color: #f7f7f9;
	}
</style>
<script>
	// Reusable Function to Enforce MaxLength
	function enforce_maxlength(event) {
		var t = event.target;
		if (t.hasAttribute('maxlength')) {
			t.value = t.value.slice(0, t.getAttribute('maxlength'));
		}
	}

	// Global Listener for anything with an maxlength attribute.
	// I put the listener on the body, put it on whatever.
	document.body.addEventListener('input', enforce_maxlength);
</script>
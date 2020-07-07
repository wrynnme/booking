<?php
require_once("./../config.php");
@SESSION_START();
// CHECK LOGIN
if (!isset($_SESSION['emp_id'])) {
	hd("/booking/");
}
include('header.php');
?>
<?php
$qr = $con->query("SELECT * FROM `employee` WHERE `emp_id` = '$_GET[emp_id]'");
$r = $qr->fetch_array(MYSQLI_ASSOC);
if (isset($_POST['done'])) {
	@$pass = $_SESSION['emp_pass'];
	@$curpass = $_POST['curpass'];
	@$newpass = $_POST['newpass'];
	@$renewpass = $_POST['renewpass'];
	if ($curpass != strtolower($_SESSION['emp_pass'])) {
		sc('Your current password incorrect. !');
	} elseif ($curpass == strtolower($_SESSION['emp_pass'])) {
		if ($newpass != $renewpass) {
			sc('Re-new password not match. !');
		} else {
			// $con->query("UPDATE `employee` SET `emp_pass` = '$newpass' WHERE `emp_id` = '$_GET[emp_id]'");
			// ao("Change Password successfully. !", '/booingk/logout');
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../css/manager.css">
	<link rel="stylesheet" href="/booking/dist/css/bootstrap.css">
</head>

<body>
	<div class="container">
		<br><br>
		<h2>Change Password <font color="red"><?= $_SESSION['emp_fname'] . " " . $_SESSION['emp_lname'] ?></font>
		</h2>
		<br>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
			<div class="form-group">
				<label for="curpass"><b>Current Password</b></label>
				<input type="password" class="form-control" id="curpass" name="curpass" placeholder="Current Password">
			</div>
			<div class="form-group">
				<label for="newpass"><b>New Password</b></label>
				<input type="password" class="form-control" id="newpass" name="curpnewpassass" placeholder="New Password">
			</div>
			<div class="form-group">
				<label for="renewpass"><b>Re-new Password</b></label>
				<input type="password" class="form-control" id="renewpass" name="renewpass" placeholder="Re-new Password">
			</div>
			<button type="submit" name="done" class="btn btn-block btn-lg btn-primary">Done!</button>
		</form>
	</div>
</body>

</html>
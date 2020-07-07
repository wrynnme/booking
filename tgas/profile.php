<?php
require_once("./../config.php");
@SESSION_START();
include('header.php');
?>
<?php
$qr = $con->query("SELECT * FROM `employee` WHERE `emp_id` = '$_GET[emp_id]'");
// $qs = $con->query("SELECT * FROM `employee`,`bk_car`,`bk_mtr`,`bk_interpreter` WHERE employee.emp_id = bkemp_id");
$r = $qr->fetch_array(MYSQLI_ASSOC);
// $s = $qs->fetch_array(MYSQLI_ASSOC)
?>
<html>

<head>
	<meta charset="UTF-8">
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../css/manager.css">
	<link rel="stylesheet" href="/booking/dist/css/bootstrap.css">
	<style media="screen">
		body {
			background-color: #f7f7f9;
		}
	</style>
</head>

<body>
	<div class="container">
		<br>
		<div class="starter-template text-center">
			<h1>Profile <?php echo $r['emp_fname'] . " " . $r['emp_lname']; ?></h1>
			<p class="lead"><br></p>
		</div>
		<h5>
			<form>
				<?php
				// $sql=$con->query("select * from employee where emp_id = ".$_SESSION['emp_id']." ");
				// while($r=mysql_fetch_array($sql)){
				?>
				<div class="form-group">
					<b><label for="firstname">First name : </label></b>
					<?php echo $r["emp_fname"]; ?>
				</div>
				<div class="form-group">
					<b><label for="lastname">Last name : </label></b>
					<?php echo $r["emp_lname"]; ?>
				</div>
				<div class="form-group">
					<b><label for="emp_tel">Telephone Number : </label></b>
					<?php echo $r["emp_tel"]; ?>
				</div>
				<div class="form-group">
					<b><label for="emp_tel">Email address : </label></b>
					<?php echo $r["emp_email"]; ?>
				</div>
				<div class="form-group">
					<b><label for="emp_position">Position : </label></b>
					<?php echo $r["emp_position"]; ?>
				</div>
				<div class="form-group">
					<b><label for='emp_factory'>Factory : </label></b>
					<?php
					if ($r['emp_factory'] == '1') {
						echo "TGAS";
					} elseif ($r['emp_factory'] == '2') {
						echo "TGT";
					} elseif ($r['emp_factory'] == '3') {
						echo "TGRT";
					}
					?>
				</div>
				<div class="form-group">
					<b><label for="emp_tel">Department : </label></b>
					<?php echo $r["emp_dept"]; ?>
				</div>
				<div class="form-group">
					<b><label for="emp_user">Username : </label></b>
					<?php echo $r["emp_user"]; ?>
				</div>
				<div class="form-group">
					<b><label for="emp_pass">Passwords : </label></b>
					********
					<?php
					if ($r['emp_id'] == $_SESSION['emp_id']) { ?>
						<a href="changepass.php?emp_id=<?= $r['emp_id'] ?>">
							<font color="red"><b>Change Password</b></font>
						</a>
					<?php	} ?>
				</div>
				<div class="form-group">
					<b><label for="emp_status">Status : </label></b>
					<?php
					if ($r["emp_status"] == '1') {
						echo "Enable";
					} elseif ($r["emp_status"] == '2') {
						echo "Disable";
					}
					?>
				</div>
				<?php // } 
				?>
			</form>
		</h5>
	</div>
</body>

</html>
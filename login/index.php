<?php
require_once('../config.php');
if (isset($_SESSION['emp_id'])) {
	hd("/booking/index.php");
}
?>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Bootstrap core CSS -->
	<link href="./css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="./css/signin.css" rel="stylesheet">
	<style media="screen">
		input {
			margin: 10px 0px 10px 0px;
		}
	</style>
</head>

<body>
	<?php
	$hour = 0;
	$min  = 0;
	$logtime = date("U", mktime(date("H") + $hour, date("i") + $min));
	if (isset($_POST['done'])) {
		$user = $_POST['user'];
		$pass = $_POST['pass'];
		if ($user == "" || $pass == "") {
			echo "<script>alert('Please fill out the form. !!');window.history.back();</script>";
		} else {
			$qe1 = $con->query("SELECT * FROM `employee` WHERE `emp_user` = '$user' AND `emp_pass` = '$pass'");
			$ck1 = $qe1->num_rows;
			if ($ck1 != 1) {
				echo "<script>alert('Username or Password in corrcet');window.history.back();</script>";
			} else {
				$qe2 = $con->query("SELECT * FROM `employee` WHERE `emp_user` = '$user' AND `emp_pass` = '$pass' AND `emp_status` = '1'");
				$ck2 = $qe2->num_rows;
				if ($ck2 != 1) {
					echo "<script>alert('Account is disabled. Please contact admin Infomation System (IS.));window.history.back();</script>";
				} else {
					$qe3 = $con->query("SELECT * FROM `employee` WHERE `emp_user` = '$user' AND `emp_pass` = '$pass'");
					$ck3 = $qe3->fetch_array(MYSQLI_ASSOC);
					$_SESSION['emp_id']           = $ck3['emp_id'];
					$_SESSION['emp_fname']        = $ck3['emp_fname'];
					$_SESSION['emp_lname']        = $ck3['emp_lname'];
					$_SESSION['emp_tel']          = $ck3['emp_tel'];
					$_SESSION['emp_email']        = $ck3['emp_email'];
					$_SESSION['emp_position']     = $ck3['emp_position'];
					$_SESSION['emp_factory']      = $ck3['emp_factory'];
					$_SESSION['emp_dept']         = $ck3['emp_dept'];
					$_SESSION['emp_user']         = $ck3['emp_user'];
					$_SESSION['emp_pass']         = $ck3['emp_pass'];
					$_SESSION['emp_status']       = $ck3['emp_status'];
					$_SESSION['emp_descriptions'] = $ck3['emp_descriptions'];
					sc('Please Logout after use');
					hd("/booking/");
				}
			}
		}
	}
	?>

	<div class="container text-center">

		<img src="/booking/images/logo.png" alt="" width="15%">
		<form class="form-signin" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
			<font color="red"><b></b></font>
			<font class="form-signin-heading text-center" style="font-size:1.4em;">
				<font color="red"><b>B</b></font>ooking <font color="red"><b>M</b></font>anagement <font color="red"><b>S</b></font>ystem
			</font>
			<label for="inputUser" class="sr-only">Username</label>
			<input type="text" id="inputUser" class="form-control" name="user" placeholder="Username" required="" autofocus="">
			<label for="inputPassword" class="sr-only">Password</label>
			<input type="password" id="inputPassword" class="form-control" name="pass" placeholder="Password" required="">
			<button class="btn btn-lg btn-primary btn-block" type="submit" name="done" id="done">Sign in</button>
		</form>
		<hr>
		<div class="card">
			<div class="card-body text-center">
				<h3>Account for test</h3>
				<hr>
				<table class="table" style="border:0;">
					<thead>
						<tr class="text-center">
							<th>Username</th>
							<th>Password</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>admin</td>
							<td>admin</td>
						</tr>
						<tr>
							<td>tester</td>
							<td>00</td>
						</tr>
						<tr>
							<td>tester</td>
							<td>01</td>
						</tr>
						<tr>
							<td>tester</td>
							<td>02</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div> <!-- /container -->


	<!-- Bootstrap core JavaScript
		================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>


</body>

</html>
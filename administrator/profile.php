<?php require_once("./../config.php"); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="../css/manager.css">
		<?php include ('header.php'); ?>
	</head>

	<body>
		<div class="container">
			<br>
			<div class="starter-template text-center">
        <h1>Profile <?php echo $_SESSION['emp_fname']." ".$_SESSION['emp_lname']; ?></h1>
        <p class="lead"><br></p>
      </div>

			<form>
				<?php
				$sql=mysql_query("select * from employee where emp_id = ".$_SESSION['emp_id']." ");
				while($row=mysql_fetch_array($sql)){
				?>
			  <div class="form-group">
			    <b><label for="firstname">First name : </label></b>
					<?php echo $row["emp_fname"]; ?>
				</div>
			  <div class="form-group">
			    <b><label for="lastname">Last name : </label></b>
					<?php echo $row["emp_lname"]; ?>
				</div>
				<div class="form-group">
					<b><label for="emp_tel">Telephone Number : </label></b>
					<?php echo $row["emp_tel"]; ?>
				</div>
				<div class="form-group">
					<b><label for="emp_position">Position : </label></b>
					<?php echo $row["emp_position"]; ?>
				</div>
				<div class="form-group">
					<b><label for="emp_factory">Factory : </label></b>
					<?php
						if ($row["emp_factory"] == '1') {
							echo "TGAS";
						}elseif ($row["emp_factory"] == '2') {
							echo "TGT";
						}elseif ($row["emp_factory"] == '3') {
							echo "TGRT";
						}
					?>
				</div>
				<div class="form-group">
					<b><label for="emp_user">Username : </label></b>
					<?php echo $row["emp_user"]; ?>
				</div>
				<div class="form-group">
					<b><label for="emp_pass">Passwords : </label></b>
					******** <a href="changepass.php"><t><font color="#FF0000">Change Passwords</font></t></a>
				</div>
				<div class="form-group">
					<b><label for="emp_status">Status : </label></b>
					<?php
						if ($row["emp_status"] == '1') {
							echo "Enable";
						}elseif ($row["emp_status"] == '2') {
							echo "Disable";
						}
					?>
				</div>
				<?php } ?>
			</form>
		</div>
	</body>
</html>

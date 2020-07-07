<?php
	require_once('./../../config.php');
	@session_start();
	// CHECK LOGIN
	if (strtolower($_SESSION['emp_position']) == "administrator") {
		$_SESSION['emp_factory'] = '1';
	}
	if (!isset($_SESSION['emp_id'])) {
		hd("/booking/logout");
	}elseif (strtolower($_SESSION['emp_position']) != "manager" && strtolower($_SESSION['emp_position']) != "administrator" || $_SESSION['emp_factory'] != "1") {
		hd("/booking/");
	}
	include './../header.php';
?>
<?php include './../main.php'; ?>
<!-- }elseif (strtolower($_SESSION['emp_position']) != "hr approval" && $_SESSION['emp_factory'] != "1" || strtolower($_SESSION['emp_position']) != "administrator" && $_SESSION['emp_factory'] != "1") {
		hd("/booking/");
	} -->

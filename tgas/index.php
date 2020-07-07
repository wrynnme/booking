<?php
	require_once('./../config.php');
	@session_start();
	if (strtolower($_SESSION['emp_position']) == "administrator") {
		$_SESSION['emp_factory'] = '1';
	}
	$ft = $_SESSION['emp_factory'];
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
	$pt = strtolower($_SESSION['emp_position']);
	// CHECK LOGIN
	if (!isset($_SESSION['emp_id'])) {
		hd("/booking/logout");
	}elseif ($pt == 'hr approval'){
		hd("/booking/$ft/hr");
	}elseif ($pt == 'administrator') {
		hd("/booking/$pt/");
	}else{
		hd("/booking/$ft/$pt");
	}
?>
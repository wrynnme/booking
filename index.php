<?php
	$txt = "You don't have permission to use it.";
	require_once('config.php');
	if(!isset($_SESSION['emp_id'])){
		hd("login");
	}else{
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
		if (!isset($_SESSION['emp_id'])) {
			hd("/booking/logout");
		}elseif ($pt == 'hr approval'){
			hd("/booking/$ft/hr");
		}elseif ($pt == 'administrator' || $pt == 'admin') {
			hd("/booking/$pt");
		}else{
			hd("/booking/$ft/$pt");
		}
	}
?>

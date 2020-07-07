<?php
header("Content-type:text/html; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
date_default_timezone_set("Asia/Bangkok");
?>
<?php /*if(isset($_GET['smonth']) && $_GET['smonth']!=""){
	$m  = $_GET['smonth']; ?>
  <option value="">&lt;-- Please Select Day --&gt;</option>
  <?php for($i=1;$i<=$m;$i++){ ?>
  <option value="<?=$i?>"><?=$i;?> <?=$_GET['smonth'];?></option>
  <?php } ?>
<?php }else{ ?>
    <option value="">&lt;-- Please Select Month --&gt;</option>
<?php } */?>

<?php 
	$leapyear = date("L");
	$sm = $_GET['smonth'];
	$ii = 01;
	if ($sm == "01" || $sm == "03" || $sm == "05" || $sm == "07" || $sm == "08" || $sm == "10" || $sm == "12") {
		$rw = 31;
	}elseif ($sm == "04" || $sm == "06" || $sm == "09" || $sm == "11") {
		$rw = 30;
	}elseif ($sm == "02") {
		$rw = 28;
	}elseif ($sm == "02" && $leapyear == "1") {
		$rw = 29;
	}
	if ($sm == date('m')) {
		$ii = date('d');
	}
 ?>
 <option value="">Select Date</option>
<?php 
	for ($i = $ii; $i <= $rw ; $i++) { 
 ?>

 <option value="<?=$i?>"><?=$i;?></option>
<?php } ?>
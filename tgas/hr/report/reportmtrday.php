<?php
	require_once('./../../../config.php');
	@session_start();
	// CHECK LOGIN
	if (!isset($_SESSION['emp_id'])) {
		hd("/booking/logout");
	}elseif (strtolower($_SESSION['emp_position']) != "hr approval" && strtolower($_SESSION['emp_position']) != "administrator" && $_SESSION['emp_factory'] != "1") {
		hd("/booking/");
	}
?>
<?php 
	$sql = mysql_query("SELECT * FROM bk_mtr WHERE")
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="" class="rnow" method="get">
    	<label>Month</label>
        <select class="tb" name="month">
        	<option value="<?php echo $_GET['month']; ?>"><?php echo month_en($_GET['month']); ?></option>
        <?php
			for($i=1;$i<=12;$i++){
				echo "<option value='$i'>".month_en($i)."</option>";
			}
		?>
        </select>
        <label>ปี</label>
        <select class="tb" name="year">
        	<option value="<?php echo $_GET['year']; ?>"><?php echo $_GET['year']+"543"; ?></option>
        <?php
			$select_year = mysql_query("SELECT year(bktime_start) as year from bk_mtr group by year(bktime_start) order by year desc");
			while($result_year = mysql_fetch_array($select_year)){
				echo "<option value='$result_year[year]'>"; echo $result_year['year']+543; echo"</option>";
			}
		?>
        </select>
        <input type="submit" class="btny" value="ค้นหา" />
    </form>
</body>
</html>
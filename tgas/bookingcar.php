<?php
require_once('./../config.php');
@session_start();
// CHECK LOGIN
if (!isset($_SESSION['emp_id'])) {
	hd("/booking/logout");
} elseif (strtolower($_SESSION['emp_position']) != "user" && strtolower($_SESSION['emp_position']) != "manager" && strtolower($_SESSION['emp_position']) != "administrator" || $_SESSION['emp_factory'] != "1") {
	hd("/booking/");
}
include './header.php';
?>
<?php
$m = date('m');
$eng_month_arr = array(
	"0" => "", "01" => "January", "02" => "February", "03" => "March", "04" => "April", "05" => "May", "06" => "June", "07" => "July", "08" => "August", "09" => "September", "10" => "October", "11" => "November", "12" => "December", "1" => "January", "2" => "February", "3" => "March", "4" => "April", "5" => "May", "6" => "June", "7" => "July", "8" => "August", "9" => "September"
);
$thai_month_arr = array("0" => "", "01" => "มกราคม", "02" => "กุมภาพันธ์", "03" => "มีนาคม", "04" => "เมษายน", "05" => "พฤษภาคม", "06" => "มิถุนายน", "07" => "กรกฎาคม", "08" => "สิงหาคม", "09" => "กันยายน", "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม");
function DateDiff($ssdate, $eedate)
{
	return (strtotime($ssdate) - strtotime($eedate));
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title></title>
	<style media="screen">
		#form1 {
			width: 100%;
			border-width: 0px;
			border-style: double dashed;
		}
	</style>
</head>

<body>
	<?php
	if (isset($_POST['done'])) {
		$bkemp_id      = $_SESSION['emp_id'];
		$leapyear      = date("L");
		$year          = date("Y");
		$smonth        = $_POST['smonth'];
		$sday          = $_POST['sday'];
		$stime         = $_POST['stime'];
		$emonth        = $_POST['emonth'];
		$eday          = $_POST['eday'];
		$etime         = $_POST['etime'];
		$bk_name       = $_POST['bk_name'];
		$bk_idcode     = $_POST['bk_idcode'];
		$bk_tel        = $_POST['bk_tel'];
		$bk_email      = $_POST['bk_email'] . "@tgas.co.th";
		$bk_position   = $_POST['bk_position'];
		$bk_dept       = $_POST['bk_dept'];
		$sdate         = "$year-$smonth-$sday $stime";
		$edate         = "$year-$smonth-$sday $etime";
		$bk_destination = $_POST['bk_destination'];
		$bk_purpose    = $_POST['bk_purpose'];
		if ($bk_name == "" || $sdate == "" || $edate == "" || $bk_email == "" || $bk_tel == "" || $bk_position == "" || $bk_dept == "") {
			sc("Please fill out the form. !!");
		} elseif (DateDiff($sdate, $edate) >= 0) {
			sc("Time Error" . "  " . "Start : " . $stime . " " . "End : " . $etime);
		} else {
			$sql = $con->query("INSERT INTO `bk_car` VALUES (NULL, '$bk_name', '$bk_idcode', '$bk_tel', '$bk_email', '$bk_position', '$bk_dept', '$sdate', '$edate', '$bk_destination', '$bk_purpose', NULL, '1', '$bkemp_id', NULL, NULL);");
			if ($sql) {
				$qe = $con->query("SELECT * FROM `employee` WHERE emp_id = $_SESSION[emp_id]");
				$qrr = $con->query("SELECT meeting_room.mtr_number FROM `meeting_room` WHERE mtr_id = $ipmtr_id");
				$qemgr = $con->query("SELECT * FROM `employee` WHERE (SELECT employee.emp_dept FROM `employee` WHERE emp_id = $_SESSION[emp_id]) = emp_dept AND emp_position = 'manager'");
				$qlink = $con->query("SELECT * FROM bk_car WHERE bk_name = '$bk_name' AND bk_idcode = '$bk_idcode' AND bk_tel = '$bk_tel' AND bk_email = '$bk_email' AND bk_position = '$bk_position' AND bk_dept = '$bk_dept' AND bktime_start = '$sdate' AND bktime_end = '$edate' AND bk_destination = '$bk_destination' AND bk_purpose = '$bk_purpose' AND bkemp_id = '$_SESSION[emp_id]'");
				$e = $qe->fetch_array(MYSQLI_ASSOC);
				$rr = $qrr->fetch_array(MYSQLI_ASSOC);
				$emgr = $qemgr->fetch_array(MYSQLI_ASSOC);
				$link = $qlink->fetch_array(MYSQLI_ASSOC);
				$lii = $link['bkcar_id'];

				// ini_set("SMTP","localhost");
				// ini_set("sendmail_from","$bk_email");

				$strTo       = "$emgr[emp_fname] $emgr[emp_lname] <$emgr[emp_email]>";
				$strSubject  = "=?UTF-8?B?" . base64_encode("The requester has booking car.") . "?=";
				$Subject     = "The requester has booking car.";
				$strHeader  .= "MIME-Version: 1.0' . \r\n";
				$strHeader  .= "Content-type: text/html; charset=utf-8\r\n";
				$strHeader  .= "From: Booking Management System <$bk_email>\rReply-To:<$bk_email>\r\n";
				$strHeader  .= "Cc: User Requester<$bk_email>\r\n";

				$headmessages = "Requester name : <b>$bk_name</b> <br> ID Code : <b>$bk_idcode</b> <br> Telephone number : <b>$bk_tel</b> <br> Email : <b>$bk_email</b> <br> Position : <b>$bk_position</b> <br> Department : <b>$bk_dept</b>  <br> Time start : <b>$sdate</b> <br> Time end : <b>$edate</b> <br> Desination : <b>$bk_destination</b> <br> Purpose : <b>$bk_purpose</b> <br><br> <a target='_blank' href='http://192.168.222.223/booking/tgas/manager/car/viewbookingcar.php?bkcar_id=$lii'>Click here to approval page</a> <br>";

				include './mail.php';

				$flgSend = mail($strTo, $strSubject, $strMessage, $strHeader);
				if ($flgSend) {
					al_g("Email Sending.");
				} else {
					al_r("Cannot send Mail!");
				}
				ao("Booking successfully !", "/booking/tgas/$pt/car/");
			}
		}
	}
	?>
	<div class="container" style="margin-top: 30px;">
		<h3 class="text-center"><b>Form <font color="red">B</font>ooking Car</b></h3>
		<br>
		<div class="container" id="form1">
			<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" id="">
				<div class="row">
					<div class="col-md">
						<label for="bk_name" class="col col-form-label text-right"><b>Name : </b></label>
					</div>
					<div class="col-md-3">
						<input type="text" class="col form-control form-check" name="bk_name" placeholder="Name" maxlength="50">
					</div>
					<div class="col-md-2">
						<label for="bk_idcode" class="col col-form-label text-right"><b>ID Code : </b></label>
					</div>
					<div class="col-md-4">
						<input type="text" class="col form-control form-check" name="bk_idcode" placeholder="ID Code" maxlength="50">
					</div>
				</div>
				<div class="row">
					<div class="col-md">
						<label for="bk_tel" class="col col-form-label text-right"><b>Telephone number : </b></label>
					</div>
					<div class="col-md-3">
						<input type="number" class="col form-control form-check" name="bk_tel" placeholder="Telephone number" maxlength="10">
					</div>
					<div class="col-md-2">
						<label for="bk_email" class="col col-form-label text-right"><b>Email : </b></label>
					</div>
					<div class="col-md-4 input-group">
						<input type="text" class="col form-control form-check" name="bk_email" placeholder="Email" maxlength="50" required>
						<div class="input-group-addon">@tgas.co.th</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md">
						<label for="bk_position" class="col col-form-label text-right"><b>Position : </b></label>
					</div>
					<div class="col-md-3">
						<select class="col form-control form-check" id="bk_position" name="bk_position">
							<option value="">Select Position</option>
							<option value="Staff">Staff</option>
							<option value="GL">GL</option>
							<option value="Mgr">MGR</option>
							<option value="GM">GM</option>
						</select>
						<!-- <input type="text" class="col form-control form-check" name="bk_position" placeholder="" maxlength=""> -->
					</div>
					<div class="col-md-2">
						<label for="bk_dept" class="col col-form-label text-right"><b>Department : </b></label>
					</div>
					<div class="col-md-4">
						<select name="bk_dept" id="bk_dept" class="col form-control form-check">
							<option value="">Choose Department</option>
							<option value="VIP">VIP</option>
							<option value="HR">HR</option>
							<option value="CA">CA</option>
							<option value="CP">CP</option>
							<option value="MK1">MK1</option>
							<option value="MK2">MK2</option>
							<option value="TC">TC</option>
							<option value="AC">AC</option>
							<option value="IS">IS</option>
						</select>
						<!-- <input type="text" class="col form-control form-check" name="bk_dept" placeholder="" maxlength=""> -->
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="bk_position" class="col col-form-label text-right"><b>Date Start : </b></label>
					</div>
					<div class="col-md-4">
						<select class="col form-control form-check" id="smonth" name="smonth">
							<option value="">Select Month</option>
							<?php for ($i = $m; $i <= 12; $i++) { ?>
								<option value="<?= $i ?>"><?= $eng_month_arr["$i"]; ?></option>
							<?php } ?>
						</select>
						<div class="row" style="margin-left: 0px; margin-right: 0px;">
							<select class="col form-control form-check" id="sday" name="sday" style="display: none;">
								<option value="">Select Day</option>
							</select>
							<select class="col form-control form-check" id="stime" name="stime" style="display: none;">
								<option value="">Select Time</option>
								<option value="08:00:00">08:00</option>
								<option value="08:30:00">08:30</option>
								<option value="09:00:00">09:00</option>
								<option value="09:30:00">09:30</option>
								<option value="10:00:00">10:00</option>
								<option value="10:30:00">10:30</option>
								<option value="11:00:00">11:00</option>
								<option value="10:30:00">11:30</option>
								<option value="12:00:00">12:00</option>
								<option value="12:30:00">12:30</option>
								<option value="13:00:00">13:00</option>
								<option value="13:30:00">13:30</option>
								<option value="14:00:00">14:00</option>
								<option value="14:30:00">14:30</option>
								<option value="15:00:00">15:00</option>
								<option value="15:30:00">15:30</option>
								<option value="16:00:00">16:00</option>
								<option value="16:30:00">16:30</option>
								<option value="17:00:00">17:00</option>
								<option value="17:30:00">17:30</option>
								<option value="18:00:00">18:00</option>
								<option value="18:30:00">18:30</option>
								<option value="19:00:00">19:00</option>
								<option value="19:30:00">19:30</option>
								<option value="20:00:00">20:00</option>
								<option value="20:30:00">20:30</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<label for="bk_department" class="col col-form-label text-right"><b>Date End : </b></label>
					</div>
					<div class="col-md-4">
						<select class="col form-control form-check" id="emonth" name="emonth" disabled>
							<option value="">Select Month</option>
							<?php for ($i = $m; $i <= 12; $i++) { ?>
								<option value="<?= $i ?>"><?= $eng_month_arr["$i"]; ?></option>
							<?php } ?>
						</select>
						<div class="row" style="margin-left: 0px; margin-right: 0px;">
							<select class="col form-control form-check" id="eday" name="eday" style="display: none;" disabled>
								<option value="">Select Day</option>
							</select>
							<select class="col form-control form-check" id="etime" name="etime" style="display: none;">
								<option value="">Select Time</option>
								<option value="08:30:00">08:30</option>
								<option value="09:00:00">09:00</option>
								<option value="09:30:00">09:30</option>
								<option value="10:00:00">10:00</option>
								<option value="10:30:00">10:30</option>
								<option value="11:00:00">11:00</option>
								<option value="10:30:00">11:30</option>
								<option value="12:00:00">12:00</option>
								<option value="12:30:00">12:30</option>
								<option value="13:00:00">13:00</option>
								<option value="13:30:00">13:30</option>
								<option value="14:00:00">14:00</option>
								<option value="14:30:00">14:30</option>
								<option value="15:00:00">15:00</option>
								<option value="15:30:00">15:30</option>
								<option value="16:00:00">16:00</option>
								<option value="16:30:00">16:30</option>
								<option value="17:00:00">17:00</option>
								<option value="17:30:00">17:30</option>
								<option value="18:00:00">18:00</option>
								<option value="18:30:00">18:30</option>
								<option value="19:00:00">19:00</option>
								<option value="19:30:00">19:30</option>
								<option value="20:00:00">20:00</option>
								<option value="20:30:00">20:30</option>
								<option value="21:00:00">21:00</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md">
						<label for="bk_destination" class="col col-form-label text-right"><b>Destination or Contact Name : </b></label>
					</div>
					<div class="col-md-10">
						<textarea name="bk_destination" class="col form-control form-check" id="bk_destination" cols="100" rows="3" maxlength="200"></textarea>
					</div>
				</div>
				<div class="row">
					<div class="col-md">
						<label for="bk_purpose" class="col col-form-label text-right"><b>Purpose reque : </b></label>
					</div>
					<div class="col-md-10">
						<textarea name="bk_purpose" class="col form-control form-check" id="bk_purpose" cols="100" rows="3" maxlength="200"></textarea>
					</div>
				</div>
				<hr>
				<button type="submit" name="done" class="btn btn-sm btn-primary">Submit</button>
				<button type="reset" name="reset" class="btn btn-sm btn-danger">Reset</button>
			</form>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript">
		$(function() {
			// เมื่อเปลี่ยนค่าของ select id เท่ากับ smonth
			$("select#smonth").change(function() {
				var val = $("select#smonth").val();
				$("select#emonth").val(val);
				// ส่งค่า ตัวแปร list1 มีค่าเท่ากับค่าที่เลือก ส่งแบบ get ไปที่ไฟล์ smonth.php
				$.get("smonth.php", {
					smonth: $(this).val()
				}, function(data) { // คืนค่ากลับมา
					document.getElementById('sday').style.display = '';
					document.getElementById('eday').style.display = '';
					$("select#sday").html(data);
					$("select#eday").html(data); // นำค่าที่ได้ไปใส่ใน select id เท่ากับ sday
				});
				if (document.getElementById('sday') != '') {
					document.getElementById('stime').style.display = '';
					document.getElementById('etime').style.display = '';
				}
			});
			$("select#sday").change(function() {
				// ส่งค่า ตัวแปร list1 มีค่าเท่ากับค่าที่เลือก ส่งแบบ get ไปที่ไฟล์ smonth.php
				var val = $("select#sday").val();
				$("select#eday").val(val);
			});
		});
	</script>
</body>

</html>
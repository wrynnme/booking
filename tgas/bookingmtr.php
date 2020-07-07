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
		$bk_tel        = $_POST['bk_tel'];
		$bk_email      = $_POST['bk_email'] . "@tgas.co.th";
		$bk_dept       = $_POST['bk_dept'];
		$mtr_id        = $_POST['mtr_id'];
		$sdate         = "$year-$smonth-$sday $stime";
		$edate         = "$year-$smonth-$sday $etime";

		$bk_objective  = $_POST['bk_objective'];
		$data_h_type = (isset($_POST['h_type'])) ? $_POST['h_type'] : NULL;
		$data_type = (isset($_POST['type'])) ? $_POST['type'] : NULL;
		$data_more = (isset($_POST['more'])) ? $_POST['more'] : NULL;
		if (count($data_h_type) > 0) {  // ตรวจสอบ checkbox ว่ามีการเลือกมาอย่างน้อย 1 รายการหรือไม่
			foreach ($data_h_type as $key => $value) {
				$item_request .= $data_type[$value] . " - " . $data_more[$value] . ", ";
			}
		}
		if ($bk_name == "" || $sdate == "" || $edate == "" || $bk_email == "" || $bk_tel == "" || $bk_dept == "") {
			sc("Please fill out the form. !!");
		} elseif (DateDiff($sdate, $edate) >= 0) {
			sc("Time Error" . "  " . "Start : " . $stime . " " . "End : " . $etime);
		} else {
			$sql = "SELECT * FROM `bk_mtr` WHERE `mtr_id` = $mtr_id AND `bk_hr_ap` = 1 AND (`bktime_start` BETWEEN '$sdate' AND '$edate') OR `mtr_id` = $mtr_id AND `bk_hr_ap` = 1 AND (`bktime_end` BETWEEN '$sdate' AND '$edate') OR `mtr_id` = $mtr_id AND `bk_hr_ap` = 1 AND ('$sdate' BETWEEN `bktime_start` AND`bktime_end`) OR `mtr_id` = $mtr_id AND `bk_hr_ap` = 1 AND ('$edate' BETWEEN `bktime_start` AND`bktime_end`)";
			$qry = $con->query($sql);
			if ($row = $qry->fetch_array(MYSQLI_ASSOC)) {
				sc("This room was booking already by " . $row['bk_name'] . " " . $row['bktime_start'] . " - " . $row['bktime_end'] . ". Please try again in later time.");
			}
			$kk = $con->query($sql);
			$ck1 = $kk->num_rows;
			if ($ck1 != '0') {
				sc("Booking was already. Please try again. !!");
			} else {
				$sql = $con->query("INSERT INTO `bk_mtr` VALUES (NULL, '$bk_name', '$bk_dept', '$bk_tel', '$bk_email', '$sdate', '$edate', '$mtr_id', '$item_request', '$bk_objective', 1, '$bkemp_id', NULL, NULL);");
				if ($sql) {
					$qe = $con->query("SELECT * FROM `employee` WHERE emp_id = $_SESSION[emp_id]");
					$qrr = $con->query("SELECT * FROM `meeting_room` WHERE mtr_id = $mtr_id");
					$qemgr = $con->query("SELECT * FROM `employee` WHERE (SELECT employee.emp_dept FROM `employee` WHERE emp_id = $_SESSION[emp_id]) = emp_dept AND emp_position = 'manager'");
					$qlink = $con->query("SELECT * FROM bk_mtr WHERE bk_name = '$bk_name' AND bk_tel = '$bk_tel' AND bk_email = '$bk_email' AND bk_dept = '$bk_dept' AND bktime_start = '$sdate' AND bktime_end = '$edate' AND mtr_id = '$mtr_id' AND bkemp_id = '$_SESSION[emp_id]'");
					$e = $qe->fetch_array(MYSQLI_ASSOC);
					$rr = $qrr->fetch_array(MYSQLI_ASSOC);
					$emgr = $qemgr->fetch_array(MYSQLI_ASSOC);
					$link = $qlink->fetch_array(MYSQLI_ASSOC);
					$lii = $link['bkmtr_id'];

					// ini_set("SMTP","localhost");
					// ini_set("sendmail_from","$bk_email");

					$strTo       = "$emgr[emp_fname] $emgr[emp_lname] <$emgr[emp_email]>";
					$strSubject  = "=?UTF-8?B?" . base64_encode("The requester has booking meeting room.") . "?=";
					$Subject     = "The requester has booking meeting room.";
					$strHeader  .= "MIME-Version: 1.0' . \r\n";
					$strHeader  .= "Content-type: text/html; charset=utf-8\r\n";
					$strHeader  .= "From: Booking Management System <$bk_email>\rReply-To:<$bk_email>\r\n";
					$strHeader  .= "Cc: User Requester<$bk_email>\r\n";

					$headmessages = "Meeting room : <b>$rr[mtr_number]</b> <br> Requester name : <b>$bk_name</b> <br> Department : <b>$bk_dept</b> <br> Telephone number : <b>$bk_tel</b> <br> Email : <b>$bk_email</b> <br> Time start : <b>$sdate</b> <br> Time end : <b>$edate</b> <br> Objective : <b>$bk_objective</b> <br><br> <a target='_blank' href='http://192.168.222.223/booking/tgas/manager/meetingroom/viewbookingmtr.php?bkmtr_id=$lii'>Click here to approval page</a> <br>";

					include './mail.php';

					$flgSend = mail($strTo, $strSubject, $strMessage, $strHeader);
					if ($flgSend) {
						al_g("Email Sending.");
					} else {
						al_r("Cannot send Mail!");
					}
					ao("Booking successfully !", "/booking/tgas/$pt/meetingroom/");
				}
			}
		}
	}
	?>
	<div class="container" style="margin-top: 30px;">
		<h3 class="text-center"><b>Form <font color="red">B</font>ooking Meeting room</b></h3>
		<br />
		<div class="container" id="form1">
			<form class="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" id="">
				<div class="row">
					<div class="col-md">
						<label for="bk_name" class="col col-form-label text-right"><b>Name : </b></label>
					</div>
					<div class="col-md-3">
						<input type="text" class="col form-control form-check" name="bk_name" placeholder="Name" maxlength="50" required>
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
					</div>
				</div>
				<div class="row">
					<div class="col-md">
						<label for="bk_tel" class="col col-form-label text-right"><b>Telephone number : </b></label>
					</div>
					<div class="col-md-3">
						<input type="number" class="col form-control form-check" name="bk_tel" placeholder="Telephone number" maxlength="10" required>
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
					<div class="col-md-2">
						<label for="bk_position" class="col col-form-label text-right"><b>Date Start : </b></label>
					</div>
					<div class="col-md-4">
						<select class="col form-control form-check" id="smonth" name="smonth" required>
							<option value="">Select Month</option>
							<?php for ($i = $m; $i <= 12; $i++) { ?>
								<option value="<?= $i ?>"><?= $eng_month_arr["$i"]; ?></option>
							<?php } ?>
						</select>
						<div class="row" style="margin-left: 0px; margin-right: 0px;">
							<select class="col form-control form-check" id="sday" name="sday" style="display: none;" required>
								<option value="">Select Day</option>
							</select>
							<select class="col form-control form-check" id="stime" name="stime" style="display: none;" required>
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
							<select class="col form-control form-check" id="eday" name="eday" style="display:none ;" disabled>
								<option value="">Select Day</option>
							</select>
							<select class="col form-control form-check" id="etime" name="etime" style="display:none ;" required>
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
					<div class="col-md-2">
						<label for="mtr_id" class="col col-form-label text-right"><b>Meeting room : </b></label>
					</div>
					<div class="col-md-10">
						<select class="col form-check form-control form-control-danger" id="mtr_id" name="mtr_id" required>
							<option value=""> Select Room </option>
							<?php
							$r = $con->query("SELECT * FROM `meeting_room` WHERE mtr_factory = '1'");
							while ($rr = $r->fetch_array(MYSQLI_ASSOC)) {
							?>
								<option value="<?php echo $rr['mtr_id']; ?>"><?php echo $rr['mtr_number']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="bk_purpose" class="col col-form-label text-right"><b>Item request : </b></label>
					</div>
					<div class="col-md" style="padding-right: 0px;">
						<div class="input-group">
							<span class="input-group-addon">
								<input type="checkbox" aria-label="Checkbox for following text input" value="0" name="h_type[0]" id="h_type[0]">
								<input name="type[0]" type="hidden" id="type[0]" value="Coffee" />
							</span>
							<span class="input-group-addon">Coffee</span>
							<input type="number" class="form-control" aria-label="Text input with checkbox" name="more[0]" min="1" max="20" ng-model="number" maxlength="2">
						</div>
					</div>
					<div class="col-md" style="padding: 0px;">
						<div class="input-group">
							<span class="input-group-addon">
								<input type="checkbox" aria-label="Checkbox for following text input" value="1" name="h_type[1]" id="h_type[1]">
								<input name="type[1]" type="hidden" id="type[1]" value="Tea" />
							</span>
							<span class="input-group-addon">Tea</span>
							<input type="number" class="form-control" aria-label="Text input with checkbox" name="more[1]" min="1" max="20" ng-model="number" maxlength="2">
						</div>
					</div>
					<div class="col-md" style="padding-left: 0px;">
						<div class="input-group">
							<span class="input-group-addon">
								<input type="checkbox" aria-label="Checkbox for following text input" value="2" name="h_type[2]" id="h_type[2]">
								<input name="type[2]" type="hidden" id="type[2]" value="Drinking Water" />
							</span>
							<span class="input-group-addon">Drinking Water</span>
							<input type="number" class="form-control" aria-label="Text input with checkbox" name="more[2]" min="1" max="20" ng-model="number" maxlength="2">
						</div>
					</div>
				</div>
				<div class="row" style="margin-bottom: 10px;">
					<div class="col-md-2">

					</div>
					<div class="col-md" style="padding-right: 0px;">
						<div class="input-group">
							<span class="input-group-addon">
								<input type="checkbox" aria-label="Checkbox for following text input" value="3" name="h_type[3]" id="h_type[3]">
								<input name="type[3]" type="hidden" id="type[3]" value="Refreshing Towel" />
							</span>
							<span class="input-group-addon">Refreshing Towel</span>
							<input type="number" class="form-control" aria-label="Text input with checkbox" name="more[3]" min="1" max="20" ng-model="number" maxlength="2">
						</div>
					</div>
					<div class="col-md" style="padding: 0px;">
						<div class="input-group">
							<span class="input-group-addon">
								<input type="checkbox" aria-label="Checkbox for following text input" value="4" name="h_type[4]" id="h_type[4]">
								<input name="type[4]" type="hidden" id="type[4]" value="Car Parking" />
							</span>
							<span class="input-group-addon">Car Parking</span>
							<input type="number" class="form-control" aria-label="Text input with checkbox" name="more[4]" min="1" max="20" ng-model="number" maxlength="2">
						</div>
					</div>
					<div class="col-md" style="padding-left: 0px;">
						<div class="input-group">
							<span class="input-group-addon">
								<input type="checkbox" aria-label="Checkbox for following text input" value="5" name="h_type[5]" id="h_type[5]">
								<input name="type[5]" type="hidden" id="type[5]" value="Projector" />
							</span>
							<span class="input-group-addon">Projector</span>
							<input type="number" class="form-control" aria-label="Text input with checkbox" name="more[5]" min="1" max="20" ng-model="number" maxlength="2">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="bk_objective" class="col col-form-label text-right"><b>Objective : </b></label>
					</div>
					<div class="col-md-10">
						<textarea name="bk_objective" class="col form-control form-check" id="bk_objective" cols="100" rows="3" maxlength="200" required></textarea>
					</div>
				</div>
				<hr />
				<button type="submit" name="done" class="btn btn-sm btn-primary" OnClick="JavaScript:doCallAjax();">Submit</button>
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
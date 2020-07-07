<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title></title>
	<style media="screen">
		#main>img {
			width: 50%;
			height: 50%;
		}

		.row {
			border-width: 1px;
		}

		body {
			height: 100%;
		}
	</style>
</head>

<body>
	<div id="book" class="text-center align-middle" style="position: absolute; top:0; height: 100%; width: 350px;background: #4267b2; z-index: 1">
		<br>
		<div class="" style="margin-top: 20%">
			<h1 class="display-4">
				<font color="red"><b>B</b></font>
				<font color="white"><b>ooking</b></font>
			</h1>
			<h1 class="display-4">
				<font color="red"><b>M</b></font>
				<font color="white"><b>anagement</b></font>
			</h1>
			<h1 class="display-4">
				<font color="red"><b>S</b></font>
				<font color="white"><b>ystem</b></font>
			</h1>
			<br><br><br><br><br>
			<div>
				<font color="white">
					<h5>Service Support Center</h5>
				</font>
				<font color="white">HR Div. Ext. 1205 - 1206</font>
				<br>
				<font color="white">IS Div. Ext. 1251 - 1252</font>
			</div>
			<br><br>
			<div>
				<font>Version 1.0.0 /20170908</font>
			</div>
		</div>
	</div>
	<div class="container">
		<div id="main">
			<br>
			<a href="/booking/<?= $ft ?>/<?= strtolower($pt) ?>/meetingroom">
				<div class="row">
					<div class="col">
						<img src="/booking/images/meeting.png" class="rounded float-right" alt="Meeting Room" width="30%">
					</div>
					<div class="col text-left align-middle" style="margin: 30 30;">
						<h1 class="display-4">Meeting room</h1>
						<h1 class="lead">จองห้องประชุม</h1>
					</div>
				</div>
			</a>
			<br>
			<a href="/booking/<?= $ft ?>/<?= strtolower($pt) ?>/car">
				<div class="row">
					<div class="col">
						<img src="/booking/images/Car2.png" class="rounded float-right" alt="Car" width="30%">
					</div>
					<div class="col text-left align-middle" style="margin: 30 30;">
						<h1 class="display-4">Company Car</h1>
						<h1 class="lead">จองรถบริษัท</h1>
					</div>
				</div>
			</a>
			<br>
			<a href="/booking/<?= $ft ?>/<?= strtolower($pt) ?>/interpreter">
					<div class="row">
						<div class="col">
							<img src="/booking/images/translator.png" class="rounded float-right" alt="Interpreter" width="30%">
						</div>
						<div class="col text-left align-middle" style="margin: 30 30;">
							<h1 class="display-4">Interpreter</h1>
							<h1 class="lead" style="">จองล่าม</h1>
						</div>
					</div>
				</a>
		</div>
	</div>
</body>

</html>
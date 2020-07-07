<?php
@session_start();
$pt = $_SESSION['emp_position'];
$ft = $_SESSION['emp_factory'];
if ($ft = '1') {
	$ft = "tgas";
} elseif ($ft = '2') {
	$ft = "tgt";
} elseif ($ft = '3') {
	$ft = "tgrt";
}
if (strtolower($pt) == 'hr approval') {
	$pt = 'hr';
}
?>
<link rel="stylesheet" href="/booking/dist/css/bootstrap.css">
<style media="screen">
	a {
		color: #000;
		text-decoration: none;
	}

	a:hover {
		color: #626262;
		text-decoration: none;
	}

	.dropdown-item:focus,
	.dropdown-item:hover {
		background-color: rgba(0, 0, 0, 0.08);
	}

	nav {
		box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
	}

	body {
		background-color: #f7f7f9;
	}

	.nav-link:hover {
		/*background: #545454;*/
		border-radius: 5px;
	}
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top navbar-light bg-light">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleCenteredNav" aria-controls="navbarsExampleCenteredNav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse justify-content-md-center" id="navbarsExampleCenteredNav">
		<ul class="navbar-nav">
			<a class="navbar-brand" href="/booking/" style="margin: 0px; padding: 0px;">
				<img src="/booking/images/toyoda-gosei-logoo.png" width="55" height="45" class="d-inline-block align-top" alt="">
			</a>
			<li class="nav-item active">
				<a class="nav-link" href="/booking/administrator/"><img src="/booking/images/tg33.png" width="" height="27" class="d-inline-block align-top" alt=""><span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-link">
				<a class="nav-link" href="/booking/tgas/hr/">
					Human Resource (HR.)
				</a>
			</li>
			<li class="nav-link">
				<a class="nav-link" href="/booking/tgas/manager/">
					Manager Department (Mgr.)
				</a>
			</li>
			<li class="nav-link">
				<a class="nav-link" href="/booking/tgas/user/">
					User
				</a>
			</li>
			<!-- <li class="nav-item dropdown" style="border-left: 1.2px solid #9a9da0;">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">TGAS</a>
				<div class="dropdown-menu" aria-labelledby="dropdown01">
					<a class="dropdown-item" href="/booking/tgas/hr/">Human Resource (HR.)</a>
					<a class="dropdown-item" href="/booking/tgas/manager/">Manager Department (Mgr.)</a>
					<a class="dropdown-item" href="/booking/tgas/user/">User</a>
				</div>
			</li>
			<li class="nav-item dropdown" style="border-left: 1.2px solid #9a9da0;">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">TGT</a>
				<div class="dropdown-menu" aria-labelledby="dropdown02">
					<a class="dropdown-item" href="/booking/tgt/hr/">Human Resource (HR.)</a>
					<a class="dropdown-item" href="/booking/tgt/manager/">Manager Department (Mgr.)</a>
					<a class="dropdown-item" href="/booking/tgt/user/">User</a>
				</div>
			</li>
			<li class="nav-item dropdown" style="border-left: 1.2px solid #9a9da0;">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">TGRT</a>
				<div class="dropdown-menu" aria-labelledby="dropdown03">
					<a class="dropdown-item" href="/booking/tgrt/HR/">Human Resource (HR.)</a>
					<a class="dropdown-item" href="/booking/tgrt/manager/">Manager Department (Mgr.)</a>
					<a class="dropdown-item" href="/booking/tgrt/user/">User</a>
				</div>
			</li> -->
			<li class="nav-item" style="border-left: 1.2px solid #9a9da0;">
				<a class="nav-link" href="/booking/<?= $ft ?>/profile.php?emp_id=<?= $_SESSION['emp_id'] ?>">
					<font color="white">
						<?php echo $_SESSION['emp_fname'] . " " . $_SESSION['emp_lname']; ?>
					</font>
				</a>
			</li>
			<li class="nav-item">
				<font class="nav-link" color="">
					(<?php echo $_SESSION['emp_position']; ?>)
				</font>
			</li>
			<li class="nav-item" style="border-left: 1.2px solid #9a9da0;">
				<a class="nav-link" href="/booking/logout/">
					<font color="red">Logout</font>
				</a>
			</li>
		</ul>
	</div>
</nav>
<?php 
	$strMessage = <<<T
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Demystifying Email Design</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>
	<body style="margin: 0; padding: 0; background: #fff;">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
		<tr>
			<td align="center" bgcolor="" style="padding: 40px 0 30px 0;">
				<img src="http://i.imgur.com/N11q29J.png" alt="" width="80%" height="auto" style="display: block;" />
			</td>
		</tr>
		<tr>
			<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
							<b>$Subject</b>
						</td>
					</tr>
					<tr>
						<td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
							$headmessages
						</td>
					</tr>
				</table>
			</tr>
			<tr>
				<td bgcolor="#FE757C" style="padding: 30px 30px 30px 30px;">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td>
								<td style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;">
									&reg; Someone, somewhere <?=date('Y')?><br/>
									<a href="#" style="color: #ffffff;"><font color="#ffffff">Unsubscribe</font></a> to this newsletter instantly
								</td>
							</td>
							<td>
								<td align="right">
									<table border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td>
												<a href="http://www.twitter.com/">
													<img src="http://i.imgur.com/KzoyCy2.png" alt="Twitter" width="38" height="38" style="display: block;" border="0" />
												</a>
											</td>
											<td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
											<td>
												<a href="http://www.facebook.com/">
													<img src="http://i.imgur.com/UwCsLs8.png" alt="Facebook" width="38" height="38" style="display: block;" border="0" />
												</a>
											</td>
										</tr>
									</table>
								</td>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
T;
?>
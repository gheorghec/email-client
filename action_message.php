<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
     <title>Control Panel</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
     <link href="css/style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

<?php
require_once 'lib/main-class.php';
require_once 'PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;
$action_obj = new DB_query();
if(isset($_GET['uid']) && isset($_GET['email_inbox']) && isset($_GET['action']))
{
	$inbox = $_GET['email_inbox'];
		$selected_email = $action_obj -> select_email_action($inbox);
			while($row = mysql_fetch_array ($selected_email)){
				$smtp = $row['smtp'];
				$sent = $row['sent'];
				$hostname = $row['hostname'];
                $username = $row['email'];
                $password = $row['password'];
                $trash = $row['trash'];
                $full_name = $row['full_name'];
			}
$action = $_GET['action'];

	if($action == 'delete'){
		$mbox = imap_open($hostname,$username,$password) or die('Cannot connect to server: ' . imap_last_error());
			$str = $_SERVER['QUERY_STRING'];
			preg_match_all('!\d+!', $str, $matches);
				foreach ($matches[0] as $key => $uid) {
				$action_obj -> move_row_trash($trash,$inbox, $uid);
				$action_obj -> delete_row($inbox, $uid);
				imap_delete($mbox, $uid, FT_UID);
			}
			imap_expunge($mbox);
			imap_close($mbox);
	}elseif($action == 'replyall'){
		$mail = new PHPMailer;
		$arr = array();
		$str = $_SERVER['QUERY_STRING'];
					preg_match_all('!\d+!', $str, $matches);
						foreach ($matches[0] as $key => $uid) {
						$arr_email = $action_obj -> select_distinct_email_byUid($inbox,$uid);	
								while($row = mysql_fetch_array ($arr_email)){
								$email_only =  $row['email_only'];
							}

							array_push($arr, $email_only);
						
					}
					$email_send = array_unique($arr);
					$mail->isSMTP();                            // Set mailer to use SMTP
					$mail->Host = $smtp;             // Specify main and backup SMTP servers
					$mail->SMTPAuth = true;                     // Enable SMTP authentication
					$mail->Username = $username;          // SMTP username
					$mail->Password = $password; // SMTP password
					$mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
					$mail->Port = 587; 
		?>
		<div class="compose">
			<div class="send_to">
			<p>Reply To:</p>
				<?php
					foreach ($email_send as $value) {
					echo "<p class=\"email_to\">".$value."</p>";
				}
				?>
			</div>
			<div class="send_text">
				<form method="post" id="compose_form">
					<textarea name="text_msg" rows="20" cols="100" placeholder="Reply All"></textarea>
				</form>
			</div>
			<div class="send_buton">
				<button form="compose_form" type="submit" name="submit">Send</button>
			</div>
		</div>
		<?php
		if(isset($_POST['submit']) && ($_POST['text_msg'])){
			foreach ($email_send as $email_only) {
						$mail->setFrom($username, $full_name);
						
						$mail->addAddress($email_only);

						$mail->isHTML(true);  // Set email format to HTML

						$bodyContent = $_POST['text_msg'];

						$mail->Subject = $full_name;
						$mail->Body    = $bodyContent;
						 $action_obj -> insert_msg_sent($sent,$uid,$email_only,$email_date,$full_name,$bodyContent);

			}
			if(!$mail->send()) {
						    echo "<h6 class=\"error\">Messages could not be sent.<h6>";
						    echo "<h6 class=\"error\">Mailer Error:<h6>" . $mail->ErrorInfo;
						} else {
						    echo "<h6 class=\"succes\">Messages has been sent.<h6>";
						}
			
		}			

	}
}

?>

</body>
</html>
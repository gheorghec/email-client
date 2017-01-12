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

$action_obj = new DB_query();
if(isset($_GET['id']) && isset($_GET['sms_inbox']) && isset($_GET['action']))
{
	$sms_inbox = $_GET['sms_inbox'];
	$sms_trash = $_GET['sms_trash'];
	$sms_sent = $_GET['sms_sent'];


$action = $_GET['action'];
	switch ($action) {
    case 'replyall':
    	$arr = array();
		$str = $_SERVER['QUERY_STRING'];
					preg_match_all('!\d+!', $str, $matches);
						foreach ($matches[0] as $key => $id){
							$arr_sms = $action_obj ->select_distinct_sms_byId($sms_inbox, $id);	
								while($row = mysql_fetch_array ($arr_sms)){
								$phone_to =  $row['sms_from'];
							}

							array_push($arr, $phone_to);
						}
				$phone = array_unique($arr);

        ?>
       <section class="message-section body-bg">
        <div class="msg_compose">
          <div class="send_to">
          <p>Reply To:</p>
            <?php
					foreach ($phone as $value) {
					echo "<p class=\"email_to\">".$value."</p>";
				}
				?>
          </div>
          <div class="send_text">
            <form method="post" id="compose_form">
              <textarea name="text_sms" rows="10" cols="50" placeholder="Reply"></textarea>
            </form>
          </div>
          <div class="send_buton">
            <button form="compose_form" type="submit" name="submit">Send</button>
          </div>
        </div>
        </section>
          <?php
          if(isset($_POST['submit']) && ($_POST['text_sms'])){
          		foreach ($phone as $phone_to) {
	                $name_file = chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90));
	                $sms_text = $_POST['text_sms'];
	                $phone_to = str_replace("+", "", $phone_to);

	                $dir    = '/var/spool/sms/outgoing';
	                $content = "To: {$phone_to}
{$sms_text}";  
	                $fp = fopen($dir. "/send_{$name_file}","wb");
	                fwrite($fp,$content);
	                fclose($fp);
	                $action_obj -> insert_sms_sent ($phone_to, $sms_text);
	            }

              }
        break;

    case 'deleteall':
        $arr = array();
		$str = $_SERVER['QUERY_STRING'];
					preg_match_all('!\d+!', $str, $matches);
						foreach ($matches[0] as $key => $id){
							$arr_sms = $action_obj ->select_distinct_sms_byId($sms_inbox, $id);	
								while($row = mysql_fetch_array ($arr_sms)){
								$id =  $row['id'];
							}

							array_push($arr, $id);

						}
						foreach ($arr as $id) {
							$action_obj -> move_sms_trash ($sms_trash, $sms_inbox, $id);
          					$action_obj -> delete_sms($sms_inbox,$id);
						}

        break;
    
}
}

?>

</body>
</html>
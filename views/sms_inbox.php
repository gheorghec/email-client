
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Control Panel</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../css/font-awesome/css/font-awesome.min.css">
     <link href="../css/style.css" rel="stylesheet">
     <link href="../css/jquery-ui.css" rel="stylesheet">
     <script src="../js/jquery.js"></script>
      <script src="../js/jquery-ui.js"></script>
      <script src="../js/jquery.cookie.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
<body>
<?php
require_once '../lib/main-class.php';
$inbox_obj = new DB_query();
if(isset($_GET['tel_id']) && isset($_GET['loadInbox']))
  {

    $tel_id = mysql_real_escape_string($_GET['tel_id']); //intval

$selectedTel = $inbox_obj -> selectedTel($tel_id);
    while($row = mysql_fetch_array ($selectedTel))
            {
                $sms_inbox = $row['inbox'];
                $sms_trash = $row['trash'];
                $sms_sent = $row['sent'];
                $sms_number = $row['number'];
                $sms_name = $row['name'];
            }
?>
      <div class="fixed_menu">
        <form  id="action_form"  action="../action_sms.php" method="get">
            <button onclick = "deleteAll();" form="action_form"  id="delete_button" type="submit" name="action" value="deleteall" ><img src="../css/icons/1465134159_trash.png"> 
              <span>delete</span></button>
            <button  form="action_form" id="replyall_button" type="submit" name="action" value="replyall" 
            onclick="replyAll(this);" data-frame="<?php echo $sms_inbox;?>"
            ><img src="../css/icons/1465762420_mail-reply-all.png"> 
            <span>reply all</span></button>
        </form>
      </div>
<section class="mails-section">
<?php
$files = array();
$dir    = '/var/spool/sms/incoming';
$files = scandir($dir);
 $new_files = array_slice($files, 2, 1000);
  foreach ($new_files as $file) {
    $sms_file = fopen("/var/spool/sms/incoming/".$file,"r");
      $line_arr = array();
      $msg = array();
      while(! feof($sms_file))
        {
          $line = fgets($sms_file);
            array_push($line_arr,$line);
        }
          $lenght = $line_arr[10];
          $phone_arr = $line_arr[0];
            $phone = explode(":", $phone_arr);
            $sms_id = explode(":", $lenght);
              $msg = array_slice($line_arr, 11, 30);
              
              foreach ($msg as $text_sms) {}
              $sms_from = $phone[1];
              $sms_id = intval($sms_id[1]);

          $db_sms_id = $inbox_obj -> select_sms($sms_inbox);
            $arr_db = array();
          while($row = mysql_fetch_array ($db_sms_id))
            {
                array_push($arr_db, $row['sms_id']);
            }
            if (!in_array($sms_id, $arr_db)) {
              $inbox_obj -> insert_sms_inbox($sms_inbox,$sms_from,$text_sms,$sms_id);
            }
          

      fclose($sms_file);
  }

$inbox_sms  = $inbox_obj -> select_sms($sms_inbox);
        while($row = mysql_fetch_array ($inbox_sms))
        {
          echo "
          <div class=\"msg\" id=\"{$row['id']}\">
              <input class=\"check_box\" form=\"action_form\" type=\"checkbox\" name=\"id\" value=\"{$row['id']}\">
                    
              <form class=\"email-heading {$row['status']}\" target=\"{$sms_inbox}\" action=\"sms_body.php\" method=\"get\">
              <button type=\"submit\" class=\"email-link\" name=\"action\" value=\"show_sms\">
                    <input class=\"disable\" type=\"text\" name=\"id\" value=\"{$row['id']}\">
                    <input class=\"disable\" type=\"text\" name=\"sms_inbox\" value=\"{$sms_inbox}\">
                    <input class=\"disable\" type=\"text\" name=\"sms_trash\" value=\"{$sms_trash}\">
                    <input class=\"email_from\" type=\"text\" name=\"sms_from\" placeholder=\"{$row['sms_from']}\" disabled>
                    <input class=\"subject\" type=\"text\" name=\"text_sms\" placeholder=\"{$row['text_sms']}\" disabled>
              </button>
            </form>
            </div>
          ";
        }

}
?>

  <input class="disable" form="action_form" type="text" name="sms_inbox" value="<?php echo $sms_inbox;?>">
  <input class="disable" form="action_form" type="text" name="sms_trash" value="<?php echo $sms_trash;?>">
  <input class="disable" form="action_form" type="text" name="sms_sent" value="<?php echo $sms_sent;?>">


</section>
<script type="text/javascript">
window.onload = function() {
    parent.iframeLoaded();
}

$('.email-heading').click(function(e){
    $('.email-heading').removeClass('active');
    $(this).addClass('active');
    $(this).removeClass('unseen').addClass('seen');
});

$(document).ready(function() {
$( "#delete_button" ).click(function() {
        $( "input:checked.check_box" ).closest('.msg').hide();
      });



});
  
function mark(content){
  var dataMark = content.getAttribute("data-mark");
  if ( $( "input[data-mark="+dataMark+"]" ).is( ":checked" ) ){
    $("input[data-mark="+dataMark+"]").prop( "checked", false );
  }else{
    $("input[data-mark="+dataMark+"]").prop( "checked", true );
  }
  
}
function replyAll(content){
  var dataframe = content.getAttribute("data-frame");
  $("#action_form").prop("target", dataframe);
}
function deleteAll(){
  $("#action_form").prop("target", 'action_frame');
}
</script>

  </body>
</html>


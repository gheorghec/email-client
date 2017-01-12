
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

<section class="mails-section padding-top">

<?php
require_once '../lib/main-class.php';
$inbox_obj = new DB_query();
if(isset($_GET['tel_id']) && isset($_GET['loadSent']))
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


$inbox_sms  = $inbox_obj -> select_sms($sms_sent);
        while($row = mysql_fetch_array ($inbox_sms))
        {
          echo "
          <div class=\"msg\" id=\"{$row['id']}\">
              <input class=\"check_box\" form=\"action_form\" type=\"checkbox\" name=\"id\" value=\"{$row['id']}\">
                    
              <form class=\"email-heading {$row['status']}\" target=\"{$sms_sent}\" action=\"sms_body.php\" method=\"get\">
              <button type=\"submit\" class=\"email-link\" name=\"action\" value=\"show_sms\">
                    <input class=\"disable\" type=\"text\" name=\"id\" value=\"{$row['id']}\">
                    <input class=\"disable\" type=\"text\" name=\"sms_inbox\" value=\"{$sms_sent}\">
                    <input class=\"disable\" type=\"text\" name=\"sms_trash\" value=\"{$sms_trash}\">
                    <input class=\"email_from\" type=\"text\" name=\"sms_from\" placeholder=\"{$row['phone_to']}\" disabled>
                    <input class=\"subject\" type=\"text\" name=\"text_sms\" placeholder=\"{$row['text_sms']}\" disabled>
              </button>
            </form>
            </div>
          ";
        }

}
?>




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


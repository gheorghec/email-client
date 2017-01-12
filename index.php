<?php
  require_once 'lib/globals.php';
  require_once 'lib/main-class.php';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Control Panel</title>
    <link href="css/style.css" rel="stylesheet">
    <link href="css/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/jquery.cookie.js"></script>
  </head>
<body class="body-bg">
<form id="sign_out" method="post">
</form>

<div class="cp_wrap">
  <div class="gradient">
    <div class="status ">
      <div class="connection_status buton">
        <button data-name="Connect email" onclick="connect_email(this);"><img src="css/icons/1463845381_connect.png"> </button>
        <p class="buton_title">Connections</p>
      </div>
      <div class="loading_status">
        <div class="inactiv"  id="progressbar"></div>
      </div>
      <div class="done_status">
          <div class="inactiv" id="done"><figure class="done"></figure></div>
      </div>
      <div class="settings buton">
        <button form="sign_out" name="signout" type="submit"><img  src="css/icons/1466792461_exit.png"> </button>
        <p class="buton_title" >Sign Out</p>
      </div>
      <div class="clear"></div>
    </div>
  </div>

<div class="top-menu body-bg">
  <div class="write buton">
      <button data-name="Compose" onclick="compose(this);"><img src="css/icons/1463845121_message_edit.png"> </button>
      <p class="buton_title">Write</p>
    </div>
    <div class="address_book buton">
      <button data-name="Address Book" onclick="address_book(this);"><img src="css/icons/1463844867_addressbook.png"> </button>
      <p class="buton_title">Address Book</p>
    </div>
    <div class="deco_line"></div>
    <div class="email_folders buton">
      <a href="index.php?email=folders"><img src="css/icons/1463966233_email.png"> </a>
      <p class="buton_title">Email Folders</p>
    </div>
    <div class="sms_folders buton">
      <a href="index.php?sms=folders"><img src="css/icons/1463845368_message.png"> </a>
      <p class="buton_title">Sms Folders</p>
    </div>
    <div class="sms_folders buton">
    </div>
  </div>
  <div class="clear"></div>
 
<div class="list_section">

<?php
 $res = new DB_query();
 if(isset($_GET['email']))
  {
  $listFolders = $res -> select_conectedEmails();
     while($row = mysql_fetch_array ($listFolders))
            {
              echo "
              <ul>
                <li> 
                  <img src=\"css/icons/1465133779_folder.png\">
                  <button>   
                    {$row['email']}
                  </button>
                </li>               
              ";
              echo "
                <li> 
                  <img src=\"css/icons/1463966233_email.png\">
                  <button 
                  data-src=\"views/email-inbox.php?email_id={$row['id']}&loadInbox\" 
                  data-name=\"Inbox\"
                  data-msg = \"{$row['inbox']}\" 
                  id=\"deactivateBtn\" 
                  onclick=\"create(this);\">   
                    Inbox
                  </button>
                </li>               
              ";
              echo "
                <li> 
                  <img src=\"css/icons/1465134319_email-send.png\">
                  <button 
                  data-src=\"views/email_sent.php?email_id={$row['id']}&loadSent\" 
                  data-name=\"Sent\" 
                  id=\"deactivateBtn\"
                  data-msg = \"{$row['sent']}\" 
                  onclick=\"create(this);\">   
                    Sent
                  </button>
                </li>               
              ";
              echo "
                <li> 
                  <img src=\"css/icons/1465134159_trash.png\">
                  <button data-src=\"views/email_trash.php?email_id={$row['id']}&loadTrash\" 
                  data-name=\"Trash\" 
                  id=\"deactivateBtn\" 
                  data-msg = \"{$row['trash']}\"
                  onclick=\"create(this);\">   
                    Trash
                  </button>
                </li> 
              </ul>               
              ";
            }
        }elseif(isset($_GET['sms'])){
  $listFolders = $res -> select_conectedNumbers();
     while($row = mysql_fetch_array ($listFolders))
            {
              echo "
              <ul>
                <li> 
                  <img src=\"css/icons/1465133779_folder.png\">
                  <button>   
                    {$row['name']}:{$row['number']}
                  </button>
                </li>               
              ";
              echo "
                <li> 
                  <img src=\"css/icons/1463845368_message.png\">
                  <button 
                  data-src=\"views/sms_inbox.php?tel_id={$row['id']}&loadInbox\" 
                  data-name=\"SMS-Inbox\"
                  data-msg = \"{$row['inbox']}\" 
                  id=\"deactivateBtn\" 
                  onclick=\"create(this);\">   
                    Inbox
                  </button>
                </li>               
              ";
              echo "
                <li> 
                  <img src=\"css/icons/1465134319_email-send.png\">
                  <button 
                  data-src=\"views/sms_sent.php?tel_id={$row['id']}&loadSent\" 
                  data-name=\"SMS-Sent\" 
                  id=\"deactivateBtn\"
                  data-msg = \"{$row['sent']}\" 
                  onclick=\"create(this);\">   
                    Sent
                  </button>
                </li>               
              ";
              echo "
                <li> 
                  <img src=\"css/icons/1465134159_trash.png\">
                  <button data-src=\"views/sms_trash.php?tel_id={$row['id']}&loadTrash\" 
                  data-name=\"SMS-Trash\" 
                  id=\"deactivateBtn\" 
                  data-msg = \"{$row['trash']}\"
                  onclick=\"create(this);\">   
                    Trash
                  </button>
                </li> 
              </ul>               
              ";
            }
        }
?>

</div>




<div class="tabs">
    <ul>

    </ul>

    <div class="index-section" id="index-area"> 

    </div>
</div>

<iframe name="action_frame" class="disable"  >
  
</iframe>
<iframe name="mark_frame" class="disable"  src="mark_message.php">
  
</iframe>

</div>

<script>
/* loading bar*/
$( "#progressbar" ).progressbar({
  value: false
});
function compose(content){
  var nameFile = content.getAttribute("data-name");
  $(".tabs > ul").append("<li >"+nameFile+"<button onclick=\"removeTab(this);\"  id=\"close\">x</button></li>")
  $("#index-area").append("<div><iframe  class=\"address_book\"  src=\"views/write.php\"> </iframe></div>")
  $("#progressbar").removeClass("inactiv")//activare loading bar
  $("#done").addClass("inactiv")//dezactivare done status

  $(".tabs").lightTabs();//activare a unui nou tab

  var count = $(".tabs").children("ul").children("li").length;
    if(count == 1){
      $(".tabs").children("ul").children("li:first-child").addClass("active");
      $(".tabs").children("div").children("div:first-child").show();
    }else{
      $(".tabs").children("ul").children("li").removeClass("active");
      $(".tabs").children("div").children("div").hide();
      $(".tabs").children("ul").children("li:last-child").addClass("active");
      $(".tabs").children("div").children("div:last-child").show();
    }
}
function address_book(content){
  var nameFile = content.getAttribute("data-name");
  $(".tabs > ul").append("<li >"+nameFile+"<button onclick=\"removeTab(this);\"  id=\"close\">x</button></li>")
  $("#index-area").append("<div><iframe  class=\"address_book\"  src=\"views/address_book.php\"> </iframe></div>")
  $("#progressbar").removeClass("inactiv")//activare loading bar
  $("#done").addClass("inactiv")//dezactivare done status

  $(".tabs").lightTabs();//activare a unui nou tab

  var count = $(".tabs").children("ul").children("li").length;
    if(count == 1){
      $(".tabs").children("ul").children("li:first-child").addClass("active");
      $(".tabs").children("div").children("div:first-child").show();
    }else{
      $(".tabs").children("ul").children("li").removeClass("active");
      $(".tabs").children("div").children("div").hide();
      $(".tabs").children("ul").children("li:last-child").addClass("active");
      $(".tabs").children("div").children("div:last-child").show();
    }
}
function connect_email(content){
  var nameFile = content.getAttribute("data-name");
  $(".tabs > ul").append("<li >"+nameFile+"<button onclick=\"removeTab(this);\"  id=\"close\">x</button></li>")
  $("#index-area").append("<div><iframe  class=\"address_book\"  src=\"connect_email.php\"> </iframe></div>")
  $("#progressbar").removeClass("inactiv")//activare loading bar
  $("#done").addClass("inactiv")//dezactivare done status

  $(".tabs").lightTabs();//activare a unui nou tab

  var count = $(".tabs").children("ul").children("li").length;
    if(count == 1){
      $(".tabs").children("ul").children("li:first-child").addClass("active");
      $(".tabs").children("div").children("div:first-child").show();
    }else{
      $(".tabs").children("ul").children("li").removeClass("active");
      $(".tabs").children("div").children("div").hide();
      $(".tabs").children("ul").children("li:last-child").addClass("active");
      $(".tabs").children("div").children("div:last-child").show();
    }
}

function create(content){ // functia pentru afisarea in iframe a fisierelor pentru vizualizarea mesajelor

  var contentFile = content.getAttribute("data-src");
  var nameFile = content.getAttribute("data-name");
  var msgFrame = content.getAttribute("data-msg");
$(".tabs > ul").append("<li >"+nameFile+"<button onclick=\"removeTab(this);\"  id=\"close\">x</button></li>")
$("#index-area").append("<div><iframe  class=\"inbox\" id=\""+msgFrame+"\" src=\""+contentFile+"\"> </iframe> <iframe class=\"message_frame\"  name=\""+msgFrame+"\" src=\"views/message_body.php\"> </iframe></div>")
$("#progressbar").removeClass("inactiv")//activare loading bar
$("#done").addClass("inactiv")//dezactivare done status
$(".tabs").lightTabs();//activare a unui nou tab

  var count = $(".tabs").children("ul").children("li").length;
    if(count == 1){
      $(".tabs").children("ul").children("li:first-child").addClass("active");
      $(".tabs").children("div").children("div:first-child").show();
    }else{
      $(".tabs").children("ul").children("li").removeClass("active");
      $(".tabs").children("div").children("div").hide();
      $(".tabs").children("ul").children("li:last-child").addClass("active");
      $(".tabs").children("div").children("div:last-child").show();
    }
}

function refresh() {//functia refresh pentru reincarcarea continutului in iframe
document.getElementById('index').contentWindow.location.reload();
$("#progressbar").removeClass("inactiv")//activare loading bar
$("#done").addClass("inactiv")//dezactivare done status
}

function disbaleButton(){ //functia pentru deactivarea butoanelor pentru fisiere dupa primul click
  document.getElementById("deactivateBtn").disabled = true;
}

function iframeLoaded() {
  $("#progressbar").addClass("inactiv")//activare loading bar
  $("#done").removeClass("inactiv")//dezactivare done status
  
}

function removeTab(content){
  var current = content.getAttribute("data-page");
 
  if ($("li[data-page="+current+"]").is(':first-child')){
    $(".tabs").children("div").children("div[data-page="+current+"]").next().show();
    $(".tabs").children("ul").children("li[data-page="+current+"]").next().addClass("active");
    $(".tabs").children("div").children("div[data-page="+current+"]").remove();
    $(".tabs").children("ul").children("li[data-page="+current+"]").remove();
  }else if($("li[data-page="+current+"]").is(':last-child')){
    $(".tabs").children("div").children("div[data-page="+current+"]").prev().show();
    $(".tabs").children("ul").children("li[data-page="+current+"]").prev().addClass("active");
    $(".tabs").children("div").children("div[data-page="+current+"]").remove();
    $(".tabs").children("ul").children("li[data-page="+current+"]").remove();
  }else{
    $(".tabs").children("div").children("div[data-page="+current+"]").next().show();
    $(".tabs").children("ul").children("li[data-page="+current+"]").next().addClass("active");
    $(".tabs").children("div").children("div[data-page="+current+"]").remove();
    $(".tabs").children("ul").children("li[data-page="+current+"]").remove();
  }
} 
//modulul pentru taburi
(function($){       
  jQuery.fn.lightTabs = function(options){

    var createTabs = function(){
      tabs = this;
      i=0;
      
      showPage = function(i){
        $(tabs).children("div").children("div").hide();
        $(tabs).children("div").children("div[data-page="+i+"]").show();
        $(tabs).children("ul").children("li").removeClass("active");
        $(tabs).children("ul").children("li[data-page="+i+"]").addClass("active");
      }

     $(tabs).children("div").children("div").each(function(index, element){
        $(element).attr("data-page",index);                      
      });
      $(tabs).children("ul").children("li").each(function(index, element){
        $(element).attr("data-page",index);                      
      });
      $(tabs).children("ul").children("li").children("button").each(function(index, element){
        $(element).attr("data-page", index);                     
      });
      
      $(tabs).children("ul").children("li").click(function(){
        showPage(parseInt($(this).attr("data-page")));
      });  
    };    
    return this.each(createTabs);
  };  
})(jQuery);

var deleteFunc = function(target,uid){
$("#"+target+"").contents().find( "#"+uid+"" ).hide();
}
var deleteSms = function(target,id){
$("#"+target+"").contents().find( "#"+id+"" ).hide();
}
</script>


</body>
</html>


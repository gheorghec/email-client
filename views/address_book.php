<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
     <title>Control Panel</title>
    <!-- Bootstrap -->
     <link href="../css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/font-awesome/css/font-awesome.min.css">
     <link href="../css/style.css" rel="stylesheet">
     <link href="../css/jquery-ui.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
<body>
<table class="table table-striped contact">
  <tbody>
  <tr><th></th><th>name</th><th>e-mail</th><th>phone number</th></tr>
  <tr>
  <form method="post">
    <td><button type="submit" name="submit"><img src="../css/icons/1465922090_profile_add.png">Add</button></td>
    <td><input type="text" name="name" placeholder="Name"></td>
    <td><input type="email" name="email" placeholder="e-mail"></td>
    <td><input type="tel" name="phone" placeholder="Phone Number"></td>
    </form>
  </tr>
    
      
      
      
      
    


<?php
  require_once '../lib/main-class.php';
  $contact_obj = new DB_query();

  $show_contacts = $contact_obj -> select_contact();
    while($row = mysql_fetch_array($show_contacts)){
      $name = $row['name'];
      $email = $row['email'];
      $phone = $row['phone'];
      ?>
        <tr>
        <td><img src="../css/icons/1463845204_profile.png"></td>
        <td><?php echo $name; ?></td>
        <td><?php echo $email; ?></td>
        <td><?php echo $phone; ?></td>
        </tr>
      <?php
    }
    if(isset($_POST['submit']) && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['phone'])){
      $insert_name = $_POST['name'];
      $insert_email = $_POST['email'];
      $insert_phone = $_POST['phone'];
      $contact_obj -> insert_contact($insert_name,$insert_email,$insert_phone);
    }

?>
</tbody>
</table>


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

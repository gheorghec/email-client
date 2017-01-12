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
    <link href="css/bootstrap.css" rel="stylesheet">
     <link href="css/style.css" rel="stylesheet">
     <link href="css/jquery-ui.css" rel="stylesheet">
    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui.js"></script>
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
  <tr><th></th><th>name</th><th>e-mail</th><th></th></tr>
<tr>
  <form method="post">
    <td><button type="submit" name="submit"><img src="css/icons/1465922090_profile_add.png">Add</button></td>
    <td><input type="text" name="name" placeholder="Name"></td>
    <td><input type="email" name="email" placeholder="e-mail"></td>
    <td><input type="password" name="password" placeholder="e-mail password"></td>
    </form>
  </tr>
<?php
require_once 'lib/main-class.php';
$contact_obj = new DB_query();
  $emails_acc = $contact_obj -> select_emails_acc();
    while($row = mysql_fetch_array($emails_acc)){
      ?>
      <tr>
        <td><img src="css/icons/1463845204_profile.png"></td>
        <td><?php echo $row['full_name']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td></td>
      </tr>
    <?php
    }



if(isset($_POST['submit']) && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])){
      $full_name = $_POST['name'];
      $email = $_POST['email'];
      $password = $_POST['password'];

        $email_type = explode("@", $email);
        $email_type =  $email_type[1]; // кусок2
          switch ($email_type) {
            case 'yahoo.com':
              $name_file = chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90));
              $smtp = 'smtp.mail.yahoo.com';
              $hostname = '{imap.mail.yahoo.com:993/imap/ssl}INBOX';
              $inbox = $name_file."_inbox";
              $sent = $name_file."_sent";
              $trash = $name_file."_trash";
               $contact_obj -> insert_email_acc($email, $password, $smtp, $hostname, $inbox, $sent, $trash, $full_name);
               $contact_obj -> create_table_inbox($inbox);
               $contact_obj -> create_table_sent($sent);
               $contact_obj -> create_table_trash($trash);
              break;
            
            case 'gmail.com':
              $name_file = chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90));
              $smtp = 'smtp.gmail.com';
              $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
              $inbox = $name_file."_inbox";
              $sent = $name_file."_sent";
              $trash = $name_file."_trash";
               $contact_obj -> insert_email_acc($email, $password, $smtp, $hostname, $inbox, $sent, $trash, $full_name);
               $contact_obj -> create_table_inbox($inbox);
               $contact_obj -> create_table_sent($sent);
               $contact_obj -> create_table_trash($trash);
              break;
          }
    }
?>
</tbody>
</table>
<script type="text/javascript">
window.onload = function() {
    parent.iframeLoaded();
}

</script>

  </body>
</html>

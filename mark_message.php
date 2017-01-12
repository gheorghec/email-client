
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
<body>
<?php
require_once 'lib/main-class.php';
$mark_obj = new DB_query();
if(isset($_GET['uid']) && isset($_GET['email_inbox']) && isset($_GET['action'])){
  
$action =$_GET['action'];
$inbox = $_GET['email_inbox'];
$mark_obj -> mark_row_default($inbox);
  if($action == 'mark_message'){
  $str = $_SERVER['QUERY_STRING'];
  preg_match_all('!\d+!', $str, $matches);
    foreach ($matches[0] as $key => $uid) {
      $mark_obj -> mark_row($inbox,$uid);
      }
  }
}






/*
echo "key:".$key."val:".$mat."<br>";
$pizza  = $_SERVER['QUERY_STRING'];
$pieces = explode("&", $pizza);
foreach ($pieces as $key => $value) {
  # code...
  echo "key:".$key."val:".$value."<br>";
}*/

?>


<script>

</script>


</body>
</html>


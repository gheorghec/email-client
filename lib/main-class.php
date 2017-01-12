<?php
require_once 'globals.php';
define('DB_SERVER', $server);
define('DB_USER', $user);
define('DB_PASS' , $pass);
define('DB_NAME', $db);

class DB_query
{
 function __construct()
 {
  $conn = mysql_connect(DB_SERVER,DB_USER,DB_PASS) or die('localhost connection problem'.mysql_error());
  mysql_select_db(DB_NAME, $conn);
 }
//selecteaza toate mesajele email din baza de date
 public function select_emails($emails_inbox)
 {
  $res = mysql_query("SELECT * FROM $emails_inbox ORDER BY uid DESC;");
  return $res;
 }
  public function select_sms($sms_inbox)
 {
  $res = mysql_query("SELECT * FROM $sms_inbox ORDER BY id DESC;");
  return $res;
 }

  public function move_sms_trash($sms_trash, $sms_inbox,$id){
  $res = mysql_query(" INSERT INTO $sms_trash (sms_from, text_sms)
                        SELECT sms_from, text_sms
                        FROM $sms_inbox
                        WHERE id = '$id'");

  return $res;
 }


  public function delete_sms($sms_inbox,$id)
 {
  $res = mysql_query("DELETE FROM $sms_inbox WHERE id='$id';");
  return $res;
 }
   public function select_sms_inbox($sms_inbox, $id)
 {
  $res = mysql_query("SELECT * FROM $sms_inbox WHERE id = '$id';");
  return $res;
 }
  public function select_emails_acc()
 {
  $res = mysql_query("SELECT * FROM emails ");
  return $res;
 }
  public function select_emails_byemail($email_from)
 {
  $res = mysql_query("SELECT * FROM emails WHERE email = '$email_from'");
  return $res;
 }
 public function select_email_action($inbox)
 {
  $res = mysql_query("SELECT * FROM emails WHERE inbox = '$inbox';");
  return $res;
 }
 public function selectedEmail($email_id)
 {
  $res = mysql_query("SELECT * FROM emails WHERE id = '$email_id';");
  return $res;
 }
  public function selectedTel($tel_id)
 {
  $res = mysql_query("SELECT * FROM phone_numbers WHERE id = '$tel_id';");
  return $res;
 }
 public function select_email_byUid($inbox, $uid)
 {
  $res = mysql_query("SELECT * FROM $inbox WHERE uid = '$uid';");
  return $res;
 }
  public function select_distinct_email_byUid($inbox,$uid)
 {
  $res = mysql_query("SELECT * FROM $inbox WHERE uid = '$uid';");
  return $res;
 }
 //insereaza mesajele email din casuta in baza de date
  public function insert_emails($emails_inbox,$uid, $seen, $email_from, $email_date, $subject, $message, $status, $email_only,$mark, $msgno){
   $res = mysql_query("INSERT $emails_inbox (uid, seen, email_from, email_date, subject, message, status, email_only, mark, msgno) VALUES ('$uid', '$seen', '$email_from', '$email_date', '$subject', '$message', '$status', '$email_only','$mark', '$msgno')");
   return $res;
 }
public function insert_msg_sent($sent,$uid,$email_only,$email_date,$full_name,$bodyContent){
   $res = mysql_query("INSERT $sent (uid, email_to, email_date, subject, message) VALUES ('$uid', '$email_only', '', '$full_name', '$bodyContent')");
   return $res;
 }
 public function select_uid($emails_inbox)
 {
  $res = mysql_query("SELECT uid FROM $emails_inbox;");
  return $res;
 }
 public function select_msgno($emails_inbox)
 {
  $res = mysql_query("SELECT uid FROM $emails_inbox;");
  return $res;
 }
 public function select_conectedEmails(){
  $res = mysql_query("SELECT * FROM emails;");
  return $res;
 }

  public function select_conectedNumbers(){
  $res = mysql_query("SELECT * FROM phone_numbers;");
  return $res;
 }
 public function update_email_status($selectedInbox, $uid){
  $res = mysql_query("UPDATE $selectedInbox
                        SET status = 'seen'
                        WHERE uid = '$uid'");
 }
 public function move_row_trash($trash, $inbox,$uid){
  $res = mysql_query(" INSERT INTO $trash (uid, seen, email_from, email_date, subject, message, status, email_only)
                        SELECT uid, seen, email_from, email_date, subject, message, status, email_only 
                        FROM $inbox
                        WHERE uid = '$uid'");

  return $res;
 }

  public function delete_row($inbox,$uid){
  $res = mysql_query(" DELETE FROM $inbox WHERE uid='$uid'");
  return $res;
 }

public function mark_row($Inbox,$uid){
  $res = mysql_query("UPDATE $Inbox
                        SET mark = 'checked'
                        WHERE uid = '$uid'");
 }
 public function mark_row_default($Inbox){
  $res = mysql_query("UPDATE $Inbox
                        SET mark = 'default'");
 }

 public function select_contact(){
  $res = mysql_query("SELECT * FROM contact");
  return $res;
 }

 
 public function insert_contact($name,$email,$phone){
  $res = mysql_query("INSERT contact (name,email,phone) VALUES ('$name','$email','$phone')");
  return $res;
 }

 public function insert_sms_sent($phone_to,$sms_text){
  $res = mysql_query("INSERT sms_sent (phone_to,text_sms ) VALUES ('$phone_to','$sms_text')");
  return $res;
 }

 public function insert_sms_inbox($sms_inbox,$sms_from,$text_sms,$sms_id){
  $res = mysql_query("INSERT $sms_inbox (sms_from, text_sms, status, sms_id ) VALUES ('$sms_from','$text_sms','unseen', '$sms_id')");
  return $res;
 }

  public function update_sms_status($sms_inbox, $sms_id){
  $res = mysql_query("UPDATE $sms_inbox
                        SET status = 'seen'
                        WHERE sms_id = '$sms_id'");
 }

  public function select_distinct_sms_byId($sms_inbox,$id)
 {
  $res = mysql_query("SELECT * FROM $sms_inbox WHERE id = '$id';");
  return $res;
 }


 public function insert_email_acc($email, $password, $smtp, $hostname, $inbox, $sent, $trash, $full_name){
  $res = mysql_query("INSERT emails (email, password, smtp, hostname, inbox, sent, trash, full_name) VALUES ('$email','$password', '$smtp', '$hostname', '$inbox', '$sent', '$trash', '$full_name')");
  return $res;
 }
  public function create_table_inbox($inbox){
  $res = mysql_query("CREATE TABLE $inbox (
                        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        uid VARCHAR(30) NOT NULL,
                        seen VARCHAR(30) NOT NULL,
                        email_from VARCHAR(30) NOT NULL,
                        email_date VARCHAR(30) NOT NULL,
                        subject VARCHAR(30) NOT NULL,
                        message TEXT NOT NULL,
                        status VARCHAR(30) NOT NULL,
                        email_only VARCHAR(30) NOT NULL,
                        mark VARCHAR(30) NOT NULL,
                        msgno VARCHAR(30) NOT NULL
                        )");
  return $res;
 }

 public function create_table_sent($sent){
  $res = mysql_query("CREATE TABLE $sent (
                        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        uid VARCHAR(30) NOT NULL,
                        email_to VARCHAR(30) NOT NULL,
                        email_date TIMESTAMP NOT NULL,
                        subject VARCHAR(30) NOT NULL,
                        message TEXT NOT NULL
                        )");
  return $res;
 }

 public function create_table_trash($trash){
  $res = mysql_query("CREATE TABLE $trash (
                        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        uid VARCHAR(30) NOT NULL,
                        seen VARCHAR(30) NOT NULL,
                        email_from VARCHAR(30) NOT NULL,
                        email_date VARCHAR(30) NOT NULL,
                        subject VARCHAR(30) NOT NULL,
                        message TEXT NOT NULL,
                        status VARCHAR(30) NOT NULL,
                        email_only VARCHAR(30) NOT NULL,
                        mark VARCHAR(30) NOT NULL,
                        msgno VARCHAR(30) NOT NULL
                        )");
  return $res;
 }
}
?>

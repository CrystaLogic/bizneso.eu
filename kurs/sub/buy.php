<?php

  session_start();

  if(!isset($_SESSION['loggedin']))
  {
    header('Location: login.php');
    die();
  }

  $week = $_GET['w'];

  /* Get database settings */

  $database = json_decode(file_get_contents('database.json'));

  /* Create connection */

  $db = new mysqli($database->host, $database->user, $database->password, $database->name) ;
  $db->set_charset('utf8');

  if($db->connect_error)
  {
    die('Wystąpił błąd bazy danych ('.$db->connect_error.')');
  }

  /* Get data */

  $sql = $db->prepare("SELECT * FROM videos WHERE week=?");
  $sql->bind_param('s', $week);
  $sql->execute();
  $res = $sql->get_result();
  $ass = $res->fetch_assoc();

  if(count($ass) == 0)
  {
    header('Location: index.php');
    die();
  }

  $sql = $db->prepare("SELECT email FROM users WHERE id=?");
  $sql->bind_param('s', $_SESSION['userid']);
  $sql->execute();
  $res = $sql->get_result();
  $ass = $res->fetch_assoc();
  $email = $ass['email'];


  echo '
    <form action="https://ssl.dotpay.pl/test_payment/" method="post" id="formik">
      <input type="hidden" name="id" value="751765">
      <input type="hidden" name="amount" value="100.00">
      <input type="hidden" name="currency" value="PLN">
      <input type="hidden" name="URL" value="http://bizneso.eu/kurs/">
      <input type="hidden" name="type" value="0">
      <input type="hidden" name="control" value="'.$week.'|'.$_SESSION['userid'].'">
      <input type="hidden" name="email" value="'.$email.'">
      <input type="hidden" name="description" value="Dostęp do okresu: '.$week.'">
    </form>
    <script>document.getElementById("formik").submit();</script>
  ';

?>

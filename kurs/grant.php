<?php

  $PIN="kHc4qmmth9jMsbK3mjCWIPZbrI86GmJH";

  $sign=
  $PIN.
  $_POST['id'].
  $_POST['operation_number'].
  $_POST['operation_type'].
  $_POST['operation_status'].
  $_POST['operation_amount'].
  $_POST['operation_currency'].
  $_POST['operation_withdrawal_amount'].
  $_POST['operation_commission_amount'].
  $_POST['is_completed'].
  $_POST['operation_original_amount'].
  $_POST['operation_original_currency'].
  $_POST['operation_datetime'].
  $_POST['operation_related_number'].
  $_POST['control'].
  $_POST['description'].
  $_POST['email'].
  $_POST['p_info'].
  $_POST['p_email'].
  $_POST['credit_card_issuer_identification_number'].
  $_POST['credit_card_masked_number'].
  $_POST['credit_card_brand_codename'].
  $_POST['credit_card_brand_code'].
  $_POST['credit_card_id'].
  $_POST['channel'].
  $_POST['channel_country'].
  $_POST['geoip_country'];

  $signature=hash('sha256',$sign);

  if($signature == $_POST['signature'])
  {
    $arr = explode('|', $_POST['control']);
    $week = $arr[0];
    $id = $arr[1];

    if($_POST['operation_amount'] == 100)
    {
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

      $sql = $db->prepare("SELECT id FROM videos WHERE id NOT IN (SELECT video_id FROM access WHERE user_id=?) AND week=?");
      $sql->bind_param('ds', $id, $week);
      $sql->execute();
      $res = $sql->get_result();

      while($ass = $res->fetch_assoc())
      {
        $sql = $db->prepare("INSERT INTO access (user_id, video_id) VALUES (?, ?)");
        $sql->bind_param('dd', $id, $ass['id']);
        $sql->execute();
      }
    }
  }

  echo 'OK' ;

?>

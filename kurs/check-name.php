<?php

  $name = $_POST['name'] ;

  /* Get database settings */

  $database = json_decode(file_get_contents('database.json'));

  /* Create connection */

  $db = new mysqli($database->host, $database->user, $database->password, $database->name) ;
  $db->set_charset('utf8');

  /* Get data */

  $sql = $db->prepare("SELECT id FROM users WHERE email=?");
  $sql->bind_param('s', $name);
  $sql->execute();
  $res = $sql->get_result();

  $ass = $res->fetch_assoc();

  if(count($ass) > 0)
  {
    echo 'not_avabile' ;
  }
  else
  {
    echo 'avabile';
  }

  $sql->close();
  $db->close();

?>

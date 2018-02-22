<?php

  if(isset($_SESSION['loggedin']))
  {
    // blah
  }
  else
  {
    header('Location: login.php');
    die();
  }

?>

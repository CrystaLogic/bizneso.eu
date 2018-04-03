<?php

  session_start();

  if(!isset($_SESSION['loggedin']))
  {
    header('Location: login.php');
    die();
  }

?>
<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kurs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Lato|Montserrat:600,700&amp;subset=latin-ext" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <header id="main">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-2 col-md-2 col-sm-4 col-6 mx-auto py-3">
            <a href="index.php"><img class="w-100" src="img/logo.png" alt="bizneso.eu"></a>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-12 col-12">
            <ul class="nav justify-content-center">
              <li class="nav-item">
                <a class="nav-link active" href="#">Tydzień 1</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Tydzień 2</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Tydzień 3</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Tydzień 4</a>
              </li>
            </ul>
          </div>
          <div class="col-lg-1 col-md-1 col-sm-3 col-4 mx-auto py-3 icons d-flex align-items-center justify-content-between">
            <a href="settings.php">
              <i class="material-icons">settings</i>
            </a>
            <a href="logout.php">
              <i class="material-icons">exit_to_app</i>
            </a>
          </div>
        </div>
      </div>
    </header>
    <!-- Videos -->
    <section id="videos">
      <div class="container">
        <!-- Spacer -->
        <div class="w-100 py-4"></div>
        <!-- Spacer end -->
        <?php

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

          $id = $_GET['v'] ;

          if(!preg_match("/^[0-9]{1,}$/", $id))
          {
            header('Location: index.php');
            die();
          }

          $sql = $db->prepare("SELECT * FROM videos WHERE id=?");
          $sql->bind_param('d', $id);
          $sql->execute();
          $res = $sql->get_result();
          $ass = $res->fetch_assoc();

          if(count($ass) == 0)
          {
            header('Location: index.php');
            die();
          }

          $name = $ass['name'];
          $video = $ass['video'];
          $content = $ass['content'];

          $sql->close();
          $db->close();

        ?>
        <div class="row mb-4">
          <div class="col-lg-12">
            <h4>Styczeń</h4>
          </div>
        </div>
        <div class="row pb-3 justify-content-center">
          <div class="col-lg-8">
            <iframe id="ytplayer" class="w-100" type="text/html" width="640" height="360" src="http://www.youtube.com/embed/<?php echo $video; ?>?autoplay=0&origin=http://example.com" frameborder="0" allowfullscreen></iframe>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <h5 class="my-3"><?php echo $name; ?></h5>
          </div>
          <div class="col-lg-12">
            <?php echo $content; ?>
          </div>
        </div>
      </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

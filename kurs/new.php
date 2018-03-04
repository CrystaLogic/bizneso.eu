<?php

  session_start();

  if(!isset($_SESSION['loggedin']))
  {
    header('Location: login.php');
    die();
  }
  else
  {
    if(!$_SESSION['admin'])
    {
      header('Location: login.php');
      die();
    }
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
          <div class="col-lg-2">
            <a href="index.php"><img class="w-100" src="img/logo.png" alt="bizneso.eu"></a>
          </div>
          <div class="col-lg-9">
            <ul class="nav justify-content-end">
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
          <div class="col-lg-1 icons d-flex align-items-center justify-content-between">
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
    <section id="settings">
      <div class="container">
        <!-- Spacer -->
        <div class="w-100 py-4"></div>
        <!-- Spacer end -->
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <?php if(!isset($_POST['name'])
                  && !isset($_POST['week'])
                  && !isset($_POST['video'])
                  && !isset($_POST['content'])):
            ?>
            <form class="needs-validation" action method="POST" novalidate>
              <fieldset class="form-group">
                <label for="name">Tytuł</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Tytuł">
                <small class="form-text text-muted">Opcjonalne</small>
              </fieldset>
              <fieldset class="form-group">
                <label for="week">Miesiąc</label>
                <input type="text" class="form-control" name="week" id="week" placeholder="Miesiąc">
              </fieldset>
              <fieldset class="form-group">
                <label for="video">Film</label>
                <input type="text" class="form-control" id="video" name="video" placeholder="Film">
              </fieldset>
              <fieldset class="form-group">
                <label for="phone">Treść</label>
                <textarea class="form-control" name="content"></textarea>
                <small class="form-text text-muted">Dopuszaczalne znaczniki: &#x3C;b&#x3E; &#x3C;u&#x3E; &#x3C;i&#x3E; &#x3C;ul&#x3E; &#x3C;ol&#x3E; &#x3C;li&#x3E;</small>
              </fieldset>
              <input type="hidden" name="id">
              <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            </form>
            <?php else:

              /* POST data */

              $name = $_POST['name'];
              $week = $_POST['week'];
              $video = $_POST['video'];
              $content = $_POST['content'];

              /* Get database settings */

              $database = json_decode(file_get_contents('database.json'));

              /* Create connection */

              $db = new mysqli($database->host, $database->user, $database->password, $database->name) ;
              $db->set_charset('utf8');

              if($db->connect_error)
              {
                die('Wystąpił błąd bazy danych ('.$db->connect_error.')');
              }

              /* Insert data */

              $sql = $db->prepare("INSERT INTO videos (name, week, video, content) VALUES (?,?,?,?)");
              $sql->bind_param('ssss', $name, $week, $video, $content);
              $sql->execute();

              $sql = $db->prepare("SELECT id FROM videos ORDER BY id DESC LIMIT 1");
              $sql->execute();
              $res = $sql->get_result();
              $ass = $res->fetch_assoc();
              $vid = $ass['id'];

              /* Update accesses */

              $sql = $db->prepare("SELECT id FROM users WHERE id IN (SELECT user_id FROM access WHERE video_id IN (SELECT id FROM videos WHERE week=?))");
              $sql->bind_param('s', $week);
              $sql->execute();
              $res = $sql->get_result();

              while($ass = $res->fetch_assoc())
              {
                $sql = $db->prepare("INSERT INTO access (user_id, video_id) VALUES (?,?)");
                $sql->bind_param('dd', $ass['id'], $vid);
                $sql->execute();
              }
              
              $sql->close();
              $db->close();

              header('Location: index.php');
              die();

            ?>
            <?php endif ?>
          </div>
        </div>
      </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

<?php

  session_start();

  if(isset($_SESSION['loggedin']) && isset($_SESSION['userid']))
  {
    if($_SESSION['loggedin'])
    {
      header('Location: index.php');
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
    <section id="login">
      <div class="container">
        <!-- Spacer -->
        <div class="w-100 py-4"></div>
        <!-- Spacer end -->
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <?php if(isset($_GET['h']) && !isset($_POST['password'])): ?>
            <form class="needs-validation" action method="POST" novalidate>
              <fieldset class="form-group">
                <label for="password">Hasło</label>
                <input type="password" required class="form-control" name="password" id="password" pattern="[a-zA-Z\u0060\u0031\u0032\u0033\u0034\u0035\u0036\u0037\u0038\u0039\u0030\u002D\u003D\u007E\u0021\u0040\u0023\u0024\u0025\u005E\u0026\u002A\u0028\u0029\u005F\u002B\u005B\u005D\u005C\u007B\u007D\u007C\u003B\u0027\u003A\u0022\u002C\u002E\u002F\u003C\u003E\u003F]{8,}" placeholder="Nowe hasło">
                <input type="hidden" required class="form-control" name="token" id="token" value="<?php echo $_GET['h']; ?>">
              </fieldset>
              <button type="submit" class="btn btn-primary">Wyślij</button>
            </form>
            <script>

              (function()
              {
                'use strict';
                window.addEventListener('load', function()
                {
                  var forms = document.getElementsByClassName('needs-validation');
                  var validation = Array.prototype.filter.call(forms, function(form)
                  {
                    form.addEventListener('submit', function(event)
                    {
                      if(form.checkValidity() === false)
                      {
                        event.preventDefault();
                        event.stopPropagation();
                      }

                      form.classList.add('was-validated');
                    }, false);
                  });
                }, false);
              })();

            </script>
            <?php else:

              /* POST data */

              $password = $_POST['password'];
              $token = $_POST['token'];

              /* Regex patterns */

              $password_pat = "/[a-zA-Z0-9~!@#\$%\^&\*\(\)_\+`\[\]\\\{\}\|;':\",\.\/<>\?]{8,}/";

              /* Validity flag */

              $valid = 0 ;

              /* Data check */

              $valid += preg_match($password_pat, $password) ? 0 : 2;

              if($valid == 0)
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

                $sql = $db->prepare("SELECT * FROM hashes WHERE token=?");
                $sql->bind_param('s', $token);
                $sql->execute();
                $res = $sql->get_result();
                $ass = $res->fetch_assoc();

                $x = $password;

                $password = password_hash($password, PASSWORD_DEFAULT);

                if(isset($ass['user_id']))
                {
                  $sql = $db->prepare("UPDATE users SET password=? WHERE id=?");
                  $sql->bind_param('sd', $password, $ass['user_id']);
                  $sql->execute();

                  $sql = $db->prepare("DELETE FROM hashes WHERE user_id=?");
                  $sql->bind_param('d', $ass['user_id']);
                  $sql->execute();
                }
                else
                {
                  echo '<div class="loginmsg">Błędne dane. Spróbuj ponownie</div>' ;
                }

                $sql->close();
                $db->close();

                header('Location: login.php');
              }
              else
              {
                echo 'Validation error, javascript disabled ('.$valid.')';
              }
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

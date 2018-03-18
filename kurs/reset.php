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
            <?php if(!isset($_POST['email'])): ?>
            <form class="needs-validation" action method="POST" novalidate>
              <fieldset class="form-group">
                <label for="email">Email</label>
                <input type="email" required class="form-control" name="email" id="email" pattern="[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{1,}" placeholder="Enter email">
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

              $email = $_POST['email'];

              /* Regex patterns */

              $email_pat = "/[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{1,}/";

              /* Validity flag */

              $valid = 0 ;

              /* Data check */

              $valid += preg_match($email_pat, $email) ? 0 : 1;

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

                $sql = $db->prepare("SELECT * FROM users WHERE email=?");
                $sql->bind_param('s', $email);
                $sql->execute();
                $res = $sql->get_result();
                $ass = $res->fetch_assoc();

                if(count($res) == 1)
                {
                  $token = bin2hex(random_bytes(64)).password_hash($ass['email'], PASSWORD_DEFAULT);
                  $sql = $db->prepare("INSERT INTO hashes (token, user_id) VALUES (?,?)");
                  $sql->bind_param('sd', $token, $ass['id']);
                  $sql->execute();

                  mail($ass['email'], 'Zmiana hasła', 'Aby zresetować hasło przejdź pod podany link: bizneso.eu/kurs/pass.php?h='.$token);
                  echo $ass['email'];
                }
                else
                {
                  echo '<div class="loginmsg">Błędny email. Spróbuj ponownie</div>' ;
                }

                $sql->close();
                $db->close();
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

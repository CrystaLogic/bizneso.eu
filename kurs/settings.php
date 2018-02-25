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
          <div class="col-lg-2">
            <img class="w-100" src="img/logo.png" alt="bizneso.eu">
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
            <?php if(!isset($_POST['name']) && !isset($_POST['address'])&& !isset($_POST['nip'])&& !isset($_POST['phone'])):

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

              $user_id = $_SESSION['userid'];

              $sql = $db->prepare("SELECT name, address, nip, phone FROM users WHERE id=?");
              $sql->bind_param('d', $user_id);
              $sql->execute();
              $res = $sql->get_result();
              $ass = $res->fetch_assoc();

              $name = $ass['name'] ;
              $address = $ass['address'] ;
              $nip = $ass['nip'] ;
              $phone = $ass['phone'] ;

              if($nip == 0) $nip = '';
              if($phone == 0) $phone = '';

              $sql->close();
              $db->close();

            ?>
            <form class="needs-validation" action method="POST" novalidate>
              <fieldset class="form-group">
                <label for="name">Imię i Nazwisko/Nazwa Firmy</label>
                <input value="<?php echo $name; ?>"type="text" class="form-control" name="name" id="name" pattern="[A-Za-z0-9\/\-\u0104\u0106\u0118\u0141\u0143\u00D3\u015A\u0179\u017B\u0105\u0107\u0119\u0142\u0144\u00F3\u015B\u017A\u017C\u0020\.]{1,}" placeholder="Imię i Nazwisko/Nazwa Firmy">
                <small class="form-text text-muted">Opcjonalne</small>
              </fieldset>
              <fieldset class="form-group">
                <label for="address">Adres</label>
                <input value="<?php echo $address; ?>"type="text" class="form-control" name="address" id="address" pattern="[A-Za-z0-9\/\-\u0104\u0106\u0118\u0141\u0143\u00D3\u015A\u0179\u017B\u0105\u0107\u0119\u0142\u0144\u00F3\u015B\u017A\u017C\u0020\.]{1,}" placeholder="Adres">
                <small class="form-text text-muted">Opcjonalne</small>
              </fieldset>
              <fieldset class="form-group">
                <label for="nip">NIP (pozostaw puste jeżeli zakładasz konto dla osoby fizycznej)</label>
                <input value="<?php echo $nip; ?>"type="text" class="form-control" name="nip" id="nip" pattern="[0-9]{10}" placeholder="NIP">
                <small class="form-text text-muted">Bez spacji/myślników (Opcjonalne)</small>
              </fieldset>
              <fieldset class="form-group">
                <label for="phone">Telefon</label>
                <input value="<?php echo $phone; ?>"type="tel" class="form-control" name="phone" id="phone" pattern="[0-9]{9}" placeholder="Telefon">
                <small class="form-text text-muted">Bez spacji/myślników (Opcjonalne)</small>
              </fieldset>
              <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
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

              $name = isset($_POST['name']) ? $_POST['name'] : '';
              $address = isset($_POST['address']) ? $_POST['address'] : '';
              $nip = isset($_POST['nip']) ? $_POST['nip'] : '';
              $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

              /* Regex patterns */

              $name_pat = "/[a-zA-Z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ \/\-\.]{0,}|^$/u" ;
              $address_pat = "/[a-zA-Z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ \/\-\.]{0,}|^$/u" ;
              $nip_pat = "/[0-9]{10}|^$/" ;
              $phone_pat = "/[0-9]{9}|^$/" ;

              /* Validity flag */

              $valid = 0 ;

              /* Data check */

              $valid += preg_match($name_pat, $name) ? 0 : 3;
              $valid += preg_match($address_pat, $address) ? 0 : 4;
              $valid += preg_match($nip_pat, $nip) ? 0 : 5;
              $valid += preg_match($phone_pat, $phone) ? 0 : 6;

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

                /* Insert data */

                $user_id = $_SESSION['userid'];

                $sql = $db->prepare("UPDATE users SET name=?, address=?, nip=?, phone=? WHERE id=?");
                $sql->bind_param('ssddd', $name, $address, $nip, $phone, $user_id);
                $sql->execute();

                $sql->close();
                $db->close();

                header('Location: settings.php');
                die();
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

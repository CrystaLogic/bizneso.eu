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
    <script src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
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
            <a href="login.php">
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
            <?php if(!isset($_POST['email']) && !isset($_POST['haslo'])): ?>
            <form id="register" class="needs-validation" action method="POST" novalidate>
              <fieldset class="form-group">
                <label for="email">Email</label>
                <input type="email" required class="form-control" name="email" id="email" pattern="[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{1,}" placeholder="Enter email">
                <small id="email_message" class="form-text text-muted"></small>
              </fieldset>
              <fieldset class="form-group">
                <label for="password">Hasło</label>
                <input type="password" required class="form-control" name="password" id="password" pattern="[a-zA-Z\u0060\u0031\u0032\u0033\u0034\u0035\u0036\u0037\u0038\u0039\u0030\u002D\u003D\u007E\u0021\u0040\u0023\u0024\u0025\u005E\u0026\u002A\u0028\u0029\u005F\u002B\u005B\u005D\u005C\u007B\u007D\u007C\u003B\u0027\u003A\u0022\u002C\u002E\u002F\u003C\u003E\u003F]{8,}" placeholder="Hasło">
                <small class="form-text text-muted">Minimum 8 znaków</small>
              </fieldset>
              <fieldset class="form-group">
                <label for="name">Imię i Nazwisko/Nazwa Firmy</label>
                <input type="text" class="form-control" name="name" id="name" pattern="[A-Za-z0-9\/\-\u0104\u0106\u0118\u0141\u0143\u00D3\u015A\u0179\u017B\u0105\u0107\u0119\u0142\u0144\u00F3\u015B\u017A\u017C\u0020\.]{1,}" placeholder="Imię i Nazwisko/Nazwa Firmy">
                <small class="form-text text-muted">Opcjonalne</small>
              </fieldset>
              <fieldset class="form-group">
                <label for="address">Adres</label>
                <input type="text" class="form-control" name="address" id="address" pattern="[A-Za-z0-9\/\-\u0104\u0106\u0118\u0141\u0143\u00D3\u015A\u0179\u017B\u0105\u0107\u0119\u0142\u0144\u00F3\u015B\u017A\u017C\u0020\.]{1,}" placeholder="Adres">
                <small class="form-text text-muted">Opcjonalne</small>
              </fieldset>
              <fieldset class="form-group">
                <label for="nip">NIP (pozostaw puste jeżeli zakładasz konto dla osoby fizycznej)</label>
                <input type="text" class="form-control" name="nip" id="nip" pattern="[0-9]{10}" placeholder="NIP">
                <small class="form-text text-muted">Bez spacji/myślników (Opcjonalne)</small>
              </fieldset>
              <fieldset class="form-group">
                <label for="phone">Telefon</label>
                <input type="tel" class="form-control" name="phone" id="phone" pattern="[0-9]{9}" placeholder="Telefon">
                <small class="form-text text-muted">Bez spacji/myślników (Opcjonalne)</small>
              </fieldset>
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" checked disabled id="reg">
                <label class="form-check-label" for="reg">Akceptuję <a href="../regulamin.html">Regulamin</a> i <a href="../polityka-prywatnosci.html">Politykę Prywatności</a></label>
              </div>
              <button id="sub" type="button" class="btn btn-primary">Zarejestruj się</button>
              <button id="sub2" type="submit" class="d-none btn btn-primary">Zarejestruj się</button>
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

                  /*$(function()
                  {*/
                    $('#sub').on('click', function()
                    {
                      $.ajax(
                      {
                        url: 'check-name.php',
                        type: 'POST',
                        data:
                        {
                          name: $('#email').val()
                        },
                        success: function(res)
                        {
                          if(res == 'avabile')
                          {
                            $('#sub2').click();
                          }
                          else
                          {
                            $('#email_message').html('Taki email znajduje się już w bazie danych');
                          }
                        }
                      });
                    });
                  /*});*/



                }, false);
              })();

            </script>
            <?php else:

              /* POST data */

              $email = $_POST['email'];
              $password = $_POST['password'];

              $name = isset($_POST['name']) ? $_POST['name'] : '';
              $address = isset($_POST['address']) ? $_POST['address'] : '';
              $nip = isset($_POST['nip']) ? $_POST['nip'] : '';
              $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

              /* Regex patterns */

              $email_pat = "/[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{1,}/";
              $password_pat = "/[a-zA-Z0-9~!@#\$%\^&\*\(\)_\+`\[\]\\\{\}\|;':\",\.\/<>\?]{8,}/";

              $name_pat = "/[a-zA-Z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ \/\-\.]{0,}|^$/u" ;
              $address_pat = "/[a-zA-Z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ \/\-\.]{0,}|^$/u" ;
              $nip_pat = "/[0-9]{10}|^$/" ;
              $phone_pat = "/[0-9]{9}|^$/" ;

              /* Validity flag */

              $valid = 0 ;

              /* Data check */

              $valid += preg_match($email_pat, $email) ? 0 : 1;
              $valid += preg_match($password_pat, $password) ? 0 : 2;
              $valid += preg_match($name_pat, $name) ? 0 : 3;
              $valid += preg_match($address_pat, $address) ? 0 : 4;
              $valid += preg_match($nip_pat, $nip) ? 0 : 5;
              $valid += preg_match($phone_pat, $phone) ? 0 : 6;

              /* Hash password */

              $password = password_hash($password, PASSWORD_DEFAULT);

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

                /* Check for existing email */

                $sql = $db->prepare("SELECT id FROM users WHERE email=?");
                $sql->bind_param('s', $email);
                $sql->execute();

                $res = $sql->get_result();
                $ass = $res->fetch_assoc();

                if(count($ass) == 0)
                {
                  /* Insert data */

                  $sql = $db->prepare("INSERT INTO users (email,password,name,address,nip,phone) VALUES (?,?,?,?,?,?)");
                  $sql->bind_param('ssssdd', $email, $password, $name, $address, $nip, $phone);
                  $sql->execute();

                  $sql->close();
                  $db->close();

                  header('Location: login.php');
                  die();
                }
                else
                {
                  echo 'Validation error, javascript disabled ('.$valid.')';
                }
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

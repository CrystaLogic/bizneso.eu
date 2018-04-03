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
                <a class="nav-link" href="new.php">Dodaj film</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="stat.php">Statystyki</a>
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
        <div class="row">
          <div class="col-lg-12 pt-5">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Email</th>
                  <th scope="col">Nazwa</th>
                  <th scope="col">Adres</th>
                  <th scope="col">NIP</th>
                  <th scope="col">Telefon</th>
                </tr>
              </thead>
              <tbody>
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

                /* Insert data */

                $sql = $db->prepare("SELECT id, email, name, address, nip, phone FROM users");
                $sql->execute();

                $res = $sql->get_result();

                while($ass = $res->fetch_assoc())
                {
                  echo
                  '<tr>
                    <th scope="row">'.$ass['id'].'</th>
                    <td>'.$ass['email'].'</td>
                    <td>'.$ass['name'].'</td>
                    <td>'.$ass['address'].'</td>
                    <td>'.$ass['nip'].'</td>
                    <td>'.$ass['phone'].'</td>
                  </tr>';
                }

                $sql->close();
                $db->close();

              ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

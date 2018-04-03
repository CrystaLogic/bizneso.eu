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
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = 'https://connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.12';
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <header id="main">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-2 col-md-2 col-sm-4 col-6 mx-auto py-3">
            <a href="index.php"><img class="w-100" src="img/logo.png" alt="bizneso.eu"></a>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-12 col-12">
            <ul class="nav justify-content-center">
              <?php

                if(isset($_SESSION['admin']))
                {
                  if($_SESSION['admin'])
                  {
                    echo '
                    <li class="nav-item">
                      <a class="nav-link" href="new.php">Dodaj film</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="stat.php">Statystyki</a>
                    </li>';
                  }
                  else
                  {
                    echo '<ul class="nav justify-content-center">
                      <li class="nav-item">
                        <a class="nav-link" href="../">Serwis 1</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link active" href="">Serwis 2</a>
                      </li>
                    </ul>';
                  }
                }

              ?>
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
        <div class="row mb-3 pt-5">
          <div class="col-lg-3 mx-auto">
            <iframe class="w-100" height="200" src="https://www.youtube.com/embed/L1uuEv-GaJc?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            <h5 class="text-center px-2 mt-3">Film wprowadzający</h5>
          </div>
        </div>
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

          $sql = $db->prepare("SELECT * FROM videos WHERE id IN (SELECT video_id FROM access WHERE user_id=?) ORDER BY week, id");
          $sql->bind_param('d', $_SESSION['userid']);
          $sql->execute();
          $res = $sql->get_result();

          if(isset($_SESSION['admin']))
          {
            if($_SESSION['admin'])
            {
              unset($sql);
              unset($res);

              $sql = $db->prepare("SELECT * FROM videos ORDER BY week, id");
              $sql->execute();
              $res = $sql->get_result();
            }
          }

          $count=0;

          while($ass = $res->fetch_assoc())
          {
            $videos['id'][] = $ass['id'];
            $videos['name'][] = $ass['name'];
            $videos['week'][] = $ass['week'];
            $videos['video'][] = $ass['video'];
            $count++;
          }

          for($i=0; $i<$count; $i++)
          {
            if($i != 0)
            {
              if($videos['week'][$i-1] == $videos['week'][$i])
              {
                echo '
                <div class="col-lg-3">';
                if($_SESSION['admin'])
                {
                  echo
                    '<a href="edit.php?v='.$videos['id'][$i].'">
                      <div class="video">
                        <img class="w-100" src="https://img.youtube.com/vi/'.$videos['video'][$i].'/0.jpg">
                        <i class="material-icons">play_arrow</i>
                      </div>
                    </a>
                    <a href="edit.php?v='.$videos['id'][$i].'">
                      <h5 class="text-center px-2 mt-3">'.$videos['name'][$i].'</h5>
                    </a>
                  </div>';
                }
                else
                {
                  echo
                    '<a href="video.php?v='.$videos['id'][$i].'">
                      <div class="video">
                        <img class="w-100" src="https://img.youtube.com/vi/'.$videos['video'][$i].'/0.jpg">
                        <i class="material-icons">play_arrow</i>
                      </div>
                    </a>
                    <a href="video.php?v='.$videos['id'][$i].'">
                      <h5 class="text-center px-2 mt-3">'.$videos['name'][$i].'</h5>
                    </a>
                  </div>';
                }
              }
              else
              {
                echo '
                </div>
                <div class="row mb-4">
                  <div class="col-lg-6">
                    <h4>'.$videos['week'][$i].'</h4>
                  </div>
                </div>
                <div class="row mb-5 pb-5">';
                echo '
                <div class="col-lg-3">';

                if($_SESSION['admin'])
                {
                  echo
                    '<a href="edit.php?v='.$videos['id'][$i].'">
                      <div class="video">
                        <img class="w-100" src="https://img.youtube.com/vi/'.$videos['video'][$i].'/0.jpg">
                        <i class="material-icons">play_arrow</i>
                      </div>
                    </a>
                    <a href="edit.php?v='.$videos['id'][$i].'">
                      <h5 class="text-center px-2 mt-3">'.$videos['name'][$i].'</h5>
                    </a>
                  </div>';
                }
                else
                {
                  echo
                    '<a href="video.php?v='.$videos['id'][$i].'">
                      <div class="video">
                        <img class="w-100" src="https://img.youtube.com/vi/'.$videos['video'][$i].'/0.jpg">
                        <i class="material-icons">play_arrow</i>
                      </div>
                    </a>
                    <a href="video.php?v='.$videos['id'][$i].'">
                      <h5 class="text-center px-2 mt-3">'.$videos['name'][$i].'</h5>
                    </a>
                  </div>';
                }
              }
            }
            else
            {
              echo '

              <div class="row mb-4">
                <div class="col-lg-6">
                  <h4>'.$videos['week'][$i].'</h4>
                </div>
              </div>
              <div class="row mb-5 pb-5">';
              echo '
              <div class="col-lg-3">';

              if($_SESSION['admin'])
              {
                echo
                  '<a href="edit.php?v='.$videos['id'][$i].'">
                    <div class="video">
                      <img class="w-100" src="https://img.youtube.com/vi/'.$videos['video'][$i].'/0.jpg">
                      <i class="material-icons">play_arrow</i>
                    </div>
                  </a>
                  <a href="edit.php?v='.$videos['id'][$i].'">
                    <h5 class="text-center px-2 mt-3">'.$videos['name'][$i].'</h5>
                  </a>
                </div>';
              }
              else
              {
                echo
                  '<a href="video.php?v='.$videos['id'][$i].'">
                    <div class="video">
                      <img class="w-100" src="https://img.youtube.com/vi/'.$videos['video'][$i].'/0.jpg">
                      <i class="material-icons">play_arrow</i>
                    </div>
                  </a>
                  <a href="video.php?v='.$videos['id'][$i].'">
                    <h5 class="text-center px-2 mt-3">'.$videos['name'][$i].'</h5>
                  </a>
                </div>';
              }
            }
          }

          $sql->close();
          $db->close();

          if($count != 0)
          {
            echo '</div>';
          }

        ?>
        <!--<div class="row mb-4">
          <div class="col-lg-6">
            <h4>Styczeń</h4>
          </div>
          <div class="col-lg-6 d-flex justify-content-end">
            <button type="button" class="btn btn-primary">Wykup dostęp</button>
          </div>
        </div>
        <div class="row mb-5 pb-5">
          <div class="col-lg-3">
            <a href="#">
              <div class="video">
                <img class="w-100" src="img/bg.jpg">
                <i class="material-icons">play_arrow</i>
              </div>
            </a>
            <a href="#">
              <h5 class="text-center px-2 mt-3">Lorem ipsum dolor sit amet</h5>
            </a>
          </div>
          <div class="col-lg-3">
            <a href="#">
              <div class="video">
                <img class="w-100" src="img/bg.jpg">
                <i class="material-icons">play_arrow</i>
              </div>
            </a>
            <a href="#">
              <h5 class="text-center px-2 mt-3">Lorem ipsum dolor sit amet</h5>
            </a>
          </div>
          <div class="col-lg-3">
            <a href="#">
              <div class="video">
                <img class="w-100" src="img/bg.jpg">
                <i class="material-icons">play_arrow</i>
              </div>
            </a>
            <a href="#">
              <h5 class="text-center px-2 mt-3">Lorem ipsum dolor sit amet</h5>
            </a>
          </div>
          <div class="col-lg-3">
            <a href="#">
              <div class="video">
                <img class="w-100" src="img/bg.jpg">
                <i class="material-icons">play_arrow</i>
              </div>
            </a>
            <a href="#">
              <h5 class="text-center px-2 mt-3">Lorem ipsum dolor sit amet</h5>
            </a>
          </div>
        </div>-->
        <?php

          if(!$_SESSION['admin'])
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

            $sql = $db->prepare("SELECT week FROM videos WHERE id NOT IN (SELECT video_id FROM access WHERE user_id=?) GROUP BY week");
            $sql->bind_param('d', $_SESSION['userid']);
            $sql->execute();
            $res = $sql->get_result();

            while($ass = $res->fetch_assoc())
            {
              echo '
              <div class="row mb-4">
                <div class="col-lg-6">
                  <h4>'.$ass['week'].'</h4>
                </div>
                <div class="col-lg-6 d-flex justify-content-end">
                  <a href="buy.php?w='.$ass['week'].'"><button type="button" class="btn btn-primary">Wykup dostęp</button></a>
                </div>
              </div>
              <div class="row mb-5 pb-5 disabled">';

              $sql2 = $db->prepare("SELECT * FROM videos WHERE week=?");
              $sql2->bind_param('s', $ass['week']);
              $sql2->execute();
              $res2 = $sql2->get_result();

              while($ass2 = $res2->fetch_assoc())
              {
                echo '
                <div class="col-lg-3">
                  <a>
                    <div class="video">
                      <img class="w-100" src="https://img.youtube.com/vi/'.$ass2['video'].'/0.jpg">
                      <i class="material-icons">play_arrow</i>
                    </div>
                  </a>
                  <a>
                    <h5 class="text-center px-2 mt-3">'.$ass2['name'].'</h5>
                  </a>
                </div>';
              }

              echo '</div>';

              /*echo '
              <li class="nav-item">
                <a class="nav-link active" href="buy.php?w='.$ass['week'].'">'.$ass['week'].'</a>
              </li>
              ';*/
            }

            $sql->close();
            $sql2->close();
            $db->close();
          }

        ?>
      </div>
    </section>
    <section id="footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 mx-auto d-flex justify-content-center">
            <div class="fb-page" data-href="https://web.facebook.com/Tissu-design-244543962700930/" data-tabs="timeline" data-width="500" data-height="250" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="text-center">
          <ul class="nav justify-content-center mt-3">
            <li class="nav-item"><a class="nav-link active" href="https://facebook.com">Grupa FB</a></li>
            <li class="nav-item"><a class="nav-link active" href="../program-indywidualny.html">Oferta Specjalna</a></li>
            <li class="nav-item"><a class="nav-link" href="../regulamin.html">Regulamin</a></li>
            <li class="nav-item"><a class="nav-link" href="../polityka-prywatnosci.html">Polityka Prywatności</a></li>
            <li class="nav-item"><a class="nav-link" href="../platnosci.html">Płatności</a></li>
            <li class="nav-item"><a class="nav-link" href="../index.html#contact">Wsparcie</a></li>
          </ul>
        </div>
        <div class="copyright">Copyright © 2018 bizneso.eu - biznes i marketing</div>
      </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

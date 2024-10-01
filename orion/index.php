<!DOCTYPE html>
<html lang="pt-br">
<head>
      <script src="https://kit.fontawesome.com/d4568edc1e.js" crossorigin="anonymous"></script>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Projeto orion</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
      <?php
      include_once('assets/includes/dbc.inc.php');
      include_once('assets/includes/session.inc.php');
      ?>

      <header>
            <div>
                  <nav>
                        <a href="#" id="logo"><img src="assets/images/20220322_141044_0000.png"></a>
                        <a href="#">Inicio</a>
                        <a href="#">Catálogo</a>
                        <a href="#">Sobre nós</a>
                        <a href="#">Contato</a>
                  </nav>
                  <div class="search-bar">
                        <input type="text" id="searchInput" placeholder="Pesquisar...">
                        <button id="searchButton"><i class="fa-solid fa-magnifying-glass"></i></button>
                  </div>
            </div>
      </header>

      <div id="carouselExampleIndicators" class="carousel carousel-dark slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="https://i0.wp.com/tsundoku.com.br/wp-content/uploads/2021/12/chihara.jpg" class="d-block w-100" alt="...">
              </div>
              <div class="carousel-item">
                  <img src="https://i0.wp.com/tsundoku.com.br/wp-content/uploads/2021/12/chihara.jpg" class="d-block w-100" alt="...">
              </div>
              <div class="carousel-item">
                  <img src="https://i0.wp.com/tsundoku.com.br/wp-content/uploads/2021/12/chihara.jpg" class="d-block w-100" alt="...">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>

      <main>
            <div class="wrapper">
                  <div class="mainBody">
                        <div class="novelBox">
                              <div class="title">Recomendados</div>
                              <div class="content">
                                    <div class="novels">
                                          <?php
                                          $query = "select novels.id as n_id, title, description, thumbnail, novels.creation_date as cd, users.id as a_id, users.tag as author from novels join users on users.id = novels.author_id;";
                                          $cmd = $db->prepare($query);
                                          $cmd->execute();
                                  
                                          if($cmd->rowCount() > 0){
                                              for($i = 0; $curNovel = $cmd->fetch(PDO::FETCH_ASSOC); $i++){
                                                $link = "novel/read?s=" . $curNovel["n_id"];
                                                echo "<div class='novel'>";
                                                echo "<a class='thumbnail' href='" . $link . "'><img src='assets/images/novel/thumbnail/" . $curNovel['thumbnail'] . "'></a>";

                                                echo "<div class='info'>";
                                                echo "<a id='title' href='" . $link . "'>" . $curNovel['title'] . "</a>";
                                                //echo "<div id='time'><span>Vol.1 - Cap. 01</span> <a>há 4 dias</a></div>";
                                                echo "</div>";

                                                echo "</div>";
                                              }
                                          } else {
                                              echo"Nenhuma visual novel encontrada...";
                                          }
                                          ?>
                                    </div>
                              </div>
                        </div>

                        <div class="novelBox">
                              <div class="title">Ultimas Atualizações</div>
                              <div class="content">
                                    <div class="novels">
                                          <div class="novel">
                                                <a class="thumbnail" href="#">
                                                      <img src="assets/images/capa.png">
                                                      <!--<div class="pin hot"><i class="bi bi-fire"></i></div>-->
                                                </a>
                                                <div class="info">
                                                      <a id="title" href="#">Rancor de Ferro</a>
                                                      <div id="time"><span>Vol.1 - Cap. 01</span> <a>há 4 dias</a></div>
                                                </div>
                                          </div>

                                          <div class="novel">
                                                <a class="thumbnail" href="#">
                                                      <img src="assets/images/capa.png">
                                                      <!--<div class="pin hot"><i class="bi bi-fire"></i></div>-->
                                                </a>
                                                <div class="info">
                                                      <a id="title" href="#">Rancor de Ferro</a>
                                                      <div id="time"><span>Vol.1 - Cap. 01</span> <a>há 4 dias</a></div>
                                                </div>
                                          </div>

                                          <div class="novel">
                                                <a class="thumbnail" href="#">
                                                      <img src="assets/images/capa.png">
                                                      <!--<div class="pin hot"><i class="bi bi-fire"></i></div>-->
                                                </a>
                                                <div class="info">
                                                      <a id="title" href="#">Rancor de Ferro</a>
                                                      <div id="time"><span>Vol.1 - Cap. 01</span> <a>há 4 dias</a></div>
                                                </div>
                                          </div>

                                          <div class="novel">
                                                <a class="thumbnail" href="#">
                                                      <img src="assets/images/capa.png">
                                                      <!--<div class="pin hot"><i class="bi bi-fire"></i></div>-->
                                                </a>
                                                <div class="info">
                                                      <a id="title" href="#">Rancor de Ferro</a>
                                                      <div id="time"><span>Vol.1 - Cap. 01</span> <a>há 4 dias</a></div>
                                                </div>
                                          </div>

                                          <div class="novel">
                                                <a class="thumbnail" href="#">
                                                      <img src="assets/images/capa.png">
                                                      <!--<div class="pin hot"><i class="bi bi-fire"></i></div>-->
                                                </a>
                                                <div class="info">
                                                      <a id="title" href="#">Rancor de Ferro</a>
                                                      <div id="time"><span>Vol.1 - Cap. 01</span> <a>há 4 dias</a></div>
                                                </div>
                                          </div>

                                          <div class="novel">
                                                <a class="thumbnail" href="#">
                                                      <img src="assets/images/capa.png">
                                                      <!--<div class="pin hot"><i class="bi bi-fire"></i></div>-->
                                                </a>
                                                <div class="info">
                                                      <a id="title" href="#">Rancor de Ferro</a>
                                                      <div id="time"><span>Vol.1 - Cap. 01</span> <a>há 4 dias</a></div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>

                        <div class="novelBox">
                              <div class="title">Recomendação por Gênero</div>
                              <div class="content">
                                    <div class="novels">
                                          <div class="pick">
                                                <button class="active">Ação</button>
                                                <button>Aventura</button>
                                                <button>Horror</button>
                                                <button>Suspense</button>
                                                <button>Comédia</button>
                                          </div>

                                          <div class="novel">
                                                <a class="thumbnail" href="#">
                                                      <img src="assets/images/capa.png">
                                                      <!--<div class="pin hot"><i class="bi bi-fire"></i></div>-->
                                                </a>
                                                <div class="info">
                                                      <a id="title" href="#">Rancor de Ferro</a>
                                                      <div id="time"><span>Vol.1 - Cap. 01</span> <a>há 4 dias</a></div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
      
                  <div class="sideBody">
                        <div class="novelBox">
                              <div class="title">Populares</div>
                              <div class="content">
                                    <div class="novels">
                                          <div class="pick">
                                                <button class="active">Semanalmente</button>
                                                <button>Mensalmente</button>
                                                <button>Tudo</button>
                                          </div>

                                          <div class="novel">
                                                <a class="thumbnail" href="#"><img src="assets/images/capa.png"></a>
                                                <div class="info">
                                                      <a id="title" href="#">Rancor de Ferro</a>
                                                      <div id="genre"><span>Gênero:</span> <a>Ação, Aventura.</a></div>
                                                </div>
                                          </div>

                                          <div class="novel">
                                                <a class="thumbnail" href="#"><img src="assets/images/capa.png"></a>
                                                <div class="info">
                                                      <a id="title" href="#">Rancor de Ferro</a>
                                                      <div id="genre"><span>Gênero:</span> <a>Ação, Aventura.</a></div>
                                                </div>
                                          </div>

                                          <div class="novel">
                                                <a class="thumbnail" href="#"><img src="assets/images/capa.png"></a>
                                                <div class="info">
                                                      <a id="title" href="#">Rancor de Ferro</a>
                                                      <div id="genre"><span>Gênero:</span> <a>Ação, Aventura.</a></div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>

                        <div class="novelBox">
                              <div class="title">Recentes</div>
                              <div class="content">
                                    <div class="novels">
                                          <div class="novel">
                                                <a class="thumbnail" href="#"><img src="assets/images/capa.png"></a>
                                                <div class="info">
                                                      <a id="title" href="#">Rancor de Ferro</a>
                                                      <div id="genre"><span>Gênero:</span> <a>Ação, Aventura.</a></div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </main>

      <footer>
            <div class="socials">
                  <a href="#"><i class="bi bi-discord"></i> Discord</a>
            </div>
            <div class="copyright">
                  Todos direitos reservados | someoneiguess 2024-2024
            </div>
      </footer>
      
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
      <script src="assets/js/script.js"></script>
      <script src="assets/js/main.js"></script>
</body>
</html>
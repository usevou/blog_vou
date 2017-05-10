<?php
  include_once "../conn.php";

  if ( !isset($_SESSION["usuario"]) ){
    header('Location: index.php');
  } else {
    $sql = "SELECT * FROM autor WHERE idautor = ".$_SESSION["usuario"];
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($res);
  }
?>
<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
  		<meta name="description" content="" />
  		<meta name="keywords" content="">
  		<meta name="author" content="Gustavo Oldenburg">
  		<link rel="shortcut icon" type="image/png" href="../imgs/sets/favicon.png"/>
  		<title>Área restrita</title>
  		<script>document.write('<base href="' + document.location + '" />');</script>
      <link rel="stylesheet" type="text/css" href="../css/adm.css">
      <link rel="stylesheet" type="text/css" href="../css/sweetalert.css">
      <script type="text/javascript" src="../js/jquery.min.js"></script>
      <script type="text/javascript" src="../js/sweetalert.min.js"></script>
    </head>
    <body>
      <header>
        <a href="http://blog.usevou.com"><div id="logo_topo"><img src="../imgs/sets/logo.png"></div></a>
        <?php
          if ( $row['idautor'] == 1 ){
        ?>
        <nav>
          <ul>
            <a href="?tab=postagem"><li id="post">Postagens</li></a>
            <a href="?tab=autores"><li id="aut">Autores</li></a>
            <a href="?tab=tags"><li id="tags">Tags</li></a>
          </ul>
        </nav>
        <?php
          }
        ?>
        <div id="profile_topo">
          <div id="ft_topo" style="background-image:url('../imgs/images/profile/<?php echo $row["foto"]; ?>.png')"></div>
          <div id="name_profile"><?php echo $row["nome"]; ?></div>
        </div>
        <div id="opc_profile">
          <ul>
            <a href="?tab=configuracoes"><li>Configurações</li></a>
            <a href="?tab=logout"><li>Sair</li></a>
          </ul>
        </div>
      </header>
      <section id="conteudo">
        <?php
          if ( isset($_GET["tab"]) ){
            if ( $_GET["tab"] == 'logout' ){
              session_destroy();
              header("Location: index.php");
            }
            if ( $row["idautor"] != 1 && $_GET["tab"] != "postagem" ){
              header("Location: home.php?tab=postagem");
            }
            include_once $_GET["tab"].".php";
          } else {
            include_once "postagem.php";
          }
        ?>
      </section>
      <script type="text/javascript" src="../js/adm.js"></script>
    </body>
</html>

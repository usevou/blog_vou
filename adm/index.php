<?php
  include_once "../conn.php";

  if ( isset($_POST["usuario"]) ){
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];
    $erro = 0;
  }
  if ( isset($_SESSION["usuario"]) ){
    header('Location: home.php');
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
    </head>
    <body>
      <header>
        <a href="http://blog.usevou.com"><div id="logo_topo"><img src="../imgs/sets/logo.png"></div></a>
      </header>
      <section id="login">
        <div id="logo"><img src="../imgs/sets/logo_footer.png"></div>
        <form method="post">
          <?php
            if ( isset($_POST["usuario"]) ){
              if ( $usuario == '' ){
                $erro++;
                echo '<input type="text" name="usuario" placeholder="Usuário"><div class="erro">* Digite seu nome de usuário.</div>';
              } else {
                echo '<input type="text" name="usuario" placeholder="Usuário" value="'.$usuario.'">';
              }
            } else {
              echo '<input type="text" name="usuario" placeholder="Usuário">';
            }
          ?>
          <input type="password" name="senha"  placeholder="Senha">
          <?php
            if ( isset($_POST["usuario"]) ){
              if ( $senha == '' ){
                $erro++;
                echo "<div class='erro'>* Digite sua senha.</div>";
              }

              if ( $erro == 0 ){

                $sql = "SELECT * FROM autor WHERE login = '".$usuario."'";
                $res = mysqli_query($conn, $sql);

                if ( mysqli_num_rows($res) > 0 ){
                  $row = mysqli_fetch_array($res);
                  if ( password_verify($senha,$row['senha']) ){
                    $_SESSION["usuario"] = $row["idautor"];
                    header('Location: home.php');
                  } else {
                    echo "<div class='erro'>* Usuário e/ou senha inválidos.</div>";
                  }
                } else {
                  echo "<div class='erro'>* Usuário e/ou senha inválidos.</div>";
                }
              }
            }
          ?>
          <input type="submit" value="Entrar">
        </form>
      </section>
      <script type="text/javascript" src="../js/jquery.min.js"></script>
      <script type="text/javascript">$('#login').css('margin-top','calc(25% - '+($('#login').height()/2)+'px)');</script>
    </body>
</html>

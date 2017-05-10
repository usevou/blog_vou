<?php
  include_once "conn.php";
?>
<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
  		<meta name="description" content="" />
  		<meta name="keywords" content="">
  		<meta name="author" content="Gustavo Oldenburg">
  		<link rel="shortcut icon" type="image/png" href="imgs/sets/favicon.png"/>
  		<title>Vou</title>
  		<script>document.write('<base href="' + document.location + '" />');</script>
      <link rel="stylesheet" type="text/css" href="css/index.css">
    </head>
    <body>
      <header>
        <a href="http://blog.usevou.com"><div id="logo_topo"><img src="imgs/sets/logo.png"></div></a>
        <div id="pesquisa">
          <div id="btn_pesq"><img src="imgs/sets/pesq.png"></div>
          <div id="input_pesq"><input type="text" name="pesq" id="pesq" placeholder="Pesquisar"></div>
        </div>
      </header>
      <div class="btn_tags" id="back" style="left:16px;"><img src="imgs/sets/back.png"></div>
      <section id="tags">
        <div id="content_tags">
          <?php
            $sql_tags = "SELECT * FROM tag";
            $res_tags = mysqli_query($conn,$sql_tags);
            if ( mysqli_num_rows($res_tags) > 0 ){
              while($row_tags = mysqli_fetch_array($res_tags)){
                echo '<div class="tag" onclick="pesqTag('.$row_tags["idtag"].')">#'.$row_tags["nome"].'</div>';
              }
            }
          ?>
        </div>
      </section>
      <div class="btn_tags" id="next" style="right:16px;"><img src="imgs/sets/next.png"></div>
      <section id="feed"></section>
      <div id="btn_more">Carregar mais postagens</div>
      <footer>
        <div id="rodape">
          <div id="social">
            <img src="imgs/sets/logo_footer.png">
          </div>
          <div id="copyright">Copyright © Vou - 2016</div>
        </div>
      </footer>
      <script type="text/javascript" src="js/jquery.min.js"></script>
      <script type="text/javascript" src="js/index.js"></script>
    </body>
</html>

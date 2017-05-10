<?php
  include_once "conn.php";

  if ( isset($_GET["id"]) ){
    $id = $_GET["id"];
    $sql_post = "SELECT *, concat(day(dt_hr), ' de ',
            if(Month(dt_hr)=1,'janeiro',
            if(Month(dt_hr)=2,'fevereiro',
            if(Month(dt_hr)=3,'março',
            if(Month(dt_hr)=4,'abril',
            if(Month(dt_hr)=5,'maio',
            if(Month(dt_hr)=6,'junho',
            if(Month(dt_hr)=7,'julho',
            if(Month(dt_hr)=8,'agosto',
            if(Month(dt_hr)=9,'setembro',
            if(Month(dt_hr)=10,'outubro',
            if(Month(dt_hr)=11,'novembro','dezembro'))))))))))),' de ',
            Year(dt_hr), ' às ', DATE_FORMAT(dt_hr,'%H:%i')) as data FROM postagem INNER JOIN autor ON autor.idautor = postagem.idautor WHERE idpostagem = ".$id;
    $res_post = mysqli_query($conn, $sql_post);
    $row_post = mysqli_fetch_array($res_post);
  } else {
    header('Location: index.php');
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
  		<link rel="shortcut icon" type="image/png" href="imgs/sets/favicon.png"/>
  		<title>Vou</title>
  		<script>document.write('<base href="' + document.location + '" />');</script>
      <meta property="og:url"           content="http://blog.usevou.com/post.php?id=<?php echo $id; ?>" />
      <meta property="og:type"          content="website" />
      <meta property="og:title"         content="<?php echo $row_post["titulo"]; ?>" />
      <meta property="og:description"   content="[Description of page]" />
      <meta property="og:image"         content="http://blog.usevou.com/imgs/images/fundo.jpg" />
      <link rel="stylesheet" type="text/css" href="css/article.css">
    </head>
    <body>
      <header>
        <a href="http://blog.usevou.com"><div id="logo_topo"><img src="imgs/sets/logo.png"></div></a>
      </header>
      <section id="topo" <?php echo 'style="background-image:url(\''.$row_post["img_principal"].'\')"'; ?>>
        <div id="fundo_topo">
          <div id="content_topo">
            <div id="title"><?php echo $row_post["titulo"]; ?></div>
            <div id="date"><?php echo $row_post["data"]; ?></div>
            <div id="autor">por <?php echo $row_post["nome"]; ?></div>
          </div>
        </div>
        <a href="#texto" class="scroll"><div id="arrow"><img src="imgs/sets/arrow.png"></div></a>
      </section>
      <section id="texto">
        <article id="conteudo">
          <?php echo $row_post["texto"]; ?>
        </article>
      </section>
      <section id="comentario">
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.8&appId=380223525703372";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        <div class="fb-like" data-href="https://blog.usevou.com/teste.php" data-layout="standard" data-action="like" data-size="large" data-show-faces="true" data-share="true"></div>
        <div class="fb-comments" data-href="http://blog.usevou.com/teste.php" data-numposts="5" id="coment" width="100%"></div>
      </section>
      <footer>
        <div id="rodape">
          <div id="social">
            <img src="imgs/sets/logo_footer.png">
          </div>
          <div id="copyright">Copyright © Vou - 2016</div>
        </div>
      </footer>
      <script type="text/javascript" src="js/jquery.min.js"></script>
      <script type="text/javascript" src="js/article.js"></script>
    </body>
</html>

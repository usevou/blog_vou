<?php
  include_once "../conn.php";

  if ( !isset($_SESSION["usuario"]) ){
    header('Location: index.php');
  } else {
    $sql = "SELECT * FROM autor WHERE idautor = ".$_SESSION["usuario"];
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($res);
  }

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
    if ( $row["idautor"] != $row_post["idautor"] && $row["idautor"] != 1 ){
      header('Location: home.php');
    }
  }
?>
<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
  		<meta name="author" content="Gustavo Oldenburg">
  		<link rel="shortcut icon" type="image/png" href="../imgs/sets/favicon.png"/>
  		<title>Vou</title>
  		<script>document.write('<base href="' + document.location + '" />');</script>
      <script src="../js/tinymce/tinymce.min.js?apiKey=jkn4amjhkspahbeg5dptorrcses9lpykmcaj7rl02q62nnk1"></script>
      <script>
        tinymce.init({
          selector: 'textarea',
          plugins: [
              "advlist autolink lists link image charmap print preview anchor",
              "searchreplace visualblocks code fullscreen",
              "insertdatetime media table contextmenu paste imagetools"
          ],
          toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
          content_css: [
            '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
            '//www.tinymce.com/css/codepen.min.css'
          ]
        });
      </script>
      <script type="text/javascript" src="../js/jquery.min.js"></script>
      <script type="text/javascript" src="../js/sweetalert.min.js"></script>
      <link rel="stylesheet" type="text/css" href="../css/sweetalert.css">
      <link rel="stylesheet" type="text/css" href="../css/article.css">
    </head>
    <body>
      <?php
        if ( $row["idautor"] != 1 ){
      ?>
      <form enctype="multipart/form-data" method="POST">
        <header>
          <a href="http://blog.usevou.com"><div id="logo_topo"><img src="../imgs/sets/logo.png"></div></a>
          <input type="submit" value="Salvar">
          <input type="button" id="cancel" value="Voltar">
        </header>
        <section id="topo"
        <?php
          if(isset($_FILES["foto"])){
            $erro = 0;
            $msg = "";
            date_default_timezone_set('America/Sao_Paulo');
            $date = date('YmdHi');
            $data = 'IMG'.$date;
            $uploadfile = 'imgs/images/posts/' . $data . '.png';

            if ( $_FILES["foto"]["error"] == 0 ){
              if (move_uploaded_file($_FILES["foto"]['tmp_name'], '../'.$uploadfile)) {
                echo "style='background-image:url(\"../".$uploadfile."\")'";
              } else {
                $erro++;
                $msg .= "Ocorreu um erro com a imagem.<br>";
              }
            } else if(isset($_GET["id"])){
              $uploadfile = $row_post["img_principal"];
              echo "style='background-image:url(\"../".$row_post["img_principal"]."\")'";
            } else {
              $erro++;
              $msg .= "Selecione uma foto para a postagem.<br>";
            }
          } else if(isset($_GET["id"])){
            echo "style='background-image:url(\"../".$row_post["img_principal"]."\")'";
          }
        ?>>
          <div id="fundo_topo">
            <div id="label"><label for="input_file">Selecionar foto da postagem</label></div>
            <input type="file" name="foto" id="input_file" accept="image/*">
            <div id="content_topo">
              <div id="title">
                <input type="text" name="titulo" placeholder="Título" autocomplete="off"
                <?php
                  if(isset($_POST["titulo"])){
                    if ( $_POST["titulo"] != "" ){
                      echo "value='".$_POST["titulo"]."'";
                    } else {
                      $erro++;
                      $msg .= "Escolha um título para a postagem.<br>";
                    }
                  } else if(isset($_GET["id"])){
                    echo "value='".$row_post["titulo"]."'";
                  }
                ?>
                >
              </div>
              <?php
                if(isset($_GET["id"])){
                  echo "<div id='date'>".$row_post["data"]."</div>";
                }
              ?>
              <div id="autor">por <?php echo $row["nome"]; ?></div>
            </div>
          </div>
          <a href="#texto" class="scroll"><div id="arrow"><img src="../imgs/sets/arrow.png"></div></a>
        </section>
        <section id="texto">
          <article id="conteudo">
            <textarea name="txt" placeholder="Digite o texto da postagem"><?php
              if(isset($_POST["txt"])){
                if ( $_POST["txt"] != "" ){
                  echo $_POST["txt"];
                } else {
                  $erro++;
                  $msg .= "Digite o conteúdo da postagem.<br>";
                }
              } else if(isset($_GET["id"])){
                echo $row_post["texto"];
              }
            ?></textarea>

          </article>
        </section>
        <section id="tags">
          <div id="content_tags">
            <div id="title_tags">Tags</div>
            <input type="text" id="input_tag" list="tagsname" autocomplete="off">
            <datalist id="tagsname">
              <?php
                $sql_tags = "SELECT * FROM tag";
                $res_tags = mysqli_query($conn,$sql_tags);
                if ( mysqli_num_rows($res_tags)  > 0 ){
                  while($row_tags = mysqli_fetch_array($res_tags)){
                    echo '<option id="add_tag'.$row_tags["idtag"].'" value="'.$row_tags["nome"].'">';
                  }
                }
              ?>
            </datalist>
            <div id="btn_add_tag">Adicionar</div>
            <input type="hidden" name="tags" id="hidden_tags">
            <div id="quadro_tags">
              <?php
                if(isset($_POST["tags"])){
                  if ( $_POST["tags"] != "" ){
                    $tags = $_POST["tags"];
                    $partes = explode(",", $tags);
                    $num = substr_count($tags, ',');
                    for ( $i=1;$i<$num;$i++ ){
                      echo "<div class='tag' id='tag".$partes[$i]."'></div>
                      <script>
                        var nome = $('#add_tag".$partes[$i]."').attr('value');
                        $('#tag".$partes[$i]."').html('#'+nome+'<div class=\"opc_tag\"><img src=\"../imgs/sets/excluir.png\" onclick=\"removeTag(".$partes[$i].",\''+nome+'\')\"></div>');
                        $('#add_tag".$partes[$i]."').remove();
                        $('#hidden_tags').val('".$tags."');
                      </script>";
                    }
                  } else {
                    $erro++;
                    $msg .= "Escolha algumas tags para facilitar a busca pela postagem.<br>";
                  }

                  if ( $erro == 0 ){
                    $titulo = $_POST["titulo"];
                    $foto = $uploadfile;
                    $texto = $_POST["txt"];
                    $tags = $_POST["tags"];

                    if ( isset($_GET["id"]) ){
                      $sql_p = "UPDATE postagem SET titulo = '".$titulo."', img_principal = '".$foto."', texto = '".$texto."' WHERE idpostagem = ".$_GET["id"];
                      $res_p = mysqli_query($conn,$sql_p);
                      if ( $res_p ){
                        $sql_t = "DELETE FROM post_tag WHERE idpostagem = ".$_GET["id"];
                        $res_t = mysqli_query($conn,$sql_t);
                        if ( $res_t ){
                          $partes = explode(",", $tags);
                          $num = substr_count($tags, ',');
                          $ok = 0;
                          for ( $i=1;$i<$num;$i++ ){
                            $sql_tag = "INSERT INTO post_tag VALUES(null,".$_GET["id"].",".$partes[$i].")";
                            $res_tag = mysqli_query($conn,$sql_tag);
                            $ok++;
                          }
                          if ( $ok == ($num - 1) ){
                            echo "<script>swal('Concluído!','Alteração concluída com sucesso.','success')</script>";
                          } else {
                            echo "<script>swal('Erro!','Ocorreu um erro durante a alteração.','error')</script>";
                          }
                        } else {
                          echo "<script>swal('Erro!','Ocorreu um erro durante a alteração.','error')</script>";
                        }
                      } else {
                        echo "<script>swal('Erro!','Ocorreu um erro durante a alteração.','error')</script>";
                      }
                    } else {
                      date_default_timezone_set('America/Sao_Paulo');
                      $date = date('Y-m-d H:i');
                      $sql_p = "INSERT INTO postagem VALUES(null,".$row["idautor"].", '".$titulo."', '".$texto."', '".$foto."','".$date."')";
                      $res_p = mysqli_query($conn,$sql_p);
                      if ( $res_p ){
                        $sql_t = "SELECT idpostagem FROM postagem WHERE dt_hr = '".$date."' AND titulo = '".$titulo."'";
                        $res_t = mysqli_query($conn,$sql_t);
                        if ( mysqli_num_rows($res_t) > 0 ){
                          $row_t = mysqli_fetch_array($res_t);
                          $partes = explode(",", $tags);
                          $num = substr_count($tags, ',');
                          $ok = 0;
                          for ( $i=1;$i<$num;$i++ ){
                            $sql_tag = "INSERT INTO post_tag VALUES(null,".$row_t["idpostagem"].",".$partes[$i].")";
                            $res_tag = mysqli_query($conn,$sql_tag);
                            $ok++;
                          }
                          if ( $ok == ($num - 1) ){
                            echo "<script>swal('Concluído!','Cadastro concluído com sucesso.','success')</script>";
                            header('post.php?id='.$row_t["idpostagem"]);
                          } else {
                            echo "<script>swal('Erro!','Ocorreu um erro durante a alteração.','error')</script>";
                          }
                        } else {
                          echo "<script>swal('Erro!','Ocorreu um erro durante a alteração.','error')</script>";
                        }
                      } else {
                        echo "<script>swal('Erro!','Ocorreu um erro durante a alteração.','error')</script>";
                      }
                    }
                  } else {
                    echo "<script>swal({title:'Erro!',text:'Abaixo segue alguns requisitos que não foram cumpridos:<br>".$msg."',type:'error',html:true})</script>";
                  }
                } else if ( isset($_GET["id"]) ){
                  $sql_pt = "SELECT tag.nome, tag.idtag FROM tag INNER JOIN post_tag ON post_tag.idtag = tag.idtag INNER JOIN postagem ON postagem.idpostagem = post_tag.idpostagem WHERE postagem.idpostagem = ".$id;
                  $res_pt = mysqli_query($conn,$sql_pt);
                  if ( mysqli_num_rows($res_pt) > 0 ){
                    while($row_pt = mysqli_fetch_array($res_pt)){
                      echo "<div class='tag' id='tag".$row_pt["idtag"]."'>#".$row_pt["nome"]."
                        <div class='opc_tag'>
                          <img src='../imgs/sets/excluir.png' onclick='removeTag(".$row_pt["idtag"].",\"".$row_pt["nome"]."\")'>
                        </div>
                      </div>
                      <script>
                        $('#add_tag".$row_pt["idtag"]."').remove();
                        if ( $('#hidden_tags').val() == '' ){
              						$('#hidden_tags').val(',".$row_pt["idtag"].",');
              					} else {
              						$('#hidden_tags').val($('#hidden_tags').val() + '".$row_pt["idtag"].",')
              					}
                      </script>";
                    }
                  }
                }
              ?>
            </div>
          </div>
        </section>
      </form>
      <?php
        } else {
      ?>
        <header>
          <a href="http://blog.usevou.com"><div id="logo_topo"><img src="../imgs/sets/logo.png"></div></a>
          <input type="button" id="exc" value="Excluir">
          <input type="button" id="cancel" value="Voltar">
        </header>
        <section id="topo"
        <?php
          if(isset($_GET["id"])){
            echo "style='background-image:url(\"../".$row_post["img_principal"]."\")'";
          } else {
            header('Location: home.php');
          }
        ?>>
          <div id="fundo_topo">
            <div id="content_topo">
              <div id="title">
                <?php
                  echo $row_post["titulo"];
                ?>
              </div>
              <?php
                echo "<div id='date'>".$row_post["data"]."</div>";
              ?>
              <div id="autor">por <?php echo $row_post["nome"]; ?></div>
            </div>
          </div>
          <a href="#texto" class="scroll"><div id="arrow"><img src="../imgs/sets/arrow.png"></div></a>
        </section>
        <section id="texto">
          <article id="conteudo">
            <?php
              echo $row_post["texto"];
            ?>
          </article>
        </section>
        <section id="tags">
          <div id="content_tags">
            <div id="title_tags">Tags</div>
            <div id="quadro_tags">
              <?php
                $sql_pt = "SELECT tag.nome, tag.idtag FROM tag INNER JOIN post_tag ON post_tag.idtag = tag.idtag INNER JOIN postagem ON postagem.idpostagem = post_tag.idpostagem WHERE postagem.idpostagem = ".$id;
                $res_pt = mysqli_query($conn,$sql_pt);
                if ( mysqli_num_rows($res_pt) > 0 ){
                  while($row_pt = mysqli_fetch_array($res_pt)){
                    echo "<div class='tag' id='tag".$row_pt["idtag"]."'>#".$row_pt["nome"]."</div>";
                  }
                }
              ?>
            </div>
          </div>
        </section>
      <?php
        }
      ?>
      <footer>
        <div id="rodape">
          <div id="social">
            <img src="../imgs/sets/logo_footer.png">
          </div>
          <div id="copyright">Copyright © Vou - 2016</div>
        </div>
      </footer>
      <script type="text/javascript" src="../js/article.js"></script>
    </body>
</html>

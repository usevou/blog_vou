<div id="cont">
  <div id="title">Postagens</div>
  <?php
    if ( $row["idautor"] != 1 ){
      echo '<div id="add"><a href="post.php"><img src="../imgs/sets/add.png"></a></div>';
    }
  ?>
  <div id="post_grid">
    <?php
      if ( $row["idautor"] == 1 ){
        $sql = "SELECT *, concat(day(dt_hr), ' de ',
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
                Year(dt_hr), ' às ', DATE_FORMAT(dt_hr,'%H:%i')) as data FROM postagem INNER JOIN autor ON postagem.idautor = autor.idautor";
      } else {
        $sql = "SELECT *, concat(day(dt_hr), ' de ',
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
                Year(dt_hr), ' às ', DATE_FORMAT(dt_hr,'%H:%i')) as data FROM postagem INNER JOIN autor ON postagem.idautor = autor.idautor WHERE postagem.idautor = ".$row["idautor"];
      }
      $res = mysqli_query($conn,$sql);
      if ( mysqli_num_rows($res) > 0 ){
        while($r = mysqli_fetch_array($res)){
          echo '<div class="card_post" style="background-image:url(\'../'.$r["img_principal"].'\')">
            <div class="fundo_card">
              <a href="post.php?id='.$r["idpostagem"].'" class="content_card">
      					<div class="card_title">'.$r["titulo"].'</div>
      					<div class="date_card">'.$r["data"].'</div>
      					<div class="autor_card">por '.$r["nome"].'</div>
              </a>
              <div class="edit_card">';
          if ( $row["idautor"] != 1 ){
            echo '<a href="post.php?id='.$r["idpostagem"].'"><img src="../imgs/sets/editar.png"></a>';
          }
          echo '<a href="home.php?tab=postagem&exc='.$r["idpostagem"].'"><img ';
          if ( $row["idautor"] == 1 ){
            echo 'style="margin-top:126px;" ';
          }
          echo 'src="../imgs/sets/excluir.png"></a>
              </div>
      			</div>
      		</div>';
        }
      } else {
        echo "<div id='nothing' style='margin-top:0px;'>Nenhuma postagem encontrada.</div>";
      }

      if ( isset($_GET["exc"]) ){
        $id = $_GET["exc"];
        if ( isset($_GET["e"]) ){
          $sql_pt = "DELETE FROM post_tag WHERE idpostagem = ".$id;
          $res_pt = mysqli_query($conn,$sql_pt);
          $sql = "DELETE FROM postagem WHERE idpostagem = ".$id;
          $res = mysqli_query($conn,$sql);
          if ( $res ){
    ?>
    <script>
      swal({
        title: "Excluído!",
        text: "A postagem foi excluída com sucesso.",
        type: "success"
      },function(){
        window.location.href = "home.php?tab=postagem";
      });
    </script>
    <?php
          } else {
    ?>
    <script>
      swal({
        title: "Erro!",
        text: "Ocorreu um erro, tente novamente mais tarde.",
        type: "error"
      },function(){
        window.location.href = "home.php?tab=postagem";
      });
    </script>
    <?php
          }
        } else {
    ?>
    <script>
    swal({
      title: "Deseja mesmo excluir?",
      text: "Você tem certeza que quer excluir essa postagem?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Sim, exclua a postagem!",
      closeOnConfirm: false
    },
    function(){
      window.location.href = "home.php?tab=postagem&exc=<?php echo $id; ?>&e=true";
    });
    </script>
    <?php
        }
      }
    ?>
  </div>
</div>

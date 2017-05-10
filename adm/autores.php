<div id="cont">
  <div id="title">Autores</div>
  <div id="add"><a href="home.php?tab=autores&add=true"><img src="../imgs/sets/add.png"></a></div>
  <div id="aut_grid">
    <?php
      $sql = "SELECT * FROM autor WHERE idautor > 1";
      $res = mysqli_query($conn,$sql);
      if ( mysqli_num_rows($res) > 0 ){
        $i = 0;
        while($row = mysqli_fetch_array($res)){
          echo '<div class="card" '.($i%2==0?'':'style="border-left:1px solid #666666;"').'>
            <div class="foto"><img src="../imgs/images/profile/'.$row["foto"].'.png"></div>
            <div class="nome">'.$row["nome"].'<br><div>'.$row["login"].'</div></div>
            <div class="btn">
              <a href="?tab=autores&exc='.$row["idautor"].'&nome='.$row["nome"].'"><img src="../imgs/sets/excluir_b.png"></a>
            </div>
          </div>';
          $i++;
        }
      } else {
        echo "<div id='nothing'>Nenhum autor cadastrado.</div>";
      }
    ?>
  </div>
</div>
<?php
  function tirarAcentos($string){
      return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
  }
  if ( isset($_GET["exc"]) ){
    $id = $_GET["exc"];
    if ( isset($_GET["e"]) ){
      $sql_p = "SELECT idpostagem FROM postagem WHERE idautor = ".$id;
      $res_p = mysqli_query($conn,$sql_p);
      if ( mysqli_num_rows($res_p) > 0 ){
        while ( $row_p = mysqli_fetch_array($res_p) ){
          $sql_pt = "DELETE FROM post_tag WHERE idpostagem = ".$row_p["idpostagem"];
          $res_pt = mysqli_query($conn,$sql_pt);
        }
        $sql_post = "DELETE FROM postagem WHERE idautor = ".$id;
        $res_post = mysqli_query($conn,$sql_post);
      }
      $sql = "DELETE FROM autor WHERE idautor = ".$id;
      $res = mysqli_query($conn,$sql);
      if ( $res ){
?>
<script>
  swal({
    title: "Excluído!",
    text: "O autor foi excluído com sucesso.",
    type: "success"
  },function(){
    window.location.href = "home.php?tab=autores";
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
    window.location.href = "home.php?tab=autores";
  });
</script>
<?php
      }
    } else {
      $nome = $_GET["nome"];
?>
<script>
swal({
  title: "Deseja mesmo excluir?",
  text: "Atenção! Ao excluir um autor, você automaticamente exclui todas as suas postagens. Você tem certeza que quer excluir o autor <?php echo $nome; ?>?",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "Sim, exclua!",
  closeOnConfirm: false
},
function(){
  window.location.href = "home.php?tab=autores&exc=<?php echo $id; ?>&e=true";
});
</script>
<?php
    }
  } else if ( isset($_GET["add"]) ){
    if ( isset($_GET["e"]) ){
      $nome = $_GET["nome"];
      $nome = tirarAcentos($nome);
      $partes = explode(" ", $nome);
      $num = substr_count($nome, ' ');
      $login = strtolower(substr($partes[0],0,4));
      $login .= strtolower(substr($partes[$num],0,4));
      $sql_s = "SELECT COUNT(*) as c FROM autor WHERE login LIKE '".$login."%'";
      $res_s = mysqli_query($conn,$sql_s);
      if ( $res_s ){
        $row_s = mysqli_fetch_array($res_s);
        if ( $row_s['c'] > 0 ){
          $login .= $row_s['c'];
        }
      }
      $sql = "INSERT INTO autor VALUES (null, '".$nome."',null, 'padrao', null, '".$login."', '".password_hash($login,PASSWORD_DEFAULT)."')";
      $res = mysqli_query($conn,$sql);
      if ( $res ){
?>
<script>
  swal({
    title: "Cadastrado!",
    text: "O autor foi cadastrada com sucesso.",
    type: "success"
  },function(){
    window.location.href = "home.php?tab=autores";
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
    window.location.href = "home.php?tab=autores";
  });
</script>
<?php
      }
    } else {
?>
<script>
swal({
  title: "Cadastrar!",
  type: "input",
  showCancelButton: true,
  closeOnConfirm: false,
  confirmButtonColor: "#652C90",
  inputPlaceholder: "Nome do autor"
},
function(inputValue){
  if (inputValue === false) return false;

  if (inputValue === "") {
    swal.showInputError("Digite alguma coisa!");
    return false
  }

  window.location.href = "home.php?tab=autores&add=true&nome="+ inputValue +"&e=true";
});
</script>
<?php
    }
  }
?>

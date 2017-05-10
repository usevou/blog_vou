<div id="cont">
  <div id="title">Tags</div>
  <div id="add"><a href="home.php?tab=tags&add=true"><img src="../imgs/sets/add.png"></a></div>
  <div id="tag_grid">
    <?php
      $sql = "SELECT * FROM tag";
      $res = mysqli_query($conn, $sql);

      if ( mysqli_num_rows($res) > 0 ){
        while( $row = mysqli_fetch_array($res) ){
          echo "<div class='tag' id='tag".$row["idtag"]."'>#".$row["nome"]."
            <div class='opc_tag'>
              <a href='home.php?tab=tags&edit=".$row["idtag"]."&nome=".$row["nome"]."'><img src='../imgs/sets/editar.png'></a>
              <a href='home.php?tab=tags&exc=".$row["idtag"]."&nome=".$row["nome"]."'><img src='../imgs/sets/excluir.png'></a>
            </div>
          </div>";
        }
      } else {
        echo "<div id='nothing'>Nenhuma tag encontrada.</div>";
      }
    ?>
  </div>
</div>
<?php
  if ( isset($_GET["exc"]) ){
    $id = $_GET["exc"];
    if ( isset($_GET["e"]) ){
      $sql_pt = "DELETE FROM post_tag WHERE idtag = ".$id;
      $res_pt = mysqli_query($conn,$sql_pt);
      $sql = "DELETE FROM tag WHERE idtag = ".$id;
      $res = mysqli_query($conn,$sql);
      if ( $res ){
?>
<script>
  swal({
    title: "Excluído!",
    text: "A tag foi excluída com sucesso.",
    type: "success"
  },function(){
    window.location.href = "home.php?tab=tags";
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
    window.location.href = "home.php?tab=tags";
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
  text: "Você tem certeza que quer excluir a tag #<?php echo $nome; ?>?",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "Sim, exclua a tag!",
  closeOnConfirm: false
},
function(){
  window.location.href = "home.php?tab=tags&exc=<?php echo $id; ?>&e=true";
});
</script>
<?php
    }
  } else if ( isset($_GET["edit"]) ){
    $id = $_GET["edit"];
    $nome = $_GET["nome"];
    if ( isset($_GET["e"]) ){
      $sql = "UPDATE tag SET nome = '".$nome."' WHERE idtag = ".$id;
      $res = mysqli_query($conn,$sql);
      if ( $res ){
?>
<script>
  swal({
    title: "Alterado!",
    text: "A tag foi alterada com sucesso.",
    type: "success"
  },function(){
    window.location.href = "home.php?tab=tags";
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
    window.location.href = "home.php?tab=tags";
  });
</script>
<?php
      }
    } else {
?>
<script>
swal({
  title: "Alterar!",
  type: "input",
  showCancelButton: true,
  closeOnConfirm: false,
  confirmButtonColor: "#652C90",
  inputValue: "<?php echo $nome; ?>",
  inputPlaceholder: "Nome da tag"
},
function(inputValue){
  if (inputValue === false) return false;

  if (inputValue === "") {
    swal.showInputError("Digite alguma coisa!");
    return false
  }

  window.location.href = "home.php?tab=tags&edit=<?php echo $id; ?>&nome="+ inputValue +"&e=true";
});
</script>
<?php
    }
  } else if ( isset($_GET["add"]) ){
    if ( isset($_GET["e"]) ){
      $nome = $_GET["nome"];
      $sql = "INSERT INTO tag VALUES (null, '".$nome."')";
      $res = mysqli_query($conn,$sql);
      if ( $res ){
?>
<script>
  swal({
    title: "Cadastrado!",
    text: "A tag foi cadastrada com sucesso.",
    type: "success"
  },function(){
    window.location.href = "home.php?tab=tags";
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
    window.location.href = "home.php?tab=tags";
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
  inputPlaceholder: "Nome da tag"
},
function(inputValue){
  if (inputValue === false) return false;

  if (inputValue === "") {
    swal.showInputError("Digite alguma coisa!");
    return false
  }

  window.location.href = "home.php?tab=tags&add=true&nome="+ inputValue +"&e=true";
});
</script>
<?php
    }
  }
?>

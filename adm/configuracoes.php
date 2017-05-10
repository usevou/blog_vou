<div id="cont">
  <div id="title">Configurações</div>
  <form enctype="multipart/form-data" id="config_grid" method="POST">
    <input type="submit" value="Salvar">
    <div id="foto_config">
      <div id="ft" style="background-image:url('../imgs/images/profile/<?php echo $row["foto"]; ?>.png')"></div>
      <div id="label"><label for="input_file">Trocar foto de perfil</label></div>
      <input type="file" name="foto" id="input_file" accept="image/*">
    </div>
    <?php
      function validaemail($email){
        if (!ereg('^([a-zA-Z0-9.-_])*([@])([a-z0-9]).([a-z]{2,3})',$email)){
          return false;
        } else {
          $dominio = explode('@',$email);
          if(!checkdnsrr($dominio[1],'A')){
            return false;
          } else {
            return true;
          }
        }
      }

      function validalogin($conn,$login){
        $sql_login = "SELECT * FROM autor WHERE login = '".$login."'";
        $res_login = mysqli_query($conn,$sql_login);
        if ( mysqli_num_rows($res_login) > 0 ){
          return false;
        } else {
          return true;
        }
      }

      if ( isset($_POST["nome"]) ){
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $descricao = $_POST["descricao"];
        $login = $_POST["login"];
        $erro = 0;

        if ( $nome == "" ){
          $erro++;
          echo '<div class="input_config"><input type="text" style="border:1px solid red;" name="nome" placeholder="Nome">* O nome é obrigatório.</div>';
        } else {
          echo '<div class="input_config"><input type="text" name="nome" placeholder="Nome" value="'.$nome.'"></div>';
        }
        if ( $email != "" && !validaemail($email) ){
          $erro++;
          echo '<div class="input_config"><input type="text" style="border:1px solid red;" name="email" placeholder="E-mail" value="'.$email.'">* E-mail inválido.</div>';
        } else {
          echo '<div class="input_config"><input type="text" name="email" placeholder="E-mail" value="'.$email.'"></div>';
        }
        echo '<div class="input_config"><textarea name="descricao" placeholder="Descrição">'.$descricao.'</textarea></div>';
        if ( $login == "" ){
          $erro++;
          echo '<div class="input_config"><input type="text" style="border:1px solid red;" name="login" placeholder="Login">* O login é obrigatório.</div>';
        } else if ( $login != $row["login"] && !validalogin($conn,$login) ){
          $erro++;
          echo '<div class="input_config"><input type="text" style="border:1px solid red;" name="login" placeholder="Login" value="'.$login.'">* Esse login já está em uso.</div>';
        } else {
          echo '<div class="input_config"><input type="text" name="login" placeholder="Login" value="'.$login.'"></div>';
        }

        if ( $erro == 0 ){
          $foto = $_FILES["foto"];
          if ( $foto['error'] > 0 ){
            $sql_autor = "UPDATE autor SET nome = '".$nome."', email = '".$email."', descricao = '".$descricao."', login = '".$login."' WHERE idautor = ".$row["idautor"];
          } else {
            $sql_autor = "UPDATE autor SET foto = '".$login."', nome = '".$nome."', email = '".$email."', descricao = '".$descricao."', login = '".$login."' WHERE idautor = ".$row["idautor"];
            $uploadfile = '../imgs/images/profile/' . $login . '.png';

            if (!move_uploaded_file($foto['tmp_name'], $uploadfile)) {
              $erro++;
            } else {
              $row["foto"] = $login;
            }
          }

          if ( $erro == 0 ){
            $res_autor = mysqli_query($conn,$sql_autor);
            if ( $res_autor ){
              $row["nome"] = $nome;
              $row["email"] = $email;
              $row["descricao"] = $descricao;
              $row["login"] = $login;
              echo '<script>swal({
                title: "Informações alteradas!",
                text: "Suas informações foram alteradas com sucesso.",
                type: "success"
              },function(){
                window.location.href = "home.php?tab=configuracoes";
              });</script>';
            }
          }

        }
      } else {
    ?>
    <div class="input_config"><input type="text" name="nome" placeholder="Nome" value="<?php echo $row["nome"]; ?>"></div>
    <div class="input_config"><input type="text" name="email" placeholder="E-mail" value="<?php echo $row["email"]; ?>"></div>
    <div class="input_config"><textarea name="descricao" placeholder="Descrição"><?php echo $row["descricao"]; ?></textarea></div>
    <div class="input_config"><input type="text" name="login" placeholder="Login" value="<?php echo $row["login"]; ?>"></div>
    <?php
      }
    ?>
  </form>
  <div id="troca_senha">
    <div id="title_senha">Trocar senha</div>
    <form method="POST">
      <?php
        if ( isset($_POST["senha_atual"]) ){
          $senha_atual = $_POST["senha_atual"];
          $senha_nova = $_POST["senha_nova"];
          $senha_nova_rep = $_POST["senha_nova_rep"];
          $erro = 0;

          if ( $senha_atual == "" ){
            $erro++;
            echo '<div class="input_config"><input type="password" style="border:1px solid red;" name="senha_atual" placeholder="Senha atual">* Digite sua senha atual.</div>';
          } else if ( !password_verify($senha_atual,$row['senha']) ){
            $erro++;
            echo '<div class="input_config"><input type="password" style="border:1px solid red;" name="senha_atual" placeholder="Senha atual">* Senha inválida.</div>';
          } else {
            echo '<div class="input_config"><input type="password" name="senha_atual" placeholder="Senha atual"></div>';
          }

          if ( $senha_nova == "" ){
            $erro++;
            echo '<div class="input_config" style="width:48%;"><input type="password" style="border:1px solid red;" name="senha_nova" placeholder="Senha nova">* Digite a senha nova.</div>
            <div class="input_config" style="width:48%;"><input type="password" style="border:1px solid red;" name="senha_nova_rep" placeholder="Repita a nova senha"></div>';
          } else if ( $senha_nova_rep == "" ){
            $erro++;
            echo '<div class="input_config" style="width:48%;"><input type="password" name="senha_nova" placeholder="Senha nova"></div>
            <div class="input_config" style="width:48%;"><input type="password" style="border:1px solid red;" name="senha_nova_rep" placeholder="Repita a nova senha">* Repita a senha.</div>';
          } else if ( $senha_nova != $senha_nova_rep ){
            $erro++;
            echo '<div class="input_config" style="width:48%;"><input type="password" style="border:1px solid red;" name="senha_nova" placeholder="Senha nova">* As senhas não conferem.</div>
            <div class="input_config" style="width:48%;"><input type="password" style="border:1px solid red;" name="senha_nova_rep" placeholder="Repita a nova senha"></div>';
          } else {
            echo '<div class="input_config" style="width:48%;"><input type="password" name="senha_nova" placeholder="Senha nova"></div>
            <div class="input_config" style="width:48%;"><input type="password" name="senha_nova_rep" placeholder="Repita a nova senha"></div>';
          }

          if ( $erro == 0 ){
            $sql_senha = "UPDATE autor SET senha = '".password_hash($senha_nova,PASSWORD_DEFAULT)."' WHERE idautor = ".$row["idautor"];
            $res_senha = mysqli_query($conn,$sql_senha);
            if ( $res_senha ){
              $row["senha"] = password_hash($senha_nova,PASSWORD_DEFAULT);
              echo '<script>swal({
                title: "Senha alterada!",
                text: "Sua senha foi alterada com sucesso.",
                type: "success"
              });</script>';
            }
          }
        } else {
      ?>
      <div class="input_config"><input type="password" name="senha_atual" placeholder="Senha atual"></div>
      <div class="input_config" style="width:48%;"><input type="password" name="senha_nova" placeholder="Senha nova"></div>
      <div class="input_config" style="width:48%;"><input type="password" name="senha_nova_rep" placeholder="Repita a nova senha"></div>
      <?php
        }
      ?>
      <input type="submit" value="Trocar senha">
    </form>
  </div>
</div>
<script>
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
          $('#ft').css('background-image','url("'+e.target.result+'")');
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#input_file").change(function(){
    readURL(this);
  });

  $('#ft').height($('#ft').width());
</script>

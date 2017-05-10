<?php
  include_once 'conn.php';

  $a=array();
  $b=array();

  if ( isset($_POST["q"]) ){
    $q = $_POST["q"];
    if ( $q == 'normal' ){
      $limit = $_POST["limit"];

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
              Year(dt_hr), ' às ', DATE_FORMAT(dt_hr,'%H:%i')) as data FROM postagem INNER JOIN autor ON postagem.idautor = autor.idautor ORDER BY dt_hr DESC LIMIT ".$limit.",10";
      $res = mysqli_query($conn, $sql);

      if(mysqli_num_rows($res) > 0){
        while($row = mysqli_fetch_array($res)){
          $b["res"] = 1;
          $b["idpost"] = $row["idpostagem"];
          $b["titulo"] = $row["titulo"];
          $b["data"] = $row["data"];
          $b["nome"] = $row["nome"];
          $b["foto"] = $row["img_principal"];
          array_push($a,$b);
        }
      }else{
        $b["res"] = 0;
        array_push($a,$b);
      }
    } else if( $q == 'pesq' ) {
      $limit = $_POST["limit"];
      $pesq = $_POST["pesq"];

      $sql = "SELECT *, DATE_FORMAT(dt_hr,'%d de %M de %Y às %H:%i') as data FROM postagem INNER JOIN autor ON postagem.idautor = autor.idautor WHERE titulo LIKE '%".$pesq."%' OR texto LIKE '%".$pesq."%' ORDER BY dt_hr DESC LIMIT ".$limit.",10";
      $res = mysqli_query($conn, $sql);

      if(mysqli_num_rows($res) > 0){
        while($row = mysqli_fetch_array($res)){
          $b["res"] = 1;
          $b["idpost"] = $row["idpostagem"];
          $b["titulo"] = $row["titulo"];
          $b["data"] = $row["data"];
          $b["nome"] = $row["nome"];
          $b["foto"] = $row["img_principal"];
          array_push($a,$b);
        }
      }else{
        $b["res"] = 0;
        array_push($a,$b);
      }
    } else if( $q == 'tag' ) {
      $limit = $_POST["limit"];
      $tag = $_POST["tag"];

      $sql = "SELECT *, DATE_FORMAT(dt_hr,'%d de %M de %Y às %H:%i') as data FROM autor
              INNER JOIN postagem ON postagem.idautor = autor.idautor
              INNER JOIN post_tag ON post_tag.idpostagem = postagem.idpostagem
              WHERE post_tag.idtag = ".$tag." ORDER BY dt_hr DESC LIMIT ".$limit.",10";
      $res = mysqli_query($conn, $sql);

      if(mysqli_num_rows($res) > 0){
        while($row = mysqli_fetch_array($res)){
          $b["res"] = 1;
          $b["idpost"] = $row["idpostagem"];
          $b["titulo"] = $row["titulo"];
          $b["data"] = $row["data"];
          $b["nome"] = $row["nome"];
          $b["foto"] = $row["img_principal"];
          array_push($a,$b);
        }
      }else{
        $b["res"] = 0;
        array_push($a,$b);
      }
    }
  }

  echo json_encode($a);
?>

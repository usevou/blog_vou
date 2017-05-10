<?php
  include_once '../conn.php';

  $a=array();
  $b=array();

  if ( isset($_POST["q"]) ){
    $q = $_POST["q"];
    if ( $q == 'get' ){
      $name = $_POST["name"];

      $sql = "SELECT * FROM tag WHERE nome = '".$name."'";
      $res = mysqli_query($conn, $sql);

      if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_array($res);
        $b["res"] = 1;
        $b["id"] = $row["idtag"];
      }else{
        $b["res"] = 0;
      }

      array_push($a,$b);
    } else if( $q == 'ins' ) {
      $name = $_POST["name"];

      $sql = "INSERT INTO tag VALUES(null, '".$name."')";
      $res = mysqli_query($conn, $sql);

      if($res){
        $sql = "SELECT * FROM tag WHERE nome = '".$name."'";
        $res = mysqli_query($conn, $sql);

        if(mysqli_num_rows($res) > 0){
          $row = mysqli_fetch_array($res);
          $b["res"] = 1;
          $b["id"] = $row["idtag"];
        }else{
          $b["res"] = 0;
        }
      }else{
        $b["res"] = 0;
      }

      array_push($a,$b);
    }
  }

  echo json_encode($a);
?>

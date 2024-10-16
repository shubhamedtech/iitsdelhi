<?php
if (isset($_GET['center'])) {
  require '../../includes/db-config.php';
  session_start();

  $center = intval($_GET['center']);
  
  $get_uni_id_sql = $conn->query("SELECT University_ID FROM Users WHERE ID = $center AND Role = 'Center'");
  $get_university_ids = $get_uni_id_sql->fetch_assoc();
  $get_university_ids = $get_university_ids['University_ID'];
  $uni_id =null;
  if(isset($_GET['university_ids'])) {
    $uni_id = $_GET['university_ids'];
  }
  if (empty($center)) {
    echo 'Center';
    exit();
  }
  $university_ids_sql = $conn->query("SELECT ID,CONCAT(Name, ' (',Short_Name, ')') as Name FROM Universities WHERE ID IN($get_university_ids)");
  while ($university_ids_arr = mysqli_fetch_assoc($university_ids_sql)) { ?>
    <option value="<?= $university_ids_arr['ID'] ?>" <?php if($university_ids_arr['ID']==$uni_id){echo "selected"; }else{
      echo "";
    } ?>><?= $university_ids_arr['Name'] ?></option>
  <?php }


} ?>
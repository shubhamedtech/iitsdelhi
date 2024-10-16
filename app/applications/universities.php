<?php
//   echo("<pre>");print_r($_POST['university']);die;
require '../../includes/db-config.php';

if (isset($_POST['university'])) {
    $id_uni = $_POST['university'];
    session_start();
    if($id_uni=="All"){
        $sql ='';
    }else{
        $sql = "AND University_ID=".$id_uni;
    }
    $university_id = $conn->query("SELECT Name FROM  Admission_Sessions WHERE Status =1 $sql");
    $university_id = $university_id->fetch_assoc();
    //   echo("<pre>");print_r($university_id);die;
    //   $_SESSION['current_session'] = $_POST['university'];
    $option = "";

    foreach ($university_id as $admission_session) {
       
        $option .= '<option value="' . $admission_session . '">' . $admission_session . '</option>';
    }
    echo($option);
}

<?php
  if(isset($_POST['university_id']) && isset($_POST['id'])){
    require '../../includes/db-config.php';

    session_start();

    $id = intval($_POST['id']);
    $university_ids = $_POST['university_id'];

    $remove = $conn->query("DELETE FROM University_User WHERE Reporting = $id AND University_ID IN($university_ids)");
    $remove = $conn->query("DELETE FROM Fee_Variables WHERE Code = $id AND University_ID IN($university_ids)");
    // $remove = $conn->query("DELETE FROM Alloted_Center_To_SubCounsellor WHERE Code = $id AND University_ID IN($university_ids)");
    // $remove = $conn->query("DELETE FROM Alloted_Center_To_Counsellor WHERE Code = $id AND University_ID IN($university_ids)");

    $is_vocational = $conn->query("SELECT ID FROM Universities WHERE ID IN($university_ids) AND Is_Vocational = 1");
    if($is_vocational->num_rows>0){
      $remove = $conn->query("DELETE FROM Center_Course_Types WHERE `User_ID` = $id AND University_ID IN($university_ids)");
      $remove = $conn->query("DELETE FROM Center_Sub_Courses WHERE `User_ID` = $id AND University_ID IN($university_ids)");
    }

    if($remove){
      echo json_encode(['status'=>200, 'message'=>'Allotment removed successfully!']);
    }else{
      echo json_encode(['status'=>400, 'message'=>'Something went wrong!']);
    }
  }

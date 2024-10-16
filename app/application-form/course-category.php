<?php

  if(isset($_GET['id'])){
    require '../../includes/db-config.php';
    session_start();

    $sub_course_id = intval($_GET['id']);
    //print_r($sub_course_id);die;
    if(empty($sub_course_id)){
      echo '<option value="">Please add sub-course</option>';
      exit();
    }

    //$admission_type = $conn->query("SELECT Name FROM Admission_Types WHERE ID = $admission_type_id");
    //$admission_type = mysqli_fetch_assoc($admission_type);
    //$admission_type = $admission_type['Name'];

   // $column = "1";
    //if(strcasecmp($admission_type, 'lateral')==0){
    //  $column = "LE_Start";
   // }
   // if(strcasecmp($admission_type, 'credit transfer')==0){
     // $column = "CT_Start";
   // }

    if($_SESSION['university_id'] == 48){  
         $durations = $conn->query("SELECT Course_Category FROM Sub_Courses WHERE ID = $sub_course_id");
      while($duration = $durations->fetch_assoc()){ 
          $course_categories = json_decode($duration['Course_Category']);        
      }
    }

    if (!empty($course_categories) && is_array($course_categories)) {
      $option = "<option>Select Choose Category</option>";
        foreach ($course_categories as $course_category) {
            $course_category1 = $course_category ;
            $option .= '<option value="'.$course_category1.'">'.$course_category1.'</option>'; 
        }
    } else {
        $option = "<option>No Categories found</option>";
    }

    echo $option;
 }
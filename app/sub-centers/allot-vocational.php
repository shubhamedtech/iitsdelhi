<?php
ini_set('display_errors', 1);
if (isset($_POST['university_id']) && isset($_POST['id']) && isset($_POST['counsellor'])) {
  require '../../includes/db-config.php';
  session_start();

  $id = intval($_POST['id']);
  $university_id = intval($_POST['university_id']);
  $counsellor = intval($_POST['counsellor']);
  $sub_counsellor = intval($_POST['sub_counsellor']);

  $course_types = is_array($_POST['course_type']) ? $_POST['course_type'] : [];

  if (empty($counsellor) || empty($university_id) || empty($id)) {
    echo json_encode(['status' => 403, 'message' => 'Missing required fields!']);
    exit();
  }

  if ($_POST['university_id'] == 48) {
    $check = $conn->query("SELECT ID FROM Alloted_Center_To_Counsellor WHERE Code = $id AND University_ID = $university_id");
    if ($check->num_rows > 0) {
      $update_allot_counsellor = $conn->query("UPDATE Alloted_Center_To_Counsellor SET Counsellor_ID = $counsellor WHERE Code = $id AND University_ID = $university_id");
    } else {
      $update_allot_counsellor = $conn->query("INSERT INTO Alloted_Center_To_Counsellor (`Counsellor_ID`, `Code`, `University_ID`) VALUES ($counsellor, $id, $university_id)");
    }

    $conn->query("DELETE FROM Sub_Center_Course_Types WHERE `User_ID` = $id AND University_ID = $university_id");
    foreach ($course_types as $course_type) {
      $conn->query("INSERT INTO Sub_Center_Course_Types (`User_ID`, `Course_Type_ID`, `University_ID`) VALUES ($id, $course_type, $university_id)");
    }

    $i = 0;
    $fee = array();
    $durations = array(); // Create an array to store durations
    $alloted = 0;
    foreach ($_POST['subcourse_id'] as $index => $subcourse_id) {
      $fee = $_POST['fee'][$index];
      $sub_course_id = mysqli_real_escape_string($conn, $subcourse_id);

      // Fetch the course information for the current subcourse
      $course_id = $conn->query("SELECT Course_ID, Min_Duration as Duration FROM Sub_Courses WHERE ID = $sub_course_id AND University_ID = $university_id");
      $subcourses_course_id = $course_id->fetch_assoc();
      $course_id = $subcourses_course_id['Course_ID'];
      $duraction_check = json_decode($subcourses_course_id['Duration'], true);

      // Initialize $i to 0 if it doesn't exist
      if (!isset($durations[$sub_course_id])) {
        $durations[$sub_course_id] = 0;
      }

      // Use the duration from the array and increment the value
      $duraction = $duraction_check[$durations[$sub_course_id]];
      $durations[$sub_course_id]++;

      $allot = '';
      $fees = array();
      foreach ($duraction_check as $key => $duraction) {
        $fees[] = $fee[$duraction];
      }
      foreach ($fees as $key1 => $fee) {
        if ($fee) {

          $duraction = $duraction_check[$key1];

          $center_sub_course = $conn->query("SELECT * FROM Sub_Center_Sub_Courses WHERE User_ID = $id AND Course_ID = $course_id AND Sub_Course_ID = $sub_course_id AND Duration = '" . $duraction . "' AND University_ID = $university_id");

          if ($center_sub_course->num_rows > 0) {
            $allot = $conn->query("UPDATE Sub_Center_Sub_Courses SET Fee = $fee WHERE User_ID = $id AND Course_ID = $course_id AND Sub_Course_ID = $sub_course_id AND Duration = '" . $duraction . "' AND University_ID = $university_id");
            $alloted = $allot ? 1 : 0;
          } else {

            $allot = $conn->query("INSERT INTO Sub_Center_Sub_Courses (`Fee`,  `Duration`, `User_ID`, `Course_ID`, `Sub_Course_ID`, `University_ID`) VALUES ($fee, '" . $duraction . "', $id, $course_id, $sub_course_id, $university_id)");
            $alloted = $allot ? 1 : 0;
          }
        }
      }
    }

  } else {
    // $fees = is_array($_POST['fee']) ? $_POST['fee'] : [];
    $fees = isset($_POST['fee']) && is_array($_POST['fee']) ? $_POST['fee'] : [];

    $fees = array_filter($fees);

    $check = $conn->query("SELECT ID FROM Alloted_Center_To_Counsellor WHERE Code = $id AND University_ID = $university_id");
    if ($check->num_rows > 0) {
      $update_allot_counsellor = $conn->query("UPDATE Alloted_Center_To_Counsellor SET Counsellor_ID = $counsellor WHERE Code = $id AND University_ID = $university_id");
    } else {
      $update_allot_counsellor = $conn->query("INSERT INTO Alloted_Center_To_Counsellor (`Counsellor_ID`, `Code`, `University_ID`) VALUES ($counsellor, $id, $university_id)");
    }


    $conn->query("DELETE FROM Sub_Center_Course_Types WHERE `User_ID` = $id AND University_ID = $university_id");
    foreach ($course_types as $course_type) {
      $conn->query("INSERT INTO Sub_Center_Course_Types (`User_ID`, `Course_Type_ID`, `University_ID`) VALUES ($id, $course_type, $university_id)");
    }

    $conn->query("DELETE FROM Sub_Center_Sub_Courses WHERE `User_ID` = $id AND University_ID = $university_id");
    foreach ($fees as $sub_course_id => $fee) {

      $course_id = $conn->query("SELECT Course_ID FROM Sub_Courses WHERE ID = $sub_course_id AND University_ID = $university_id");
      $course_id = $course_id->fetch_assoc();
      $course_id = $course_id['Course_ID'];

      $allot = $conn->query("INSERT INTO Sub_Center_Sub_Courses (`Fee`, `Duration`, `User_ID`, `Course_ID`, `Sub_Course_ID`, `University_ID`) VALUES ($fee, 1, $id, $course_id, $sub_course_id, $university_id)");
    }
  }

  if ($update_allot_counsellor) {
    echo json_encode(['status' => 200, 'message' => 'University alloted successfully!']);
  } else {
    echo json_encode(['status' => 403, 'message' => 'Unable to allot university!']);
  }
}
?>
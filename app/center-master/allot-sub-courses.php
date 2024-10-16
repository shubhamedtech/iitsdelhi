<?php
if (isset($_POST['university_id']) && isset($_POST['id']) && isset($_POST['sub_course']) && isset($_POST['course_type'])) {
  require '../../includes/db-config.php';
  session_start();

  $id = intval($_POST['id']);
  foreach ($_POST['adm_session'] as $sub_course => $sessionArr) {
    $session_ids[$sub_course] = implode(',', $sessionArr);
  }
  $university_id = implode(',', $_POST['university_id']);
  $sub_course = is_array($_POST['sub_course']) ? $_POST['sub_course'] : [];
  $sub_courseArr = array_filter($sub_course);
  $course_types = is_array($_POST['course_type']) ? $_POST['course_type'] : [];

  if (empty($university_id) || empty($id) || empty($course_types)) {
    echo json_encode(['status' => 403, 'message' => 'Missing required fields!']);
    exit();
  }

  $course_to_university = array();
  $conn->query("DELETE FROM Center_Course_Types WHERE `User_ID` = $id AND University_ID IN($university_id)");
  for ($i = 0; $i < count($course_types); $i++) {
    list($course_type,  $university_ids) = explode('|', $course_types[$i]);
    $course_to_university[$course_type] = $university_ids;
    $conn->query("INSERT INTO Center_Course_Types (`User_ID`, `Course_Type_ID`, `University_ID`) VALUES ($id, $course_type, $university_ids)");
  }

  $conn->query("DELETE FROM Center_Sub_Courses WHERE `User_ID` = $id AND University_ID IN($university_id)");

  foreach ($sub_courseArr as $sub_course_id => $courses_ids) {
    $adm_session_id =  $session_ids[$sub_course_id];
    $university_id = $course_to_university[$courses_ids];
   $allot = $conn->query("INSERT INTO Center_Sub_Courses (`Duration`, `User_ID`, `Course_ID`, `Sub_Course_ID`,`Admission_Sessions_ID`, `University_ID`) VALUES (1, $id, $courses_ids, $sub_course_id,'$adm_session_id', $university_id)");
  }

  if ($allot) {
    echo json_encode(['status' => 200, 'message' => 'University alloted successfully!']);
  } else {
    echo json_encode(['status' => 403, 'message' => 'Unable to allot university!']);
  }
}

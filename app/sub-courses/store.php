<?php

if (isset($_POST['name']) && isset($_POST['course']) && isset($_POST['university_id']) && isset($_POST['scheme']) || isset($_POST['mode']) && isset($_POST['min_duration']) && isset($_POST['max_duration'])) {
  require '../../includes/db-config.php';
  session_start();

  $university_id = intval($_POST['university_id']);
  $course = intval($_POST['course']);
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $short_name = mysqli_real_escape_string($conn, $_POST['short_name']);
  $scheme = intval($_POST['scheme']);
  $mode = intval($_POST['mode']);
  $min_duration = intval($_POST['min_duration']);
  $max_duration = intval($_POST['max_duration']);
  $applicable = array_key_exists('applicable_in', $_POST) ? $_POST['applicable_in'] : [];
  $lateral = intval($_POST['lateral']);
  $le_start = mysqli_real_escape_string($conn, $_POST['le_start']);
  $le_sol = intval($_POST['le_sol']);
  $ct_transfer = intval($_POST['ct_transfer']);
  $ct_start = intval($_POST['ct_start']);
  $ct_sol = intval($_POST['ct_sol']);
  $eligibilities = is_array($_POST['eligibilities']) ? array_filter($_POST['eligibilities']) : [];

  if (empty($name) || empty($short_name) || empty($course) || empty($university_id) || empty($scheme) || empty($mode) || empty($eligibilities)) {
    echo json_encode(['status' => 403, 'message' => 'All fields are mandatory!']);
    exit();
  }

  if (!empty($lateral) && (empty($le_start) || empty($le_sol))) {
    echo json_encode(['status' => 403, 'message' => 'Please fill all LE fields!']);
    exit();
  }

  if (!empty($ct_transfer) && (empty($ct_start) || empty($ct_sol))) {
    echo json_encode(['status' => 403, 'message' => 'Please fill all CT fields!']);
    exit();
  }

  $check = $conn->query("SELECT ID FROM Sub_Courses WHERE (Name like '$name' OR Short_Name LIKE '$short_name') AND University_ID = $university_id AND Course_ID = $course AND Scheme_ID = $scheme");
  if ($check->num_rows > 0) {
    echo json_encode(['status' => 400, 'message' => $short_name . ' already exists!']);
    exit();
  }

  $add = $conn->query("INSERT INTO `Sub_Courses`(`Name`, `Short_Name`, `Course_ID`, `University_ID`, `Scheme_ID`, `Mode_ID`, `Min_Duration`, `SOL`, `Lateral`, `LE_Start`, `LE_SOL`, `Credit_Transfer`, `CT_Start`, `CT_SOL`, `Eligibility`) VALUES ('$name', '$short_name', $course, $university_id, $scheme, $mode, $min_duration, $max_duration, $lateral, '$le_start', $le_sol, $ct_transfer, $ct_start, $ct_sol, '" . json_encode($eligibilities) . "')");
  if ($add) {
    echo json_encode(['status' => 200, 'message' => $short_name . ' added successlly!']);
  } else {
    $conn->query("DELETE FROM Sub_Courses WHERE ID = $sub_course_id");
  }
}

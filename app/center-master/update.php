<?php
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['id'])) {
  require '../../includes/db-config.php';
  session_start();

  $id = intval($_POST['id']);
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $short_name = mysqli_real_escape_string($conn, $_POST['short_name']);
  $contact_person_name = mysqli_real_escape_string($conn, $_POST['contact_person_name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $contact = mysqli_real_escape_string($conn, $_POST['contact']);
  $alternate_contact = mysqli_real_escape_string($conn, $_POST['alternate_contact']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
  $city = mysqli_real_escape_string($conn, $_POST['city']);
  $district = mysqli_real_escape_string($conn, $_POST['district']);
  $state = mysqli_real_escape_string($conn, $_POST['state']);
  $university_id = implode(',', $_POST['university_id']);
  $university_head = intval($_POST['university_head']);

  $remove = $conn->query("DELETE FROM Alloted_Center_To_Counsellor WHERE Code = $id AND University_ID IN($university_id)");
  foreach ($_POST['university_id'] as $uni_id) {
    $checks = $conn->query("SELECT ID FROM Alloted_Center_To_Counsellor WHERE Code = $id AND University_ID = $uni_id");
    if ($checks->num_rows > 0) {
      $update_allot_counsellor = $conn->query("UPDATE Alloted_Center_To_Counsellor SET Counsellor_ID= $university_head, University_ID = $uni_id WHERE Code = $id");
    } else {
      $update_allot_counsellor = $conn->query("INSERT INTO Alloted_Center_To_Counsellor (`Counsellor_ID`,`Code`,`University_ID`) VALUES ($university_head, $id, $uni_id)");
    }
  }


  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 400, "message" => "Invalid email!"]);
    exit();
  }

  if (empty($name) || empty($email) || empty($contact) || empty($short_name) || empty($contact_person_name) || empty($address)) {
    echo json_encode(['status' => 403, 'message' => 'All fields are mandatory!']);
    exit();
  }

  $photo = '';
  if (isset($_FILES["photo"]["name"]) && $_FILES["photo"]["name"] != '') {
    $temp = explode(".", $_FILES["photo"]["name"]);
    $filename = round(microtime(true)) . '.' . end($temp);
    $tempname = $_FILES["photo"]["tmp_name"];
    $folder = "../../assets/img/centers/" . $filename;
    if (move_uploaded_file($tempname, $folder)) {
      $filename = "/assets/img/centers/" . $filename;
    } else {
      echo json_encode(['status' => 403, 'message' => 'Unable to save photo!']);
      exit();
    }
    $photo = " , Photo = '$filename'";
  }

  $add = $conn->query("UPDATE `Users` SET `Name` = '$name', `Short_Name` = '$short_name', `Contact_Name` = '$contact_person_name', `Email` = '$email', `Mobile` = '$contact', `Alternate_Mobile` = '$alternate_contact', `Address` = '$address', `Pincode` = '$pincode', `City` = '$city', `District` = '$district', `State` = '$state',  `University_ID` = '$university_id '  $photo WHERE ID = $id");
  if ($add) {
    echo json_encode(['status' => 200, 'message' => 'Center updated successlly!']);
  } else {
    echo json_encode(['status' => 400, 'message' => 'Something went wrong!']);
  }
}

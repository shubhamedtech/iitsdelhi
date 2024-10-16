<?php
if (isset($_POST['inserted_id'])) {
  require '../../includes/db-config.php';
  require '../../includes/helpers.php';

  session_start();

  $inserted_id = intval($_POST['inserted_id']);

  $step = $conn->query("SELECT Step FROM Students WHERE ID = $inserted_id");
  $step = mysqli_fetch_array($step);
  $step = $step['Step'];

  $allowed_file_extensions = array("jpeg", "jpg", "png", "gif", "JPG", "PNG", "JPEG");
  $photo_folder = '../../uploads/photo/';
  $aadhar_folder = '../../uploads/aadhar/';
  $signature_folder = '../../uploads/signature/';
  $migration_folder = '../../uploads/migration/';
  $affidavit_folder = '../../uploads/affidavit/';
  $other_certificate_folder = '../../uploads/other_certificates/';
  $student_documents = '../../uploads/student_documents/';


  if (empty($inserted_id)) {
    echo json_encode(['status' => 400, 'message' => 'ID is required.']);
    exit();
  }

  // Photo
  if (isset($_FILES["photo"]['tmp_name']) && $_FILES["photo"]['tmp_name'] != '') {
    $photo = mysqli_real_escape_string($conn, $_FILES["photo"]['name']);
    $tmp_name = $_FILES["photo"]["tmp_name"];
    $photo_extension = pathinfo($photo, PATHINFO_EXTENSION);
    $photo = $inserted_id . "." . $photo_extension;
    if (in_array($photo_extension, $allowed_file_extensions)) {
      if (!move_uploaded_file($tmp_name, $photo_folder . $photo)) {
        echo json_encode(['status' => 503, 'message' => 'Unable to upload photo!']);
        exit();
      } else {
        $photo = str_replace('../..', '', $photo_folder) . $photo;
        $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Photo'");
        if ($check->num_rows > 0) {
          $update = $conn->query("UPDATE Student_Documents SET Location = '$photo' WHERE Student_ID = $inserted_id AND Type = 'Photo'");
        } else {
          $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Photo', '$photo')");
        }
      }
    } else {
      echo json_encode(['status' => 302, 'message' => 'Photo should be image!']);
      exit();
    }
  } else {
    $check = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Photo'");
    if ($check->num_rows == 0) {
      echo json_encode(['status' => 400, 'message' => 'Photo is required!']);
      exit();
    } else {
      $update = true;
    }
  }

  // Aadhar
  if (isset($_FILES["aadhar"]["tmp_name"]) && $_FILES["aadhar"]['tmp_name'] != '' && count(array_filter($_FILES["aadhar"]['tmp_name'])) > 0) {
    foreach ($_FILES["aadhar"]["tmp_name"] as $key => $tmp_name) {
      $aadhar = mysqli_real_escape_string($conn, $_FILES["aadhar"]["name"][$key]);
      $tmp_name = $_FILES["aadhar"]["tmp_name"][$key];
      $aadhar_extension = pathinfo($aadhar, PATHINFO_EXTENSION);
      $aadhar_name = $inserted_id . "_Aadhar_" . $key . "." . $aadhar_extension;
      if (in_array($aadhar_extension, $allowed_file_extensions)) {
        if (file_exists($aadhar_folder . $aadhar_name)) {
          unlink($aadhar_folder . $aadhar_name);
        }
        if (move_uploaded_file($tmp_name, $aadhar_folder . $aadhar_name)) {
          $aadhars[] = str_replace('../..', '', $aadhar_folder) . $aadhar_name;
        } else {
          echo json_encode(['status' => 503, 'message' => 'Unable to upload Aadhar!']);
          exit();
        }
      } else {
        echo json_encode(['status' => 302, 'message' => 'Aadhar should be image!']);
        exit();
      }
    }
    $aadhar = implode("|", $aadhars);
    $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Aadhar'");
    if ($check->num_rows > 0) {
      $update = $conn->query("UPDATE Student_Documents SET Location = '$aadhar' WHERE Student_ID = $inserted_id AND Type = 'Aadhar'");
    } else {
      $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Aadhar', '$aadhar')");
    }
  }

  // Student's Signature
  if (isset($_FILES["student_signature"]['tmp_name']) && $_FILES["student_signature"]['tmp_name'] != '') {
    $student_signature = mysqli_real_escape_string($conn, $_FILES["student_signature"]['name']);
    $tmp_name = $_FILES["student_signature"]["tmp_name"];
    $student_signature_extension = pathinfo($student_signature, PATHINFO_EXTENSION);
    $student_signature = $inserted_id . "_Student_Signature." . $student_signature_extension;
    if (in_array($student_signature_extension, $allowed_file_extensions)) {
      if (!move_uploaded_file($tmp_name, $signature_folder . $student_signature)) {
        echo json_encode(['status' => 503, 'message' => 'Unable to upload Student Signature!']);
        exit();
      } else {
        $student_signature = str_replace('../..', '', $signature_folder) . $student_signature;
        $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Student Signature'");
        if ($check->num_rows > 0) {
          $update = $conn->query("UPDATE Student_Documents SET Location = '$student_signature' WHERE Student_ID = $inserted_id AND Type = 'Student Signature'");
        } else {
          $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Student Signature', '$student_signature')");
        }
      }
    } else {
      echo json_encode(['status' => 302, 'message' => 'Student Signature should be image!']);
      exit();
    }
  }

  // Parent's Signature
  if (isset($_FILES["parent_signature"]['tmp_name']) && $_FILES["parent_signature"]['tmp_name'] != '') {
    $parent_signature = mysqli_real_escape_string($conn, $_FILES["parent_signature"]['name']);
    $tmp_name = $_FILES["parent_signature"]["tmp_name"];
    $parent_signature_extension = pathinfo($parent_signature, PATHINFO_EXTENSION);
    $parent_signature = $inserted_id . "_Parent_Signature." . $parent_signature_extension;
    if (in_array($parent_signature_extension, $allowed_file_extensions)) {
      if (!move_uploaded_file($tmp_name, $signature_folder . $parent_signature)) {
        echo json_encode(['status' => 503, 'message' => 'Unable to upload Parent Signature!']);
        exit();
      } else {
        $parent_signature = str_replace('../..', '', $signature_folder) . $parent_signature;
        $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Parent Signature'");
        if ($check->num_rows > 0) {
          $update = $conn->query("UPDATE Student_Documents SET Location = '$parent_signature' WHERE Student_ID = $inserted_id AND Type = 'Parent Signature'");
        } else {
          $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Parent Signature', '$parent_signature')");
        }
      }
    } else {
      echo json_encode(['status' => 302, 'message' => 'Parent Signature should be image!']);
      exit();
    }
  }

  // Migration
  if (isset($_FILES["migration"]["tmp_name"]) && $_FILES["migration"]['tmp_name'] != '' && count(array_filter($_FILES["migration"]['tmp_name'])) > 0) {
    foreach ($_FILES["migration"]["tmp_name"] as $key => $tmp_name) {
      $migration = mysqli_real_escape_string($conn, $_FILES["migration"]["name"][$key]);
      $tmp_name = $_FILES["migration"]["tmp_name"][$key];
      $migration_extension = pathinfo($migration, PATHINFO_EXTENSION);
      $migration_name = $inserted_id . "_Migration_" . $key . "." . $migration_extension;
      if (in_array($migration_extension, $allowed_file_extensions)) {
        if (file_exists($migration_folder . $migration_name)) {
          unlink($migration_folder . $migration_name);
        }
        if (move_uploaded_file($tmp_name, $migration_folder . $migration_name)) {
          $migrations[] = str_replace('../..', '', $migration_folder) . $migration_name;
        } else {
          echo json_encode(['status' => 503, 'message' => 'Unable to upload Migration!']);
          exit();
        }
      } else {
        echo json_encode(['status' => 302, 'message' => 'Migration should be image!']);
        exit();
      }
    }
    $migration = implode("|", $migrations);
    $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Migration'");
    if ($check->num_rows > 0) {
      $update = $conn->query("UPDATE Student_Documents SET Location = '$migration' WHERE Student_ID = $inserted_id AND Type = 'Migration'");
    } else {
      $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Migration', '$migration')");
    }
  }

  // Affidavit
  if (isset($_FILES["affidavit"]["tmp_name"]) && $_FILES["affidavit"]['tmp_name'] != '' && count(array_filter($_FILES["affidavit"]['tmp_name'])) > 0) {
    foreach ($_FILES["affidavit"]["tmp_name"] as $key => $tmp_name) {
      $affidavit = mysqli_real_escape_string($conn, $_FILES["affidavit"]["name"][$key]);
      $tmp_name = $_FILES["affidavit"]["tmp_name"][$key];
      $affidavit_extension = pathinfo($affidavit, PATHINFO_EXTENSION);
      $affidavit_name = $inserted_id . "_Affidavit_" . $key . "." . $affidavit_extension;
      if (in_array($affidavit_extension, $allowed_file_extensions)) {
        if (file_exists($affidavit_folder . $affidavit_name)) {
          unlink($affidavit_folder . $affidavit_name);
        }
        if (move_uploaded_file($tmp_name, $affidavit_folder . $affidavit_name)) {
          $affidavits[] = str_replace('../..', '', $affidavit_folder) . $affidavit_name;
        } else {
          echo json_encode(['status' => 503, 'message' => 'Unable to upload affidavit!']);
          exit();
        }
      } else {
        echo json_encode(['status' => 302, 'message' => 'Affidavit should be image!']);
        exit();
      }
    }
    $affidavit = implode("|", $affidavits);
    $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Affidavit'");
    if ($check->num_rows > 0) {
      $update = $conn->query("UPDATE Student_Documents SET Location = '$affidavit' WHERE Student_ID = $inserted_id AND Type = 'Affidavit'");
    } else {
      $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Affidavit', '$affidavit')");
    }
  }

  // Other Certificates
  if (isset($_FILES["other_certificate"]["tmp_name"]) && $_FILES["other_certificate"]['tmp_name'] != '' && count(array_filter($_FILES["other_certificate"]['tmp_name'])) > 0) {
    foreach ($_FILES["other_certificate"]["tmp_name"] as $key => $tmp_name) {
      $other_certificate = mysqli_real_escape_string($conn, $_FILES["other_certificate"]["name"][$key]);
      $tmp_name = $_FILES["other_certificate"]["tmp_name"][$key];
      $other_certificate_extension = pathinfo($other_certificate, PATHINFO_EXTENSION);
      $other_certificate_name = $inserted_id . "_other_certificate_" . $key . "." . $other_certificate_extension;
      if (in_array($other_certificate_extension, $allowed_file_extensions)) {
        if (file_exists($other_certificate_folder . $other_certificate_name)) {
          unlink($other_certificate_folder . $other_certificate_name);
        }
        if (move_uploaded_file($tmp_name, $other_certificate_folder . $other_certificate_name)) {
          $other_certificates[] = str_replace('../..', '', $other_certificate_folder) . $other_certificate_name;
        } else {
          echo json_encode(['status' => 503, 'message' => 'Unable to upload other_certificate!']);
          exit();
        }
      } else {
        echo json_encode(['status' => 302, 'message' => 'other_certificate should be image!']);
        exit();
      }
    }
    $other_certificate = implode("|", $other_certificates);
    $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Other Certificate'");
    if ($check->num_rows > 0) {
      $update = $conn->query("UPDATE Student_Documents SET Location = '$other_certificate' WHERE Student_ID = $inserted_id AND Type = 'Other Certificate'");
    } else {
      $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Other Certificate', '$other_certificate')");
    }
  }
   // Degree Mark List
   if (isset($_FILES["degree_mark_list"]['tmp_name']) && $_FILES["degree_mark_list"]['tmp_name'] != '') {
    $degree_mark_list = mysqli_real_escape_string($conn, $_FILES["degree_mark_list"]['name']);
    $tmp_name = $_FILES["degree_mark_list"]["tmp_name"];
    $degree_mark_list_extension = pathinfo($degree_mark_list, PATHINFO_EXTENSION);
    $degree_mark_list = $inserted_id . "_degree_mark_list." . $degree_mark_list_extension;
    if (in_array($degree_mark_list_extension, $allowed_file_extensions)) {
      if (!move_uploaded_file($tmp_name, $student_documents . $degree_mark_list)) {
        echo json_encode(['status' => 503, 'message' => 'Unable to upload Degree Mark List!']);
        exit();
      } else {
        $degree_mark_list = str_replace('../..', '', $student_documents) . $degree_mark_list;
        $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Degree Mark List'");
        if ($check->num_rows > 0) {
           $update = $conn->query("UPDATE Student_Documents SET Location = '$degree_mark_list' WHERE Student_ID = $inserted_id AND Type = 'Degree Mark List'");
        } else {
          $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Degree Mark List', '$degree_mark_list')");
        }
      }
    } else {
      echo json_encode(['status' => 302, 'message' => 'Degree Mark List should be image!']);
      exit();
    }
  }
// echo "<pre>"; print_r($_FILES);die;
// Plus two
if (isset($_FILES["plus_two"]["tmp_name"]) && $_FILES["plus_two"]['tmp_name'] != '' && count(array_filter($_FILES["plus_two"]['tmp_name'])) > 0) {
  foreach ($_FILES["plus_two"]["tmp_name"] as $key => $tmp_name) {
    $plus_two = mysqli_real_escape_string($conn, $_FILES["plus_two"]["name"][$key]);
    $tmp_name = $_FILES["plus_two"]["tmp_name"][$key];
    $plus_two_extension = pathinfo($plus_two, PATHINFO_EXTENSION);
    $plus_two_name = $inserted_id . "_plus_two_" . $key . "." . $plus_two_extension;
    if (in_array($plus_two_extension, $allowed_file_extensions)) {
      if (file_exists($student_documents . $plus_two_name)) {
        unlink($student_documents . $plus_two_name);
      }
      if (move_uploaded_file($tmp_name, $student_documents . $plus_two_name)) {
        $plus_twos[] = str_replace('../..', '', $student_documents) . $plus_two_name;
      } else {
        echo json_encode(['status' => 503, 'message' => 'Unable to upload Plus Two!']);
        exit();
      }
    } else {
      echo json_encode(['status' => 302, 'message' => 'Plus Two should be image!']);
      exit();
    }
  }
 $plus_two = implode("|", $plus_twos);
  $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Plus Two'");
  if ($check->num_rows > 0) {
    $update = $conn->query("UPDATE Student_Documents SET Location = '$plus_two' WHERE Student_ID = $inserted_id AND Type = 'Plus Two'");
  } else {
    $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Plus Two', '$plus_two')");
  }
}

// SSLC
if (isset($_FILES["sslc"]["tmp_name"]) && $_FILES["sslc"]['tmp_name'] != '' && count(array_filter($_FILES["sslc"]['tmp_name'])) > 0) {
  foreach ($_FILES["sslc"]["tmp_name"] as $key => $tmp_name) {
    $sslc = mysqli_real_escape_string($conn, $_FILES["sslc"]["name"][$key]);
    $tmp_name = $_FILES["sslc"]["tmp_name"][$key];
    $sslc_extension = pathinfo($sslc, PATHINFO_EXTENSION);
    $sslc_name = $inserted_id . "_sslc_" . $key . "." . $sslc_extension;
    if (in_array($sslc_extension, $allowed_file_extensions)) {
      if (file_exists($student_documents . $sslc_name)) {
        unlink($student_documents . $sslc_name);
      }
      if (move_uploaded_file($tmp_name, $student_documents . $sslc_name)) {
        $sslcs[] = str_replace('../..', '', $student_documents) . $sslc_name;
      } else {
        echo json_encode(['status' => 503, 'message' => 'Unable to upload SSLC!']);
        exit();
      }
    } else {
      echo json_encode(['status' => 302, 'message' => 'SSLC should be image!']);
      exit();
    }
  }
 $sslc = implode("|", $sslcs);
  $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'SSLC'");
  if ($check->num_rows > 0) {
    $update = $conn->query("UPDATE Student_Documents SET Location = '$sslc' WHERE Student_ID = $inserted_id AND Type = 'SSLC'");
  } else {
    $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'SSLC', '$sslc')");
  }
}

// Application Form
if (isset($_FILES["app_form"]["tmp_name"]) && $_FILES["app_form"]['tmp_name'] != '' && count(array_filter($_FILES["app_form"]['tmp_name'])) > 0) {
  foreach ($_FILES["app_form"]["tmp_name"] as $key => $tmp_name) {
    $app_form = mysqli_real_escape_string($conn, $_FILES["app_form"]["name"][$key]);
    $tmp_name = $_FILES["app_form"]["tmp_name"][$key];
    $app_form_extension = pathinfo($app_form, PATHINFO_EXTENSION);
    $app_form_name = $inserted_id . "_app_form_" . $key . "." . $app_form_extension;
    if (in_array($app_form_extension, $allowed_file_extensions)) {
      if (file_exists($student_documents . $app_form_name)) {
        unlink($student_documents . $app_form_name);
      }
      if (move_uploaded_file($tmp_name, $student_documents . $app_form_name)) {
        $app_forms[] = str_replace('../..', '', $student_documents) . $app_form_name;
      } else {
        echo json_encode(['status' => 503, 'message' => 'Unable to upload Application Form!']);
        exit();
      }
    } else {
      echo json_encode(['status' => 302, 'message' => 'Application Form should be image!']);
      exit();
    }
  }
 $app_form = implode("|", $app_forms);
  $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Application Form'");
  if ($check->num_rows > 0) {
    $update = $conn->query("UPDATE Student_Documents SET Location = '$app_form' WHERE Student_ID = $inserted_id AND Type = 'Application Form'");
  } else {
    $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Application Form', '$app_form')");
  }
}
// Birth Certificate
if (isset($_FILES["birth_certificate"]["tmp_name"]) && $_FILES["birth_certificate"]['tmp_name'] != '' && count(array_filter($_FILES["birth_certificate"]['tmp_name'])) > 0) {
  foreach ($_FILES["birth_certificate"]["tmp_name"] as $key => $tmp_name) {
    $birth_certificate = mysqli_real_escape_string($conn, $_FILES["birth_certificate"]["name"][$key]);
    $tmp_name = $_FILES["birth_certificate"]["tmp_name"][$key];
    $birth_certificate_extension = pathinfo($birth_certificate, PATHINFO_EXTENSION);
    $birth_certificate_name = $inserted_id . "_birth_certificate_" . $key . "." . $birth_certificate_extension;
    if (in_array($birth_certificate_extension, $allowed_file_extensions)) {
      if (file_exists($student_documents . $birth_certificate_name)) {
        unlink($student_documents . $birth_certificate_name);
      }
      if (move_uploaded_file($tmp_name, $student_documents . $birth_certificate_name)) {
        $birth_certificates[] = str_replace('../..', '', $student_documents) . $birth_certificate_name;
      } else {
        echo json_encode(['status' => 503, 'message' => 'Unable to upload Birth Certificate!']);
        exit();
      }
    } else {
      echo json_encode(['status' => 302, 'message' => 'Birth Certificate should be image!']);
      exit();
    }
  }
 $birth_certificate = implode("|", $birth_certificates);
  $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Birth Certificate'");
  if ($check->num_rows > 0) {
    $update = $conn->query("UPDATE Student_Documents SET Location = '$birth_certificate' WHERE Student_ID = $inserted_id AND Type = 'Birth Certificate'");
  } else {
    $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Birth Certificate', '$birth_certificate')");
  }
}

// Certificate
if (isset($_FILES["certificate"]["tmp_name"]) && $_FILES["certificate"]['tmp_name'] != '' && count(array_filter($_FILES["certificate"]['tmp_name'])) > 0) {
  foreach ($_FILES["certificate"]["tmp_name"] as $key => $tmp_name) {
    $certificate = mysqli_real_escape_string($conn, $_FILES["certificate"]["name"][$key]);
    $tmp_name = $_FILES["certificate"]["tmp_name"][$key];
    $certificate_extension = pathinfo($certificate, PATHINFO_EXTENSION);
    $certificate_name = $inserted_id . "_certificate_" . $key . "." . $certificate_extension;
    if (in_array($certificate_extension, $allowed_file_extensions)) {
      if (file_exists($student_documents . $certificate_name)) {
        unlink($student_documents . $certificate_name);
      }
      if (move_uploaded_file($tmp_name, $student_documents . $certificate_name)) {
        $certificates[] = str_replace('../..', '', $student_documents) . $certificate_name;
      } else {
        echo json_encode(['status' => 503, 'message' => 'Unable to upload Certificate!']);
        exit();
      }
    } else {
      echo json_encode(['status' => 302, 'message' => 'Certificate should be image!']);
      exit();
    }
  }
 $certificate = implode("|", $certificates);
  $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Certificate'");
  if ($check->num_rows > 0) {
    $update = $conn->query("UPDATE Student_Documents SET Location = '$certificate' WHERE Student_ID = $inserted_id AND Type = 'Certificate'");
  } else {
    $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Certificate', '$certificate')");
  }
}
// degree
if (isset($_FILES["degree"]["tmp_name"]) && $_FILES["degree"]['tmp_name'] != '' && count(array_filter($_FILES["degree"]['tmp_name'])) > 0) {
  foreach ($_FILES["degree"]["tmp_name"] as $key => $tmp_name) {
    $degree = mysqli_real_escape_string($conn, $_FILES["degree"]["name"][$key]);
    $tmp_name = $_FILES["degree"]["tmp_name"][$key];
    $degree_extension = pathinfo($degree, PATHINFO_EXTENSION);
    $degree_name = $inserted_id . "_degree_" . $key . "." . $degree_extension;
    if (in_array($degree_extension, $allowed_file_extensions)) {
      if (file_exists($student_documents . $degree_name)) {
        unlink($student_documents . $degree_name);
      }
      if (move_uploaded_file($tmp_name, $student_documents . $degree_name)) {
        $degrees[] = str_replace('../..', '', $student_documents) . $degree_name;
      } else {
        echo json_encode(['status' => 503, 'message' => 'Unable to upload Degree!']);
        exit();
      }
    } else {
      echo json_encode(['status' => 302, 'message' => 'Degree should be image!']);
      exit();
    }
  }
 $degree = implode("|", $degrees);
  $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Degree'");
  if ($check->num_rows > 0) {
    $update = $conn->query("UPDATE Student_Documents SET Location = '$degree' WHERE Student_ID = $inserted_id AND Type = 'Degree'");
  } else {
    $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Degree', '$degree')");
  }
}
// Deploma
if (isset($_FILES["deploma"]["tmp_name"]) && $_FILES["deploma"]['tmp_name'] != '' && count(array_filter($_FILES["deploma"]['tmp_name'])) > 0) {
  foreach ($_FILES["deploma"]["tmp_name"] as $key => $tmp_name) {
    $deploma = mysqli_real_escape_string($conn, $_FILES["deploma"]["name"][$key]);
    $tmp_name = $_FILES["deploma"]["tmp_name"][$key];
    $deploma_extension = pathinfo($deploma, PATHINFO_EXTENSION);
    $deploma_name = $inserted_id . "_deploma_" . $key . "." . $deploma_extension;
    if (in_array($deploma_extension, $allowed_file_extensions)) {
      if (file_exists($student_documents . $deploma_name)) {
        unlink($student_documents . $deploma_name);
      }
      if (move_uploaded_file($tmp_name, $student_documents . $deploma_name)) {
        $deplomas[] = str_replace('../..', '', $student_documents) . $deploma_name;
      } else {
        echo json_encode(['status' => 503, 'message' => 'Unable to upload Deploma!']);
        exit();
      }
    } else {
      echo json_encode(['status' => 302, 'message' => 'Deploma should be image!']);
      exit();
    }
  }



 $deploma = implode("|", $deplomas);
  $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Deploma'");
  if ($check->num_rows > 0) {
    $update = $conn->query("UPDATE Student_Documents SET Location = '$deploma' WHERE Student_ID = $inserted_id AND Type = 'Deploma'");
  } else {
    $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Deploma', '$deploma')");
  }
}

  // provisional_certificate
  if (isset($_FILES["provisional_certificate"]["tmp_name"]) && $_FILES["provisional_certificate"]['tmp_name'] != '' && count(array_filter($_FILES["provisional_certificate"]['tmp_name'])) > 0) {
    foreach ($_FILES["provisional_certificate"]["tmp_name"] as $key => $tmp_name) {
      $provisional_certificate = mysqli_real_escape_string($conn, $_FILES["provisional_certificate"]["name"][$key]);
      $tmp_name = $_FILES["provisional_certificate"]["tmp_name"][$key];
      $provisional_certificate_extension = pathinfo($provisional_certificate, PATHINFO_EXTENSION);
      $provisional_certificate_name = $inserted_id . "_provisional_certificate_" . $key . "." . $provisional_certificate_extension;
      if (in_array($provisional_certificate_extension, $allowed_file_extensions)) {
        if (file_exists($student_documents . $provisional_certificate_name)) {
          unlink($student_documents . $provisional_certificate_name);
        }
        if (move_uploaded_file($tmp_name, $student_documents . $provisional_certificate_name)) {
          $provisional_certificates[] = str_replace('../..', '', $student_documents) . $provisional_certificate_name;
        } else {
          echo json_encode(['status' => 503, 'message' => 'Unable to upload Provisional Certificate!']);
          exit();
        }
      } else {
        echo json_encode(['status' => 302, 'message' => 'Provisional Certificate should be image!']);
        exit();
      }
    }
   $provisional_certificate = implode("|", $provisional_certificates);
  
   
    $check = $conn->query("SELECT ID FROM Student_Documents WHERE Student_ID = $inserted_id AND Type = 'Provisional Certificate'");
    if ($check->num_rows > 0) {
      $update = $conn->query("UPDATE Student_Documents SET Location = '$provisional_certificate' WHERE Student_ID = $inserted_id AND Type = 'Provisional Certificate'");
    } else {
      $update = $conn->query("INSERT INTO Student_Documents (Student_ID, Type, Location) VALUES ($inserted_id, 'Provisional Certificate', '$provisional_certificate')");
    }
  }

  
  if ($update) {
    $id = base64_encode('W1Ebt1IhGN3ZOLplom9I' . $inserted_id);
    if ($step < 4) {
      $conn->query("UPDATE Students SET Step = 4 WHERE ID = $inserted_id");
    }
    
    $inserted_ids =  base64_encode($inserted_id .'W1Ebt1IhGN3ZOLplom9I'); 
    echo json_encode(['status' => 200, 'message' => 'Step 4 details saved successfully!', 'id' => $inserted_ids]);
  } else {
    echo json_encode(['status' => 400, 'message' => 'Something went wrong!']);
  }
}

<?php

require '../../includes/db-config.php';

if (isset($_POST['university'])) {
    $id_uni = $_POST['university'];
    session_start();
    $row =[];
    
    if ($id_uni == "All") {
        $sql = '';
    } else {
        $sql = "AND University_ID=" . $id_uni;
    }
    $option = "<option value=''>Select Admission Session</option>";


    $university_id = $conn->query("SELECT Admission_Sessions.Name , Admission_Sessions.ID, Universities.Name AS uni_name FROM  Admission_Sessions LEFT JOIN Universities ON Admission_Sessions.University_ID= Universities.ID WHERE Admission_Sessions.Status =1 $sql");

    while ($row = $university_id->fetch_assoc()) {

        if ($id_uni == "All") {
            $u_name = '  ('.$row['uni_name'].')';
        } else {
            $u_name='';
        }

        $option .= '<option value="' . $row['Name'] . '">' . $row['Name'].$u_name.'</option>';
    }
    echo $option;
}

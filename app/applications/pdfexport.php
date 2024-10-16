<?php
require '../../includes/db-config.php';
require '../../includes/helpers.php';
// session_start();
use setasign\Fpdi\PdfReader;
use setasign\Fpdi\Fpdi;

ob_end_clean();
require_once('../../extras/TCPDF/tcpdf.php');
require_once('../../extras/vendor/setasign/fpdf/fpdf.php');
require_once('../../extras/vendor/setasign/fpdi/src/autoload.php');
require '../../extras/vendor/autoload.php';
ini_set('display_errors', 1);
session_start();














if (isset($_SESSION['current_session'])) {
    if ($_SESSION['current_session'] == 'All') {
        $session_query = '';
    } else {
        $session_query = "AND Admission_Sessions.Name like '%" . $_SESSION['current_session'] . "%'";
    }
} else {
    $get_current_session = $conn->query("SELECT Name FROM Admission_Sessions WHERE Current_Status = 1");
    if ($get_current_session->num_rows > 0) {
        $gsc = mysqli_fetch_assoc($get_current_session);
        $session_query = ''; //kp
        // $session_query = "AND Admission_Sessions.Name like '%" . $gsc['Name'] . "%'";
    } else {
        $session_query = '';
    }
}

$role_query = str_replace('{{ table }}', 'Students', $_SESSION['RoleQuery']);
$role_query = str_replace('{{ column }}', 'Added_For', $role_query);
$step_query = "";









## Search 
$searchQuery = " ";














$filterByDepartment = "";
if (isset($_SESSION['filterByDepartment'])) {
    $filterByDepartment = $_SESSION['filterByDepartment'];
}

$filterBySubCourse = "";
if (isset($_SESSION['filterBySubCourses'])) {
    $filterBySubCourse = $_SESSION['filterBySubCourses'];
}

$filterByStatus = "";
if (isset($_SESSION['filterByStatus'])) {
    $filterByStatus = $_SESSION['filterByStatus'];
}

$filterQueryUser = "";
if (isset($_SESSION['filterByUser'])) {
    $filterQueryUser = $_SESSION['filterByUser'];
}

$filterByDate = "";
if (isset($_SESSION['filterByDate'])) {
    $filterByDate = $_SESSION['filterByDate'];
}

$filterByFeeAssigned = "";
if (isset($_SESSION['filterByFeeAssigned'])) {
    $filterByFeeAssigned = $_SESSION['filterByFeeAssigned'];
}

$searchQuery .= $filterByDepartment . $filterQueryUser . $filterByDate . $filterBySubCourse . $filterByStatus . $filterByFeeAssigned;
//University Head

$whereQuery = "";
if ($_SESSION['Role'] == 'University Head') {
    $universities = implode(',', $_SESSION['Alloted_Universities']);
    $whereQuery = " AND Students.University_ID IN ($universities)";
}













// echo $searchQuery; die;

## Total number of records without filtering














## Total number of record with filtering




















## Fetch records
$result_record = "SELECT Student_Pendencies.ID as Pendency,Students.University_ID, Student_Pendencies.Status as Pendency_Status, UPPER(DATE_FORMAT(Students.DOB, '%d%b%Y')) as DOB, Students.Status, Students.ID, Students.Added_For, CONCAT(TRIM(CONCAT(Students.First_Name, ' ', Students.Middle_Name, ' ', Students.Last_Name)), ' (', IF(Students.Unique_ID='' OR Students.Unique_ID IS NULL, RIGHT(CONCAT('000000', Students.ID), 6), Students.Unique_ID), ')') as Unique_ID, CONCAT(Students.First_Name, ' ', Students.Middle_Name, ' ', Students.Last_Name) as First_Name,Students.Course_ID, Students.Sub_Course_ID, Students.Father_Name, Students.Enrollment_No, Students.OA_Number, Students.Duration,Students.Course_Category, Students.Step, Students.Process_By_Center, Students.Payment_Received, Students.Document_Verified, Students.Processed_To_University, Admission_Sessions.`Name` as Adm_Session, Admission_Types.`Name` as Adm_Type, CONCAT(Courses.Short_Name, ' (', Sub_Courses.Name, ')') as Short_Name, Student_Documents.`Location`, Students.ID_Card, Students.Admit_Card, Students.Exam, Studnent_Sub_Course_Fee.Fee AS sub_course_fee, Universities.Name as university_name,Courses.Name as courseName FROM Students
 LEFT JOIN Universities on Students.University_ID = Universities.ID
  LEFT JOIN Student_Pendencies ON Students.ID = Student_Pendencies.Student_ID AND Student_Pendencies.Status != 1 
  LEFT JOIN Admission_Sessions ON Students.Admission_Session_ID = Admission_Sessions.ID
   LEFT JOIN Admission_Types ON Students.Admission_Type_ID = Admission_Types.ID 
   LEFT JOIN Courses ON Students.Course_ID = Courses.ID 
   LEFT JOIN Sub_Courses ON Students.Sub_Course_ID = Sub_Courses.ID 
   LEFT JOIN Student_Documents ON Students.ID = Student_Documents.Student_ID AND Student_Documents.`Type` = 'Photo' 
   LEFT JOIN Studnent_Sub_Course_Fee ON Students.ID = Studnent_Sub_Course_Fee.Student_ID WHERE Students.University_ID IS NOT NULL $whereQuery $searchQuery $role_query $step_query $session_query";
$empRecords = mysqli_query($conn, $result_record);
$data = array();
// print_r($result_record) ;die('SD');






















while ($row = mysqli_fetch_assoc($empRecords)) {
    // Added_For
    if ($_SESSION['Role'] == 'Center') {
        $user = $conn->query("SELECT ID, Code, Name FROM Users WHERE ID = " . $row['Added_For'] . "");
        if ($user->num_rows == 0) {
            $user = $conn->query("SELECT Users.ID, Code, Name FROM Users LEFT JOIN Center_SubCenter ON Users.ID = Center_SubCenter.Center WHERE `Sub_Center` = " . $row['Added_For']);
        }
    } else {
        $user = $conn->query("SELECT ID, Code, Name FROM Users WHERE ID = " . $row['Added_For'] . " AND Role = 'Center'");
        if ($user->num_rows == 0) {
            $user = $conn->query("SELECT Users.ID, Code, Name FROM Users LEFT JOIN Center_SubCenter ON Users.ID = Center_SubCenter.Center WHERE `Sub_Center` = " . $row['Added_For']);
        }
    }

    $user = mysqli_fetch_array($user);
    // Sub_Center Name 
    $sub_centers = [];
    $sub_centers['Name'] = "";
    if (!empty($user)) {
        $sub_centers = $conn->query("SELECT Users.ID, Code, Name FROM Users LEFT JOIN Center_SubCenter ON Users.ID = Center_SubCenter.Sub_Center WHERE `Sub_Center` = " . $row['Added_For']);
        $sub_centers = mysqli_fetch_array($sub_centers);
    } else {
        $sub_centers = '';
    }

    // RM
    $rm['Name'] = "";
    if (!empty($user)) {
        // RM
        $rm = $conn->query("SELECT CONCAT(Users.Name, ' (', Users.Code, ')') as Name FROM Alloted_Center_To_Counsellor LEFT JOIN Users ON Alloted_Center_To_Counsellor.Counsellor_ID = Users.ID  WHERE Alloted_Center_To_Counsellor.Code = " . $user['ID'] . " ");
        if ($rm->num_rows > 0) {
            $rm = mysqli_fetch_array($rm);
        } else {
            $rm = $user;
        }
    }

    $get_ledger_stu_data = $conn->query("SELECT Fee,Type FROM Student_Ledgers WHERE Student_ID = '" . $row['ID'] . "'");
    $debited_fee = 0;
    $credited_fee = [];
    $fee_arr = [];
    while ($fee_arr = $get_ledger_stu_data->fetch_assoc()) {
        if ($fee_arr['Type'] == 1) {
            $debited_fee = $fee_arr['Fee'];
        }
        if ($fee_arr['Type'] == 2) {
            $credited_fee[] = $fee_arr['Fee'];
        }
    }
    if ($debited_fee == 0) {
        $course_fee = "Fee Not Initiated";
    } else {
        $course_fee = array_sum($credited_fee) . '/' . $debited_fee;
    }
    $data[] = array(
        "University_ID" => $row['University_ID'],
        "Photo" => empty($row['Location']) ? '/assets/img/default-user.png' : $row['Location'],
        "First_Name" => $row['First_Name'],
        "Father_Name" => $row['Father_Name'],
        "Unique_ID" => $row['Unique_ID'],
        "Enrollment_No" => !empty($row['Enrollment_No']) ? $row['Enrollment_No'] : '',
        "OA_Number" => !empty($row['OA_Number']) ? $row['OA_Number'] : '',
        "Duration" => $row['Duration'],
        "Step" => $row['Step'],
        "Process_By_Center" => !empty($row['Process_By_Center']) ? date("d-m-Y", strtotime($row['Process_By_Center'])) : "1",
        "Payment_Received" => !empty($row['Payment_Received']) ? date("d-m-Y", strtotime($row['Payment_Received'])) : "1",
        "Document_Verified" => !empty($row['Document_Verified']) ? date("d-m-Y", strtotime($row['Document_Verified'])) : "1",
        "Processed_To_University" => !empty($row['Processed_To_University']) ? date("d-m-Y", strtotime($row['Processed_To_University'])) : '1',
        "Adm_Session" => $row['Adm_Session'],
        "courseName" => $row['courseName'],
        "Adm_Type" => $row['Adm_Type'],
        "Short_Name" => $row['Short_Name'],
        "Center_Code" => isset($user['Code']) ? $user['Code'] : '',
        "Center_Name" => isset($user['Name']) ? $user['Name'] : '',
        "Sub_Center_Name" => (!empty($sub_centers['Name']) && $_SESSION['Role'] != 'Center') ? $sub_centers['Name'] : '',
        "RM" => $rm['Name'],
        "Status" => $row['Status'],
        "DOB" => $row['DOB'],
        "ID_Card" => $row['ID_Card'],
        "Admit_Card" => $row['Admit_Card'],
        "Exam" => $row['Exam'],
        "Pendency" => empty($row['Pendency']) ? 0 : (int)$row['Pendency'],
        "Pendency_Status" => empty($row['Pendency_Status']) ? 0 : (int)$row['Pendency_Status'],
        "ID" => base64_encode($row['ID'] . 'W1Ebt1IhGN3ZOLplom9I'),
        "Course_ID" => $row['Course_ID'],
        "Sub_Course_ID" => $row['Sub_Course_ID'],
        "Added_For" => $row['Added_For'],
        "Fee" => $course_fee,
        "university_name" => $row['university_name'],
    );
    // print_r($data);


}
if (isset($_REQUEST['format']) && $_REQUEST['format'] == 'pdf') {
    if (count($data) > 0) {
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Application Data');
        $pdf->SetSubject('PDF Export of Application Data');
        $pdf->SetHeaderData('', 0, 'Application Data', 'Generated on: ' . date('Y-m-d'));
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetMargins(10, 20, 10);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        $pdf->SetAutoPageBreak(TRUE, 15);

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->AddPage('L', 'A2');

        $html = '<h3 style="text-align:center;">Application Data</h3>';
        $html .= '<table border="1" cellpadding="2">';
        $html .= '<thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Status</th>
                        <th>Processed By Center</th>
                        <th>Document Verified</th>
                        <th>Payment Received</th>
                        <th>Processed To University</th>
                        <th>Enrollment No</th>
                        <th>Adm Session</th>
                        <th>Adm Type</th>
                        <th>Pendency</th>
                        <th>Father Name</th>
                        <th>Course Name</th>
                        <th>Fee</th>
                        <th>Duration</th>
                        <th>DOB</th>
                        <th>Center Code</th>
                        <th>Center Name</th>
                        <th>University Name</th>
                    </tr>
                </thead>';
        $html .= '<tbody>';
        foreach ($data as $row) {
            $html .= '<tr>
                        <td>' . $row['First_Name'] . '</td>
                        <td>' . $row['Status'] . '</td>
                        <td>' . $row['Process_By_Center'] . '</td>
                        <td>' . $row['Document_Verified'] . '</td>
                        <td>' . $row['Payment_Received'] . '</td>
                        <td>' . $row['Processed_To_University'] . '</td>
                        <td>' . $row['Enrollment_No'] . '</td>
                        <td>' . $row['Adm_Session'] . '</td>
                        <td>' . $row['Adm_Type'] . '</td>
                        <td>' . $row['Pendency'] . '</td>
                        <td>' . $row['Father_Name'] . '</td>
                        <td>' . $row['courseName'] . '</td>
                        <td>' . $row['Fee'] . '</td>
                        <td>' . $row['Duration'] . '</td>
                        <td>' . $row['DOB'] . '</td>
                        <td>' . $row['Center_Code'] . '</td>
                        <td>' . $row['Center_Name'] . '</td>
                        <td>' . $row['university_name'] . '</td>
                    </tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $filename = "application_data_" . date('Y-m-d') . ".pdf";
        $pdf->Output($filename, 'D');
        exit();
    } else {
        echo "No data available to download.";
    }
}

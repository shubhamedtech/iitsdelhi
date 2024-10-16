<?php
ini_set('display_errors', 1);
include '../../includes/db-config.php';
session_start();
$draw = $_POST['draw'] ?? '';
$row = $_POST['start'] ?? 0;
$rowperpage = $_POST['length'] ?? 10;
$columnIndex = $_POST['order'][0]['column'] ?? null;
$columnName = $_POST['columns'][$columnIndex]['data'] ?? null;
$columnSortOrder = $_POST['order'][0]['dir'] ?? 'DESC';
$searchValue = $_POST['search']['value'] ?? '';
$orderby = $columnName ? "ORDER BY $columnName $columnSortOrder" : "ORDER BY Students.ID DESC";




// Initialize query parts
$session_query = '';
$sessionParams = [];
$sessionTypes = '';


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
        $session_query = '';
    } else {
        $session_query = '';
    }
}

// Search query
$searchQuery = '';
if (!empty($searchValue)) {
    if (strcasecmp($searchValue, 'completed') == 0) {
        $searchQuery .= "AND Step = 4 ";
    } else {
        $searchTerms = [
            "Students.First_Name LIKE '%$searchValue%'",
            "Students.Middle_Name LIKE '%$searchValue%'",
            "Students.Last_Name LIKE '%$searchValue%'"
        ];
        $searchQuery .= ' AND (' . implode(' OR ', $searchTerms) . ')';
    }
}

// Apply additional filters

$filterQueries = [
    'filterByDepartment' => $_SESSION['filterByDepartment'] ?? '',
    'filterBySubCourses' => $_SESSION['filterBySubCourses'] ?? '',
    'filterByUsers' => $_SESSION['filterByUsers'] ?? '',
    'filterByFeeAssigned' => $_SESSION['filterByFeeAssigned'] ?? '',
    'filterByDate' => $_SESSION['filterByDate'] ?? ''
];

$pro_filter = implode(' ', $filterQueries);








// Build the main queries
$allCountQuery = $conn->query("SELECT COUNT(Students.ID) as allcount
    FROM Students 
    LEFT JOIN Universities ON Students.University_ID = Universities.ID 
    LEFT JOIN Admission_Sessions ON Students.Admission_Session_ID = Admission_Sessions.ID 
    LEFT JOIN Admission_Types ON Students.Admission_Type_ID = Admission_Types.ID 
    LEFT JOIN Courses ON Students.Course_ID = Courses.ID 
    LEFT JOIN Sub_Courses ON Students.Sub_Course_ID = Sub_Courses.ID
    LEFT JOIN Users ON Students.Added_For = Users.ID 
    LEFT JOIN Student_Ledgers ON Students.ID = Student_Ledgers.Student_ID WHERE 1=1 $pro_filter $searchQuery $session_query");
// Check if the query execution was successful
if (!$allCountQuery) {
    die("All Count Query Failed: " . $conn->error);
}
$recordsFiletr = $allCountQuery->fetch_assoc();
$totalRecordwithFilter = $recordsFiletr['allcount'];
// print_r($totalRecordwithFilter);
// die();




































// $amountQuery = "SELECT COUNT(Student_ID) as total, SUM(Fee) as totalFee  FROM `Student_Ledgers`  
// WHERE 1=1 and Student_Ledgers.Type=2 $pro_filter $searchQuery $session_query order by Student_Ledgers.Created_At";
// $amountResult = mysqli_query($conn, $amountQuery);
// if (!$amountResult) {
//     die("Data Query Failed: " . $conn->error);
// }
// $amount = $amountResult->fetch_assoc();
// print_r($amount);








$filterCountQuery = $conn->query("SELECT 
    COUNT(Students.ID) AS filtered
FROM Students 
LEFT JOIN Universities ON Students.University_ID = Universities.ID 
LEFT JOIN Admission_Sessions ON Students.Admission_Session_ID = Admission_Sessions.ID 
LEFT JOIN Admission_Types ON Students.Admission_Type_ID = Admission_Types.ID 
LEFT JOIN Courses ON Students.Course_ID = Courses.ID 
LEFT JOIN Sub_Courses ON Students.Sub_Course_ID = Sub_Courses.ID
LEFT JOIN Users ON Students.Added_For = Users.ID 
LEFT JOIN Student_Ledgers ON Students.ID = Student_Ledgers.Student_ID 
WHERE 1=1 $pro_filter $searchQuery $session_query");

// Check if the query execution was successful
if (!$filterCountQuery) {
    die("Filter Count Query Failed: " . $conn->error);
}
$records = $filterCountQuery->fetch_assoc();
$totalRecords = $records['filtered'];


































$dataQuery = "SELECT Students.ID, CONCAT(Students.First_Name, ' ', Students.Middle_Name, ' ', Students.Last_Name) as First_Name,
    UPPER(DATE_FORMAT(Students.DOB, '%d-%b-%Y')) as DOB, Admission_Sessions.Name as Adm_Session,
    Students.University_ID, Users.Code AS center_Code, Users.Name AS center_Name,
    Universities.Name as university_name, CONCAT(Courses.Short_Name, ' (', Sub_Courses.Name, ')') as course_name, Students.Duration,Students.Added_For,
    Student_Ledgers.Created_At AS transaction_date
    FROM Students 
    LEFT JOIN Universities ON Students.University_ID = Universities.ID 
    LEFT JOIN Admission_Sessions ON Students.Admission_Session_ID = Admission_Sessions.ID 
    LEFT JOIN Admission_Types ON Students.Admission_Type_ID = Admission_Types.ID 
    LEFT JOIN Courses ON Students.Course_ID = Courses.ID 
    LEFT JOIN Sub_Courses ON Students.Sub_Course_ID = Sub_Courses.ID
    LEFT JOIN Users ON Students.Added_For = Users.ID 
    LEFT JOIN Student_Ledgers ON Students.ID = Student_Ledgers.Student_ID WHERE 1=1
    $pro_filter $searchQuery $session_query
     group by Students.ID $orderby LIMIT " . $row . "," . $rowperpage;
$dataResult = mysqli_query($conn, $dataQuery);
if (!$dataResult) {
    die("Data Query Failed: " . $conn->error);
}

// print_r($dataQuery);die;

















$amountQuery = "SELECT COUNT(Student_Ledgers.Student_ID) as total, SUM(Student_Ledgers.Fee) as totalFee  FROM `Students`  
LEFT JOIN Universities ON Students.University_ID = Universities.ID 
    LEFT JOIN Admission_Sessions ON Students.Admission_Session_ID = Admission_Sessions.ID 
    LEFT JOIN Admission_Types ON Students.Admission_Type_ID = Admission_Types.ID 
    LEFT JOIN Courses ON Students.Course_ID = Courses.ID 
    LEFT JOIN Sub_Courses ON Students.Sub_Course_ID = Sub_Courses.ID
    LEFT JOIN Users ON Students.Added_For = Users.ID 
    LEFT JOIN Student_Ledgers ON Students.ID = Student_Ledgers.Student_ID
WHERE 1=1 and Student_Ledgers.Type=2 $pro_filter $searchQuery $session_query order by Student_Ledgers.Created_At";

$amountResult = mysqli_query($conn, $amountQuery);
if (!$amountResult) {
    die("Data Query Failed: " . $conn->error);
}
// print_r($amountQuery);
// die();
$amount = $amountResult->fetch_assoc();



$data = [];
while ($row = $dataResult->fetch_assoc()) {
    if ($_SESSION['Role'] == 'Center') {
        $user = $conn->query("SELECT ID, Code, Name FROM Users WHERE ID = " . $row['Added_For']);
    } else {
        $user = $conn->query("SELECT ID, Code, Name FROM Users WHERE ID = " . $row['Added_For'] . " AND Role = 'Center'");
        if ($user->num_rows == 0) {
            $user = $conn->query("SELECT Users.ID, Code, Name FROM Users  
            LEFT JOIN Center_SubCenter ON Users.ID = Center_SubCenter.Center WHERE `Sub_Center` = " . $row['Added_For']);
        }
    }
    if (!$user) {
        die("User Query Failed: " . $conn->error);
    }
    $user = mysqli_fetch_array($user);
    $get_ledger_stu_data = $conn->prepare("SELECT Fee, Type FROM Student_Ledgers WHERE Student_ID = ?");
    $get_ledger_stu_data->bind_param('i', $row['ID']);
    $get_ledger_stu_data->execute();
    $ledgerResult = $get_ledger_stu_data->get_result();
    if (!$ledgerResult) {
        die("Ledger Query Failed: " . $conn->error);
    }
    $debited_fee = 0;
    $credited_fee = [];
    while ($fee_arr = $ledgerResult->fetch_assoc()) {
        if ($fee_arr['Type'] == 1) {
            $debited_fee += (float) $fee_arr['Fee'];
        } elseif ($fee_arr['Type'] == 2) {
            $credited_fee[] = (float) $fee_arr['Fee'];
        }
    }
    $course_fee = array_sum($credited_fee);
    $Totalcountfee = 'INST-' . count($credited_fee);
    $pending_fee = $debited_fee - $course_fee;

    $data[] = [
        "First_Name" => $row['First_Name'],
        "transaction_date" => $row['transaction_date'],
        "DOB" => $row['DOB'],
        "Adm_Session" => $row['Adm_Session'],
        "university_name" => $row['university_name'],
        "course_name" => $row['course_name'],
        "Duration" => $row['Duration'],
        "course_debited_fee" => $debited_fee,
        "course_credited_fee" => $course_fee,
        "total_fee_count" => $Totalcountfee,
        "total_fee_pending" => $pending_fee,
        "University_ID" => $row['University_ID'],
        "center_Code" => $user['Code'],
        "center_Name" => $user['Name'],
        'total_amount' => $amount['totalFee']
    ];
    $get_ledger_stu_data->close();
}
// Build the response
$response = [
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
];
echo json_encode($response);
$conn->close();

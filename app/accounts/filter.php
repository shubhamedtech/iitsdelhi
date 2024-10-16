<?php
ini_set('display_errors', 1);
if (isset($_POST['id']) && isset($_POST['by'])) {
    session_start();
    require '../../includes/db-config.php';
    $by = $_POST['by'];
    $id = intval($_POST['id']);
    $sub_center_name = "";



    if ($by == 'departments') {
        $courseIds = $conn->query("SELECT GROUP_CONCAT(ID) as ID FROM Courses WHERE Course_Type_ID = $id AND University_ID = " . $_SESSION['university_id']);
        if ($courseIds->num_rows > 0) {
            $courseIds = $courseIds->fetch_assoc();
            $courseIds = $courseIds['ID'];
             $_SESSION['filterByDepartment'] = !empty($courseIds) ? " AND Students.Course_ID IN ($courseIds)" : " AND Students.ID IS NULL";
        } else {
             $_SESSION['filterByDepartment'] = " AND Students.ID IS NULL";
        }
    } elseif ($by == 'sub_courses') {
     $_SESSION['filterBySubCourses'] = " AND Students.Sub_Course_ID = $id";
    } elseif ($by == 'users') {
        $user = $conn->query("SELECT Role FROM Users WHERE ID = $id")->fetch_assoc();
        $role = $user['Role'];
        $role_query = " AND Students.Added_For = $id";
         $_SESSION['filterByUsers'] = " AND Students.Added_For = $id";
    } else if ($by == 'search_assign_fee') {
        $get_ledger_stu_data = $conn->query("SELECT Student_ID, Fee FROM Student_Ledgers WHERE University_ID = '" . $_SESSION['University_ID'] . "' GROUP BY Student_ID ORDER BY Student_ID ASC");
        $stu_ledger_id_arr = [];
        while ($stu_ledger_id = $get_ledger_stu_data->fetch_assoc()) {
            $stu_ledger_id_arr[] = $stu_ledger_id['Student_ID'];
        }
        $fee_alloted_ids = implode(',', $stu_ledger_id_arr);
        if ($id == 1) {
              $_SESSION['filterByFeeAssigned'] = " AND Students.ID IN (" . $fee_alloted_ids . ")";
        } else {
            $_SESSION['filterByFeeAssigned'] = " AND Students.ID NOT IN (" . $fee_alloted_ids . ")";
        }
    }




    if ($_POST['by'] == 'datetime') {
        $startDate = !empty($_POST['startDate']) ? date("Y-m-d 00:00:00", strtotime($_POST['startDate'])) : null;
        $endDate = !empty($_POST['endDate']) ? date("Y-m-d 23:59:59", strtotime($_POST['endDate'])) : null;
        if ($startDate && $endDate) {
             $_SESSION['filterByDate'] = " AND Student_Ledgers.Created_At BETWEEN '$startDate' AND '$endDate'";
        } else {
             $_SESSION['filterByDate'] = "";
        }
    }





    echo json_encode(['status' => true, 'subCenterName' => $sub_center_name]);
}

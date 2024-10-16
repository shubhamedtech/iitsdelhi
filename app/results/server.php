<?php
## Database configuration
include '../../includes/db-config.php';
session_start();
$sqlQuery = '';
  $sqlQueryData ='';


if(isset($_POST['course_id']) && !empty($_POST['course_id'])){ 
  $course_id = intval($_POST['course_id']);
  $sqlQueryData.= " AND s.Course_ID=$course_id";
}

if(isset($_POST['sub_course_id']) && !empty($_POST['sub_course_id'])){
  list($sub_course_id, $scheme_id) = explode('|', $_POST['sub_course_id']);
  $sqlQueryData.= " AND s.Sub_Course_ID =$sub_course_id AND s.Scheme_ID=$scheme_id";
 }

 if(isset($_POST['stu_id']) && !empty($_POST['stu_id'])){
  $student_id = intval($_POST['stu_id']);
  list($student_id, $duration, $university_id, $enrollment_no) = explode('|', $_POST['stu_id']);
  $sqlQueryData.= " AND m.enrollment_no='$enrollment_no' AND s.University_ID = $university_id";
 }


$orderby = "ORDER BY m.id DESC ";
$query = "";

## Total number of records without filtering
$all_count = $conn->query("SELECT COUNT(m.id) as allcount from marksheets as m LEFT JOIN Syllabi AS s ON m.subject_id=s.ID WHERE m.status=1 $sqlQueryData $query");
$records = mysqli_fetch_assoc($all_count);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$filter_count = $conn->query("SELECT count(m.id) AS filtered, m.*, s.ID,s.Name as subject_name FROM Syllabi AS s LEFT JOIN marksheets AS m ON m.subject_id =s.ID WHERE m.status=1 $sqlQueryData  $sqlQuery");
$records = mysqli_fetch_assoc($filter_count);
 $totalRecordwithFilter = $records['filtered'];

## Fetch records
$result_record = "SELECT m.id,m.enrollment_no, m.max_marks_ext,s.Min_Marks, m.max_marks_int, m.obt_marks AS total, m.status,s.Name AS subject_name  from marksheets as m LEFT JOIN Syllabi AS s ON m.subject_id=s.ID WHERE m.status=1 $sqlQueryData $orderby";
$results = mysqli_query($conn, $result_record);
$data = array();
if ($results->num_rows > 0) {
  while ($row = mysqli_fetch_assoc($results)) {
    if ($row['max_marks_ext'] > $row['Min_Marks']) {
      $status = "Pass";
    } else {
      $status = "Fail";
    }
    $data[] = array(
      "enrollment_no" => $row["enrollment_no"],
      "max_marks_ext" => $row["max_marks_ext"],
      "max_marks_int" => $row["max_marks_int"],
      "total" => $row['total'],
      "status" => $status,
      "subject_name" => $row['subject_name'],
      "ID" => $row["id"],
    ); ?>
    <tr>
      <td><?= $row["enrollment_no"] ?> </td>
      <td><?= $row["subject_name"] ?> </td>
      <td><?= $row["max_marks_ext"] ?> </td>
      <td><?= $row["max_marks_int"] ?> </td>
      <td><?= $row["total"] ?> </td>
      <td><?= $status ?> </td>
    </tr>
  <?php
  } ?>

  <tr><td>Showing <b>1 to <?= $totalRecordwithFilter ?></b> of <?= $totalRecords?> entries</td></tr>
  <?php } else { ?>
    <tr><td></td><td style="text-align: center;"> No Records Found! </td><td></td><td></td></tr>
<?php } ?>


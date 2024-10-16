<?php
// get sub_course
if (isset($_POST['university_ids'])) {
    require '../../includes/db-config.php';
    session_start();

    $university_types = is_array($_POST['university_ids']) ? $_POST['university_ids'] : [];
    
    $university_id = implode(',', $university_types);
    $course_ids =array();
    if(isset($_POST['course_id'])) {
        $course_ids = explode(',', $_POST['course_id']);
       
    }
  
    $course_sql = $conn->query("SELECT ID, CONCAT(Name, ' (',Short_Name, ')') as Name, University_ID FROM Courses WHERE University_ID IN ($university_id) AND Status=1 ORDER BY Name ASC");
     if ($course_sql->num_rows > 0) {
        while ($row = $course_sql->fetch_assoc()) {?>
            <option value="<?= $row['ID'].'|'.$row['University_ID'] ?>" <?= in_array($row['ID'], $course_ids) ? 'selected' : '' ?>><?= $row['Name'] ?></option>

        <?php  }
    } else { ?>
        <option value="">No Courses Found !</option>
<?php }
}
?>
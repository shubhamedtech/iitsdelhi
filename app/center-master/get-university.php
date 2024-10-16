<?php
// get sub_course
if (isset($_POST['head_id'])) {
    require '../../includes/db-config.php';
    session_start();
    // $university_head_types = is_array($_POST['head_id']) ? $_POST['head_id'] : [];

    $head_id = $_POST['head_id'];
    // echo "SELECT ID, CONCAT(Universities.Short_Name, ' (', Universities.Vertical, ')') as Name FROM Universities LEFT JOIN University_User as us ON Universities.ID =us.University_ID WHERE User_ID = $head_id AND  Universities.status=1 group by us.University_ID ORDER BY Universities.ID ASC";die;
    $universities = $conn->query("SELECT ID, CONCAT(Universities.Short_Name, ' (', Universities.Vertical, ')') as Name FROM Universities LEFT JOIN University_User as us ON Universities.ID =us.University_ID WHERE User_ID = $head_id AND  Universities.status=1 group by us.University_ID ORDER BY Universities.ID ASC");

     if ($universities->num_rows > 0) {
        while ($row = $universities->fetch_assoc()) {?>
            <option value="<?= $row['ID'] ?>"><?= $row['Name'] ?></option>

        <?php  }
    } else { ?>
        <option value="">No University Manager Found !</option>
<?php }
}
?>
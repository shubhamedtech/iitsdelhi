<?php
// get sub_course
if (isset($_POST['course_id'])) {
    require '../../includes/db-config.php';

    session_start();
    $center_id = intval($_POST['center_id']);
    $course_types = is_array($_POST['course_id']) ? $_POST['course_id'] : [];

    $course_ids = array();
    $university_ids = array();

    for ($i = 0; $i < count($course_types); $i++) {
        list($course_ids[],  $university_ids[]) = explode('|', $course_types[$i]);
    }

    $course_ids = implode(',', $course_ids);
    $university_ids = implode(',', $university_ids);
    $sub_course = $conn->query("SELECT Sub_Courses.ID, CONCAT(Courses.Short_Name, ' (',Sub_Courses.Short_Name, ')') as Name,Scheme_ID, Sub_Courses.University_ID AS uni_name, u.Name as university_name,Course_ID FROM Sub_Courses LEFT JOIN Courses ON Sub_Courses.Course_ID = Courses.ID LEFT JOIN Universities as u ON Sub_Courses.University_ID= u.ID WHERE Course_ID IN ($course_ids) AND Sub_Courses.University_ID IN ($university_ids) AND Sub_Courses.Status=1 ORDER BY Sub_Courses.Name ASC");

   // $sub_course = $conn->query("SELECT Sub_Courses.ID, CONCAT(Sub_Courses.Name, ' (',Sub_Courses.Short_Name, ')') as Name,Scheme_ID, University_ID, u.Name as university_name,Course_ID FROM Sub_Courses LEFT JOIN Universities as u ON Sub_Courses.University_ID= u.ID WHERE Course_ID IN ($course_ids) AND University_ID IN ($university_ids) AND Sub_Courses.Status=1 ORDER BY Sub_Courses.Name ASC");
?>


    <?php if ($sub_course->num_rows > 0) {
        while ($row = $sub_course->fetch_assoc()) {
            $courses = [];
            $course_types_array = [];
            $alloted_sub_courses = $conn->query("SELECT Fee, Sub_Course_ID, Duration,Admission_Sessions_ID FROM Center_Sub_Courses WHERE `User_ID` = $center_id AND `Course_ID` IN ($course_ids) AND `Sub_Course_ID` = " . $row['ID'] . " AND `University_ID` IN ($university_ids)");
            while ($alloted_sub_course_arr = $alloted_sub_courses->fetch_assoc()) {
                $courses[$alloted_sub_course_arr['Sub_Course_ID']] = $alloted_sub_course_arr['Sub_Course_ID'];
                $course_types_array = explode(',', $alloted_sub_course_arr['Admission_Sessions_ID']);
            }

    ?>
            <div class="row mt-3 mb-2">
                <div class="col-md-3">
                    <div class="form-group" style="border:unset">
                        <dt class="pt-1"><?= $row['university_name']; ?></dt>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" style="border:unset">
                        <dt class="pt-1"><?= $row['Name']; ?></dt>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" style="border:unset">
                        <select class="adm_session full-width" data-init-plugin="select2" name="adm_session[<?= $row['ID']; ?>][]" multiple>
                            <?php
                            $admission_sessions = $conn->query("SELECT ID, Name FROM Admission_Sessions WHERE Status=1 AND University_ID = '".$row['uni_name']."' ORDER BY Name ASC");

                            // $admission_sessions = $conn->query("SELECT ID, Name FROM Admission_Sessions WHERE Status=1 AND University_ID IN ($university_ids) GROUP BY Name ORDER BY Name ASC");
                            while ($admission_sessions_arr = $admission_sessions->fetch_assoc()) {
                                $selected = in_array($admission_sessions_arr['ID'], $course_types_array) ? 'selected' : '';
                            ?>
                                <option value="<?= $admission_sessions_arr['ID'] ?>" <?= $selected; ?>>
                                    <?= $admission_sessions_arr['Name'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group" style="border:unset">
                        <input type="checkbox" value="<?= $row['Course_ID']; ?>" <?= array_key_exists($row['ID'], $courses) ? "checked" : '' ?> name="sub_course[<?= $row['ID']; ?>]" class="sub_course_checkbox">
                    </div>
                </div>
            </div>

        <?php  }
    } else { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group" style="border:unset">
                    <p style="font-size: 18px;text-align: center;font-weight: 700;">No Sub Course Found!</p>
                </div>
            </div>
        </div>
<?php }
}
?>
<script>
    $(document).ready(function() {
        $('#select_all').click(function(event) {
            if (this.checked) {
                $('.sub_course_checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.sub_course_checkbox').each(function() {
                    this.checked = false;
                });
            }
        });
    });
</script>
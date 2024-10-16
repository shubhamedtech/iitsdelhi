<?php
require '../../includes/db-config.php';
session_start();

if ((isset($_GET['course_id']) && isset($_GET['semester'])) || (isset($_GET['duration']) && isset($_GET['course_id']))) {
  $sub_course_id = intval($_GET['course_id']);
  $duration = $_GET['duration'];

  if ($duration != null) {
    $syllabus = $conn->query("SELECT * FROM Syllabi WHERE Semester ='". $duration."' AND Sub_Course_ID = $sub_course_id");

  } else {
    $semester = explode("|", $_GET['semester']);
    $scheme = $semester[0];
    $semester = $semester[1];
    $syllabus = $conn->query("SELECT * FROM Syllabi WHERE Sub_Course_ID = $sub_course_id AND Scheme_ID = $scheme AND Semester = $semester");

  }
  // print_r($syllabus);die;
  ?>
  <div class="col-md-12">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Credit</th>
            <th>Paper Type</th>
            <th>Min/Max Marks</th>
            <th>Syllabus</th>
          </tr>
        </thead>
        <tbody>
          <?php //if($syllabus->num_rows > 0){
            while ($row = $syllabus->fetch_assoc()) { ?>
            <tr>
              <td>
                <?= $row['Code'] ?>
              </td>
              <td>
                <?= $row['Name'] ?>
              </td>
              <td>
                <?= $row['Credit'] ?>
              </td>
              <td>
                <?= $row['Paper_Type'] ?>
              </td>
              <td>
                <?= $row['Min_Marks'] ?>/
                <?= $row['Max_Marks'] ?>
              </td>
              <td>
                <?php if (!is_null($row['Syllabus']) && !empty($row['Syllabus'])) {
                  $files = explode("|", $row['Syllabus']);
                  foreach ($files as $file) { ?>
                    <a href="<?= $file ?>" target="_blank" download="<?= $row['Code'] ?>">Download</a>
                  <?php }
                } ?>
                <?php if (in_array($_SESSION['Role'], ['Administrator', 'University Head', 'Academic'])) { ?>
                  <div class="d-flex">
                    Upload (
                    <span class="text-primary cursor-pointer"
                      onclick="uploadFile('Syllabi', 'Syllabus', <?= $row['ID'] ?>)">PDF</span> /
                    <span class="text-primary cursor-pointer"
                      onclick="uploadFile('Syllabi', 'Syllabus', <?= $row['ID'] ?>)">Video</span>
                    )
                  </div>
                <?php } ?>
              </td>
            </tr>
          <?php }//}else{ ?>
            <!-- <tr><td colspan='6' style="text-align:center">No data available in table</td></tr> -->
          <?php// } ?>

        </tbody>
      </table>
    </div>
  </div>
<?php } else {
  $syllabus = $conn->query("SELECT * FROM Syllabi WHERE University_ID = " . $_SESSION['university_id'] . "");
  ?>
  <div class="col-md-12">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Credit</th>
            <th>Paper Type</th>
            <th>Min/Max Marks</th>
            <th>Syllabus</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $syllabus->fetch_assoc()) { ?>
            <tr>
              <td>
                <?= $row['Code'] ?>
              </td>
              <td>
                <?= $row['Name'] ?>
              </td>
              <td>
                <?= $row['Credit'] ?>
              </td>
              <td>
                <?= $row['Paper_Type'] ?>
              </td>
              <td>
                <?= $row['Min_Marks'] ?>/
                <?= $row['Max_Marks'] ?>
              </td>
              <td>
                <?php if (!is_null($row['Syllabus']) && !empty($row['Syllabus'])) {
                  $files = explode("|", $row['Syllabus']);
                  foreach ($files as $file) { ?>
                    <a href="<?= $file ?>" target="_blank" download="<?= $row['Code'] ?>">Download</a>
                  <?php }
                } ?>
                <?php if (in_array($_SESSION['Role'], ['Administrator', 'University Head', 'Academic'])) { ?><span
                    class="text-primary cursor-pointer"
                    onclick="uploadFile('Syllabi', 'Syllabus', <?= $row['ID'] ?>)">Upload</span>
                <?php } ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
<?php } ?>
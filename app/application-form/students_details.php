<?php
require '../../includes/db-config.php';
session_start();
if (isset($_POST['ID'])) {
    $id = $_POST['ID'];
    $id = str_replace('W1Ebt1IhGN3ZOLplom9I', '', base64_decode($id));
    $stmt = $conn->prepare("SELECT 
            Student_Pendencies.ID as Pendency,
            Students.University_ID, 
            Student_Pendencies.Status as Pendency_Status, 
            UPPER(DATE_FORMAT(Students.DOB, '%d%b%Y')) as DOB, 
            Students.Status, 
            Students.ID, 
            Students.Added_For, 
            CONCAT(
                TRIM(CONCAT(Students.First_Name, ' ', Students.Middle_Name, ' ', Students.Last_Name)), 
                ' (', 
                IF(Students.Unique_ID='' OR Students.Unique_ID IS NULL, RIGHT(CONCAT('000000', Students.ID), 6), Students.Unique_ID), 
                ')'
            ) as Unique_ID, 
            CONCAT(Students.First_Name, ' ', Students.Middle_Name, ' ', Students.Last_Name) as First_Name,
            Students.Course_ID, 
            Students.Sub_Course_ID, 
            Students.Father_Name, 
            Students.Mother_Name, 
            Students.Enrollment_No, 
            Students.OA_Number, 
            Students.Duration,
            Students.Course_Category, 
            Students.Gender, 
            Students.Category, 
             Students.Employement_Status,
             Students.Aadhar_Number,
             Students.Nationality,
            Students.Step, 
            Students.Process_By_Center, 
            Students.Payment_Received, 
            Students.Document_Verified, 
            -- Student_Documents.Type,
            Students.Processed_To_University, 
            Admission_Sessions.`Name` as Adm_Session, 
            Admission_Types.`Name` as Adm_Type, 
            CONCAT(Courses.Short_Name, ' (', Sub_Courses.Name, ')') as Short_Name, 
            -- Student_Documents.`Location`, 
            Students.ID_Card, 
            Students.Admit_Card, 
               Students.Religion, 
               	Students.Email,
                Students.Alternate_Email,
                	Students.Address,
            Students.Marital_Status,
            Courses.Name as Course_name,
            Students.Exam, 
            Users.Name as centerName,
            Users.Code as centerCode,
            Studnent_Sub_Course_Fee.Fee AS sub_course_fee, 
            Universities.Name as university_name
        FROM Students 
        LEFT JOIN Universities ON Students.University_ID = Universities.ID 
        LEFT JOIN Student_Pendencies ON Students.ID = Student_Pendencies.Student_ID
        LEFT JOIN Admission_Sessions ON Students.Admission_Session_ID = Admission_Sessions.ID 
        LEFT JOIN Admission_Types ON Students.Admission_Type_ID = Admission_Types.ID 
        LEFT JOIN Courses ON Students.Course_ID = Courses.ID 
        LEFT JOIN Users ON Students.Added_For = Users.ID
        LEFT JOIN Sub_Courses ON Students.Sub_Course_ID = Sub_Courses.ID 
        -- LEFT JOIN Student_Documents ON Students.ID = Student_Documents.Student_ID
        LEFT JOIN Studnent_Sub_Course_Fee ON Students.ID = Studnent_Sub_Course_Fee.Student_ID 
        WHERE Students.ID = ?
    ");

    $AcademicsQuery = 'SELECT * FROM Student_Academics WHERE Student_ID = ' . $id;
    $result1 = $conn->query($AcademicsQuery);
    $Academicsfiles = 'SELECT * FROM Student_Documents WHERE Student_ID = ' . $id;
    $filedata = $conn->query($Academicsfiles);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($subcourse_arr = $result->fetch_assoc()) {
    } else {
        echo "No student data found.";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "ID is not set.";
}
?>

<div class="modal-header clearfix text-left m-0 p-0">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    <h3>Students Details</h3>
</div>
<div class="modal-body" style="overflow-y: auto; height: 80vh;">
    <center>
        <h4 class="fw-bold" style="color: black; background-color: red;">Basic Details</h4>
    </center>
    <div class="row mb-3">
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="student-name">
                Student Name: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['First_Name']) ? $subcourse_arr['First_Name'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="dob">
                Student DOB: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['DOB']) ? $subcourse_arr['DOB'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="center-name">
                Center Name: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['centerName']) ? $subcourse_arr['centerName'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="adm-session">
                Admission Session: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['Adm_Session']) ? $subcourse_arr['Adm_Session'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="adm-type">
                Admission Type: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['Adm_Type']) ? $subcourse_arr['Adm_Type'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="adm-type">
                Father Name: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['Father_Name']) ? $subcourse_arr['Father_Name'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="adm-type">
                Courses: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['Course_name']) ? $subcourse_arr['Course_name'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="adm-type">
                Mother Name: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['Mother_Name']) ? $subcourse_arr['Mother_Name'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="adm-type">
                Gender: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['Gender']) ? $subcourse_arr['Gender'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="adm-type">
                Category: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['Category']) ? $subcourse_arr['Category'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="adm-type">
                Employent Status: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['Employement_Status']) ? $subcourse_arr['Employement_Status'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="adm-type">
                Material Status: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['Marital_Status']) ? $subcourse_arr['Marital_Status'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="adm-type">
                Religion: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['Religion']) ? $subcourse_arr['Religion'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="adm-type">
                Aadhar: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['Aadhar_Number']) ? $subcourse_arr['Aadhar_Number'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-4">
            <label class="col-form-label text-black fw-bold" for="adm-type">
                Nationality: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['Nationality']) ? $subcourse_arr['Nationality'] : ''; ?>
            </label>
        </div>
    </div>

    <center>
        <h4 class="fw-bold" style="color: black; background-color: red;">Personal Details</h4>
    </center>
    <div class="row mb-3">
        <div class="col-sm-12">
            <label class="col-form-label text-black fw-bold" for="adm-session">
                Email: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['Email']) ? $subcourse_arr['Email'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-12">
            <label class="col-form-label text-black fw-bold" for="adm-type">
                Alternate Email: <span class="text-danger">*</span>
                <?php echo isset($subcourse_arr['Alternate_Email']) ? $subcourse_arr['Alternate_Email'] : ''; ?>
            </label>
        </div>
        <div class="col-sm-12">
            <label class="col-form-label text-black fw-bold" for="center-name">
                Address: <span class="text-danger">*</span>
                <?php if($subcourse_arr['Address'])
                {
					$address = json_decode($subcourse_arr['Address'],true);
  					$addressString = $address['present_address'].' '.$address['present_district'].' '.$address['present_city'].' '.$address['present_state'].' '.$address['present_pincode'];
  					echo $addressString;
                }
              ?>
            </label>
        </div>
    </div>



    <center>
        <h4 class="fw-bold" style="color: black; background-color: red;">Academics Details</h4>
    </center>
    <div class="row mb-3">
        <?php
        while ($value1 = $result1->fetch_assoc()) {
        ?>
            <h6 class="fw-bold" style="color: green;"><?= $value1['Type'] ?></h6>
            <div class="col-sm-4">
                <label class="col-form-label text-black fw-bold">
                    Subjects: <span class="text-danger">*</span>
                    <?= $value1['Subject'] ?>

                </label>
            </div>
            <div class="col-sm-4">
                <label class="col-form-label text-black fw-bold">
                    Year: <span class="text-danger">*</span>
                    <?= $value1['Year'] ?>

                </label>
            </div>
            <div class="col-sm-4">
                <label class="col-form-label text-black fw-bold">
                    Board/University: <span class="text-danger">*</span>
                    <?= $value1['Board/Institute'] ?>

                </label>
            </div>
            <div class="col-sm-4">
                <label class="col-form-label text-black fw-bold">
                    Result: <span class="text-danger">*</span>
                    <?= $value1['Total_Marks'] ?>

                </label>
            </div>
            <div class="col-sm-4">
                <label class="col-form-label text-black fw-bold">
                    Marksheet File: <span class="text-danger">*</span>

                </label>
            </div>
        <?php
        }
        ?>

































    </div>
    <center>
        <h4 class="fw-bold" style="color: black; background-color: red;">Document Details</h4>
    </center>
    <div class="row mb-3">
        <?php
        while ($files = $filedata->fetch_assoc()) {
        ?>
            <div class="col-sm-4">
                <label class="col-form-label text-black fw-bold" for="university-name">
                    <?= $files['Type'] ?>: <span class="text-danger">*</span>
                    <?php
                    if (isset($files['Location']) && !empty($files['Location'])) {
                        echo '<img src="' . $files['Location'] . '" style="max-width: 100px; height:100px;">';
                    } else {
                        echo 'No Photo Available';
                    }
                    ?>
                </label>
            </div>
        <?php
        }
        ?>
    </div>




    <div class="modal-footer clearfix text-end">
        <div class="col-md-4 m-t-10 sm-m-t-10">
        </div>
    </div>


    <script type="text/javascript" src="/assets/plugins/select2/js/select2.full.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
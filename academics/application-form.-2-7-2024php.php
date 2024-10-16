<?php include($_SERVER['DOCUMENT_ROOT'] . '../includes/header-top.php') ?>
<link rel="stylesheet" href="../assets/vendor/libs/bs-stepper/bs-stepper.css" />
<link rel="stylesheet" href="../assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
<link rel="stylesheet" href="../assets/vendor/libs/flatpickr/flatpickr.css" />
<?php ini_set('display_errors', 1); ?>

<style>
    .content {
        display: none;
    }

    .content.active {
        display: block;
    }

    .pager {
        margin-top: 20px;
    }

    input {
        text-transform: uppercase;
    }
</style>

<?php include($_SERVER['DOCUMENT_ROOT'] . '../includes/header-bottom.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '../includes/side-menu.php') ?>
<?php require '../includes/db-config.php'; ?>

<div class="layout-page">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '../includes/top-menu.php') ?>
    <?php
    $is_get = 0;
    $id = 0;
    $address = [];
    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $id = base64_decode($id);
        $id = intval(str_replace('W1Ebt1IhGN3ZOLplom9I', '', $id));
        $student = $conn->query("SELECT * FROM Students WHERE ID = $id");
        if ($student->num_rows > 0) {
            $is_get = 1;
        } else {
            header("Location: /admissions/applications");
        }
        $student = mysqli_fetch_assoc($student);
        // echo "<pre>"; print_r($student);
        $subcenters = $conn->query("SELECT * FROM Center_SubCenter WHERE Sub_Center = '" . $student['Added_For'] . "'");
        if ($subcenters->num_rows > 0) {
            $subcenter = $subcenters->fetch_assoc();
        }
        if ($_SESSION['Role'] === 'Sub-Center') {
            $subcenter['Center'] = null;
        }

        if (!empty($student['Unique_ID']) && isset($_SESSION['crm'])) {
            $check_in_leads = $conn->query("SELECT Leads.Email, Leads.Mobile FROM Lead_Status LEFT JOIN Leads ON Lead_Status.Lead_ID = Leads.ID WHERE Lead_Status.Unique_ID = '" . $student['Unique_ID'] . "'");
            if ($check_in_leads->num_rows > 0) {
                $lead = $check_in_leads->fetch_assoc();
                $student['Email'] = $lead['Email'];
                $student['Contact'] = $lead['Mobile'];
            }
        }
        echo '<script>localStorage.setItem("inserted_id",' . $id . ');</script>';
        $address = !empty($student['Address']) ? json_decode($student['Address'], true) : [];
    }

    if (isset($_GET['lead_id'])) {
        $lead_id = mysqli_real_escape_string($conn, $_GET['lead_id']);
        $lead_id = base64_decode($lead_id);
        $lead_id = intval(str_replace('W1Ebt1IhGN3ZOLplom9I', '', $lead_id));
        $lead = $conn->query("SELECT Lead_Status.Admission, Lead_Status.University_ID, Lead_Status.User_ID, Lead_Status.Course_ID,Lead_Status.Sub_Course_ID,Leads.Name,Leads.Email,Leads.Alternate_Email,Leads.Mobile,Leads.Alternate_Mobile,Leads.Address,Cities.`Name` AS City,States.`Name` AS State,Countries.`Name` AS Country,Universities.Name AS University,Courses.Name AS Category,Sub_Courses.Name AS Sub_Category,Stages.Name AS Stage,Reasons.Name AS Reason,Sources.Name AS Source,Sub_Sources.Name AS Sub_Source,Users.Name AS Lead_Owner,Users.Code,Leads.Created_At AS Created_On,Lead_Status.Created_At,Lead_Status.Updated_At FROM Leads LEFT JOIN Lead_Status ON Leads.ID=Lead_Status.Lead_ID LEFT JOIN Universities ON Lead_Status.University_ID=Universities.ID LEFT JOIN Courses ON Lead_Status.Course_ID=Courses.ID LEFT JOIN Sub_Courses ON Lead_Status.Sub_Course_ID=Sub_Courses.ID LEFT JOIN Stages ON Lead_Status.Stage_ID=Stages.ID LEFT JOIN Reasons ON Lead_Status.Reason_ID=Reasons.ID LEFT JOIN Sources ON Leads.Source_ID=Sources.ID LEFT JOIN Sub_Sources ON Leads.Sub_Source_ID=Sub_Sources.ID LEFT JOIN Users ON Lead_Status.User_ID=Users.ID LEFT JOIN Cities ON Leads.City_ID=Cities.ID LEFT JOIN States ON Leads.State_ID=States.ID LEFT JOIN Countries ON Leads.Country_ID=Countries.ID WHERE Lead_Status.ID= $lead_id");
        if ($lead->num_rows > 0) {
            $is_get = 1;
        } else {
            header("Location: /leads/lists");
        }
        $lead = $lead->fetch_assoc();
    }

    ?>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <ol class="breadcrumb d-flex flex-wrap justify-content-between align-self-start">
                <?php $breadcrumbs = array_filter(explode("/", $_SERVER['REQUEST_URI']));
                for ($i = 1; $i <= count($breadcrumbs); $i++) {
                    if (count($breadcrumbs) == $i) : $active = "active";
                        $crumb = explode("?", $breadcrumbs[$i]);
                        echo '<li class="breadcrumb-item ' . $active . '">' . strtoupper($crumb[0]) . '</li>';
                    endif;
                }
                ?>
            </ol>
            <div class="bs-stepper wizard-icons wizard-icons-example mt-2">
                <div class="bs-stepper-header">
                    <div class="step" data-target="#basic-details">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-icon">
                                <svg viewBox="0 0 54 54">
                                    <use xlink:href='https://demos.pixinvent.com/materialize-html-admin-template/assets/svg/icons/form-wizard-account.svg#wizardAccount'></use>
                                </svg>
                            </span>
                            <span class="bs-stepper-label">BASIC DETAILS</span>
                        </button>
                    </div>
                    <div class="line">
                        <i class="ri-arrow-right-s-line"></i>
                    </div>
                    <div class="step" data-target="#personal-details">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-icon">
                                <svg viewBox="0 0 58 54">
                                    <use xlink:href='https://demos.pixinvent.com/materialize-html-admin-template/assets/svg/icons/form-wizard-personal.svg#wizardPersonal'></use>
                                </svg>
                            </span>
                            <span class="bs-stepper-label">PERSONAL DETAILS</span>
                        </button>
                    </div>
                    <div class="line">
                        <i class="ri-arrow-right-s-line"></i>
                    </div>
                    <div class="step" data-target="#academics">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-icon">
                                <svg viewBox="0 0 54 54">
                                    <use xlink:href='https://demos.pixinvent.com/materialize-html-admin-template/assets/svg/icons/form-wizard-address.svg#wizardAddress'></use>
                                </svg>
                            </span>
                            <span class="bs-stepper-label">ACADEMICS</span>
                        </button>
                    </div>
                    <div class="line">
                        <i class="ri-arrow-right-s-line"></i>
                    </div>
                    <div class="step" data-target="#document">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-icon">
                                <svg viewBox="0 0 54 54">
                                    <use xlink:href='https://demos.pixinvent.com/materialize-html-admin-template/assets/svg/icons/form-wizard-social-link.svg#wizardSocialLink'></use>
                                </svg>
                            </span>
                            <span class="bs-stepper-label">DOCUMENTS</span>
                        </button>
                    </div>
                    <div class="line">
                        <i class="ri-arrow-right-s-line"></i>
                    </div>
                    <div class="step" data-target="#application-form">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-icon">
                                <svg viewBox="0 0 54 54">
                                    <use xlink:href='https://demos.pixinvent.com/materialize-html-admin-template/assets/svg/icons/form-wizard-social-link.svg#wizardSocialLink'></use>
                                </svg>
                            </span>
                            <span class="bs-stepper-label">APPLICATION FORM</span>
                        </button>
                    </div>
                </div>


                <div class="bs-stepper-content">

                    <!-- Account Details -->
                    <div id="basic-details" class="content acitve">
                        <form id="step_1" role="form" autocomplete="off" action="/app/application-form/step-1" enctype="multipart/form-data">
                            <div class="content-header mb-4">
                                <h6 class="mb-0 fs-5 text-black fw-bold">APPLYING FOR</h6>
                                <!-- <small>Enter Your Account Details.</small> -->
                            </div>

                            <div class="row g-5">
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">Center <span class="text-danger">*</span></label>
                                    <select class="select2 form-select center" data-allow-clear="true" name="center" id="center" onchange="getUniversity(this.value)">
                                        <option value="">Select</option>
                                        <?php
                                        $role_query = '';
                                        $selected = '';
                                        if ($_SESSION['Role'] == "Center") {
                                            $role_query = 'AND ID =' . $_SESSION['ID'];
                                            $selected = "selected";
                                        }
                                        $get_center_users = $conn->query("SELECT ID, Name FROM Users WHERE Role = 'Center' AND Status = 1 AND University_ID IS NOT NULL $role_query");
                                        while ($row = $get_center_users->fetch_assoc()) {
                                            $student = isset($student) ? $student : null;
                                            $student_added_for = $student !== null && isset($student['Added_For']) ? $student['Added_For'] : null;

                                        ?>
                                            <option value="<?= $row['ID'] ?>" <?= $student_added_for === $row['ID'] ? "selected" : "" ?> <?= $selected; ?>> <?= $row['Name'] ?></option>
                                        <?php }
                                        ?>

                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">University <span class="text-danger">*</span></label>
                                    <select id="multicol-country" class="select2 form-select university_id" data-allow-clear="true" name="university_id" onchange="getAdmissionSession(this.value);">
                                        <option value="">Choose</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">Admission Session <span class="text-danger">*</span></label>
                                    <select class="select2 form-select " data-allow-clear="true" name="admission_session" id="admission_session" onchange="getAdmissionType(this.value)">
                                        <option value="">Select</option>

                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">Admission Type <span class="text-danger">*</span></label>
                                    <select class="select2 form-select" data-allow-clear="true" name="admission_type" id="admission_type" onchange="getCourse()">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">Course <span class="text-danger">*</span></label>
                                    <select class="select2 form-select" data-allow-clear="true" name="course" id="course" onchange="getSubCourse()">
                                        <option value="">Select</option>

                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">Sub Course <span class="text-danger">*</span></label>
                                    <select class="select2 form-select" data-allow-clear="true" name="sub_course" id="sub_course" onchange="getDuration(), getEligibility();">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">Mode <span class="text-danger">*</span></label>
                                    <select class="select2 form-select" data-allow-clear="true"  name="duration" id="duration">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="content-header mb-1">
                                    <h6 class="mb-0 fs-5 text-black fw-bold">BASIC DETAILS</h6>
                                    <!-- <small>Enter Your Account Details.</small> -->
                                </div>
                                <div class="col-sm-3 m-0">
                                    <label class=" col-form-label" for="multicol-full-name">Full Name <span class="text-danger">*</span></label>
                                    <?php $student_name = !empty($id) ? array_filter(array($student['First_Name'], $student['Middle_Name'], $student['Last_Name'])) : [] ?>

                                    <input type="text" id="full_name" name="full_name" class="form-control" placeholder="ex: Jhon Doe" value="<?= implode(" ", $student_name) ?><?php print !empty($lead_id) ? $lead['Name'] : "" ?>" required />
                                </div>
                                <div class="col-sm-3 m-0">
                                    <label class=" col-form-label" for="multicol-full-name">Father Name <span class="text-danger">*</span></label>
                                    <input type="text" id="father_name" name="father_name" class="form-control" value="<?php print !empty($id) ? $student['Father_Name'] : "" ?>" placeholder="" required />
                                </div>
                                <div class="col-sm-3 m-0">
                                    <label class=" col-form-label" for="multicol-full-name">Mother Name <span class="text-danger">*</span></label>
                                    <input type="text" id="mother_name" name="mother_name" class="form-control" value="<?php print !empty($id) ? $student['Mother_Name'] : "" ?>" class="form-control" placeholder="" required />
                                </div>
                                <div class="col-sm-3 m-0">
                                    <label class=" col-form-label" for="multicol-full-name">DOB <span class="text-danger">*</span></label>
                                    <div class="form-floating form-floating-outline ">
                                        <input type="text" name="dob" class="form-control flatpickr-validation" value="<?php print !empty($id) ? date('d-m-Y', strtotime($student['DOB'])) : "" ?>" placeholder="dd-mm-yyyy" id="dob" required />
                                        <label for="basic-default-dob">DOB</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">Gender <span class="text-danger">*</span></label>
                                    <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="gender">
                                        <option value="">Select</option>
                                        <option value="Male" <?php print !empty($id) ? ($student['Gender'] == 'Male' ? 'selected' : '') : '' ?>>Male</option>
                                        <option value="Female" <?php print !empty($id) ? ($student['Gender'] == 'Female' ? 'selected' : '') : '' ?>>Female</option>
                                        <option value="Other" <?php print !empty($id) ? ($student['Gender'] == 'Other' ? 'selected' : '') : '' ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">Category <span class="text-danger">*</span></label>
                                    <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="category">
                                        <option value="">Select</option>
                                        <option value="General" <?php print !empty($id) ? ($student['Category'] == 'General' ? 'selected' : '') : '' ?>>General</option>
                                        <option value="OBC" <?php print !empty($id) ? ($student['Category'] == 'OBC' ? 'selected' : '') : '' ?>>OBC</option>
                                        <option value="SC" <?php print !empty($id) ? ($student['Category'] == 'SC' ? 'selected' : '') : '' ?>>SC</option>
                                        <option value="ST" <?php print !empty($id) ? ($student['Category'] == 'ST' ? 'selected' : '') : '' ?>>ST</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">Employment Status <span class="text-danger">*</span></label>
                                    <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="employment_status">
                                        <option value="">Select</option>
                                        <option value="Employed" <?php print !empty($id) ? ($student['Employement_Status'] == 'Employed' ? 'selected' : '') : '' ?>>Employed</option>
                                        <option value="Unemployed" <?php print !empty($id) ? ($student['Employement_Status'] == 'Unemployed' ? 'selected' : '') : '' ?>>Unemployed</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">Marital Status <span class="text-danger">*</span></label>
                                    <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="marital_status">
                                        <option value="">Select</option>
                                        <option value="Married" <?php print !empty($id) ? ($student['Marital_Status'] == 'Married' ? 'selected' : '') : '' ?>>Married</option>
                                        <option value="Unmarried" <?php print !empty($id) ? ($student['Marital_Status'] == 'Unmarried' ? 'selected' : '') : '' ?>>Unmarried</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">Religion <span class="text-danger">*</span></label>
                                    <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="religion">
                                        <option value="">Select</option>
                                        <option value="Hindu" <?php print !empty($id) ? ($student['Religion'] == 'Hindu' ? 'selected' : '') : '' ?>>Hindu</option>
                                        <option value="Muslim" <?php print !empty($id) ? ($student['Religion'] == 'Muslim' ? 'selected' : '') : '' ?>>Muslim</option>
                                        <option value="Sikh" <?php print !empty($id) ? ($student['Religion'] == 'Sikh' ? 'selected' : '') : '' ?>>Sikh</option>
                                        <option value="Christian" <?php print !empty($id) ? ($student['Religion'] == 'Christian' ? 'selected' : '') : '' ?>>Christian</option>
                                        <option value="Jain" <?php print !empty($id) ? ($student['Religion'] == 'Jain' ? 'selected' : '') : '' ?>>Jain</option>
                                    </select>
                                </div>

                                <div class="col-sm-3 ">
                                    <label class=" col-form-label" for="multicol-full-name">Aadhar <span class="text-danger">*</span></label>
                                    <input type="tel" maxlength="14" minlength="14" name="aadhar" value="<?php print !empty($id) ? $student['Aadhar_Number'] : '' ?>" class="form-control" placeholder="ex: XYZ Aadhar no." id="aadhar" required />
                                </div>
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">Nationality <span class="text-danger">*</span></label>
                                    <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="nationality">
                                        <option value="">Select</option>
                                        <option value="Indian" <?php print !empty($id) ? ($student['Nationality'] == 'Indian' ? 'selected' : '') : '' ?>>Indian</option>
                                        <option value="NRI" <?php print !empty($id) ? ($student['Nationality'] == 'NRI' ? 'selected' : '') : '' ?>>NRI</option>
                                    </select>
                                </div>

                            </div>
                            <div class="mt-3 d-flex pager justify-content-between wizard">
                        <button type="button" class="btn btn-outline-secondary btn-prev" id="form_preview">
                            <i class="ri-arrow-left-line me-sm-1"></i>
                            <span class="align-middle ">Previous</span>
                        </button>
                      
                        <button type="submit" class="btn btn-primary" id="form_submit" style="display: none !important;">
                            <span class="align-middle ">Submit</span>
                            <i class="ri-arrow-right-line"></i>
                        </button>
                        <button type="submit" class="btn btn-primary btn-next" id="form_next">
                            <span class="align-middle ">Next</span>
                            <i class="ri-arrow-right-line"></i>
                        </button>
                    </div>
                        </form>
                    </div>
                    <!-- Personal Info -->
                    <div id="personal-details" class="content">
                        <form id="step_2" role="form" autocomplete="off" action="/app/application-form/step-2">

                            <div class="content-header mb-1">
                                <h6 class="mb-0 fs-5 mb-5 text-black fw-bold">SOCIAL</h6>
                                <!-- <small>Enter Your Account Details.</small> -->
                            </div>

                            <div class="row g-5">

                                <div class="col-sm-6 m-0">
                                    <label class=" col-form-label" for="multicol-full-name">Email <span class="text-danger">*</span></label>
                                    <input type="email" id="multicol-full-name" name="email" class="form-control" value="<?php print !empty($id) ? $student['Email'] : '' ?> <?php print !empty($lead_id) ? $lead['Email'] : '' ?>" placeholder="ex: jhon@example.com" required />
                                </div>
                                <div class="col-sm-6 m-0">
                                    <label class=" col-form-label" for="multicol-full-name">Alternate Email <span class="text-danger">*</span></label>
                                    <input type="email" id="multicol-full-name" name="alternate_email" class="form-control" value="<?php print !empty($id) ? $student['Alternate_Email'] : '' ?><?php print !empty($lead_id) ? $lead['Alternate_Email'] : '' ?>" class="form-control" placeholder="ex: jhondoe@example.com" required />
                                </div>
                                <div class="col-sm-6 m-0">
                                    <label class=" col-form-label" for="multicol-full-name">Mobile <span class="text-danger">*</span></label>
                                    <input type="tel" id="multicol-full-name" name="contact" class="form-control" onkeypress="return isNumberKey(event);" maxlength="10" value="<?php print !empty($id) ? $student['Contact'] : '' ?><?php print !empty($lead_id) ? $lead['Mobile'] : '' ?>" class="form-control" placeholder="ex: 9977886655" required />
                                </div>
                                <div class="col-sm-6 m-0">
                                    <label class=" col-form-label" for="multicol-full-name">Alternate Mobile <span class="text-danger">*</span></label>
                                    <input type="tel" id="multicol-full-name" name="alternate_contact" class="form-control" maxlength="10" value="<?php print !empty($id) ? $student['Alternate_Contact'] : '' ?><?php print !empty($lead_id) ? $lead['Alternate_Mobile'] : '' ?>" placeholder="ex: 9988776654" required />
                                </div>
                                <div class="content-header mb-1">
                                    <h6 class="mb-0 fs-5 mb-5 text-black fw-bold">ADDRESS</h6>
                                    <!-- <small>Enter Your Account Details.</small> -->
                                </div>
                                <div class="col-sm-12 m-0">
                                    <label class=" col-form-label" for="multicol-full-name">Address <span class="text-danger">*</span></label>
                                    <input type="text" id="multicol-full-name" name="address" class="form-control" value="<?php print !empty($id) ? (!empty($address) ? $address['present_address'] : '') : '' ?>" placeholder="ex: 23 Street, California" required />
                                </div>
                                <div class="col-sm-3 m-0">
                                    <label class=" col-form-label" for="multicol-full-name">Pincode <span class="text-danger">*</span></label>
                                    <input type="tel" name="pincode" maxlength="6" class="form-control" placeholder="ex: 123456" value="<?php print !empty($address) ? (array_key_exists('present_pincode', $address) ? $address['present_pincode'] : '') : '' ?>" onkeypress="return isNumberKey(event)" onkeyup="getRegion(this.value);" required />
                                </div>
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">City <span class="text-danger">*</span></label>
                                    <select class="select2 form-select" data-allow-clear="true" name="city" id="city">
                                        <option value="">Choose</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class=" col-form-label" for="multicol-full-name">District <span class="text-danger">*</span></label>
                                    <select class="select2 form-select" data-allow-clear="true" name="district" id="district">
                                        <option value="">Choose</option>
                                    </select>
                                </div>
                                <div class="col-sm-3 m-0">
                                    <label class=" col-form-label" for="multicol-full-name">State <span class="text-danger">*</span></label>
                                    <input type="text" name="state" class="form-control" placeholder="ex: California" id="state" readonly />
                                </div>
                                <!-- <div class="col-12 d-flex justify-content-between">
                                <button class="btn btn-outline-secondary btn-prev"> <i class="ri-arrow-left-line me-sm-1"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="ri-arrow-right-line"></i></button>
                            </div> -->
                            </div>
                        </form>
                    </div>
                    <!-- Address -->
                    <div id="academics" class="content">
                        <form id="step_3" role="form" autocomplete="off" action="/app/application-form/step-3" method="POST" enctype="multipart/form-data">

                            <?php
                            $high_school = [];
                            if (!empty($id)) {
                                $high_school = $conn->query("SELECT Student_Academics.*, Location FROM Student_Academics LEFT JOIN Student_Documents ON Student_Academics.Student_ID = Student_Documents.Student_ID AND Student_Documents.`Type` = 'High School' WHERE Student_Academics.Student_ID = $id AND Student_Academics.Type = 'High School' GROUP BY Student_ID");
                                if ($high_school->num_rows > 0) {
                                    $high_school = mysqli_fetch_assoc($high_school);
                                    $high_marksheet = !empty($high_school['Location']) ? explode('|', $high_school['Location']) : [];
                                } else {
                                    $high_school = [];
                                }
                            }
                            ?>
                            <div class="g-5">
                                <div class="row" id="high_school_column" style="display:none">
                                    <div class="content-header mb-1">
                                        <h6 class="mb-0 fs-5  text-black fw-bold">HIGH SCHOOL</h6>
                                        <!-- <small>Enter Your Account Details.</small> -->
                                    </div>

                                    <div class="col-sm-4 ">
                                        <label class=" col-form-label" for="multicol-full-name">Subjects <span class="text-danger">*</span></label>
                                        <input type="text" name="high_subject" id="high_subject" class="form-control" value="<?php print !empty($high_school) ? (array_key_exists('Subject', $high_school) ? $high_school['Subject'] : '') : 'All Subjects' ?>" placeholder="ex: All">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class=" col-form-label" for="multicol-full-name">Year <span class="text-danger">*</span></label>
                                        <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="high_year" id="high_year">
                                            <option value="">Select</option>
                                            <?php for ($i = date('Y'); $i >= 1947; $i--) { ?>
                                                <option value="<?= $i ?>" <?php print !empty($high_school) ? ($high_school['Year'] == $i ? 'selected' : '') : '' ?>><?= $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class=" col-form-label" for="multicol-full-name">Board/University <span class="text-danger">*</span></label>
                                        <input type="text" name="high_board" id="high_board" value="<?php print !empty($high_school) ? $high_school['Board/Institute'] : '' ?>" class="form-control" placeholder="ex: CBSE" required />
                                    </div>
                                    <div class="col-sm-6">
                                        <label class=" col-form-label" for="multicol-full-name">Result <span class="text-danger">*</span></label>
                                        <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="high_total" id="high_total">
                                            <option value="">Select</option>
                                            <option value="Passed" <?php print !empty($high_school) && $high_school['Total_Marks'] == 'PASSED' ? 'selected' : '' ?>>Passed</option>
                                            <option value="Fail" <?php print !empty($high_school) && $high_school['Total_Marks'] == 'FAIL' ? 'selected' : '' ?>>Fail</option>
                                            <option value="Discontinued" <?php print !empty($high_school) && $high_school['Total_Marks'] == 'DISCONTINUED' ? 'selected' : '' ?>>Discontinued</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <label class=" col-form-label" for="multicol-full-name">Marksheet <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('high_marksheet');" id="high_marksheet" name="high_marksheet[]" multiple="multiple">
                                        <dt><?php print !empty($high_marksheet) ?  count($high_marksheet) . " Marksheet(s) Uploaded" : ''; ?></dt>
                                        <?php if (!empty($high_marksheet)) {
                                            foreach ($high_marksheet as $hm) { ?>
                                                <img src="<?= $hm ?>" class="cursor-pointer mr-2" onclick="window.open('<?= $hm ?>')" width="40" height="40" />
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                                <div class="row" id="intermediate_column" style="display:none">
                                    <div class="content-header mb-1">
                                        <h6 class="mb-0 fs-5  text-black fw-bold">INTERMEDIATE</h6>
                                        <!-- <small>Enter Your Account Details.</small> -->
                                    </div>

                                    <?php
                                    $intermediate = [];
                                    if (!empty($id)) {
                                        $intermediate = $conn->query("SELECT Student_Academics.*, Location FROM Student_Academics LEFT JOIN Student_Documents ON Student_Academics.Student_ID = Student_Documents.Student_ID AND Student_Documents.`Type` = 'Intermediate' WHERE Student_Academics.Student_ID = $id AND Student_Academics.Type = 'Intermediate'");
                                        if ($intermediate->num_rows > 0) {
                                            $intermediate = mysqli_fetch_assoc($intermediate);
                                            $inter_marksheet = !empty($intermediate['Location']) ? explode('|', $intermediate['Location']) : [];
                                        } else {
                                            $intermediate = [];
                                        }
                                    }
                                    ?>
                                    <div class="col-sm-4 ">
                                        <label class=" col-form-label" for="multicol-full-name">Subjects <span class="text-danger">*</span></label>
                                        <input type="text" name="inter_subject" class="form-control" value="<?php print !empty($intermediate) ? (array_key_exists('Subject', $intermediate) ? $intermediate['Subject'] : '') : '' ?>" id="inter_subject" placeholder="ex: PCM" required />
                                    </div>
                                    <div class="col-sm-4">
                                        <label class=" col-form-label" for="multicol-full-name">Year <span class="text-danger">*</span></label>
                                        <select class="select2 form-select" data-allow-clear="true" name="inter_year" id="inter_year">
                                            <option value="">Select</option>
                                            <?php for ($i = date('Y'); $i >= 1947; $i--) { ?>
                                                <option value="<?= $i ?>" <?php print !empty($intermediate) ? ($intermediate['Year'] == $i ? 'selected' : '') : '' ?>><?= $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class=" col-form-label" for="multicol-full-name">Board/University <span class="text-danger">*</span></label>
                                        <input type="text" name="inter_board" id="inter_board" value="<?php print !empty($intermediate) ? (array_key_exists('Board/Institute', $intermediate) ? $intermediate['Board/Institute'] : '') : '' ?>" class="form-control" placeholder="ex: CBSE" />
                                        <!-- <input type="text" id="multicol-full-name" name="name" class="form-control" placeholder="ex: XYZ University" required /> -->
                                    </div>
                                    <div class="col-sm-6">
                                        <label class=" col-form-label" for="multicol-full-name">Result <span class="text-danger">*</span></label>
                                        <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="inter_total" id="inter_total">
                                            <option value="">Select</option>
                                            <option value="Passed" <?php print !empty($intermediate) && $intermediate['Total_Marks'] == 'PASSED' ? 'selected' : '' ?>>Passed</option>
                                            <option value="Fail" <?php print !empty($intermediate) && $intermediate['Total_Marks'] == 'FAIL' ? 'selected' : '' ?>>Fail</option>
                                            <option value="Discontinued" <?php print !empty($intermediate) && $intermediate['Total_Marks'] == 'DISCONTINUED' ? 'selected' : '' ?>>Discontinued</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <label class=" col-form-label" for="multicol-full-name">Marksheet <span class="text-danger">*</span></label>
                                        <!-- <input type="file" class="form-control" id="basic-default-upload-file" required=""> -->
                                        <input type="file" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('inter_marksheet');" id="inter_marksheet" name="inter_marksheet[]" multiple="multiple" class="form-control ">
                                        <dt><?php print !empty($inter_marksheet) ? count($inter_marksheet) . " Marksheet Uploaded" : '' ?></dt>
                                        <?php if (!empty($inter_marksheet)) {
                                            foreach ($inter_marksheet as $im) { ?>
                                                <img src="<?= $im ?>" class="cursor-pointer mr-2" onclick="window.open('<?= $im ?>')" width="40" height="40" />
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                                <?php
                                $ug = [];
                                if (!empty($id)) {
                                    $ug = $conn->query("SELECT Student_Academics.*, Location FROM Student_Academics LEFT JOIN Student_Documents ON Student_Academics.Student_ID = Student_Documents.Student_ID AND Student_Documents.`Type` = 'UG' WHERE Student_Academics.Student_ID = $id AND Student_Academics.Type = 'UG'");
                                    if ($ug->num_rows > 0) {
                                        $ug = mysqli_fetch_assoc($ug);
                                        $ug_marksheet = !empty($ug['Location']) ? explode('|', $ug['Location']) : [];
                                    } else {
                                        $ug = [];
                                    }
                                }
                                ?>

                                <div class="row" id="ug_column" style="display:none">
                                    <div class="content-header mb-1">
                                        <h6 class="mb-0 fs-5  text-black fw-bold">Under Graduate</h6>
                                        <!-- <small>Enter Your Account Details.</small> -->
                                    </div>
                                    <div class="col-sm-4 ">
                                        <label class=" col-form-label" for="multicol-full-name">Subjects <span class="text-danger">*</span></label>
                                        <input type="text" name="ug_subject" id="ug_subject" class="form-control" class="form-control" value="<?php print !empty($ug) ? (array_key_exists('Subject', $ug) ? $ug['Subject'] : '') : '' ?>" placeholder="ex: BBA" />
                                    </div>
                                    <div class="col-sm-4">
                                        <label class=" col-form-label" for="multicol-full-name">Year <span class="text-danger">*</span></label>
                                        <select class="select2 form-select" data-allow-clear="true" name="ug_year" id="ug_year">
                                            <option value="">Select</option>
                                            <?php for ($i = date('Y'); $i >= 1947; $i--) { ?>
                                                <option value="<?= $i ?>" <?php print !empty($ug) ? ($ug['Year'] == $i ? 'selected' : '') : '' ?>><?= $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class=" col-form-label" for="multicol-full-name">Board/University <span class="text-danger">*</span></label>
                                        <input type="text" name="ug_board" id="ug_board" value="<?php print !empty($ug) ? (array_key_exists('Board/Institute', $ug) ? $ug['Board/Institute'] : '') : '' ?>" class="form-control" placeholder="ex: DU" />
                                        <!-- <input type="text" id="multicol-full-name" name="name" class="form-control" placeholder="ex: XYZ University" required /> -->
                                    </div>
                                    <div class="col-sm-4">
                                        <label class=" col-form-label" for="multicol-full-name">Marks Obtained<span class="text-danger">*</span></label>
                                        <input type="text" name="ug_obtained" id="ug_obtained" value="<?php print !empty($ug) ? (array_key_exists('Marks_Obtained', $ug) ? $ug['Marks_Obtained'] : '') : '' ?>" onblur="checkUGMarks()" placeholder="ex: 400" class="form-control" />
                                        <!-- <input type="text" id="multicol-full-name" name="name" class="form-control" placeholder="ex: XYZ University" required /> -->
                                    </div>
                                    <div class="col-sm-4">
                                        <label class=" col-form-label" for="multicol-full-name">Max Marks <span class="text-danger">*</span></label>
                                        <input type="text" name="ug_max" id="ug_max" value="<?php print !empty($ug) ? (array_key_exists('Max_Marks', $ug) ? $ug['Max_Marks'] : '') : '' ?>" onblur="checkUGMarks()" placeholder="ex: 600" class="form-control" />
                                        <!-- <input type="text" id="multicol-full-name" name="name" class="form-control" placeholder="ex: XYZ University" required /> -->
                                    </div>
                                    <div class="col-sm-4">
                                        <label class=" col-form-label" for="multicol-full-name">Grade/Percentage <span class="text-danger">*</span></label>
                                        <input type="text" name="ug_total" value="<?php print !empty($ug) ? (array_key_exists('Total_Marks', $ug) ? $ug['Total_Marks'] : '') : '' ?>" id="ug_total" class="form-control" placeholder="ex: 66%" />
                                        <!-- <input type="text" id="multicol-full-name" name="name" class="form-control" placeholder="ex: XYZ University" required /> -->
                                    </div>
                                    <div class="col-sm-6">
                                        <label class=" col-form-label" for="multicol-full-name">Result <span class="text-danger">*</span></label>
                                        <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="ug_total" id="ug_total">
                                            <option value="">Select</option>
                                            <option value="Passed" <?php print !empty($ug) && $ug['Total_Marks'] == 'PASSED' ? 'selected' : '' ?>>Passed</option>
                                            <option value="Fail" <?php print !empty($ug) && $ug['Total_Marks'] == 'FAIL' ? 'selected' : '' ?>>Fail</option>
                                            <option value="Discontinued" <?php print !empty($ug) && $ug['Total_Marks'] == 'DISCONTINUED' ? 'selected' : '' ?>>Discontinued</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <label class=" col-form-label" for="multicol-full-name">Marksheet <span class="text-danger">*</span></label>
                                        <!-- <input type="file" class="form-control" id="basic-default-upload-file" required=""> -->
                                        <input type="file" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('ug_marksheet');" id="ug_marksheet" name="ug_marksheet[]" multiple="multiple" class="form-control ">
                                        <dt><?php print !empty($ug_marksheet) ? count($ug_marksheet) . " Marksheet Uploaded" : '' ?></dt>
                                        <?php if (!empty($ug_marksheet)) {
                                            foreach ($ug_marksheet as $um) { ?>
                                                <img src="<?= $um ?>" class="cursor-pointer mr-2" onclick="window.open('<?= $um ?>')" width="40" height="40" />
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                                <?php
                                $pg = [];
                                if (!empty($id)) {
                                    $pg = $conn->query("SELECT Student_Academics.*, Location FROM Student_Academics LEFT JOIN Student_Documents ON Student_Academics.Student_ID = Student_Documents.Student_ID AND Student_Documents.`Type` = 'PG' WHERE Student_Academics.Student_ID = $id AND Student_Academics.Type = 'PG'");
                                    if ($pg->num_rows > 0) {
                                        $pg = mysqli_fetch_assoc($pg);
                                        $pg_marksheet = !empty($pg['Location']) ? explode('|', $pg['Location']) : [];
                                    } else {
                                        $pg = [];
                                    }
                                }
                                ?>
                                <div class="row" id="pg_column" style="display:none">
                                    <div class="content-header mb-1">
                                        <h6 class="mb-0 fs-5  text-black fw-bold">Post Graduate</h6>
                                        <!-- <small>Enter Your Account Details.</small> -->
                                    </div>

                                    <div class="col-sm-4 ">
                                        <label class=" col-form-label" for="multicol-full-name">Subjects <span class="text-danger">*</span></label>
                                        <input type="text" name="pg_subject" id="pg_subject" value="<?php print !empty($pg) ? (array_key_exists('Subject', $pg) ? $pg['Subject'] : '') : '' ?>" class="form-control" placeholder="ex: MBA" required />
                                    </div>
                                    <div class="col-sm-4">
                                        <label class=" col-form-label" for="multicol-full-name">Year <span class="text-danger">*</span></label>
                                        <select class="select2 form-select" data-allow-clear="true" name="pg_year" id="pg_year">
                                            <option value="">Select</option>
                                            <?php for ($i = date('Y'); $i >= 1947; $i--) { ?>
                                                <option value="<?= $i ?>" <?php print !empty($pg) ? ($pg['Year'] == $i ? 'selected' : '') : '' ?>><?= $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class=" col-form-label" for="multicol-full-name">Board/University <span class="text-danger">*</span></label>
                                        <input type="text" name="pg_board" id="pg_board" value="<?php print !empty($pg) ? (array_key_exists('Board/Institute', $pg) ? $pg['Board/Institute'] : '') : '' ?>" class="form-control" placeholder="ex: DU" />
                                        <!-- <input type="text" id="multicol-full-name" name="name" class="form-control" placeholder="ex: XYZ University" required /> -->
                                    </div>
                                    <div class="col-sm-6">
                                        <label class=" col-form-label" for="multicol-full-name">Result <span class="text-danger">*</span></label>
                                        <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="pg_total" id="pg_total">
                                            <option value="">Select</option>
                                            <option value="Passed" <?php print !empty($pg) && $pg['Total_Marks'] == 'PASSED' ? 'selected' : '' ?>>Passed</option>
                                            <option value="Fail" <?php print !empty($pg) && $pg['Total_Marks'] == 'FAIL' ? 'selected' : '' ?>>Fail</option>
                                            <option value="Discontinued" <?php print !empty($pg) && $pg['Total_Marks'] == 'DISCONTINUED' ? 'selected' : '' ?>>Discontinued</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <label class=" col-form-label" for="multicol-full-name">Marksheet <span class="text-danger">*</span></label>
                                        <!-- <input type="file" class="form-control" id="basic-default-upload-file" required=""> -->
                                        <input type="file" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('pg_marksheet');" name="pg_marksheet[]" id="pg_marksheet" multiple="multiple" class="form-control ">
                                        <dt><?php print !empty($pg_marksheet) ? count($pg_marksheet) . " Marksheet Uploaded" : '' ?></dt>
                                        <?php if (!empty($pg_marksheet)) {
                                            foreach ($pg_marksheet as $pm) { ?>
                                                <img src="<?= $pm ?>" class="cursor-pointer mr-2" onclick="window.open('<?= $pm ?>')" width="40" height="40" />
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                                <?php
                                $other = [];
                                if (!empty($id)) {
                                    $other = $conn->query("SELECT Student_Academics.*, Location FROM Student_Academics LEFT JOIN Student_Documents ON Student_Academics.Student_ID = Student_Documents.Student_ID AND Student_Documents.`Type` = 'Other' WHERE Student_Academics.Student_ID = $id AND Student_Academics.Type = 'Other' GROUP BY Student_ID");
                                    if ($other->num_rows > 0) {
                                        $other = mysqli_fetch_assoc($other);
                                        $other_marksheet = !empty($other['Location']) ? explode('|', $other['Location']) : [];
                                    } else {
                                        $other = [];
                                    }
                                }
                                ?>
                                <div class="row" id="other_column" style="display:none">
                                    <div class="content-header mb-1">
                                        <h6 class="mb-0 fs-5  text-black fw-bold">Other</h6>
                                        <!-- <small>Enter Your Account Details.</small> -->
                                    </div>
                                    <div class="col-sm-4 ">
                                        <label class=" col-form-label" for="multicol-full-name">Subjects <span class="text-danger">*</span></label>
                                        <input type="text" name="other_subject" class="form-control" value="<?php print !empty($other) ? (array_key_exists('Subject', $other) ? $other['Subject'] : '') : '' ?>" id="other_subject" placeholder="ex: Diploma" required />
                                    </div>
                                    <div class="col-sm-4">
                                        <label class=" col-form-label" for="multicol-full-name">Year <span class="text-danger">*</span></label>
                                        <select class="select2 form-select" data-allow-clear="true" name="other_year" id="other_year">
                                            <option value="">Select</option>
                                            <?php for ($i = date('Y'); $i >= 1947; $i--) { ?>
                                                <option value="<?= $i ?>" <?php print !empty($other) ? ($other['Year'] == $i ? 'selected' : '') : '' ?>><?= $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class=" col-form-label" for="multicol-full-name">Board/University <span class="text-danger">*</span></label>
                                        <input type="text" name="other_board" id="other_board" value="<?php print !empty($other) ? (array_key_exists('Board/Institute', $other) ? $other['Board/Institute'] : '') : '' ?>" class="form-control" placeholder="ex: DU" />
                                        <!-- <input type="text" id="multicol-full-name" name="name" class="form-control" placeholder="ex: XYZ University" required /> -->
                                    </div>
                                    <div class="col-sm-6">
                                        <label class=" col-form-label" for="multicol-full-name">Result <span class="text-danger">*</span></label>
                                        <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="other_total" id="other_total">
                                            <option value="">Select</option>
                                            <option value="Passed" <?php print !empty($other) && $other['Total_Marks'] == 'PASSED' ? 'selected' : '' ?>>Passed</option>
                                            <option value="Fail" <?php print !empty($other) && $other['Total_Marks'] == 'FAIL' ? 'selected' : '' ?>>Fail</option>
                                            <option value="Discontinued" <?php print !empty($other) && $other['Total_Marks'] == 'DISCONTINUED' ? 'selected' : '' ?>>Discontinued</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <label class=" col-form-label" for="multicol-full-name">Marksheet <span class="text-danger">*</span></label>
                                        <!-- <input type="file" class="form-control" id="basic-default-upload-file" required=""> -->
                                        <input type="file" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('other_marksheet');" id="other_marksheet" name="other_marksheet[]" multiple="multiple" class="form-control ">
                                        <dt><?php print !empty($other_marksheet) ? count($other_marksheet) . " Marksheet Uploaded" : '' ?></dt>
                                        <?php if (!empty($other_marksheet)) {
                                            foreach ($other_marksheet as $om) { ?>
                                                <img src="<?= $om ?>" class="cursor-pointer mr-2" onclick="window.open('<?= $om ?>')" width="40" height="40" />
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- <div class="col-12 d-flex justify-content-between">
                                <button class="btn btn-outline-secondary btn-prev"> <i class="ri-arrow-left-line me-sm-1"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="ri-arrow-right-line"></i></button>
                            </div> -->

                    </div>

                    <!-- Social Links -->
                    <div id="document" class="content">
                        <form id="step_4" role="form" action="/app/application-form/step-4" enctype="multipart/form-data">
                            <?php
                            if (!empty($id)) {
                                $photo = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Photo'");
                                $photo = mysqli_fetch_array($photo);
                            }
                            ?>
                            <div class="content-header mb-1">
                                <h6 class="mb-0 fs-5  text-black fw-bold">DOCUMENT</h6>
                                <!-- <small>Enter Your Account Details.</small> -->
                            </div>
                            <div class="row g-5">
                                <div class="col-sm-3 ">
                                    <label class=" col-form-label" for="multicol-full-name">Photo <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('photo');" id="photo" name="photo">
                                    <?php if (!empty($id) && !empty($photo)) { ?>
                                        <img src="<?php print !empty($id) ? $photo['Location'] : '' ?>" height="100" />
                                    <?php } ?>
                                </div>
                                <?php if (!empty($id)) {
                                    $aadhaars = array();
                                    $aadhaar = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Aadhar'");
                                    if ($aadhaar->num_rows > 0) {
                                        $aadhaar = mysqli_fetch_array($aadhaar);
                                        $aadhaars = explode("|", $aadhaar['Location']);
                                    }
                                }
                                ?>
                                <div class="col-sm-3 ">
                                    <label class=" col-form-label" for="multicol-full-name">Aadhar <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('aadhar');" id="aadhar" name="aadhar[]" multiple="multiple">
                                    <?php if (!empty($id) && !empty($aadhaars)) {
                                        foreach ($aadhaars as $aadhar) { ?>
                                            <img src="<?php print !empty($id) ? $aadhar : '' ?>" height="80" />
                                    <?php }
                                    } ?>
                                </div>
                                <?php
                                if (!empty($id)) {
                                    $students_signature = "";
                                    $student_signature = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Student Signature'");
                                    if ($student_signature->num_rows > 0) {
                                        $student_signature = mysqli_fetch_array($student_signature);
                                        $students_signature = $student_signature['Location'];
                                    }
                                }
                                ?>
                                <div class="col-sm-3 ">
                                    <label class=" col-form-label" for="multicol-full-name">Student's Signature <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('student_signature');" id="student_signature" name="student_signature">
                                    <?php if (!empty($id) && !empty($students_signature)) { ?>
                                        <img src="<?php print !empty($id) ? $students_signature : '' ?>" height="100" />
                                    <?php } ?>
                                </div>
                                <?php
                                if (!empty($id)) {
                                    $parents_signature = "";
                                    $parent_signature = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Parent Signature'");
                                    if ($parent_signature->num_rows > 0) {
                                        $parent_signature = mysqli_fetch_array($parent_signature);
                                        $parents_signature = $parent_signature['Location'];
                                    }
                                }
                                ?>
                                <div class="col-sm-3 ">
                                    <label class=" col-form-label" for="multicol-full-name">Parent's Signature <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('parent_signature');" id="parent_signature" name="parent_signature">
                                    <?php if (!empty($id) && !empty($parents_signature)) { ?>
                                        <img src="<?php print !empty($id) ? $parents_signature : '' ?>" height="100" />
                                    <?php } ?>
                                </div>
                                <?php
                                if (!empty($id)) {
                                    $migrations = array();
                                    $migration = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Migration'");
                                    if ($migration->num_rows > 0) {
                                        $migration = mysqli_fetch_array($migration);
                                        $migrations = explode("|", $migration['Location']);
                                    }
                                }
                                ?>
                                <div class="col-sm-3 ">
                                    <label class=" col-form-label" for="multicol-full-name">Migration <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('migration');" id="migration" name="migration[]" multiple="multiple">
                                    <?php if (!empty($id) && !empty($migrations)) {
                                        foreach ($migrations as $migration) { ?>
                                            <img src="<?php print !empty($id) ? $migration : '' ?>" height="80" />
                                    <?php }
                                    } ?>
                                </div>
                                <?php
                                if (!empty($id)) {
                                    $affidavits = array();
                                    $affidavit = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Affidavit'");
                                    if ($affidavit->num_rows > 0) {
                                        $affidavit = mysqli_fetch_array($affidavit);
                                        $affidavits = explode("|", $affidavit['Location']);
                                    }
                                }
                                ?>
                                <div class="col-sm-3 ">
                                    <label class=" col-form-label" for="multicol-full-name">Affidavit <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('affidavit');" id="affidavit" name="affidavit[]" multiple="multiple" required="">
                                    <?php if (!empty($id) && !empty($affidavits)) {
                                        foreach ($affidavits as $affidavit) { ?>
                                            <img src="<?php print !empty($id) ? $affidavit : '' ?>" height="80" />
                                    <?php }
                                    } ?>
                                </div>
                                <?php
                                if (!empty($id)) {
                                    $other_certificates = array();
                                    $other_certificate = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Other Certificate'");
                                    if ($other_certificate->num_rows > 0) {
                                        $other_certificate = mysqli_fetch_array($other_certificate);
                                        $other_certificates = explode("|", $other_certificate['Location']);
                                    }
                                }
                                ?>
                                <div class="col-sm-3 ">
                                    <label class=" col-form-label" for="multicol-full-name">Other Certificate <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('other_certificate');" id="other_certificate" name="other_certificate[]" multiple="multiple" required="">
                                    <?php if (!empty($id) && !empty($other_certificates)) {
                                        foreach ($other_certificates as $other_certificate) { ?>
                                            <img src="<?php print !empty($id) ? $other_certificate : '' ?>" height="80" />
                                    <?php }
                                    } ?>
                                </div>

                                <!-- <div class="col-12 d-flex justify-content-between">
                                <button class="btn btn-outline-secondary btn-prev"> <i class="ri-arrow-left-line me-sm-1"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="ri-arrow-right-line"></i></button>
                            </div> -->
                            </div>
                        </form>
                    </div>
                    <div id="application-form" class="content">

                        <div class="row g-5">
                            <div class="col-sm-12">
                                <h1 class="fs-1 fw-bold text-black">Thank you for providing the requested information.</h1>
                                <h3 class="text-black">Please use the link below to print the pre-filled application form.</h3>
                            </div>

                        </div>
                    </div>
                    <!-- <div class="mt-3 d-flex pager justify-content-between wizard">
                        <button type="button" class="btn btn-outline-secondary btn-prev" id="form_preview">
                            <i class="ri-arrow-left-line me-sm-1"></i>
                            <span class="align-middle ">Previous</span>
                        </button>
                      
                        <button type="submit" class="btn btn-primary" id="form_submit" style="display: none !important;">
                            <span class="align-middle ">Submit</span>
                            <i class="ri-arrow-right-line"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-next" id="form_next">
                            <span class="align-middle ">Next</span>
                            <i class="ri-arrow-right-line"></i>
                        </button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '../includes/footer-top.php') ?>

    <?php if (isset($_GET['lead_id']) && $lead['Admission'] == 1) { ?>
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Application saved successfully! To Proceed again go to Applications.',
                showConfirmButton: false,
                allowEscapeKey: false,
                allowOutsideClick: false,
                timer: 5000
            }).then((result) => {
                window.location.href = "/leads/lists"
            })
        </script>
    <?php } ?>

    <!-- <?php if ($_SESSION['crm'] > 0 && !$is_get) { ?>
      <script>
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'To apply New Application, Please add lead first!',
          showConfirmButton: false,
          allowEscapeKey: false,
          allowOutsideClick: false,
          timer: 3000
        }).then((result) => {
          window.location.href = "/leads/generate"
        })
      </script>
    <?php } ?> -->
    <script src="../assets/vendor/libs/bs-stepper/bs-stepper.js"></script>
    <script src="../assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="../assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <!-- <script src="../assets/js/form-validation.js"></script> -->
    <script src="../assets/js/form-wizard-icons.js"></script>
<script src="http://iitsdelhi-erp.local/assets/plugins/jquery-validation/js/jquery.validate.min.js"> </script>
    <?php if (empty($id)) { ?>
        <script>
            $(function() {
                if (localStorage.getItem('inserted_id') !== null) {
                    localStorage.removeItem('inserted_id');
                    Swal.fire(
                        'Previous Application is saved!',
                        'Please go to Applications > Edit if you want to proceed further!',
                        'success'
                    );
                }
            });
        </script>
    <?php } ?>
    <script>
     $(document).ready(function() {
    let currentStep = 0;
    const steps = $('.content');
    const nextBtn = $('#form_next');
    const prevBtn = $('#form_prev');
    const submitBtn = $('#form_submit');

    function showStep(index) {
        steps.removeClass('active').eq(index).addClass('active');

        if (index === steps.length - 1) {
            nextBtn.attr('style', 'display: none !important');
            submitBtn.attr('style', 'display: inline-block !important');
        } else {
            nextBtn.attr('style', 'display: inline-block !important');
            submitBtn.attr('style', 'display: none !important');
        }

        prevBtn.attr('style', index === 0 ? 'display: none !important' : 'display: inline-block !important');
    }


    showStep(currentStep);
});

    </script>


    <script>
        $(function() {
            localStorage.removeItem('print_id');
            $("#dob").mask("99-99-9999")
            $("#aadhar").mask("9999-9999-9999")
            $('#dob').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                endDate: '-15y'
            });
        });
    </script>

    <script>
        function checkInterMarks() {
            var obtained = parseInt($('#inter_obtained').val());
            var max = parseInt($("#inter_max").val());
            var alerted = localStorage.getItem('alertedInter') || '';
            if (obtained > max) {
                if (alerted != 'yes') {
                    alert("Obtained marks can not be higher than Maximum marks");
                    $(':input[type="submit"]').prop('disabled', true);
                    localStorage.setItem('alertedInter', 'yes');
                }
            } else {
                localStorage.setItem('alertedInter', 'no');
                $(':input[type="submit"]').prop('disabled', false);
                if ($('#inter_obtained').val().length > 0) {
                    var percentage = (obtained / max) * 100;
                    $('#inter_total').val(percentage.toFixed(2));
                    $("#inter_total").prop("readonly", true);
                } else if ($('#inter_obtained').val().length == 0) {
                    $("#inter_total").prop("readonly", false);
                    $('#inter_total').val('');
                }
            }
        }
    </script>

    <script>
        function checkUGMarks() {
            var obtained = parseInt($('#ug_obtained').val());
            var max = parseInt($("#ug_max").val());
            var alerted = localStorage.getItem('alertedUG') || '';
            if (obtained > max) {
                if (alerted != 'yes') {
                    alert("Obtained marks can not be higher than Maximum marks");
                    $(':input[type="submit"]').prop('disabled', true);
                    localStorage.setItem('alertedUG', 'yes');
                }
            } else {
                localStorage.setItem('alertedUG', 'no');
                $(':input[type="submit"]').prop('disabled', false);
                if ($('#ug_obtained').val().length > 0) {
                    var percentage = (obtained / max) * 100;
                    $('#ug_total').val(percentage.toFixed(2));
                    $("#ug_total").prop("readonly", true);
                } else if ($('#ug_obtained').val().length == 0) {
                    $("#ug_total").prop("readonly", false);
                    $('#ug_total').val('');
                }
            }
        }
    </script>

    <script>
        function checkPGMarks() {
            var obtained = parseInt($('#pg_obtained').val());
            var max = parseInt($("#pg_max").val());
            var alerted = localStorage.getItem('alertedPG') || '';
            if (obtained > max) {
                if (alerted != 'yes') {
                    alert("Obtained marks can not be higher than Maximum marks");
                    $(':input[type="submit"]').prop('disabled', true);
                    localStorage.setItem('alertedPG', 'yes');
                }
            } else {
                localStorage.setItem('alertedPG', 'no');
                $(':input[type="submit"]').prop('disabled', false);
                if ($('#pg_obtained').val().length > 0) {
                    var percentage = (obtained / max) * 100;
                    $('#pg_total').val(percentage.toFixed(2));
                    $("#pg_total").prop("readonly", true);
                } else if ($('#pg_obtained').val().length == 0) {
                    $("#pg_total").prop("readonly", false);
                    $('#pg_total').val('');
                }
            }
        }
    </script>

    <script>
        function getRegion(pincode) {
            if (pincode.length == 6) {
                $.ajax({
                    url: '/app/regions/cities?pincode=' + pincode,
                    type: 'GET',
                    success: function(data) {
                        $('#city').html(data);
                        <?php if (!empty($id) && !empty($address)) { ?>
                            $('#city').val('<?php echo !empty($id) && !empty($address) ? (array_key_exists('present_city', $address) ? $address['present_city'] : '') : '' ?>');
                        <?php } ?>
                    }
                });

                $.ajax({
                    url: '/app/regions/districts?pincode=' + pincode,
                    type: 'GET',
                    success: function(data) {
                        $('#district').html(data);
                        <?php if (!empty($id) && !empty($address)) { ?>
                            $('#district').val('<?php echo !empty($id) && !empty($address) ? (array_key_exists('present_district', $address) ? $address['present_district'] : '') : '' ?>');
                        <?php } ?>
                    }
                });

                $.ajax({
                    url: '/app/regions/state?pincode=' + pincode,
                    type: 'GET',
                    success: function(data) {
                        $('#state').val(data);
                    }
                })
            }
        }

        <?php if (!empty($id)) { ?>
            getRegion('<?php echo !empty($id) && !empty($address) ? (array_key_exists('present_pincode', $address) ? $address['present_pincode'] : '') : '' ?>');
        <?php } ?>
    </script>

    <!-- Application Form Functions -->
    <script type="text/javascript">
        function highDetailsRequired() {
            $('.high_school').addClass('required');
            $('#high_subject').validate();
            $('#high_subject').rules('add', {
                required: true
            });
            $('#high_year').validate();
            $('#high_year').rules('add', {
                required: true
            });
            $('#high_board').validate();
            $('#high_board').rules('add', {
                required: true
            });
            $('#high_total').validate();
            $('#high_total').rules('add', {
                required: true
            });
            <?php if (empty($id)) { ?>
                $('#high_marksheet').validate();
                $('#high_marksheet').rules('add', {
                    required: true
                });
            <?php } ?>

            <?php if (!empty($id) && empty($high_marksheet)) { ?>
                $('#high_marksheet').validate();
                $('#high_marksheet').rules('add', {
                    required: true
                });
            <?php } ?>
        }

        function highDetailsNotRequired() {
            $('.high_school').removeClass('required');
            $('#high_subject').rules('remove', 'required');
            $('#high_year').rules('remove', 'required');
            $('#high_board').rules('remove', 'required');
            $('#high_total').rules('remove', 'required');
            $('#high_marksheet').rules('remove', 'required');
        }

        function interDetailsRequired() {
            $('.intermediate').addClass('required');
            $('#inter_subject').validate();
            $('#inter_subject').rules('add', {
                required: true
            });
            $('#inter_year').validate();
            $('#inter_year').rules('add', {
                required: true
            });
            $('#inter_board').validate();
            $('#inter_board').rules('add', {
                required: true
            });
            $('#inter_total').validate();
            $('#inter_total').rules('add', {
                required: true
            });
            <?php if (empty($id)) { ?>
                $('#inter_marksheet').validate();
                $('#inter_marksheet').rules('add', {
                    required: true
                });
            <?php } ?>

            <?php if (!empty($id) && empty($inter_marksheet)) { ?>
                $('#inter_marksheet').validate();
                $('#inter_marksheet').rules('add', {
                    required: true
                });
            <?php } ?>
        }

        function interDetailsNotRequired() {
            $('.intermediate').removeClass('required');
            $('#inter_subject').rules('remove', 'required');
            $('#inter_year').rules('remove', 'required');
            $('#inter_board').rules('remove', 'required');
            $('#inter_total').rules('remove', 'required');
            $('#inter_marksheet').rules('remove', 'required');
        }

        function ugDetailsRequired() {
            $('.ug-program').addClass('required');
            $('#ug_subject').validate();
            $('#ug_subject').rules('add', {
                required: true
            });
            $('#ug_year').validate();
            $('#ug_year').rules('add', {
                required: true
            });
            $('#ug_board').validate();
            $('#ug_board').rules('add', {
                required: true
            });
            $('#ug_total').validate();
            $('#ug_total').rules('add', {
                required: true
            });
            <?php if (empty($id)) { ?>
                $('#ug_marksheet').validate();
                $('#ug_marksheet').rules('add', {
                    required: true
                });
            <?php } ?>

            <?php if (!empty($id) && empty($ug_marksheet)) { ?>
                $('#ug_marksheet').validate();
                $('#ug_marksheet').rules('add', {
                    required: true
                });
            <?php } ?>
        }

        function ugDetailsNotRequired() {
            $('.ug-program').removeClass('required');
            $('#ug_subject').rules('remove', 'required');
            $('#ug_year').rules('remove', 'required');
            $('#ug_board').rules('remove', 'required');
            $('#ug_total').rules('remove', 'required');
            $('#ug_marksheet').rules('remove', 'required');
        }

        function pgDetailsRequired() {
            $('.pg-program').addClass('required');
            $('#pg_subject').validate();
            $('#pg_subject').rules('add', {
                required: true
            });
            $('#pg_year').validate();
            $('#pg_year').rules('add', {
                required: true
            });
            $('#pg_board').validate();
            $('#pg_board').rules('add', {
                required: true
            });
            $('#pg_total').validate();
            $('#pg_total').rules('add', {
                required: true
            });
            <?php if (empty($id)) { ?>
                $('#pg_marksheet').validate();
                $('#pg_marksheet').rules('add', {
                    required: true
                });
            <?php } ?>

            <?php if (!empty($id) && empty($pg_marksheet)) { ?>
                $('#pg_marksheet').validate();
                $('#pg_marksheet').rules('add', {
                    required: true
                });
            <?php } ?>
        }

        function pgDetailsNotRequired() {
            $('.pg-program').removeClass('required');
            $('#pg_subject').rules('remove', 'required');
            $('#pg_year').rules('remove', 'required');
            $('#pg_board').rules('remove', 'required');
            $('#pg_total').rules('remove', 'required');
            $('#pg_marksheet').rules('remove', 'required');
        }

        function otherDetailsRequired() {
            $('.other-program').addClass('required');
            $('#other_subject').validate();
            $('#other_subject').rules('add', {
                required: true
            });
            $('#other_year').validate();
            $('#other_year').rules('add', {
                required: true
            });
            $('#other_board').validate();
            $('#other_board').rules('add', {
                required: true
            });
            $('#other_total').validate();
            $('#other_total').rules('add', {
                required: true
            });
            <?php if (empty($id)) { ?>
                $('#other_marksheet').validate();
                $('#other_marksheet').rules('add', {
                    required: true
                });
            <?php } ?>

            <?php if (!empty($id) && empty($other_marksheet)) { ?>
                $('#other_marksheet').validate();
                $('#other_marksheet').rules('add', {
                    required: true
                });
            <?php } ?>
        }

        function otherDetailsNotRequired() {
            $('.other-program').removeClass('required');
            $('#other_subject').rules('remove', 'required');
            $('#other_year').rules('remove', 'required');
            $('#other_board').rules('remove', 'required');
            $('#other_total').rules('remove', 'required');
            $('#other_marksheet').rules('remove', 'required');
        }

        function getSubCourse() {
            var center = $('#center').val();
            const university_id = $('.university_id').val();
            const session_id = $('#admission_session').val();
            const admission_type_id = $('#admission_type').val();
            const course_id = $('#course').val();

            // console.log(university_id);return false;
            $.ajax({
                url: '/app/application-form/sub-course?center=' + center + '&session_id=' + session_id + '&admission_type_id=' + admission_type_id + '&university_id=' + university_id + '&course_id=' + course_id,
                type: 'GET',
                success: function(data) {
                    $('#sub_course').html(data);
                    <?php if (!empty($id)) : ?>
                        $('#sub_course').val("<?php echo $student['Sub_Course_ID']; ?>");
                    <?php elseif (isset($_GET['lead_id'])) : ?>
                        $('#sub_course').val("<?php echo $lead['Sub_Course_ID']; ?>");
                    <?php endif; ?>
                    getMode();
                    getDuration()

                }
            });
        }


        function getMode() {
            const sub_course_id = $('#sub_course').val();
            $.ajax({
                url: '/app/application-form/mode?sub_course_id=' + sub_course_id,
                type: 'GET',
                success: function(data) {
                    $('#mode').html(data);
                    <?php if (!empty($id)) : ?>
                        $('#mode').val("<?php echo $student['Duration']; ?>");
                    <?php endif; ?>
                    getEligibility();
                }
            })
        }

        function getEligibility() {
            const sub_course_id = $('#sub_course').val();
            $.ajax({
                url: '/app/application-form/course-eligibility?id=' + sub_course_id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    if (data.status) {
                        var col_size = data.count == 1 ? 10 : data.count == 2 ? 5 : data.count == 3 ? 3 : data.count == 4 ? 2 : 2
                        if (data.eligibility == "Last Qulifications") {
                            otherDetailsRequired();
                            $("#other_column").css('display', 'block');
                            $("#other_column").addClass('col-sm-5');
                            // pgDetailsNotRequired();
                            // highDetailsNotRequired();
                            // interDetailsNotRequired();
                            // ugDetailsNotRequired();
                        }

                        if (data.eligibility.includes('High School')) {
                            // highDetailsRequired();
                            $("#high_school_column").css('display', 'block');
                            $("#high_school_column").addClass('col-sm-' + col_size);
                        } else {
                            // highDetailsNotRequired();
                            $("#high_school_column").css('display', 'none');
                        }

                        if (data.eligibility.includes('Intermediate')) {
                            // interDetailsRequired();
                            $("#intermediate_column").css('display', 'block');
                            $("#intermediate_column").addClass('col-sm-' + col_size);
                        } else {
                            // interDetailsNotRequired();
                        }

                        if (data.eligibility.includes('UG')) {
                            // ugDetailsRequired();
                            $("#ug_column").css('display', 'block');
                            $("#ug_column").addClass('col-md-' + col_size);
                        } else {
                            // ugDetailsNotRequired();
                        }

                        if (data.eligibility.includes('PG')) {
                            // pgDetailsRequired();
                            $("#pg_column").css('display', 'block');
                            $("#pg_column").addClass('col-md-' + col_size);
                        } else {
                            // pgDetailsNotRequired();
                        }

                        if (data.eligibility.includes('Other')) {
                            // otherDetailsRequired();
                            $("#other_column").css('display', 'block');
                            $("#other_column").addClass('col-md-' + col_size);
                        } else {
                            // otherDetailsNotRequired();
                        }
                    } else {
                        toastr.error('Eligibility is not configured for this course!');
                    }
                }
            })
        }


        function getDuration() {

            const admission_type_id = $('#admission_type').val();
            const sub_course_id = $('#sub_course').val();
            $.ajax({
                url: '/app/application-form/duration?admission_type_id=' + admission_type_id + '&sub_course_id=' + sub_course_id,
                type: 'GET',
                success: function(data) {
                    $('#duration').html(data);
                    $('#duration').val(<?php print !empty($id) ? $student['Duration'] : '' ?>)
                }
            });
        }







        // getAdmissionSession();

        function fileValidation(id) {
            var fi = document.getElementById(id);
            if (fi.files.length > 0) {
                for (var i = 0; i <= fi.files.length - 1; i++) {
                    var fsize = fi.files.item(i).size;
                    var file = Math.round((fsize / 1024));
                    // The size of the file.
                    if (file >= 500) {
                        $('#' + id).val('');
                        alert("File too Big, each file should be less than or equal to 500KB");
                    }
                }
            }
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            $('#step_1').validate({
                rules: {
                    center: {
                        required: true
                    },
                    admission_session: {
                        required: true
                    },
                    admission_type: {
                        required: true
                    },
                    course: {
                        required: true
                    },
                    sub_course: {
                        required: true
                    },
                    duration: {
                        required: true
                    },
                    full_name: {
                        required: true
                    },
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
                    father_name: {
                        required: true
                    },
                    mother_name: {
                        required: true
                    },
                    dob: {
                        required: true
                    },
                    gender: {
                        required: true
                    },
                    category: {
                        required: true
                    },
                    employment_status: {
                        required: true
                    },
                    aadhar: {
                        required: true
                    },
                    nationality: {
                        required: true
                    },
                },
                highlight: function(element) {
                    $(element).addClass('error');
                    $(element).closest('.form-control').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).removeClass('error');
                    $(element).closest('.form-control').removeClass('has-error');
                }
            });

            $('#step_1').submit(function(e) {
                if ($('#step_1').valid()) {
                    var formData = new FormData(this);
                    // console.log(formData);return false;
                    formData.append('inserted_id', localStorage.getItem('inserted_id'));
                    formData.append('lead_id', '<?php echo isset($_GET['lead_id']) ? $lead_id : 0 ?>');
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('action'),
                        type: "POST",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: 'json',
                        success: function(data) {
                            
                            if (data.status == 200) {
                                toastr.success(data.message);
                                localStorage.setItem('inserted_id', data.id);
                            } else {
                                toastr.error(data.message);
                                $('#previous-button').click();
                            }
                        },
                        error: function(data) {
                            toastr.error('Server is not responding. Please try again later');
                            $('#previous-button').click();
                            console.log(data);
                        }
                    });
                }return false;
            });
        

            $('#step_2').validate({
                rules: {
                    email: {
                        required: true
                    },
                    contact: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    pincode: {
                        required: true
                    },
                    city: {
                        required: true
                    },
                    district: {
                        required: true
                    },
                    state: {
                        required: true
                    },
                },
                highlight: function(element) {
                    $(element).addClass('error');
                    $(element).closest('.form-control').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).removeClass('error');
                    $(element).closest('.form-control').removeClass('has-error');
                }
            });

            $('#step_3').validate();

            $('#step_4').validate({
                rules: {
                    <?php print (!empty($id) && empty($photo)) ? "photo: {required:true}," : "" ?>
                    <?php print empty($id) ? "photo: {required:true}," : "" ?>
                    <?php print (!empty($id) && empty($aadhaars)) ? "'aadhar[]': {required:true}," : "" ?>
                    <?php print empty($id) ? "'aadhar[]': {required:true}," : "" ?>
                    <?php print (!empty($id) && empty($students_signature)) ? "student_signature: {required:true}," : "" ?>
                    <?php print empty($id) ? "student_signature: {required:true}," : "" ?>
                },
                highlight: function(element) {
                    $(element).addClass('error');
                    $(element).closest('.form-control').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).removeClass('error');
                    $(element).closest('.form-control').removeClass('has-error');
                }
            });


            // $('#rootwizard').bootstrapWizard({
            //     onTabShow: function(tab, navigation, index) {
            //         var $total = navigation.find('div').length;
            //         var $current = index + 1;

            //         // If it's the last tab then hide the last button and show the finish instead
            //         if ($current >= $total) {
            //             $('#rootwizard').find('.pager .next').hide();
            //             $('#rootwizard').find('.pager .finish').show().removeClass('disabled hidden');
            //         } else {
            //             $('#rootwizard').find('.pager .next').show();
            //             $('#rootwizard').find('.pager .finish').hide();
            //         }

            //         var li = navigation.find('li a.active').parent();

            //         var btnNext = $('#rootwizard').find('.pager .next').find('button');
            //         var btnPrev = $('#rootwizard').find('.pager .previous').find('button');

            //         if ($current < $total) {
            //             var nextIcon = li.next().find('.uil');
            //             var nextIconClass = nextIcon.text();

            //             btnNext.find('.uil').html(nextIconClass)

            //             var prevIcon = li.prev().find('.uil');
            //             var prevIconClass = prevIcon.html()
            //             btnPrev.addClass('btn-animated');
            //             btnPrev.find('.hidden-block').show();
            //             btnPrev.find('.uil').html(prevIconClass);
            //         }

            //         if ($current == 1) {
            //             btnPrev.find('.hidden-block').hide();
            //             btnPrev.removeClass('btn-animated');
            //         }
            //     },
            //     onTabClick: function(activeTab, navigation, currentIndex, nextIndex) {
            //         console.log(nextIndex, currentIndex);
            //         if (nextIndex <= currentIndex) {
            //             return;
            //         }
            //         if (nextIndex > currentIndex + 1) {
            //             return false;
            //         }
            //         return submitForm(nextIndex);
            //     },
            //     onNext: function(tab, navigation, index) {
            //         return submitForm(index);
            //     },
            //     onPrevious: function(tab, navigation, index) {
            //         console.log("previous");
            //     },
            //     onInit: function() {
            //         $('#rootwizard ul').removeClass('nav-pills');
            //     }
            // });

            // $('.remove-item').click(function() {
            //     $(this).parents('tr').fadeOut(function() {
            //         $(this).remove();
            //     });
            // });
        });

        // function submitForm(index=1) {

        //     var $valid = $("#step_" + index).valid();
        //     if (!$valid) {
        //         return false;
        //     } else {
        //         $('#step_' + index).submit();
        //     }
        // }



        $('#step_2').submit(function(e) {
            var formData = new FormData(this);
            formData.append('inserted_id', localStorage.getItem('inserted_id'));
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 200) {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                        $('#previous-button').click();
                    }
                },
                error: function(data) {
                    toastr.error('Server is not responding. Please try again later');
                    $('#previous-button').click();
                    console.log(data);
                }
            });
        });

        $('#step_3').submit(function(e) {
            var formData = new FormData(this);
            formData.append('inserted_id', localStorage.getItem('inserted_id'));
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 200) {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                        $('#previous-button').click();
                    }
                },
                error: function(data) {
                    toastr.error('Server is not responding. Please try again later');
                    $('#previous-button').click();
                    console.log(data);
                }
            });
        });

        $('#step_4').submit(function(e) {
            var formData = new FormData(this);
            formData.append('inserted_id', localStorage.getItem('inserted_id'));
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 200) {
                        localStorage.removeItem('inserted_id');
                        localStorage.setItem('print_id', data.print_id);
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(data) {
                    toastr.error('Server is not responding. Please try again later');
                    console.log(data);
                }
            });
        });

        function printForm() {
            window.open('/forms/47/index.php/?student_id=' + localStorage.getItem('print_id'));
        }
    </script>
    <!-- kp -->
    <script>
        $(function() {
            $(".university_id").select2({
                placeholder: 'Choose University'
            });
            // $("#center").select2({
            //     placeholder: 'Choose Center'
            // });

        })
    </script>
    <?php if (isset($_GET['id'])) {
        $center_id = $student['Added_For'];
        $university_ids = $student['University_ID'];
    } else if ($_SESSION['Role'] == "Center") {
        $center_id = $_SESSION['ID'];
        $university_ids = $_SESSION['University_ID'];
    } else {
        $center_id = NULL;
        $university_ids = NULL;
    } ?>
    <?php if (isset($_GET['id']) || $_SESSION['Role'] == "Center") { ?>
        <script>
            $(document).ready(function() {
                getUniversity('<?= $center_id ?>');


            })
        </script>
    <?php  } ?>
    <script>
        function getUniversity(center = null) {
            // var center = $('#center').val();
            $.ajax({
                url: '/app/application-form/get-university?center=' + center,
                type: 'GET',
                success: function(data) {
                    $('.university_id').html(data);
                    var university_id = $(".university_id").val();
                    getAdmissionSession(university_id);

                }
            })

        }

        function getCourse() {
            var center = $('#center').val();
            // console.log(center);return false;
            const university_id = $(".university_id").val();
            const session_id = $('#admission_session').val();
            const admission_type_id = $('#admission_type').val();
            $.ajax({
                url: '/app/application-form/course?center=' + center + '&session_id=' + session_id + '&admission_type_id=' + admission_type_id + '&university_id=' + university_id + '&form=<?php print !empty($id) || !empty($lead_id) ? 1 : "" ?>',
                type: 'GET',
                success: function(data) {
                    $('#course').html(data);
                    $('#course').val(<?php print !empty($id) ? $student['Course_ID'] : (isset($_GET['lead_id']) ? $lead['Course_ID'] : '') ?>);
                    getSubCourse();

                }
            })
        }



        function getAdmissionSession(university_id) {
            $.ajax({
                url: '/app/application-form/admission-session?university_id=' + university_id + '&form=<?php print !empty($id) ? 1 : "" ?>',
                type: 'GET',
                success: function(data) {
                    $('#admission_session').html(data);
                    $('#admission_session').val(<?php print !empty($id) ? $student['Admission_Session_ID'] : '' ?>);
                    getAdmissionType($('#admission_session').val());

                }
            })
        }

        function getAdmissionType(session_id) {
            const university_id = $(".university_id").val();
            $.ajax({
                url: '/app/application-form/admission-type?university_id=' + university_id + '&session_id=' + session_id,
                type: 'GET',
                success: function(data) {
                    $('#admission_type').html(data);
                    $('#admission_type').val(<?php print !empty($id) ? $student['Admission_Type_ID'] : '' ?>);
                    getCourse();
                }
            })
        }
    </script> <!--end kp-- >
    <?php include($_SERVER['DOCUMENT_ROOT'] . '../includes/footer-bottom.php') ?>
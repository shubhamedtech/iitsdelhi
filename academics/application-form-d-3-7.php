<?php include($_SERVER['DOCUMENT_ROOT'] . '../includes/header-top.php') ?>
<link rel="stylesheet" href="../assets/vendor/libs/bs-stepper/bs-stepper.css" />
<link rel="stylesheet" href="../assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
<!-- <link rel="stylesheet" href="../assets/vendor/libs/flatpickr/flatpickr.css" /> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" />
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<?php ini_set('display_errors', 1); ?>
<style type="text/css">
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
            <div class="bs-stepper wizard-icons wizard-icons-example mt-2">

                <div class="bs-stepper-header">
                    <div class="step" data-target="#basic-details">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-icon">
                                <svg viewBox="0 0 54 54">
                                    <use xlink:href='https://demos.pixinvent.com/materialize-html-admin-template/assets/svg/icons/form-wizard-account.svg#wizardAccount'></use>
                                </svg>
                            </span>
                            <span class="bs-stepper-label">Basic Details</span>
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
                            <span class="bs-stepper-label">Personal Details</span>
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
                            <span class="bs-stepper-label">Academics</span>
                        </button>
                    </div>
                    <div class="line">
                        <i class="ri-arrow-right-s-line"></i>
                    </div>
                    <div class="step" data-target="#documents">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-icon">
                                <svg viewBox="0 0 54 54">
                                    <use xlink:href='https://demos.pixinvent.com/materialize-html-admin-template/assets/svg/icons/form-wizard-social-link.svg#wizardSocialLink'></use>
                                </svg>
                            </span>
                            <span class="bs-stepper-label">Documents</span>
                        </button>
                    </div>
                    <div class="step" data-target="#application-form">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-icon">
                                <svg viewBox="0 0 54 54">
                                    <use xlink:href='https://demos.pixinvent.com/materialize-html-admin-template/assets/svg/icons/form-wizard-social-link.svg#wizardSocialLink'></use>
                                </svg>
                            </span>
                            <span class="bs-stepper-label">Aplication form</span>
                        </button>
                    </div>
                    <!-- <div class="line">
                        <i class="ri-arrow-right-s-line"></i>
                    </div>
                    <div class="step" data-target="#review-submit">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-icon">
                                <svg viewBox="0 0 54 54">
                                    <use xlink:href='https://demos.pixinvent.com/materialize-html-admin-template/assets/svg/icons/form-wizard-submit.svg#wizardSubmit'></use>
                                </svg>
                            </span>
                            <span class="bs-stepper-label">Review & Submit</span>
                        </button>
                    </div> -->
                </div>
                <div class="bs-stepper-content">

                    <!-- Account Details -->
                    <div id="basic-details" class="content">
                        <form id="step_1" role="form" autocomplete="off" action="/app/application-form/step-1" enctype="multipart/form-data">
    <div class="content-header mb-4">
        <h6 class="mb-0 p-0 fs-4 text-black fw-bolder">Apply For</h6>
        <!-- <small>Enter Your Account Details.</small> -->
    </div>
    <div class="row">
        <div class="col-sm-3 m-0">
            <label class="col-form-label" for="multicol-full-name">Full Name <span class="text-danger">*</span></label>
            <input type="text" id="full_name" name="full_name" class="form-control" placeholder="ex: John Doe" required />
        </div>
    </div>
    <div class="col-12 d-flex justify-content-between mt-4">
        <button class="btn btn-outline-secondary btn-prev" disabled> <i class="ri-arrow-left-line me-sm-1"></i>
            <span class="align-middle d-sm-inline-block d-none">Previous</span>
        </button>
        <button type="submit" class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="ri-arrow-right-line"></i></button>
    </div>
</form>

                    </div>


                    <!-- Personal Info -->
                    <div id="personal-details" class="content">
                        <p>h2</p>
                        <div class="content-header mb-4">
                            <h6 class="mb-0">Personal Info</h6>
                            <small>Enter Your Personal Info.</small>
                        </div>
                        <div class="row g-5">
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="first-name" class="form-control" placeholder="John" />
                                    <label for="first-name">First Name</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="last-name" class="form-control" placeholder="Doe" />
                                    <label for="last-name">Last Name</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <select class="select2" id="country">
                                        <option label=" "></option>
                                        <option>UK</option>
                                        <option>USA</option>
                                        <option>Spain</option>
                                        <option>France</option>
                                        <option>Italy</option>
                                        <option>Australia</option>
                                    </select>
                                    <label for="country">Country</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <select class="selectpicker w-auto" id="language" data-style="btn-transparent" data-tick-icon="ri-check-line text-white" multiple>
                                        <option>English</option>
                                        <option>French</option>
                                        <option>Spanish</option>
                                    </select>
                                    <label for="language">Language</label>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <button class="btn btn-outline-secondary btn-prev"> <i class="ri-arrow-left-line me-sm-1"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="ri-arrow-right-line"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- Address -->
                    <div id="academics" class="content">
                        <p>h3</p>
                        <div class="content-header mb-4">
                            <h6 class="mb-0">Address</h6>
                            <small>Enter Your Address.</small>
                        </div>
                        <div class="row g-5">
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="address-input" placeholder="98  Borough bridge Road, Birmingham">
                                    <label for="address-input">Address</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="landmark" placeholder="Borough bridge">
                                    <label for="landmark">Landmark</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="pincode" placeholder="658921">
                                    <label for="pincode">Pincode</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="city" placeholder="Birmingham">
                                    <label for="city">City</label>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <button class="btn btn-outline-secondary btn-prev"> <i class="ri-arrow-left-line me-sm-1"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="ri-arrow-right-line"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- Social Links -->
                    <div id="documents" class="content">
                        <p>h4</p>
                        <div class="content-header mb-4">
                            <h6 class="mb-0">Social Links</h6>
                            <small>Enter Your Social Links.</small>
                        </div>
                        <div class="row g-5">
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="twitter" class="form-control" placeholder="https://twitter.com/abc" />
                                    <label for="twitter">Twitter</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="facebook" class="form-control" placeholder="https://facebook.com/abc" />
                                    <label for="facebook">Facebook</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="google" class="form-control" placeholder="https://plus.google.com/abc" />
                                    <label for="google">Google+</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="linkedin" class="form-control" placeholder="https://linkedin.com/abc" />
                                    <label for="linkedin">Linkedin</label>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <button class="btn btn-outline-secondary btn-prev"> <i class="ri-arrow-left-line me-sm-1"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="ri-arrow-right-line"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- Application form-->
                    <div id="application-form" class="content">
                        <p>h</p>
                        <div class="content-header mb-4">
                            <h6 class="mb-0">Social Links</h6>
                            <small>Enter Your Social Links.</small>
                        </div>
                        <div class="row g-5">
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="twitter" class="form-control" placeholder="https://twitter.com/abc" />
                                    <label for="twitter">Twitter</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="facebook" class="form-control" placeholder="https://facebook.com/abc" />
                                    <label for="facebook">Facebook</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="google" class="form-control" placeholder="https://plus.google.com/abc" />
                                    <label for="google">Google+</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="linkedin" class="form-control" placeholder="https://linkedin.com/abc" />
                                    <label for="linkedin">Linkedin</label>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <button class="btn btn-outline-secondary btn-prev"> <i class="ri-arrow-left-line me-sm-1"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="ri-arrow-right-line"></i></button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '../includes/footer-top.php') ?>
\
    <script src="../../assets/vendor/libs/bs-stepper/bs-stepper.js"></script>
    <script src="../../assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="../../assets/js/form-wizard-icons.js"></script>
    <script src="../../assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="../../assets/vendor/libs/select2/select2.js"></script>
    <script src="../../assets/js/form-layouts.js"></script>
    <script>
        $(document).ready(function() {
            $('#step_1').validate({
                rules: {
                    full_name: {
                        required: true
                    },
                },
                highlight: function(element) {
                    console.log(element+"eleements");
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
                    alert("nope"); return false;
                    var formData = new FormData(this);
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
                                //   notification('success', data.message);
                                localStorage.setItem('inserted_id', data.id);
                            } else {
                                //   notification('danger', data.message);
                                $('#previous-button').click();
                            }
                        },
                        error: function(data) {
                            // notification('danger', 'Server is not responding. Please try again later');
                            $('#previous-button').click();
                            // console.log(data);
                        }
                    });
                } return false;
            });
        });
    </script>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '../includes/footer-bottom.php') ?>
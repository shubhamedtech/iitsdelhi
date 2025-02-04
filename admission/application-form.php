<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-top.php') ?>
<link rel="stylesheet" href="../assets/vendor/libs/bs-stepper/bs-stepper.css" />
<link rel="stylesheet" href="../assets/vendor/libs/bootstrap-select/bootstrap-select.css" />


<?php ini_set('display_errors', 1); ?>
<style type="text/css">
    input {
        text-transform: uppercase;
    }
</style>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-bottom.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/side-menu.php') ?>
<?php require ($_SERVER['DOCUMENT_ROOT'] .'/includes/db-config.php'); ?>

<div class="layout-page">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/top-menu.php') ?>
    <?php
    $is_get = 0;
    $id = 0;
    $address = [];
    $inserted_id = 0;
    if (isset($_GET['id'])) {
        $inserted_id = $_GET['id'];

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
    // echo "<pre>"; print_r($_SESSION);
    ?>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="bs-stepper wizard-icons wizard-icons-example mt-2">

                <div class="bs-stepper-header">
                    <div class="steps" data-target="#basic-detailss">
                        <button type="button" class="step-trigger">
                            <?php
                            if (isset($inserted_id) & $inserted_id != 0) {
                                $url1 = "/admission/application-form?step=1&id=" . $inserted_id;
                            } else {
                                $url1 = '#';
                            }
                            ?>
                            <a href="<?= $url1 ?>" class="d-flex flex-column">
                                <span class="bs-stepper-icon">
                                    <!-- <svg viewBox="0 0 54 54">
                                    <use xlink:href='https://demos.pixinvent.com/materialize-html-admin-template/assets/svg/icons/form-wizard-account.svg#wizardAccount'></use>
                                </svg> -->
                                    <img src="/assets/img/form_icon/application.png" alt="" width="50" height="50">
                                </span>
                                <span class="bs-stepper-label text-black fw-bold">Basic Details</span></a>
                        </button>
                    </div>
                    <div class="line">
                        <i class="ri-arrow-right-s-line"></i>
                    </div>
                    <div class="steps" data-target="#personal-details">
                        <button type="button" class="step-trigger">
                            <?php
                            if (isset($inserted_id) & $inserted_id != 0) {
                                $url2 = "/admission/application-form?step=2&id=" . $inserted_id;
                            } else {
                                $url2 = '#';
                            }
                            ?>
                            <a href="<?= $url2 ?>" class="d-flex flex-column">
                                <span class="bs-stepper-icon">
                                    <!-- <svg viewBox="0 0 58 54">
                                    <use xlink:href='https://demos.pixinvent.com/materialize-html-admin-template/assets/svg/icons/form-wizard-personal.svg#wizardPersonal'></use>
                                </svg> -->
                                    <img src="/assets/img/form_icon/personal-details.png" alt="" width="50" height="50">

                                </span>
                                <span class="bs-stepper-label text-black fw-bold">Personal Details</span></a>
                        </button>
                    </div>
                    <div class="line">
                        <i class="ri-arrow-right-s-line"></i>
                    </div>
                    <div class="steps" data-target="#academics">
                        <button type="button" class="step-trigger">
                            <?php
                            // echo "SELECT id,Student_ID FROM Student_Documents WHERE Student_ID =  $id"; die;
                            $getSql = $conn->query("SELECT id,Student_ID FROM Student_Documents WHERE Student_ID =  $id");
                            if ($getSql->num_rows > 0) {
                                $url3e = "/admission/application-form?step=3&id=" . $inserted_id;
                            } else {
                                $url3e = '#';
                            }
                            ?>
                            <a href="<?= $url3e ?>" class="d-flex flex-column">
                                <span class="bs-stepper-icon">
                                    <!-- <svg viewBox="0 0 54 54">
                                    <use xlink:href='https://demos.pixinvent.com/materialize-html-admin-template/assets/svg/icons/form-wizard-address.svg#wizardAddress'></use>
                                </svg> -->
                                    <img src="/assets/img/form_icon/academic.png" alt="" width="50" height="50">

                                </span>
                                <span class="bs-stepper-label text-black fw-bold">Academics</span></a>
                        </button>
                    </div>
                    <div class="line">
                        <i class="ri-arrow-right-s-line"></i>
                    </div>
                    <div class="steps" data-target="#documents">
                        <button type="button" class="step-trigger">
                        <?php
                            $getSql4 = $conn->query("SELECT id,Student_ID FROM Student_Documents WHERE Type!='Aadhar' AND Student_ID =  $id");
                            if ($getSql4->num_rows > 0) {
                                $url4 = "/admission/application-form?step=4&id=" . $inserted_id;
                            } else {
                                $url4 = '#';
                            }
                            ?>
                            <a href="<?= $url4 ?>" class="d-flex flex-column">
                            <span class="bs-stepper-icon">
                                <!-- <svg viewBox="0 0 54 54">
                                    <use xlink:href='https://demos.pixinvent.com/materialize-html-admin-template/assets/svg/icons/form-wizard-social-link.svg#wizardSocialLink'></use>
                                </svg> -->
                                <img src="/assets/img/form_icon/documentation.png" alt="" width="50" height="50">

                            </span>
                           <span class="bs-stepper-label text-black fw-bold">Documents</span></a>
                        </button>
                    </div>
                    <div class="line">
                        <i class="ri-arrow-right-s-line"></i>
                    </div>
                    <div class="steps" data-target="#application-form">
                        <button type="button" class="step-trigger">
                        <?php
                            $getSql5 = $conn->query("SELECT id,Student_ID FROM Student_Documents WHERE Type ='Aadhar' AND Student_ID =  $id");

                            if ($getSql5->num_rows > 0) {
                                $url5 = "/admission/application-form?step=5&id=" . $inserted_id;
                            } else {
                                $url5 = '#';
                            }
                            ?>
                            <a href="<?= $url5 ?>" class="d-flex flex-column">
                            <span class="bs-stepper-icon">
                                <!-- <svg viewBox="0 0 54 54">
                                    <use xlink:href='https://demos.pixinvent.com/materialize-html-admin-template/assets/svg/icons/form-wizard-social-link.svg#wizardSocialLink'></use>
                                </svg> -->
                                <img src="/assets/img/form_icon/user.png" alt="" width="50" height="50">

                            </span>
                           <span class="bs-stepper-label text-black fw-bold">Aplication form</span></a>
                        </button>
                    </div>

                </div>
                <div class="bs-stepper-content">

                    <!-- Account Details -->

                    <?php
                    // include('step-1.php');
                    if (isset($_GET['step'])) {

                        $step_val = $_GET['step'];
                        include('step-' . $step_val . '.php');
                    } else {
                        include('step-1.php');
                    }
                    ?>

                    <?php
                    //    include('step-2.php');
                    //    include('step-3.php');
                    //  include('step-4.php'); 
                    //  include('step-5.php');
                    ?>



                    <!-- Personal Info -->

                    <!-- Address -->

                    <!-- Social Links -->

                    <!-- Application form-->


                </div>
            </div>
        </div>
    </div>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-top.php') ?>

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

    <script src="../assets/vendor/libs/bs-stepper/bs-stepper.js"></script>
    <script src="../assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="../assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <!-- <script src="../assets/js/form-validation.js"></script> -->
    <script src="../assets/js/form-wizard-icons.js"></script>

    <!-- Bootstrap JS (for datepicker dependency) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery Validation Plugin CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <!-- jQuery Mask Plugin CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- Toastr for notifications -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
      /*  $(function() {
            localStorage.removeItem('print_id');
            $("#dob").mask("99-99-9999")
            $("#aadhar").mask("9999-9999-9999")
            $('#dob').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                endDate: '-15y'
            });
        });*/
       $(document).ready(function() {
            localStorage.removeItem('print_id');
            $("#aadhar").mask("9999-9999-9999");

            // Initialize Flatpickr on the DOB input field
            flatpickr("#dob", {
                dateFormat: "d-m-Y",
                maxDate: new Date().fp_incr(-15 * 365) // 15 years ago
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
        function printForm() {
            window.open('/forms/47/index.php/?student_id=' + localStorage.getItem('print_id'));
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
            var sub_course_id = $('#sub_course').val();

            $.ajax({
                url: '/app/application-form/course-eligibility?id=' + sub_course_id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {}
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

    <script>
        $(document).ready(function() {
            var current_fs, next_fs, previous_fs;
            var form = $("#wizard-form");

            $('#step_1').validate({
                rules: {
                    center: {
                        required: true
                    },
                    university_id: {
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
                    // console.log(element + "eleements");
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
                                window.location.href = '/admission/application-form?step=2&id=' + data.id;

                            } else {
                                toastr.error();
                                (data.message);
                                $('#previous-button').click();
                            }
                        },
                        error: function(data) {
                            // notification('danger', 'Server is not responding. Please try again later');
                            $('#previous-button').click();
                            // console.log(data);
                        }
                    });
                }
            });

            $('#step_2').validate({
                rules: {
                    email: {
                        required: true
                    },
                    alternate_email: {
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
            $('#step_2').submit(function(e) {
                if ($('#step_2').valid()) {
                    var formData = new FormData(this);
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
                                // console.log(data); return false;

                                window.location.href = '/admission/application-form?step=3&id=' + data.id;

                            } else {
                                toastr.error(data.message);
                                $('#previous-button').click();
                            }
                        },
                        error: function(data) {
                            toastr.error('Server is not responding. Please try again later');
                            $('#previous-button').click();
                            // console.log(data);
                        }
                    });
                }
            });


            $('#step_3').validate();

            $('#step_3').submit(function(e) {
                if ($('#step_3').valid()) {

                    var formData = new FormData(this);

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
                                window.location.href = '/admission/application-form?step=4&id=' + data.id;
                            } else {
                                toastr.error(data.message);
                                $('#previous-button').click();
                            }
                        },
                        error: function(data) {
                            toastr.error('Server is not responding. Please try again later');
                            $('#previous-button').click();
                            // console.log(data);
                        }
                    });
                }
            });

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

            $('#step_4').submit(function(e) {
                if ($('#step_4').valid()) {

                    var formData = new FormData(this);

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
                                // console.log(data); return false;
                                // localStorage.removeItem('inserted_id');
                                // localStorage.setItem('print_id', data.id);
                                toastr.success(data.message);
                                window.location.href = '/admission/application-form?step=5&id=' + data.id;


                            } else {
                                toastr.error(data.message);
                            }
                        },
                        error: function(data) {
                            toastr.error('Server is not responding. Please try again later');
                            // console.log(data);
                        }
                    });
                }
            });


        });
    </script>
    <script>
        $(function() {
            $(".university_id").select2({
                placeholder: 'Choose University'
            });

        })
    </script>
<?php
// Initialize variables
$center_id = NULL;
$university_ids = [];

// Determine values based on conditions
if (isset($_GET['id'])) {
    $center_id = $student['Added_For'];
    $university_ids = $student['University_ID'];
} else if ($_SESSION['Role'] == "Center") {
    $center_id = $_SESSION['ID'];
    $university_ids = $_SESSION['University_ID'];
}
?>

<?php if (isset($_GET['id']) || $_SESSION['Role'] == "Center") { ?>
    <script>
        $(document).ready(function() {
            var center = <?= json_encode($center_id) ?>;
            var universityIds = <?= json_encode($university_ids) ?>;
          
            getUniversity(center, universityIds);
        });
    </script>
<?php } ?>



    <script>
        function getUniversity(center = null,university_ids=null) {

            // var center = $('#center').val();
            $.ajax({
                url: '/app/application-form/get-university?center=' + center+'&university_ids='+university_ids,
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
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-bottom.php') ?>
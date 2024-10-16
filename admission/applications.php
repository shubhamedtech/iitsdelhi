<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-top.php') ?>

<style>
    .dataTables_length {
        text-align: start !important;
    }

    .custom-tooltip .tooltip-inner {
        max-width: 300px;
        /* Set your desired width */
        white-space: pre-wrap;
        /* Keep line breaks and spaces */
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" />
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-bottom.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/side-menu.php') ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/includes/db-config.php'); ?>
<?php
unset($_SESSION['current_session']);
unset($_SESSION['current_session']);
unset($_SESSION['filterByDepartment']);
unset($_SESSION['filterByUser']);
unset($_SESSION['filterByDate']);
unset($_SESSION['filterBySubCourses']);
unset($_SESSION['filterByStatus']);
unset($_SESSION['filterByFeeAssigned']);
?>
<div class="layout-page">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/top-menu.php') ?>
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
                <div>
                    <?php if ($_SESSION['Role'] == 'Administrator' || $_SESSION['Role'] == 'University Head') { ?>
                        <!-- <button class="btn btn-link" aria-label="" title="" data-toggle="tooltip" data-original-title="Upload OA, Enrollment AND Roll No." onclick="uploadOAEnrollRoll()"> <i class="uil uil-upload"></i></button> -->
                        <!-- <button class="btn btn-link" aria-label="" title="" data-toggle="tooltip" data-original-title="Upload Pendency" onclick="uploadMultiplePendency()"> <i class="uil uil-file-upload-alt"></i></button> -->
                    <?php } ?>
                    <!-- <button class="btn btn-link" aria-label="" title="" data-toggle="tooltip" data-original-title="Download Excel" onclick="exportData()"> <i class="uil uil-down-arrow"></i></button> -->
                    <!-- <button class="btn btn-link" aria-label="" title="" data-toggle="tooltip" data-original-title="Download Documents" onclick="exportSelectedDocument()"> <i class="uil uil-file-download-alt"></i></button> -->
                    <button class="btn btn-link btn-primary" aria-label="" title="" data-toggle="tooltip" data-original-title="Add Student" onclick="window.open('/admission/application-form');"> <i class="ri-apps-2-add-line"></i></button>
                  <div class="dropdown">
                    <button class="btn btn-sm btn-danger dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Export Record
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                        <li>
                            <button class="btn btn-sm btn-success dropdown-item buttons-pdf" id="exportPDF">Export as PDF</button>
                        </li>
                        <li>
                            <button class="btn btn-sm btn-primary dropdown-item buttons-csv" id="exportCSV">Export as CSV</button>
                        </li>
                        <!--<li>
                            <button class="btn btn-sm btn-info dropdown-item buttons-copy" id="exportCopy">Export as Copy</button>
                        </li>
                        <li>
                            <button class="btn btn-sm btn-warning dropdown-item buttons-excel" id="exportExcel">Export as Excel</button>
                        </li>-->
                    </ul>
                </div>
                </div>
            </ol>
            <div class="row justify-content-center mb-3">
                <div class="col-sm-2">
                    <select class="select2 form-select" data-allow-clear="true" id="univeristies" onchange="getadmissionsession(this.value)" data-placeholder="Choose Program">
                        <option value="All">All</option>
                        <?php
                        $universities = $conn->query("SELECT ID , Name FROM Universities WHERE Status=1");
                        while ($university = $universities->fetch_assoc()) { ?>
                            <option value="<?= $university['ID'] ?>" <?= !empty($university_array) ? (in_array($university['ID'], $university_array) ? 'selected' : '') : '' ?>><?= $university['Name'] ?></option>
                        <?php } ?>
                    </select>



                </div>
                <div class="col-sm-2">
                    <select class="select2 form-select" data-allow-clear="true" id="sessions" onchange="changeSession(this.value)">
                        <option value="All">All</option>

                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="select2 form-select" data-allow-clear="true" id="sub_courses" onchange="addFilter(this.value, 'sub_courses')" data-placeholder="Choose Program">
                        <option value="All">All</option>
                        <?php
                        $programs = $conn->query("SELECT Sub_Courses.ID, CONCAT(Courses.Short_Name, ' (', Sub_Courses.Name, ')') as Name FROM Students LEFT JOIN Sub_Courses ON Students.Sub_Course_ID = Sub_Courses.ID LEFT JOIN Courses ON Sub_Courses.Course_ID = Courses.ID WHERE  Students.University_ID IS NOT NULL $role_querys GROUP BY Students.Sub_Course_ID");
                        while ($program = $programs->fetch_assoc()) {
                            echo '<option value="' . $program['ID'] . '">' . $program['Name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="select2 form-select" data-allow-clear="true" id="search_assign_fee" onchange="addFilter(this.value, 'search_assign_fee');" data-placeholder="Choose Program">
                        <option value="">Choose Fee Type</option>
                        <option value="1">Fee Assigned</option>
                        <option value="2">Fee not Assigned</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <div class="input-daterange input-group d-flex align-items-center" id="datepicker-range">
                        <input type="text" class="input-sm form-control" placeholder="Select Date" id="startDateFilter" name="start" />
                        <div class="input-group-addon mx-2">to</div>
                        <input type="text" class="input-sm form-control" placeholder="Select Date" id="endDateFilter" onchange="addDateFilter()" name="end" />
                    </div>
                </div>
                <?php if ($_SESSION['Role'] != 'Sub-Center' && $_SESSION['Role'] != 'Center') { ?>
                    <div class="col-sm-2">
                        <select class="select2 form-select" data-allow-clear="true" id="users" onchange="addFilter(this.value, 'users')" data-placeholder="Choose User">

                        </select>
                    </div> <?php } ?>
                <?php if ($_SESSION['Role'] == 'Center') { ?>

                    <!-- <div class="col-md-2 m-b-10">
                    <div class="form-group">
                    <select class="form-control sub_center" data-init-plugin="select2" id="center_sub_center" onchange="addSubCenterFilter(this.value, 'users')" data-placeholder="Choose Sub Center">
                      <?php $sub_center_query = $conn->query("SELECT Users.ID, Users.Name, Users.Code FROM Center_SubCenter LEFT JOIN Users ON Users.ID = Center_SubCenter.Sub_Center  WHERE Center_SubCenter.Center='" . $_SESSION['ID'] . "' AND Users.Role='Sub-Center'");
                        while ($subCenterArr = $sub_center_query->fetch_assoc()) { ?>
                        <option value="">Choose Sub Center</option>
                        <option value="<?= $subCenterArr['ID'] ?>"><?= $subCenterArr['Name'] . "(" . $subCenterArr['Code'] . ")"  ?></option>
                      <?php } ?>  
                    </select>
                    </div>
                  </div> -->
                <?php } ?>
            </div>
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="card text-center mb-4">
                        <div class="card-header">
                            <div class="nav-align-top ">
                                <ul class="nav nav-pills d-flex justify-content-between" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link d-flex flex-column gap-1 waves-effect waves-light active" role="tab" data-bs-toggle="tab" data-bs-target="#all_applications" aria-controls="navs-pills-within-card-active" aria-selected="true"><i class="tf-icons ri-home-smile-line"></i><span> ALL APPLICATIONS (<span id="application_count">0</span>)</span></button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="p-1 nav-link d-flex flex-column gap-1 waves-effect waves-light" role="tab" data-bs-toggle="tab" data-bs-target="#not_processed" aria-controls="navs-pills-within-card-link" aria-selected="false" tabindex="-1"><i class="tf-icons ri-prohibited-line"></i><span> NOT PROCESSED (<span id="not_processed_count">0</span>)</span></button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="p-1 nav-link d-flex flex-column gap-1  waves-effect waves-light" role="tab" data-bs-toggle="tab" data-bs-target="#ready_for_verification" aria-selected="false" tabindex="-1"><i class="ri-id-card-fill"></i><span> READY FOR VERIFICATION (<span id="ready_for_verification_count">0</span>)</span></button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class=" p-1 nav-link d-flex flex-column gap-1  waves-effect waves-light" role="tab" data-bs-toggle="tab" data-bs-target="#verified" aria-selected="false" tabindex="-1"><i class="ri-verified-badge-line"></i><span> VERIFIED (<span id="verified_count">0</span>)</span></button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="p-1 nav-link d-flex flex-column gap-1  waves-effect waves-light" role="tab" data-bs-toggle="tab" data-bs-target="#processed_to_university" aria-selected="false" tabindex="-1"><i class="ri-community-fill"></i></i><span> PROCESSED TO UNIVERSITY (<span id="processed_to_university_count">0</span>)</span></button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="p-1 nav-link d-flex flex-column gap-1  waves-effect waves-light" role="tab" data-bs-toggle="tab" data-bs-target="#enrolled" aria-selected="false" tabindex="-1"><i class="ri-profile-line"></i><span> ENROLLED (<span id="enrolled_count">0</span>)</span></button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <div class="tab-pane fade active show" id="all_applications" role="tabpanel">
                                    <!-- <div class="row d-flex justify-content-end">
                                        <div class="col-md-2 d-flex justify-content-start">
                                            <input type="text" id="application-search-table" class="form-control pull-right" placeholder="Search">
                                        </div>
                                    </div> -->
                                    <div class="table-responsive">
                                        <table class="table table-hover nowrap " id="application-table">

                                            <thead class="">
                                                <tr>
                                                    <th data-orderable="true"></th>

                                                    <th class="text-center">Student</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Process by Center</th>
                                                    <th class="text-center">Document Verification</th>
                                                    <th class="text-center">Payment Verification</th>
                                                    <th class="text-center">Processed to University</th>
                                                    <th class="text-center">Enrollment No.</th>

                                                    <th class="text-center">Adm Session</th>
                                                    <th class="text-center">Adm Type</th>
                                                    <th class="text-center">Pendency</th>
                                                    <th class="text-center">Student Name</th>
                                                    <th class="text-center">Father Name</th>
                                                    <th class="text-center">Program</th>
                                                    <th class="text-center">Sub Course Fee</th>
                                                    <th class="text-center">Year/Sem</th>
                                                    <!-- <th>Login</th> -->

                                                    <th class="text-center">DOB</th>
                                                    <th class="text-center">Code</th>
                                                    <th class="text-center">Center(Sub-Center) Name</th>

                                                    <th class="text-center">University</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="not_processed" role="tabpanel">

                                    <!-- <h5 class="card-header text-center text-md-start">Multilingual</h5> -->
                                    <div class="card-datatable table-responsive">
                                        <div id="DataTables_Table_3_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                            <!-- <div class="row d-flex justify-content-end">
                                                <div class="col-md-2 d-flex justify-content-start">
                                                    <input type="text" id="not-processed-search-table" class="form-control pull-right" placeholder="Search">
                                                </div>
                                            </div> -->
                                            <div class="table-responsive">
                                                <table class="dt-multilingual table table-bordered  dataTable no-footer dtr-column ctable" id="not-processed-table" aria-describedby="DataTables_Table_3_info">
                                                    <thead>
                                                        <tr>
                                                            <th data-orderable="false"></th>

                                                            <th class="text-center">Student</th>
                                                            <th class="text-center">Status</th>
                                                            <th class="text-center">Process by Center</th>
                                                            <th class="text-center">Adm Session</th>
                                                            <th class="text-center">Adm Type</th>
                                                            <th class="text-center">Pendency</th>
                                                            <th class="text-center">Student Name</th>
                                                            <th class="text-center">Father Name</th>
                                                            <th class="text-center">Program</th>
                                                            <th class="text-center">Sub Course Fee</th>

                                                            <th class="text-center">Year/Sem</th>
                                                            <!-- <th>Login</th> -->

                                                            <th class="text-center">DOB</th>
                                                            <th class="text-center">Code</th>
                                                            <th class="text-center">Center</th>

                                                            <th class="text-center">University</th>
                                                        </tr>
                                                    </thead>
                                                    <p onchange=""></p>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="ready_for_verification" role="tabpanel">
                                    <!-- <div class="row d-flex justify-content-end">
                                        <div class="col-md-2">
                                            <input type="text" id="ready-for-verification-search-table" class="form-control pull-right" placeholder="Search">
                                        </div>
                                    </div> -->
                                    <div class="table-responsive">
                                        <table class="table table-hover nowrap" id="ready-for-verification-table">
                                            <thead>
                                                <tr>
                                                    <th data-orderable="false"></th>

                                                    <th class="text-center">Student</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Process by Center</th>
                                                    <th class="text-center">Document Verification</th>
                                                    <th class="text-center">Payment Verification</th>
                                                    <th class="text-center">Enrollment No.</th>

                                                    <th class="text-center">Adm Session</th>
                                                    <th class="text-center">Adm Type</th>
                                                    <th class="text-center">Pendency</th>
                                                    <th class="text-center">Student Name</th>
                                                    <th class="text-center">Father Name</th>
                                                    <th class="text-center">Program</th>
                                                    <th class="text-center">Sub Course Fee</th>

                                                    <th class="text-center">Year/Sem</th>
                                                    <!-- <th>Login</th> -->

                                                    <th class="text-center">DOB</th>
                                                    <th class="text-center">Code</th>
                                                    <th class="text-center">Center</th>

                                                    <th class="text-center">University</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="verified" role="tabpanel">
                                    <!-- <div class="row d-flex justify-content-end">
                                        <div class="col-md-2">
                                            <input type="text" id="document-verified-search-table" class="form-control pull-right" placeholder="Search">
                                        </div>
                                    </div> -->
                                    <div class="table-responsive">
                                        <table class="table table-hover nowrap" id="verified-table">
                                            <thead>
                                                <tr>
                                                    <th data-orderable="false"></th>

                                                    <th class="text-center">Student</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Process by Center</th>
                                                    <th class="text-center">Document Verification</th>
                                                    <th class="text-center">Payment Verification</th>
                                                    <th class="text-center">Processed to University</th>
                                                    <th class="text-center">Enrollment No.</th>
                                                    <th class="text-center">Adm Session</th>
                                                    <th class="text-center">Adm Type</th>
                                                    <th class="text-center">Pendency</th>
                                                    <th class="text-center">Student Name</th>
                                                    <th class="text-center">Father Name</th>
                                                    <th class="text-center">Program</th>
                                                    <th class="text-center">Sub Course Fee</th>
                                                    <th class="text-center">Year/Sem</th>
                                                    <!-- <th>Login</th> -->

                                                    <th class="text-center">DOB</th>
                                                    <th class="text-center">Code</th>
                                                    <th class="text-center">Center</th>

                                                    <th class="text-center">University</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="processed_to_university" role="tabpanel">
                                    <!-- <div class="row d-flex justify-content-end">
                                        <div class="col-md-2">
                                            <input type="text" id="processed-to-university-search-table" class="form-control pull-right" placeholder="Search">
                                        </div>
                                    </div> -->
                                    <div class="table-responsive">
                                        <table class="table table-hover nowrap" id="proccessed-to-university-table">
                                            <thead>
                                                <tr>
                                                    <th data-orderable="false"></th>

                                                    <th class="text-center">Student</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Process by Center</th>
                                                    <th class="text-center">Document Verification</th>
                                                    <th class="text-center">Payment Verification</th>
                                                    <th class="text-center">Processed to University</th>
                                                    <th class="text-center">Enrollment No.</th>

                                                    <th class="text-center">Adm Session</th>
                                                    <th class="text-center">Adm Type</th>
                                                    <th class="text-center">Pendency</th>
                                                    <th class="text-center">Student Name</th>
                                                    <th class="text-center">Father Name</th>
                                                    <th class="text-center">Program</th>
                                                    <th class="text-center">Sub Course Fee</th>
                                                    <th class="text-center">Year/Sem</th>
                                                    <!-- <th>Login</th> -->

                                                    <th class="text-center">DOB</th>
                                                    <th class="text-center">Code</th>
                                                    <th class="text-center">Center</th>

                                                    <th class="text-center">University</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="enrolled" role="tabpanel">
                                    <!-- <div class="row d-flex justify-content-end">
                                        <div class="col-md-2">
                                            <input type="text" id="enrolled-search-table" class="form-control pull-right" placeholder="Search">
                                        </div>
                                    </div> -->
                                    <div class="table-responsive">
                                        <table class="table table-hover nowrap" id="enrolled-table">
                                            <thead>
                                                <tr>
                                                    <th data-orderable="false"></th>

                                                    <th class="text-center">Student</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Process by Center</th>
                                                    <th class="text-center">Document Verification</th>
                                                    <th class="text-center">Payment Verification</th>
                                                    <th class="text-center">Processed to University</th>
                                                    <th class="text-center">Enrollment No.</th>

                                                    <th class="text-center">Adm Session</th>
                                                    <th class="text-center">Adm Type</th>
                                                    <th class="text-center">Pendency</th>
                                                    <th class="text-center">Student Name</th>
                                                    <th class="text-center">Father Name</th>
                                                    <th class="text-center">Program</th>
                                                    <th class="text-center">Sub Course Fee</th>
                                                    <th class="text-center">Year/Sem</th>
                                                    <!-- <th>Login</th> -->

                                                    <th class="text-center">DOB</th>
                                                    <th class="text-center">Code</th>
                                                    <th class="text-center">Center</th>

                                                    <th class="text-center">University</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-top.php') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $('#datepicker-range').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            endDate: '0d'
        });
    </script>

    <?php if ($_SESSION['Role'] == 'Administrator') { ?>
        <script type="text/javascript">
            changeUniversity();
        </script>
    <?php } ?>

    <script type="text/javascript">
        $(function() {
            var role = '<?php echo $_SESSION['Role']; ?>';
            // alert(role);
            var showInhouse = role != 'Center' && role != 'Sub-Center' ? true : false;
            var is_accountant = ['Accountant', 'Administrator'].includes(role) ? true : false;
            var is_university_head = ['University Head', 'Administrator'].includes(role) ? true : false;
            var is_operations = role == 'Operations' ? true : false;
            var hasStudentLogin = '<?php echo isset($_SESSION['has_lms']) && $_SESSION['has_lms'] == 1 ? 'true' : 'false'; ?>';
            var applicationTable = $('#application-table');
            var notProcessedTable = $('#not-processed-table');
            var readyForVerificationTable = $('#ready-for-verification-table');
            var verifiedTable = $('#verified-table');
            var processedToUniversityTable = $('#proccessed-to-university-table');
            var enrolledTable = $('#enrolled-table');
            // alert(is_operations);
            var applicationSettings = {
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': '/app/applications/application-server',
                    'type': 'POST',
                    complete: function(xhr, responseText) {
                        $('#application_count').html(xhr.responseJSON.iTotalDisplayRecords);
                    }
                },
                'columns': [{
                        data: "ID",
                        "render": function(data, type, row) {
                            // console.log(row);
                            if (role == "Administrator") {
                                var fee = showInhouse || row.Process_By_Center == 1 ? '<a class="mr-1 cursor-pointer" title="Allot Course Fee" onclick="allot_course_fee(&#39;application-form&#39;, &#39;md&#39;, &#39;' + data + '&#39;,' + row.Course_ID + ',' + row.Sub_Course_ID + ',' + row.Added_For + ',' + row.Duration + ')"> <i class="ri-money-rupee-circle-line text-success"></i></a>' : '';
                            } else {
                                var fee = '';
                            }
                            var edit = showInhouse || row.Step < 4 ? '<a href="/admission/application-form?id=' + data + '"><i class="ri-edit-box-fill text-primary ms-3" title="Edit Application Form"></i></a>' : '';
                            var student_details = `<a class="mr-1 cursor-pointer" title="Student Details" onclick="students_data('application-form', 'lg', '${row.ID}')"> 
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
</svg></i></a>`;
                            var deleted = showInhouse && row.Process_By_Center == 1 ? '<i class="ri-delete-bin-5-line text-danger" title="Delete Application Form" onclick="destroy(&#39;application-form&#39;, &#39;' + data + '&#39;)"></i>' : '';
                            // var print = row.Step == 4 ? '<i class="uil uil-print mr-1 cursor-pointer" title="Print Application Form" onclick="printForm(&#39;' + data + '&#39;)"></i>' : '';
                            var proccessedByCenter = row.Process_By_Center == 1 ? "Not Proccessed" : row.Process_By_Center
                            var documentVerified = row.Document_Verified == 1 ? "Not Verified" : row.Document_Verified
                            var proccessedToUniversity = row.Processed_To_University == 1 ? "Not Proccessed" : row.Processed_To_University
                            var paymentVerified = row.Payment_Received == 1 ? "Not Verified" : row.Payment_Received
                            var info = row.Step == 4 ? '<i class="ri-info-i cursor-pointer" data-bs-html="true" data-toggle="tooltip" data-placement="top" title="Proccessed By Center: <strong>' + proccessedByCenter + '</strong>&#013;&#010;Document Verified: <strong>' + documentVerified + '</strong>&#013;&#010;Payment Verified: <strong>' + paymentVerified + '</strong>&#013;&#010;Proccessed to University: <strong>' + proccessedToUniversity + '</strong>"></i>' : '';
                            return fee + edit + deleted + info + student_details;
                        }
                    },

                    {
                        data: "Unique_ID",
                        "render": function(data, type, row) {
                            return '<span class="cursor-pointer" title="Click to export documents" onclick="exportDocuments(&#39;' + row.ID + '&#39;)"><strong>' + data + '</strong></span>';
                        }
                    },
                    {
                        data: "Step",
                        "render": function(data, type, row) {
                            var label_class = data < 4 ? 'bg-label-danger' : ' bg-label-success rounded-pill';
                            var status = data < 4 ? 'In Draft @ Step ' + data : 'Completed';
                            return '<sapn class="badge ' + label_class + '">' + status + '</sapn>';
                        }
                    },
                    {
                        data: "Process_By_Center",

                        "render": function(data, type, row) {

                            if (data == 1 && row.Step == 4 && role != 'Operations') {

                                return '<span class=" badge rounded-pill bg-label-secondary">Not Processed</span>';
                                var show = !showInhouse ? '<div class="form-check complete mt-2">\
                <input type="checkbox" id="process-by-center-' + row.ID + '" onclick="processByCenter(&#39;' + row.ID + '&#39;)">\
                <label for="process-by-center-' + row.ID + '">Mark as Processed</label>\
              </div>' : '<span class=" badge rounded-pill bg-label-secondary">Not Processed</span>';

                                return show;
                            } else if (role == 'Operations' && data == 1 && row.Step == 4) {
                                var show = '<div class="form-check complete mt-2">\
                <input type="checkbox" id="process-by-center-' + row.ID + '" onclick="processByCenter(&#39;' + row.ID + '&#39;)">\
                <label for="process-by-center-' + row.ID + '">Mark as Processed</label>\
                </div>';

                                return show;
                            } else {
                                var show = row.Step == 4 ? '<div class="text-center"><span class=" badge rounded-pill bg-label-secondary">Processed on ' + data + '</span></div>' : '';
                                return show;
                            }
                        },
                        visible: role == 'Sub-Center' ? false : true
                    },
                    {
                        data: "Document_Verified",
                        "render": function(data, type, row) {
                            if (row.Pendency_Status == 2) {
                                if (!showInhouse) {
                                    return '<div class="text-center text-danger"><span class="cursor-pointer"><strong>In Review</strong></span></div>'
                                } else {
                                    return is_operations || is_university_head ? '<div class="text-center text-danger"><span class="cursor-pointer" onclick="verifyDocument(&#39;' + row.ID + '&#39;)"><strong>Re-Review</strong></span></div>' : '<div class="text-center text-danger"><span><strong>Re-Review</strong></span></div>'
                                }
                            } else if (row.Pendency != 0) {
                                if (!showInhouse) {
                                    return '<div class="text-center text-danger"><span class="cursor-pointer" onclick="uploadPendency(&#39;' + row.ID + '&#39;)"><strong>Pendency</strong></span></div>'
                                } else {
                                    return is_operations || is_university_head ? '<div class="text-center text-danger"><span class="cursor-pointer" onclick="reportPendency(&#39;' + row.ID + '&#39;)"><strong>Pendency</strong></span></div>' : '<div class="text-center text-danger"><span><strong>Pendency</strong></span></div>'
                                }
                            } else {
                                if (data == 1) {
                                    var show = (is_operations || is_university_head) && row.Process_By_Center != 1 ? '<div class="text-center"><span class="cursor-pointer" onclick="verifyDocument(&#39;' + row.ID + '&#39;)"><strong>Review</strong></span></div>' : row.Step == 4 && row.Process_By_Center != 1 ? '<div class="text-center text-danger"><strong>Pending</strong></div>' : '';
                                    return show;
                                } else {
                                    var show = row.Step == 4 && row.Process_By_Center != 1 ? '<div class="text-center"><span class="badge rounded-pill bg-label-success">Verified at ' + data + '</span></div>' : '';
                                    return show;
                                }
                            }
                        }
                    },
                    {
                        data: "Payment_Received",
                        "render": function(data, type, row) {
                            if (data == 1 && row.Step == 4 && row.Process_By_Center != 1) {
                                var show = is_accountant ? '<div class="text-center"><span class="cursor-pointer" onclick="verifyPayment(&#39;' + row.ID + '&#39;)"><strong>Review</strong></span></div>' : '<center><span class=" badge rounded-pill bg-label-secondary">Pending</span></center>';
                                return show;
                            } else if (row.Process_By_Center != 1) {
                                var show = row.Step == 4 ? '<div class="text-center"><span class=" badge rounded-pill bg-label-secondary">Verified on ' + data + '</span></div>' : '';
                                return show;
                            } else {
                                return '';
                            }
                        },
                        visible: false
                    },
                    {
                        data: "Processed_To_University",
                        "render": function(data, type, row) {
                            if (data == 1) {
                                var show = showInhouse && row.Document_Verified != 1 && row.Payment_Received != 1 ? '<div class="form-check complete mt-2">\
                <input type="checkbox" id="processed-to-university-' + row.ID + '" onclick="processedToUniversity(&#39;' + row.ID + '&#39;)">\
                <label for="processed-to-university-' + row.ID + '">Mark as Processed</label>\
              </div>' : "";
                                return show;
                            } else {
                                var show = row.Step == 4 ? '<div class="text-center"><span class="badge rounded-pill bg-label-success">Processed on ' + data + '</span></div>' : '';
                                return show;
                            }
                        }
                    },
                    {
                        data: "Enrollment_No",
                        "render": function(data, type, row) {
                            var edit = showInhouse && row.Processed_To_University != 1 ? '<i class="ri-edit-box-fill text-primary ms-3" title="Add Enrollment No." onclick="addEnrollment(&#39;' + row.ID + '&#39;)">' : '';
                            return data + edit;
                        }
                    },

                    {
                        data: "Adm_Session"
                    },
                    {
                        data: "Adm_Type"
                    },
                    {
                        data: "Adm_Type",
                        "render": function(data, type, row) {
                            return '<span onclick="reportPendnency(' + row.ID + ')"><strong>Report</strong><span>';
                        },
                        visible: false,
                    },
                    {
                        data: "First_Name",
                        "render": function(data, type, row) {
                            return '<strong>' + data + '</strong>';
                        },
                        visible: false,
                    },
                    {
                        data: "Father_Name"
                    },

                    {
                        data: "Short_Name"
                    },
                    {
                        data: "Fee"
                    },
                    {
                        data: "Duration"
                    },

                    // {
                    //   data: "Status",
                    //   "render": function(data, type, row) {
                    //     var active = data == 1 ? 'Active' : 'Inactive';
                    //     if (row.Step == 4 && showInhouse) {
                    //       var checked = data == 1 ? 'checked' : '';
                    //       return '<div class="form-check form-check-inline switch switch-lg success">\
                    //   <input onclick="changeStatus(\'Students\', &#39;' + row.ID + '&#39;);" type="checkbox" ' + checked + ' id="student-status-switch-' + row.ID + '">\
                    //   <label for="student-status-switch-' + row.ID + '">' + active + '</label>\
                    // </div>';
                    //     } else {
                    //       return active;
                    //     }
                    //   },
                    //   visible: hasStudentLogin
                    // },

                    {
                        data: "DOB"
                    },
                    {
                        data: "Center_Code",
                        visible: role == 'Sub-Center' ? false : true
                    },
                    {
                        data: "Center_Name",
                        "render": function(data, type, row) {
                            var name = row.Center_Name;
                            var Sub_Center_Name = row.Sub_Center_Name.length > 0 ? '( ' + row.Sub_Center_Name + ' )' : '';
                            return name + Sub_Center_Name;
                        },
                        visible: role == 'Sub-Center' ? false : true
                    },

                    {
                        data: "university_name",
                    }
                ],
              	dom: 'Bfrtip',
                buttons: [
                  //{
                   //   extend: 'copy',
                   //   text: 'Copy',
                   //   className: 'btn btn-primary buttons-copy',
                  //exportOptions: {
                   //       modifier: {
                     //         page: 'all'
                      //    }
                     // }
                  //},
                  //{
                   //   extend: 'excel',
                   //   text: 'Excel',
                   //   className: 'btn btn-primary buttons-excel',
                   //   action: function(e, dt, button, config) {
                   //       window.location.href = '/app/applications/application-server?format=excel';
                   //   }
                  //},
                  {
                      extend: 'csv',
                      text: 'CSV',
                      className: 'btn btn-primary buttons-csv csv-download d-none',
                      action: function(e, dt, button, config) {
                          var exportUrl = '/app/applications/csvexport?format=csv';
                          window.location.href = exportUrl;
                      }
                  },
                  {
                      extend: 'pdfHtml5',
                      text: 'PDF',
                      className: 'btn btn-primary buttons-pdf pdf-download d-none',
                      orientation: 'landscape',
                      pageSize: 'A3',
                      action: function(e, dt, button, config) {
                          window.location = '/app/applications/pdfexport?format=pdf';
                      }
                  },

              ],
              sDom: "l<t><'row'<p i>>",
              destroy: true,
              scrollCollapse: true,
              oLanguage: {
                  sInfo: "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
              },
              drawCallback: function() {
                  $('[data-toggle="tooltip"]').tooltip({
                      template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
                  });
              },
         	 };
              
                //"dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
               // "destroy": true,
                //"scrollCollapse": true,
                // "oLanguage": {
                //     "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
                // },
                //drawCallback: function(settings, json) {
                  //  $('[data-toggle="tooltip"]').tooltip({
                 //       template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'

                  //  });
                //},
                //"aaSorting": []

            //});
			  $('#exportPDF').on('click', function() {
               		$('.pdf-download').trigger('click');
              });
              $('#exportCSV').on('click', function() {
                  $('.csv-download').trigger('click');
              });
             // $('#exportCopy').on('click', function() {
              //    applicationSettings.button('.buttons-copy').trigger();
             // });
             // $('#exportExcel').on('click', function() {
             //     applicationSettings.button('.buttons-excel').trigger();
             // });
             // $('#exportPrint').on('click', function() {
             //     applicationSettings.button('.buttons-print').trigger();
            //  });
            var notProcessedSettings = {
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': '/app/applications/not-processed-server',
                    'type': 'POST',
                    complete: function(xhr, responseText) {
                        $('#not_processed_count').html(xhr.responseJSON.iTotalDisplayRecords);
                    }
                },
                'columns': [{
                        data: "ID",
                        "render": function(data, type, row) {
                            var edit = showInhouse || row.Step < 4 ? '<a href="/admission/application-form?id=' + data + '"><i class="ri-edit-box-fill text-primary " title="Edit Application Form"></i></a>' : '';
                            var deleted = showInhouse && row.Process_By_Center == 1 ? '<i class=" ri-delete-bin-5-line text-danger" title="Delete Application Form" onclick="destroy(&#39;application-form&#39;, &#39;' + data + '&#39;)"></i>' : '';
                            // var print = row.Step == 4 ? '<i class="uil uil-print mr-1 cursor-pointer" title="Print Application Form" onclick="printForm(&#39;' + data + '&#39;)"></i>' : '';
                            var proccessedByCenter = row.Process_By_Center == 1 ? "Not Proccessed" : row.Process_By_Center
                            var documentVerified = row.Document_Verified == 1 ? "Not Verified" : row.Document_Verified
                            var proccessedToUniversity = row.Processed_To_University == 1 ? "Not Proccessed" : row.Processed_To_University
                            var paymentVerified = row.Payment_Received == 1 ? "Not Verified" : row.Payment_Received
                            var info = row.Step == 4 ? '<i class="ri-info-i" data-bs-html="true" data-toggle="tooltip" data-placement="top" title="Proccessed By Center: <strong>' + proccessedByCenter + '</strong>&#013;&#010;Document Verified: <strong>' + documentVerified + '</strong>&#013;&#010;Payment Verified: <strong>' + paymentVerified + '</strong>&#013;&#010;Proccessed to University: <strong>' + proccessedToUniversity + '</strong>"></i>' : '';
                            return edit + deleted + info;
                        }
                    },

                    {
                        data: "Unique_ID",
                        "render": function(data, type, row) {
                            return '<span class="cursor-pointer" title="Click to export documents" onclick="exportDocuments(&#39;' + row.ID + '&#39;)"><strong>' + data + '</strong></span>';
                        }
                    },
                    {
                        data: "Step",
                        "render": function(data, type, row) {
                            var label_class = data < 4 ? 'badge-important' : 'badge-success';
                            var status = data < 4 ? 'In Draft @ Step ' + data : 'Completed';
                            return '<sapn class="label ' + label_class + '">' + status + '</sapn>';
                        }
                    },
                    {
                        data: "Process_By_Center",
                        "render": function(data, type, row) {
                            if (data == 1 && row.Step == 4) {
                                return '<span class=" badge rounded-pill bg-label-secondary">Not Processed</span>';
                                var show = !showInhouse ? '<div class="form-check complete mt-2">\
                <input type="checkbox" id="process-by-center-' + row.ID + '" onclick="processByCenter(&#39;' + row.ID + '&#39;)">\
                <label for="process-by-center-' + row.ID + '">Mark as Processed</label>\
              </div>' : '<span class=" badge rounded-pill bg-label-secondary">Not Processed</span>';
                                return show;
                            } else {
                                var show = row.Step == 4 ? '<div class="text-center"><span class=" badge rounded-pill bg-label-secondary">Processed on ' + data + '</span></div>' : '';
                                return show;
                            }
                        },
                        visible: role == 'Sub-Center' ? false : true
                    },
                    {
                        data: "Adm_Session"
                    },
                    {
                        data: "Adm_Type"
                    },
                    {
                        data: "Adm_Type",
                        "render": function(data, type, row) {
                            return '<span onclick="reportPendnency(' + row.ID + ')"><strong>Report</strong><span>';
                        },
                        visible: false,
                    },
                    {
                        data: "First_Name",
                        "render": function(data, type, row) {
                            return '<strong>' + data + '</strong>';
                        },
                        visible: false,
                    },
                    {
                        data: "Father_Name"
                    },
                    {
                        data: "Short_Name"
                    },
                    {
                        data: "Fee"
                    },
                    {
                        data: "Duration"
                    },
                    // {
                    //   data: "Status",
                    //   "render": function(data, type, row) {
                    //     var active = data == 1 ? 'Active' : 'Inactive';
                    //     if (row.Step == 4 && showInhouse) {
                    //       var checked = data == 1 ? 'checked' : '';
                    //       return '<div class="form-check form-check-inline switch switch-lg success">\
                    //   <input onclick="changeStatus(\'Students\', &#39;' + row.ID + '&#39;);" type="checkbox" ' + checked + ' id="student-status-switch-' + row.ID + '">\
                    //   <label for="student-status-switch-' + row.ID + '">' + active + '</label>\
                    // </div>';
                    //     } else {
                    //       return active;
                    //     }
                    //   },
                    //   visible: hasStudentLogin
                    // },

                    {
                        data: "DOB"
                    },
                    {
                        data: "Center_Code",
                        visible: role == 'Sub-Center' ? false : true
                    },
                    {
                        data: "Center_Name",
                        visible: role == 'Sub-Center' ? false : true
                    },

                    {
                        data: "university_name",
                    }
                ],
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',

                "destroy": true,
                "scrollCollapse": true,
                "oLanguage": {
                    "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
                },
                drawCallback: function(settings, json) {
                    $('[data-toggle="tooltip"]').tooltip({
                        template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'

                    });
                },
                "aaSorting": []
            };

            var readyForVerificationSettings = {
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': '/app/applications/ready-for-verification-server',
                    'type': 'POST',
                    complete: function(xhr, responseText) {
                        $('#ready_for_verification_count').html(xhr.responseJSON.iTotalDisplayRecords);
                    }
                },
                'columns': [{
                        data: "ID",
                        "render": function(data, type, row) {
                            var edit = showInhouse || row.Step < 4 ? '<a href="/admission/application-form?id=' + data + '"><i class="ri-edit-box-fill text-primary " title="Edit Application Form"></i></a>' : '';
                            var deleted = showInhouse && row.Process_By_Center == 1 ? '<i class=" ri-delete-bin-5-line text-danger" title="Delete Application Form" onclick="destroy(&#39;application-form&#39;, &#39;' + data + '&#39;)"></i>' : '';
                            var proccessedByCenter = row.Process_By_Center == 1 ? "Not Proccessed" : row.Process_By_Center
                            var documentVerified = row.Document_Verified == 1 ? "Not Verified" : row.Document_Verified
                            var proccessedToUniversity = row.Processed_To_University == 1 ? "Not Proccessed" : row.Processed_To_University
                            var paymentVerified = row.Payment_Received == 1 ? "Not Verified" : row.Payment_Received
                            var info = row.Step == 4 ? '<i class="ri-info-i" data-bs-html="true" data-toggle="tooltip" data-placement="top" title="Proccessed By Center: <strong>' + proccessedByCenter + '</strong>&#013;&#010;Document Verified: <strong>' + documentVerified + '</strong>&#013;&#010;Payment Verified: <strong>' + paymentVerified + '</strong>&#013;&#010;Proccessed to University: <strong>' + proccessedToUniversity + '</strong>"></i>' : '';
                            return edit + deleted + info;
                        }
                    },

                    {
                        data: "Unique_ID",
                        "render": function(data, type, row) {
                            return '<span class="cursor-pointer" title="Click to export documents" onclick="exportDocuments(&#39;' + row.ID + '&#39;)"><strong>' + data + '</strong></span>';
                        }
                    },
                    {
                        data: "Step",
                        "render": function(data, type, row) {
                            var label_class = data < 4 ? 'badge-important' : 'badge-success';
                            var status = data < 4 ? 'In Draft @ Step ' + data : 'Completed';
                            return '<sapn class="label ' + label_class + '">' + status + '</sapn>';
                        }
                    },
                    {
                        data: "Process_By_Center",
                        "render": function(data, type, row) {
                            if (data == 1 && row.Step == 4) {
                                return '<span class=" badge rounded-pill bg-label-secondary">Not Processed</span>';
                                var show = !showInhouse ? '<div class="form-check complete mt-2">\
                <input type="checkbox" id="process-by-center-' + row.ID + '" onclick="processByCenter(&#39;' + row.ID + '&#39;)">\
                <label for="process-by-center-' + row.ID + '">Mark as Processed</label>\
              </div>' : '<span class=" badge rounded-pill bg-label-secondary">Not Processed</span>';
                                return show;
                            } else {
                                var show = row.Step == 4 ? '<div class="text-center"><span class=" badge rounded-pill bg-label-secondary">Processed on ' + data + '</span></div>' : '';
                                return show;
                            }
                        },
                        visible: role == 'Sub-Center' ? false : true
                    },
                    {
                        data: "Document_Verified",
                        "render": function(data, type, row) {
                            if (row.Pendency_Status == 2) {
                                if (!showInhouse) {
                                    return '<div class="text-center text-danger"><span class="cursor-pointer"><strong>In Review</strong></span></div>'
                                } else {
                                    return is_operations || is_university_head ? '<div class="text-center text-danger"><span class="cursor-pointer" onclick="verifyDocument(&#39;' + row.ID + '&#39;)"><strong>Re-Review</strong></span></div>' : '<div class="text-center text-danger"><span><strong>Re-Review</strong></span></div>'
                                }
                            } else if (row.Pendency != 0) {
                                if (!showInhouse) {
                                    return '<div class="text-center text-danger"><span class="cursor-pointer" onclick="uploadPendency(&#39;' + row.ID + '&#39;)"><strong>Pendency</strong></span></div>'
                                } else {
                                    return is_operations || is_university_head ? '<div class="text-center text-danger"><span class="cursor-pointer" onclick="reportPendency(&#39;' + row.ID + '&#39;)"><strong>Pendency</strong></span></div>' : '<div class="text-center text-danger"><span><strong>Pendency</strong></span></div>'
                                }
                            } else {
                                if (data == 1) {
                                    var show = (is_operations || is_university_head) && row.Process_By_Center != 1 ? '<div class="text-center"><span class="cursor-pointer" onclick="verifyDocument(&#39;' + row.ID + '&#39;)"><strong>Review</strong></span></div>' : row.Step == 4 && row.Process_By_Center != 1 ? '<div class="text-center text-danger"><strong>Pending</strong></div>' : '';
                                    return show;
                                } else {
                                    var show = row.Step == 4 && row.Process_By_Center != 1 ? '<div class="text-center"><span class="badge rounded-pill bg-label-success">Verified at ' + data + '</span></div>' : '';
                                    return show;
                                }
                            }
                        }
                    },
                    {
                        data: "Payment_Received",
                        "render": function(data, type, row) {
                            if (data == 1 && row.Step == 4 && row.Process_By_Center != 1) {
                                var show = is_accountant ? '<div class="text-center"><span class="cursor-pointer" onclick="verifyPayment(&#39;' + row.ID + '&#39;)"><strong>Review</strong></span></div>' : '<center><span class=" badge rounded-pill bg-label-secondary">Pending</span></center>';
                                return show;
                            } else {
                                var show = row.Step == 4 ? '<div class="text-center"><span class=" badge rounded-pill bg-label-secondary">Verified on ' + data + '</span></div>' : '';
                                return show;
                            }
                        },
                        visible: false
                    },
                    {
                        data: "Enrollment_No",
                        "render": function(data, type, row) {
                            var edit = showInhouse && row.Processed_To_University != 1 ? '<i class="ri-edit-box-fill text-primary ms-3" title="Add Enrollment No." onclick="addEnrollment(&#39;' + row.ID + '&#39;)">' : '';
                            return data + edit;
                        }
                    },

                    {
                        data: "Adm_Session"
                    },
                    {
                        data: "Adm_Type"
                    },
                    {
                        data: "Adm_Type",
                        "render": function(data, type, row) {
                            return '<span onclick="reportPendnency(' + row.ID + ')"><strong>Report</strong><span>';
                        },
                        visible: false,
                    },
                    {
                        data: "First_Name",
                        "render": function(data, type, row) {
                            return '<strong>' + data + '</strong>';
                        },
                        visible: false,
                    },
                    {
                        data: "Father_Name"
                    },
                    {
                        data: "Short_Name"
                    },
                    {
                        data: "Fee"
                    },
                    {
                        data: "Duration"
                    },
                    // {
                    //   data: "Status",
                    //   "render": function(data, type, row) {
                    //     var active = data == 1 ? 'Active' : 'Inactive';
                    //     if (row.Step == 4 && showInhouse) {
                    //       var checked = data == 1 ? 'checked' : '';
                    //       return '<div class="form-check form-check-inline switch switch-lg success">\
                    //   <input onclick="changeStatus(\'Students\', &#39;' + row.ID + '&#39;);" type="checkbox" ' + checked + ' id="student-status-switch-' + row.ID + '">\
                    //   <label for="student-status-switch-' + row.ID + '">' + active + '</label>\
                    // </div>';
                    //     } else {
                    //       return active;
                    //     }
                    //   },
                    //   visible: hasStudentLogin
                    // },

                    {
                        data: "DOB"
                    },
                    {
                        data: "Center_Code",
                        visible: role == 'Sub-Center' ? false : true
                    },
                    {
                        data: "Center_Name",
                        visible: role == 'Sub-Center' ? false : true
                    },

                    {
                        data: "university_name",
                    }
                ],
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',

                "destroy": true,
                "scrollCollapse": true,
                "oLanguage": {
                    "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
                },
                drawCallback: function(settings, json) {
                    $('[data-toggle="tooltip"]').tooltip({
                        template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'

                    });
                },
                "aaSorting": []
            };

            var verifiedSettings = {
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': '/app/applications/verified-server',
                    'type': 'POST',
                    complete: function(xhr, responseText) {
                        $('#verified_count').html(xhr.responseJSON.iTotalDisplayRecords);
                    }
                },
                'columns': [{
                        data: "ID",
                        "render": function(data, type, row) {
                            var edit = showInhouse || row.Step < 4 ? '<a href="/admission/application-form?id=' + data + '"><i class="ri-edit-box-fill text-primary " title="Edit Application Form"></i></a>' : '';
                            var deleted = showInhouse && row.Process_By_Center == 1 ? '<i class=" ri-delete-bin-5-line text-danger" title="Delete Application Form" onclick="destroy(&#39;application-form&#39;, &#39;' + data + '&#39;)"></i>' : '';
                            // var print = row.Step == 4 ? '<i class="uil uil-print mr-1 cursor-pointer" title="Print Application Form" onclick="printForm(&#39;' + data + '&#39;)"></i>' : '';
                            var proccessedByCenter = row.Process_By_Center == 1 ? "Not Proccessed" : row.Process_By_Center
                            var documentVerified = row.Document_Verified == 1 ? "Not Verified" : row.Document_Verified
                            var proccessedToUniversity = row.Processed_To_University == 1 ? "Not Proccessed" : row.Processed_To_University
                            var paymentVerified = row.Payment_Received == 1 ? "Not Verified" : row.Payment_Received
                            var info = row.Step == 4 ? '<i class="ri-info-i" data-bs-html="true" data-toggle="tooltip" data-placement="top" title="Proccessed By Center: <strong>' + proccessedByCenter + '</strong>&#013;&#010;Document Verified: <strong>' + documentVerified + '</strong>&#013;&#010;Payment Verified: <strong>' + paymentVerified + '</strong>&#013;&#010;Proccessed to University: <strong>' + proccessedToUniversity + '</strong>"></i>' : '';
                            return edit + deleted + info;
                        }
                    },

                    {
                        data: "Unique_ID",
                        "render": function(data, type, row) {
                            return '<span class="cursor-pointer" title="Click to export documents" onclick="exportDocuments(&#39;' + row.ID + '&#39;)"><strong>' + data + '</strong></span>';
                        }
                    },
                    {
                        data: "Step",
                        "render": function(data, type, row) {
                            var label_class = data < 4 ? 'badge-important' : 'badge-success';
                            var status = data < 4 ? 'In Draft @ Step ' + data : 'Completed';
                            return '<sapn class="label ' + label_class + '">' + status + '</sapn>';
                        }
                    },
                    {
                        data: "Process_By_Center",
                        "render": function(data, type, row) {
                            if (data == 1 && row.Step == 4) {
                                return '<span class=" badge rounded-pill bg-label-secondary">Not Processed</span>';
                                var show = !showInhouse ? '<div class="form-check complete mt-2">\
                <input type="checkbox" id="process-by-center-' + row.ID + '" onclick="processByCenter(&#39;' + row.ID + '&#39;)">\
                <label for="process-by-center-' + row.ID + '">Mark as Processed</label>\
              </div>' : '<span class=" badge rounded-pill bg-label-secondary">Not Processed</span>';
                                return show;
                            } else {
                                var show = row.Step == 4 ? '<div class="text-center"><span class=" badge rounded-pill bg-label-secondary">Processed on ' + data + '</span></div>' : '';
                                return show;
                            }
                        },
                        visible: role == 'Sub-Center' ? false : true
                    },
                    {
                        data: "Document_Verified",
                        "render": function(data, type, row) {
                            if (row.Pendency_Status == 2) {
                                if (!showInhouse) {
                                    return '<div class="text-center text-danger"><span class="cursor-pointer"><strong>In Review</strong></span></div>'
                                } else {
                                    return is_operations || is_university_head ? '<div class="text-center text-danger"><span class="cursor-pointer" onclick="verifyDocument(&#39;' + row.ID + '&#39;)"><strong>Re-Review</strong></span></div>' : '<div class="text-center text-danger"><span><strong>Re-Review</strong></span></div>'
                                }
                            } else if (row.Pendency != 0) {
                                if (!showInhouse) {
                                    return '<div class="text-center text-danger"><span class="cursor-pointer" onclick="uploadPendency(&#39;' + row.ID + '&#39;)"><strong>Pendency</strong></span></div>'
                                } else {
                                    return is_operations || is_university_head ? '<div class="text-center text-danger"><span class="cursor-pointer" onclick="reportPendency(&#39;' + row.ID + '&#39;)"><strong>Pendency</strong></span></div>' : '<div class="text-center text-danger"><span><strong>Pendency</strong></span></div>'
                                }
                            } else {
                                if (data == 1) {
                                    var show = (is_operations || is_university_head) && row.Process_By_Center != 1 ? '<div class="text-center"><span class="cursor-pointer" onclick="verifyDocument(&#39;' + row.ID + '&#39;)"><strong>Review</strong></span></div>' : row.Step == 4 && row.Process_By_Center != 1 ? '<div class="text-center text-danger"><strong>Pending</strong></div>' : '';
                                    return show;
                                } else {
                                    var show = row.Step == 4 && row.Process_By_Center != 1 ? '<div class="text-center"><span class="badge rounded-pill bg-label-success">Verified at ' + data + '</span></div>' : '';
                                    return show;
                                }
                            }
                        }
                    },
                    {
                        data: "Payment_Received",
                        "render": function(data, type, row) {
                            if (data == 1 && row.Step == 4 && row.Process_By_Center != 1) {
                                var show = is_accountant ? '<div class="text-center"><span class="cursor-pointer" onclick="verifyPayment(&#39;' + row.ID + '&#39;)"><strong>Review</strong></span></div>' : '<center><span class=" badge rounded-pill bg-label-secondary">Pending</span></center>';
                                return show;
                            } else {
                                var show = row.Step == 4 ? '<div class="text-center"><span class=" badge rounded-pill bg-label-secondary">Verified on ' + data + '</span></div>' : '';
                                return show;
                            }
                        },
                        visible: false
                    },
                    {
                        data: "Processed_To_University",
                        "render": function(data, type, row) {
                            if (data == 1) {
                                var show = showInhouse && row.Document_Verified != 1 && row.Payment_Received != 1 ? '<div class="form-check complete mt-2">\
                <input type="checkbox" id="processed-to-university-' + row.ID + '" onclick="processedToUniversity(&#39;' + row.ID + '&#39;)">\
                <label for="processed-to-university-' + row.ID + '">Mark as Processed</label>\
              </div>' : "";
                                return show;
                            } else {
                                var show = row.Step == 4 ? '<div class="text-center"><span class="badge rounded-pill bg-label-success">Processed on ' + data + '</span></div>' : '';
                                return show;
                            }
                        }
                    },
                    {
                        data: "Enrollment_No",
                        "render": function(data, type, row) {
                            var edit = showInhouse && row.Processed_To_University != 1 ? '<i class="ri-edit-box-fill text-primary ms-3" title="Add Enrollment No." onclick="addEnrollment(&#39;' + row.ID + '&#39;)">' : '';
                            return data + edit;
                        }
                    },

                    {
                        data: "Adm_Session"
                    },
                    {
                        data: "Adm_Type"
                    },
                    {
                        data: "Adm_Type",
                        "render": function(data, type, row) {
                            return '<span onclick="reportPendnency(' + row.ID + ')"><strong>Report</strong><span>';
                        },
                        visible: false,
                    },
                    {
                        data: "First_Name",
                        "render": function(data, type, row) {
                            return '<strong>' + data + '</strong>';
                        },
                        visible: false,
                    },
                    {
                        data: "Father_Name"
                    },
                    {
                        data: "Short_Name"
                    },
                    {
                        data: "Fee"
                    },
                    {
                        data: "Duration"
                    },
                    // {
                    //   data: "Status",
                    //   "render": function(data, type, row) {
                    //     var active = data == 1 ? 'Active' : 'Inactive';
                    //     if (row.Step == 4 && showInhouse) {
                    //       var checked = data == 1 ? 'checked' : '';
                    //       return '<div class="form-check form-check-inline switch switch-lg success">\
                    //   <input onclick="changeStatus(\'Students\', &#39;' + row.ID + '&#39;);" type="checkbox" ' + checked + ' id="student-status-switch-' + row.ID + '">\
                    //   <label for="student-status-switch-' + row.ID + '">' + active + '</label>\
                    // </div>';
                    //     } else {
                    //       return active;
                    //     }
                    //   },
                    //   visible: hasStudentLogin
                    // },

                    {
                        data: "DOB"
                    },
                    {
                        data: "Center_Code",
                        visible: role == 'Sub-Center' ? false : true
                    },
                    {
                        data: "Center_Name",
                        visible: role == 'Sub-Center' ? false : true
                    },

                    {
                        data: "university_name",
                    }
                ],
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                "destroy": true,
                "scrollCollapse": true,
                "oLanguage": {
                    "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
                },
                drawCallback: function(settings, json) {
                    $('[data-toggle="tooltip"]').tooltip({
                        template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'

                    });
                },
                "aaSorting": []
            };

            var processedToUniversitySettings = {
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': '/app/applications/processed-to-university-server',
                    'type': 'POST',
                    complete: function(xhr, responseText) {
                        $('#processed_to_university_count').html(xhr.responseJSON.iTotalDisplayRecords);
                    }
                },
                'columns': [{
                        data: "ID",
                        "render": function(data, type, row) {
                            var edit = showInhouse || row.Step < 4 ? '<a href="/admission/application-form?id=' + data + '"><i class="ri-edit-box-fill text-primary " title="Edit Application Form"></i></a>' : '';
                            var deleted = showInhouse && row.Process_By_Center == 1 ? '<i class=" ri-delete-bin-5-line text-danger" title="Delete Application Form" onclick="destroy(&#39;application-form&#39;, &#39;' + data + '&#39;)"></i>' : '';
                            var proccessedByCenter = row.Process_By_Center == 1 ? "Not Proccessed" : row.Process_By_Center
                            var documentVerified = row.Document_Verified == 1 ? "Not Verified" : row.Document_Verified
                            var proccessedToUniversity = row.Processed_To_University == 1 ? "Not Proccessed" : row.Processed_To_University
                            var paymentVerified = row.Payment_Received == 1 ? "Not Verified" : row.Payment_Received
                            var info = row.Step == 4 ? '<i class="ri-info-i" data-bs-html="true" data-toggle="tooltip" data-placement="top" title="Proccessed By Center: <strong>' + proccessedByCenter + '</strong>&#013;&#010;Document Verified: <strong>' + documentVerified + '</strong>&#013;&#010;Payment Verified: <strong>' + paymentVerified + '</strong>&#013;&#010;Proccessed to University: <strong>' + proccessedToUniversity + '</strong>"></i>' : '';
                            return edit + deleted + info;
                        }
                    },

                    {
                        data: "Unique_ID",
                        "render": function(data, type, row) {
                            return '<span class="cursor-pointer" title="Click to export documents" onclick="exportDocuments(&#39;' + row.ID + '&#39;)"><strong>' + data + '</strong></span>';
                        }
                    },
                    {
                        data: "Step",
                        "render": function(data, type, row) {
                            var label_class = data < 4 ? 'badge-important' : 'badge-success';
                            var status = data < 4 ? 'In Draft @ Step ' + data : 'Completed';
                            return '<sapn class="label ' + label_class + '">' + status + '</sapn>';
                        }
                    },
                    {
                        data: "Process_By_Center",
                        "render": function(data, type, row) {
                            if (data == 1 && row.Step == 4) {
                                return '<span class=" badge rounded-pill bg-label-secondary">Not Processed</span>';
                                var show = !showInhouse ? '<div class="form-check complete mt-2">\
                <input type="checkbox" id="process-by-center-' + row.ID + '" onclick="processByCenter(&#39;' + row.ID + '&#39;)">\
                <label for="process-by-center-' + row.ID + '">Mark as Processed</label>\
              </div>' : '<span class=" badge rounded-pill bg-label-secondary">Not Processed</span>';
                                return show;
                            } else {
                                var show = row.Step == 4 ? '<div class="text-center"><span class=" badge rounded-pill bg-label-secondary">Processed on ' + data + '</span></div>' : '';
                                return show;
                            }
                        },
                        visible: role == 'Sub-Center' ? false : true
                    },
                    {
                        data: "Document_Verified",
                        "render": function(data, type, row) {
                            if (row.Pendency_Status == 2) {
                                if (!showInhouse) {
                                    return '<div class="text-center text-danger"><span class="cursor-pointer"><strong>In Review</strong></span></div>'
                                } else {
                                    return is_operations || is_university_head ? '<div class="text-center text-danger"><span class="cursor-pointer" onclick="verifyDocument(&#39;' + row.ID + '&#39;)"><strong>Re-Review</strong></span></div>' : '<div class="text-center text-danger"><span><strong>Re-Review</strong></span></div>'
                                }
                            } else if (row.Pendency != 0) {
                                if (!showInhouse) {
                                    return '<div class="text-center text-danger"><span class="cursor-pointer" onclick="uploadPendency(&#39;' + row.ID + '&#39;)"><strong>Pendency</strong></span></div>'
                                } else {
                                    return is_operations || is_university_head ? '<div class="text-center text-danger"><span class="cursor-pointer" onclick="reportPendency(&#39;' + row.ID + '&#39;)"><strong>Pendency</strong></span></div>' : '<div class="text-center text-danger"><span><strong>Pendency</strong></span></div>'
                                }
                            } else {
                                if (data == 1) {
                                    var show = (is_operations || is_university_head) && row.Process_By_Center != 1 ? '<div class="text-center"><span class="cursor-pointer" onclick="verifyDocument(&#39;' + row.ID + '&#39;)"><strong>Review</strong></span></div>' : row.Step == 4 && row.Process_By_Center != 1 ? '<div class="text-center text-danger"><strong>Pending</strong></div>' : '';
                                    return show;
                                } else {
                                    var show = row.Step == 4 && row.Process_By_Center != 1 ? '<div class="text-center"><span class="badge rounded-pill bg-label-success">Verified at ' + data + '</span></div>' : '';
                                    return show;
                                }
                            }
                        }
                    },
                    {
                        data: "Payment_Received",
                        "render": function(data, type, row) {
                            if (data == 1 && row.Step == 4 && row.Process_By_Center != 1) {
                                var show = is_accountant ? '<div class="text-center"><span class="cursor-pointer" onclick="verifyPayment(&#39;' + row.ID + '&#39;)"><strong>Review</strong></span></div>' : '<center><span class=" badge rounded-pill bg-label-secondary">Pending</span></center>';
                                return show;
                            } else if (row.Process_By_Center != 1) {
                                var show = row.Step == 4 ? '<div class="text-center"><span class=" badge rounded-pill bg-label-secondary">Verified on ' + data + '</span></div>' : '';
                                return show;
                            } else {
                                return '';
                            }
                        },
                        visible: false
                    },
                    {
                        data: "Processed_To_University",
                        "render": function(data, type, row) {
                            if (data == 1) {
                                var show = showInhouse && row.Document_Verified != 1 && row.Payment_Received != 1 ? '<div class="form-check complete mt-2">\
                <input type="checkbox" id="processed-to-university-' + row.ID + '" onclick="processedToUniversity(&#39;' + row.ID + '&#39;)">\
                <label for="processed-to-university-' + row.ID + '">Mark as Processed</label>\
              </div>' : "";
                                return show;
                            } else {
                                var show = row.Step == 4 ? '<div class="text-center"><span class="">Processed on ' + data + '</span></div>' : '';
                                return show;
                            }
                        }
                    },
                    {
                        data: "Enrollment_No",
                        "render": function(data, type, row) {
                            var edit = showInhouse && row.Processed_To_University != 1 ? '<i class="ri-edit-box-fill text-primary ms-3" title="Add Enrollment No." onclick="addEnrollment(&#39;' + row.ID + '&#39;)">' : '';
                            return data + edit;
                        }
                    },

                    {
                        data: "Adm_Session"
                    },
                    {
                        data: "Adm_Type"
                    },
                    {
                        data: "Adm_Type",
                        "render": function(data, type, row) {
                            return '<span onclick="reportPendnency(' + row.ID + ')"><strong>Report</strong><span>';
                        },
                        visible: false,
                    },
                    {
                        data: "First_Name",
                        "render": function(data, type, row) {
                            return '<strong>' + data + '</strong>';
                        },
                        visible: false,
                    },
                    {
                        data: "Father_Name"
                    },
                    {
                        data: "Short_Name"
                    },
                    {
                        data: "Fee"
                    },
                    {
                        data: "Duration"
                    },
                    // {
                    //   data: "Status",
                    //   "render": function(data, type, row) {
                    //     var active = data == 1 ? 'Active' : 'Inactive';
                    //     if (row.Step == 4 && showInhouse) {
                    //       var checked = data == 1 ? 'checked' : '';
                    //       return '<div class="form-check form-check-inline switch switch-lg success">\
                    //   <input onclick="changeStatus(\'Students\', &#39;' + row.ID + '&#39;);" type="checkbox" ' + checked + ' id="student-status-switch-' + row.ID + '">\
                    //   <label for="student-status-switch-' + row.ID + '">' + active + '</label>\
                    // </div>';
                    //     } else {
                    //       return active;
                    //     }
                    //   },
                    //   visible: hasStudentLogin
                    // },

                    {
                        data: "DOB"
                    },
                    {
                        data: "Center_Code",
                        visible: role == 'Sub-Center' ? false : true
                    },
                    {
                        data: "Center_Name",
                        visible: role == 'Sub-Center' ? false : true
                    },

                    {
                        data: "university_name",
                    }
                ],
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                "destroy": true,
                "scrollCollapse": true,
                "oLanguage": {
                    "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
                },
                drawCallback: function(settings, json) {
                    $('[data-toggle="tooltip"]').tooltip({
                        template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'

                    });
                },
                "aaSorting": []
            };

            var enrolledSettings = {
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': '/app/applications/enrolled-server',
                    'type': 'POST',
                    complete: function(xhr, responseText) {
                        $('#enrolled_count').html(xhr.responseJSON.iTotalDisplayRecords);
                    }
                },
                'columns': [{
                        data: "ID",
                        "render": function(data, type, row) {
                            var edit = showInhouse || row.Step < 4 ? '<a href="/admission/application-form?id=' + data + '"><i class="uil uil-edit mr-1" title="Edit Application Form"></i></a>' : '';
                            var deleted = showInhouse && row.Process_By_Center == 1 ? '<i class="uil uil-trash mr-1 cursor-pointer" title="Delete Application Form" onclick="destroy(&#39;application-form&#39;, &#39;' + data + '&#39;)"></i>' : '';
                            // var print = row.Step == 4 ? '<i class="uil uil-print mr-1 cursor-pointer" title="Print Application Form" onclick="printForm(&#39;' + data + '&#39;)"></i>' : '';
                            var proccessedByCenter = row.Process_By_Center == 1 ? "Not Proccessed" : row.Process_By_Center
                            var documentVerified = row.Document_Verified == 1 ? "Not Verified" : row.Document_Verified
                            var proccessedToUniversity = row.Processed_To_University == 1 ? "Not Proccessed" : row.Processed_To_University
                            var paymentVerified = row.Payment_Received == 1 ? "Not Verified" : row.Payment_Received
                            var info = row.Step == 4 ? '<i class="uil uil-info-circle cursor-pointer" data-bs-html="true" data-toggle="tooltip" data-placement="top" title="Proccessed By Center: <strong>' + proccessedByCenter + '</strong>&#013;&#010;Document Verified: <strong>' + documentVerified + '</strong>&#013;&#010;Payment Verified: <strong>' + paymentVerified + '</strong>&#013;&#010;Proccessed to University: <strong>' + proccessedToUniversity + '</strong>"></i>' : '';
                            return edit + deleted + info;
                        }
                    },

                    {
                        data: "Unique_ID",
                        "render": function(data, type, row) {
                            return '<span class="cursor-pointer" title="Click to export documents" onclick="exportDocuments(&#39;' + row.ID + '&#39;)"><strong>' + data + '</strong></span>';
                        }
                    },
                    {
                        data: "Step",
                        "render": function(data, type, row) {
                            var label_class = data < 4 ? 'badge-important' : 'badge-success';
                            var status = data < 4 ? 'In Draft @ Step ' + data : 'Completed';
                            return '<sapn class="label ' + label_class + '">' + status + '</sapn>';
                        }
                    },
                    {
                        data: "Process_By_Center",
                        "render": function(data, type, row) {
                            if (data == 1 && row.Step == 4) {
                                return '<span class=" badge rounded-pill bg-label-secondary">Not Processed</span>';
                                var show = !showInhouse ? '<div class="form-check complete mt-2">\
                <input type="checkbox" id="process-by-center-' + row.ID + '" onclick="processByCenter(&#39;' + row.ID + '&#39;)">\
                <label for="process-by-center-' + row.ID + '">Mark as Processed</label>\
              </div>' : '<span class=" badge rounded-pill bg-label-secondary">Not Processed</span>';
                                return show;
                            } else {
                                var show = row.Step == 4 ? '<div class="text-center"><span class=" badge rounded-pill bg-label-secondary">Processed on ' + data + '</span></div>' : '';
                                return show;
                            }
                        },
                        visible: role == 'Sub-Center' ? false : true
                    },
                    {
                        data: "Document_Verified",
                        "render": function(data, type, row) {
                            if (row.Pendency_Status == 2) {
                                if (!showInhouse) {
                                    return '<div class="text-center text-danger"><span class="cursor-pointer"><strong>In Review</strong></span></div>'
                                } else {
                                    return is_operations || is_university_head ? '<div class="text-center text-danger"><span class="cursor-pointer" onclick="verifyDocument(&#39;' + row.ID + '&#39;)"><strong>Re-Review</strong></span></div>' : '<div class="text-center text-danger"><span><strong>Re-Review</strong></span></div>'
                                }
                            } else if (row.Pendency != 0) {
                                if (!showInhouse) {
                                    return '<div class="text-center text-danger"><span class="cursor-pointer" onclick="uploadPendency(&#39;' + row.ID + '&#39;)"><strong>Pendency</strong></span></div>'
                                } else {
                                    return is_operations || is_university_head ? '<div class="text-center text-danger"><span class="cursor-pointer" onclick="reportPendency(&#39;' + row.ID + '&#39;)"><strong>Pendency</strong></span></div>' : '<div class="text-center text-danger"><span><strong>Pendency</strong></span></div>'
                                }
                            } else {
                                if (data == 1) {
                                    var show = (is_operations || is_university_head) && row.Process_By_Center != 1 ? '<div class="text-center"><span class="cursor-pointer" onclick="verifyDocument(&#39;' + row.ID + '&#39;)"><strong>Review</strong></span></div>' : row.Step == 4 && row.Process_By_Center != 1 ? '<div class="text-center text-danger"><strong>Pending</strong></div>' : '';
                                    return show;
                                } else {
                                    var show = row.Step == 4 && row.Process_By_Center != 1 ? '<div class="text-center"><span class="badge rounded-pill bg-label-success">Verified at ' + data + '</span></div>' : '';
                                    return show;
                                }
                            }
                        }
                    },
                    {
                        data: "Payment_Received",
                        "render": function(data, type, row) {
                            if (data == 1 && row.Step == 4 && row.Process_By_Center != 1) {
                                var show = is_accountant ? '<div class="text-center"><span class="cursor-pointer" onclick="verifyPayment(&#39;' + row.ID + '&#39;)"><strong>Review</strong></span></div>' : '<center><span class=" badge rounded-pill bg-label-secondary">Pending</span></center>';
                                return show;
                            } else if (row.Process_By_Center != 1) {
                                var show = row.Step == 4 ? '<div class="text-center"><span class=" badge rounded-pill bg-label-secondary">Verified on ' + data + '</span></div>' : '';
                                return show;
                            } else {
                                return '';
                            }
                        },
                        visible: false
                    },
                    {
                        data: "Processed_To_University",
                        "render": function(data, type, row) {
                            if (data == 1) {
                                var show = showInhouse && row.Document_Verified != 1 && row.Payment_Received != 1 ? '<div class="form-check complete mt-2">\
                <input type="checkbox" id="processed-to-university-' + row.ID + '" onclick="processedToUniversity(&#39;' + row.ID + '&#39;)">\
                <label for="processed-to-university-' + row.ID + '">Mark as Processed</label>\
              </div>' : "";
                                return show;
                            } else {
                                var show = row.Step == 4 ? '<div class="text-center"><span class="badge rounded-pill bg-label-success">Processed on ' + data + '</span></div>' : '';
                                return show;
                            }
                        }
                    },
                    {
                        data: "Enrollment_No",
                        "render": function(data, type, row) {
                            var edit = showInhouse && row.Processed_To_University != 1 ? '<i class="ri-edit-box-fill text-primary ms-3" title="Add Enrollment No." onclick="addEnrollment(&#39;' + row.ID + '&#39;)">' : '';
                            return data + edit;
                        }
                    },

                    {
                        data: "Adm_Session"
                    },
                    {
                        data: "Adm_Type"
                    },
                    {
                        data: "Adm_Type",
                        "render": function(data, type, row) {
                            return '<span onclick="reportPendnency(' + row.ID + ')"><strong>Report</strong><span>';
                        },
                        visible: false,
                    },
                    {
                        data: "First_Name",
                        "render": function(data, type, row) {
                            return '<strong>' + data + '</strong>';
                        },
                        visible: false,
                    },
                    {
                        data: "Father_Name"
                    },
                    {
                        data: "Short_Name"
                    },
                    {
                        data: "Fee"
                    },
                    {
                        data: "Duration"
                    },
                    // {
                    //   data: "Status",
                    //   "render": function(data, type, row) {
                    //     var active = data == 1 ? 'Active' : 'Inactive';
                    //     if (row.Step == 4 && showInhouse) {
                    //       var checked = data == 1 ? 'checked' : '';
                    //       return '<div class="form-check form-check-inline switch switch-lg success">\
                    //   <input onclick="changeStatus(\'Students\', &#39;' + row.ID + '&#39;);" type="checkbox" ' + checked + ' id="student-status-switch-' + row.ID + '">\
                    //   <label for="student-status-switch-' + row.ID + '">' + active + '</label>\
                    // </div>';
                    //     } else {
                    //       return active;
                    //     }
                    //   },
                    //   visible: hasStudentLogin
                    // },

                    {
                        data: "DOB"
                    },
                    {
                        data: "Center_Code",
                        visible: role == 'Sub-Center' ? false : true
                    },
                    {
                        data: "Center_Name",
                        visible: role == 'Sub-Center' ? false : true
                    },

                    {
                        data: "university_name",
                    }
                ],
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                "destroy": true,
                "scrollCollapse": true,
                "oLanguage": {
                    "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
                },
                drawCallback: function(settings, json) {
                    $('[data-toggle="tooltip"]').tooltip({
                        template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'

                    });
                },
                "aaSorting": []
            };

            applicationTable.dataTable(applicationSettings);
            notProcessedTable.dataTable(notProcessedSettings);
            readyForVerificationTable.dataTable(readyForVerificationSettings);
            verifiedTable.dataTable(verifiedSettings);
            processedToUniversityTable.dataTable(processedToUniversitySettings);
            enrolledTable.dataTable(enrolledSettings);

            // search box for table
            $('#application-search-table').keyup(function() {
                applicationTable.fnFilter($(this).val());
            });

            $('#not-processed-search-table').keyup(function() {
                notProcessedTable.fnFilter($(this).val());
            });

            $('#ready-for-verification-search-table').keyup(function() {
                readyForVerificationTable.fnFilter($(this).val());
            });

            $('#document-verified-search-table').keyup(function() {
                documentVerifiedTable.fnFilter($(this).val());
            });

            $('#processed-to-university-search-table').keyup(function() {
                processedToUniversityTable.fnFilter($(this).val());
            });

            $('#enrolled-search-table').keyup(function() {
                enrolledTable.fnFilter($(this).val());
            });


        })
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            getadmissionsession('All');
        })

        function getadmissionsession(university = null) {
            $.ajax({
                url: '/app/applications/get-adm-session',
                data: {
                    university: university
                },
                type: 'POST',
                success: function(data) {
                    $('#sessions').html(data);
                }

            })
        }

        function changeSession(value) {
            $('input[type=search]').val('');
            updateSession();
        }

        function updateSession() {
            var session_id = $('#sessions').val();
            $.ajax({
                url: '/app/applications/change-session',
                data: {
                    session_id: session_id
                },
                type: 'POST',
                success: function(data) {
                    $('.table').DataTable().ajax.reload(null, false);
                }
            })
        }
    </script>

    <script type="text/javascript">
        function addEnrollment(id) {
            $.ajax({
                url: '/app/applications/enrollment/create?id=' + id,
                type: 'GET',
                success: function(data) {
                    $('#md-modal-content').html(data);
                    $('#mdmodal').modal('show');
                }
            })
        }

        function addOANumber(id) {
            $.ajax({
                url: '/app/applications/oa-number/create?id=' + id,
                type: 'GET',
                success: function(data) {
                    $('#md-modal-content').html(data);
                    $('#mdmodal').modal('show');
                }
            })
        }
    </script>

    <script type="text/javascript">
        function exportData() {
            var search = $('#application-search-table').val();
            //console.log(search, "sandip");
            var steps_found = $('.nav-tabs').find('li a.active').attr('data-target');
            var steps_found = steps_found.substring(1, steps_found.length);
            var url = search.length > 0 ? "?steps_found=" + steps_found + "&search=" + search : "?steps_found=" + steps_found;
            //var url = search.length > 0 ? "?search=" + search : "";
            window.open('/app/applications/export' + url);
        }

        function exportDocuments(id) {
            $.ajax({
                url: '/app/applications/document?id=' + id,
                type: 'GET',
                success: function(data) {
                    $('#md-modal-content').html(data);
                    $('#mdmodal').modal('show');
                }
            })
        }

        function exportZip(id) {
            window.open('/app/applications/zip?id=' + id);
        }

        function exportPdf(id) {
            window.open('/app/applications/pdf?id=' + id);
        }

        function exportSelectedDocument() {
            var search = $('#application-search-table').val();
            var searchQuery = search.length > 0 ? "?search=" + search : "";
            $.ajax({
                url: '/app/applications/documents/create' + searchQuery,
                type: 'GET',
                success: function(data) {
                    $('#md-modal-content').html(data);
                    $('#mdmodal').modal('show');
                }
            })
        }
    </script>

    <script type="text/javascript">
        function uploadOAEnrollRoll() {
            $.ajax({
                url: '/app/applications/uploads/create_oa_enroll_roll',
                type: 'GET',
                success: function(data) {
                    $('#md-modal-content').html(data);
                    $('#mdmodal').modal('show');
                }
            })
        }
    </script>

    <script type="text/javascript">
        function printForm(id) {
            window.open('/forms/47/index.php?student_id=' + id, '_blank');
            // window.location.href = '/forms/47/index.php?student_id=' + id;
        }
    </script>

    <script type="text/javascript">
        function processByCenter(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Process'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/app/applications/process-by-center",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            if (data.status == 200) {
                                notification('success', data.message);
                                $('.table').DataTable().ajax.reload(null, false);
                            } else {
                                notification('danger', data.message);
                                $('.table').DataTable().ajax.reload(null, false);
                            }
                        }
                    });
                } else {
                    $('.table').DataTable().ajax.reload(null, false);
                }
            })
        }

        function processedToUniversity(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Process.'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/app/applications/processed-to-university",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            console.log(data);
                            if (data.status == 200) {
                                toastr.success(data.message);
                                $('.table').DataTable().ajax.reload(null, false);
                            } else {
                                toastr.error(data.message);
                                $('.table').DataTable().ajax.reload(null, false);
                            }
                        }
                    });
                } else {
                    $('.table').DataTable().ajax.reload(null, false);
                }
            })
        }

        function verifyPayment(id) {
            $.ajax({
                url: '/app/applications/review-payment?id=' + id,
                type: 'GET',
                success: function(data) {
                    $("#lg-modal-content").html(data);
                    $("#lgmodal").modal('show');
                }
            })
        }

        function verifyDocument(id) {
            $.ajax({
                url: '/app/applications/review-documents?id=' + id,
                type: 'GET',
                success: function(data) {
                    $('#xl-modal-content').html(data);
                    $('#xlmodal').modal('show');
                }
            })
        }

        function reportPendency(id) {
            $.ajax({
                url: '/app/pendencies/create?id=' + id,
                type: 'GET',
                success: function(data) {
                    $('#report-modal-content').html(data);
                    $('#reportmodal').modal('show');
                }
            })
        }

        function uploadPendency(id) {
            $(".modal").modal('hide');
            $.ajax({
                url: '/app/pendencies/edit?id=' + id,
                type: 'GET',
                success: function(data) {
                    $("#lg-modal-content").html(data);
                    $("#lgmodal").modal('show');
                }
            })
        }

        function uploadMultiplePendency() {
            $(".modal").modal('hide');
            $.ajax({
                url: '/app/pendencies/upload',
                type: 'GET',
                success: function(data) {
                    $("#lg-modal-content").html(data);
                    $("#lgmodal").modal('show');
                }
            })
        }
    </script>

    <script>
        if ($("#users").length > 0) {
            $("#users").select2({
                placeholder: 'Choose Center'
            })
            getCenterList('users');
        }

        $("#departments").select2({
            placeholder: 'Choose Department'
        })

        function addFilter(id, by) {
            $.ajax({
                url: '/app/applications/filter',
                type: 'POST',
                data: {
                    id,
                    by
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        $('.table').DataTable().ajax.reload(null, false);
                        $("#sub_center").html(data.subCenterName);

                        if ('<?= $_SESSION['Role'] ?>' == 'Administrator') {
                            $(".sub_center").html(data.subCenterName);

                        }
                    }
                }
            })
        }

        function addSubCenterFilter(id, by) {
            $.ajax({
                url: '/app/applications/filter',
                type: 'POST',
                data: {
                    id,
                    by
                },
                dataType: 'json',
                success: function(data) {

                    if (data.status) {
                        $('.table').DataTable().ajax.reload(null, false);
                        // $  ("#sub_center").html(data.subCenterName);
                    }

                }
            })
        }

        function addDateFilter() {
            var startDate = $("#startDateFilter").val();
            var endDate = $("#endDateFilter").val();
            if (startDate.length == 0 || endDate == 0) {
                return
            }
            var id = 0;
            var by = 'date';
            $.ajax({
                url: '/app/applications/filter',
                type: 'POST',
                data: {
                    id,
                    by,
                    startDate,
                    endDate
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        $('.table').DataTable().ajax.reload(null, false);
                    }
                }
            })
        }

        function getCourses(id) {
            $.ajax({
                url: '/app/courses/department-courses',
                type: 'POST',
                data: {
                    id
                },
                success: function(data) {
                    $("#sub_courses").html(data);
                }
            })
        }

        function allot_course_fee(url = null, modal = null, student_id = null, course_id = null, sub_course_id = null, center_id = null, duration = null) {
            $.ajax({
                url: '/app/' + url + '/allot-course-fee',
                type: 'POST',
                data: {
                    student_id: student_id,
                    course_id: course_id,
                    sub_course_id: sub_course_id,
                    center_id: center_id,
                    duration: duration
                },
                success: function(data) {
                    $('#' + modal + '-modal-content').html(data);
                    $('#' + modal + 'modal').modal('show');
                }
            })
        }
      function students_data(url = null, modal = null, ID = null) {
        $.ajax({
            url: '/app/' + url + '/students_details',
            type: 'POST',
            data: {
                ID: ID
            },
            success: function(data) {
                $('#' + modal + '-modal-content').html(data);
                $('#' + modal + 'modal').modal('show');
            }
        })
    }
    </script>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-bottom.php') ?>
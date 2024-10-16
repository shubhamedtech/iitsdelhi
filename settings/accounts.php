<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-top.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-bottom.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/side-menu.php') ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/includes/db-config.php'); ?>
<?php

unset($_SESSION['current_session']);
// unset($_SESSION['Role']);
unset($_SESSION['filterByUsers']);
unset($_SESSION['filterBySubCourses']);
unset($_SESSION['filterByFeeAssigned']);
unset($_SESSION['filterByDepartment']);
unset($_SESSION['filterByDate']);
?>
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
    #totalAmountDisplay {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        /* Button color */
        color: white;
        /* Text color */
        border: none;
        border-radius: 5px;
        /* Rounded corners */
        text-align: center;
        text-decoration: none;
        font-size: 16px;
        cursor: pointer;
        /* Hand cursor on hover */
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        /* Optional: Adds shadow */
    }

    #totalAmountDisplay:hover {
        background-color: #0056b3;
        /* Darker shade on hover */
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" />
<div class="layout-page">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/top-menu.php') ?>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <ol class="breadcrumb d-flex flex-wrap justify-content-between align-self-start">
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
                        $programs = $conn->query("SELECT Sub_Courses.ID, CONCAT(Courses.Short_Name, ' (', Sub_Courses.Name, ')') as Name FROM Students LEFT JOIN Sub_Courses ON Students.Sub_Course_ID = Sub_Courses.ID LEFT JOIN Courses ON Sub_Courses.Course_ID = Courses.ID WHERE  Students.University_ID IS NOT NULL $role_qurys GROUP BY Students.Sub_Course_ID");
                        while ($program = $programs->fetch_assoc()) {
                            echo '<option value="' . $program['ID'] . '">' . $program['Name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="select2 form-select" data-allow-clear="true" id="search_assign_fee" onchange="addFilter(this.value, 'search_assign_fee');" data-placeholder="Choose Sta">
                        <option value="">Choose Fee Type</option>
                        <option value="1">Fee Assigned</option>
                        <option value="2">Fee not Assigned</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="select2 form-select" data-allow-clear="true" id="users" onchange="addFilter(this.value, 'users')" data-placeholder="Choose User">
                        <option value="All">All</option>
                        <?php
                        $centers = $conn->query("SELECT ID, Name FROM `Users` WHERE Role = 'Center'");
                        while ($center = $centers->fetch_assoc()) {
                            echo '<option value="' . $center['ID'] . '">' . $center['Name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-sm-2">
                    <div class="input-daterange input-group d-flex align-items-center" id="datepicker-range">
                        <input type="text" class="input-sm form-control" placeholder="Select Date" id="startDateFilter" name="start" />
                        <div class="input-group-addon mx-2">to</div>
                        <input type="text" class="input-sm form-control" placeholder="Select Date" id="endDateFilter" onchange="addDateFilter()" name="end" />
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_3_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="dataTables_length" id="DataTables_Table_3_length"><label>Show <select name="DataTables_Table_3_length" aria-controls="DataTables_Table_3" class="form-select form-select-sm">
                                            <option value="7">7</option>
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="75">75</option>
                                            <option value="100">100</option>
                                        </select> entries</label></div>
                            </div>
                            <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">
                                <div id="DataTables_Table_2" class="dataTables_filter">
                                    <div id="totalAmountDisplay">Total Revenue: <span id="totalamount"></span> </div>
                                    <label>Search:
                                        <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="DataTables_Table_3">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="dt-multilingual table table-bordered dataTable no-footer dtr-column ctable" id="DataTables_Table_3" aria-describedby="DataTables_Table_3_info">
                                <thead>
                                    <tr>
                                        <th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" aria-label="">Student Name</th>
                                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Student Courses</th>
                                        <th class="sorting sorting_desc" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" aria-sort="descending" aria-label="Position: activate to sort column ascending">Admissions Sessions</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions">Students DOB</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions">Students Year/Semester</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" data-orderable="false">Students installment</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" data-orderable="false">Course allotted Debited Fee</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" data-orderable="false">Student Credited Amount</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" data-orderable="false">Student Pending Fee</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" data-orderable="false">Center Code</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" data-orderable="false">Center(SubCenter)Name</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" data-orderable="false">Transaction Date</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" data-orderable="false">University Name</th>
                                    </tr>
                                </thead>
                                <p onchange=""></p>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-top.php') ?>
        <script type="text/javascript">
            $(function() {
                var role = '<?= $_SESSION['Role'] ?>';
                var table = $('#DataTables_Table_3');
                var settings = {
                    processing: true,
                    serverSide: true,
                    serverMethod: 'post',
                    ajax: {
                        url: '/app/accounts/server',
                        // dataSrc: function(json) 
                        // {
                        //     // console.log();
                        //     // // Extract the total amount from the response
                        //     // var totalAmount = 0;
                        //     // Loop through the returned data to calculate the total amount
                        //     // json['aaData'].forEach(function(item) {
                        //     //     if (item.course_credited_fee) {
                        //     //         totalAmount += parseFloat(item.course_credited_fee);
                        //     //     }
                        //     // });
                        //     // Display the total amount in the specified HTML element
                        //     $('#totalAmountDisplay').text('Revenue: ₹' + json['aaData'][0].total_amount);
                        //     // Return the data for DataTables to process
                        //     return json['aaData'];
                        // }
                    },
                    columns: [{
                            data: "First_Name",
                        },
                        {
                            data: "course_name",
                            render: function(data, type, full, meta) {
                                if (data) {
                                    return '<div class="text-center"><span class="label label-success">' + data + '</span></div>';
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            data: 'Adm_Session',
                            render: function(data, type, full, meta) {
                                if (data) {
                                    return '<div class="text-center"><span class="label label-success">Verified At ' + data + '</span></div>';
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            data: "DOB"
                        },
                        {
                            data: "Duration"
                        },
                        {
                            data: "total_fee_count"
                        },
                        {
                            data: "course_debited_fee"
                        },
                        {
                            data: "course_credited_fee"
                        },
                        {
                            data: 'total_fee_pending'
                        },
                        {
                            data: "center_Code",
                            render: function(data, type, full, meta) {
                                if (data) {
                                    return '<div class="text-center"><span class="label label-success">' + data + '</span></div>';
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            data: "center_Name",
                            render: function(data, type, full, meta) {
                                if (data) {
                                    return '<div class="text-center"><span class="label label-success">' + data + '</span></div>';
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            data: "transaction_date",
                        },
                        {
                            data: "university_name",
                            render: function(data, type, full, meta) {
                                if (data) {
                                    return '<div class="text-center"><span class="label label-success">' + data + '</span></div>';
                                } else {
                                    return '';
                                }
                            }
                        },
                    ],
                    sDom: "<t><'row'<p i>>",
                    destroy: true,
                    scrollCollapse: true,
                    oLanguage: {
                        sLengthMenu: "_MENU_ ",
                        sInfo: "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
                    },
                    aaSorting: [],
                    iDisplayLength: 15,
                    drawCallback:function(setting,json)
                    {
                        var amount = 0;
                        if(setting['json']['aaData'])
                        {
                            amount = setting['json']['aaData'][0].total_amount;
                        }
                        $('#totalamount').text(amount);
                    }
                };
                table.dataTable(settings);
                $('#DataTables_Table_2 input').keyup(function() {
                    table.fnFilter($(this).val());
                });
            });
        </script>
        <script>
            function addFilter(id, by) {
                // alert('ankit');
                $.ajax({
                    url: '/app/accounts/filter',
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
                            $('#totalamount').text(0);

                        }
                    }
                });
            }
        </script>
        <script>
            $(document).ready(function() {
                // Initialize the date picker
                $('#datepicker-range').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true
                });
                // Add Date Filter function
                function addDateFilter() {
                    var startDate = $("#startDateFilter").val();
                    var endDate = $("#endDateFilter").val();
                    if (!startDate || !endDate) {
                        return;
                    }
                    var id = 0;
                    var by = 'datetime';
                    $.ajax({
                        url: '/app/accounts/filter',
                        type: 'POST',
                        data: {
                            id: id,
                            by: by,
                            startDate: startDate,
                            endDate: endDate
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.status) {
                                $('.table').DataTable().ajax.reload(null, false);
                                $('#totalamount').text(0);
                            } else {
                                console.error("Filter failed: ", data.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX error: ", status, error);
                        }
                    });
                }
                // Trigger the filter function when the end date changes
                $('#endDateFilter').change(addDateFilter);
            });
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
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
        <!-- <script>
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
        </script> -->
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-bottom.php') ?>
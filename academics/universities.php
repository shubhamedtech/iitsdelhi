<!-- Include necessary PHP files -->
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-top.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-bottom.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/side-menu.php') ?>

<div class="layout-page">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/top-menu.php') ?>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <ol class="breadcrumb d-flex flex-wrap justify-content-between align-self-start">
                <?php 
                $breadcrumbs = array_filter(explode("/", $_SERVER['REQUEST_URI']));
                for ($i = 1; $i <= count($breadcrumbs); $i++) {
                    if (count($breadcrumbs) == $i) : $active = "active";
                        $crumb = explode("?", $breadcrumbs[$i]);
                        echo '<li class="breadcrumb-item ' . $active . '">' . strtoupper($crumb[0]) . '</li>';
                    endif;
                }
                ?>
                <div class="text-end">
                    <span type="button" class="btn btn-primary" onclick="add('universities','md')"> <i class="ri-apps-2-add-line"></i> </span>
                </div>
            </ol>

            <div class="card">
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_3_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="dataTables_length" id="DataTables_Table_3_length">
                                    <label>Show
                                        <select name="DataTables_Table_3_length" aria-controls="DataTables_Table_3" class="form-select form-select-sm" id="dataTablesLength" onchange="changeDisplayLength()">
                                            <option value="2">2</option>
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="75">75</option>
                                            <option value="100">100</option>
                                        </select>
                                        entries
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">
                                <div id="universities-search-table" class="dataTables_filter">
                                    <label>Search:
                                        <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="DataTables_Table_3">
                                    </label>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="dt-multilingual table table-bordered dataTable no-footer dtr-column ctable" id="DataTables_Table_3" aria-describedby="DataTables_Table_3_info">
                                    <thead>
                                        <tr>
                                            <th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" style="width: 33.3281px;" aria-label="">LOGO</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" style="width: 100px !important;" aria-label="Name: activate to sort column ascending">NAME</th>
                                            <th class="sorting sorting_desc" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" style="width: 154.312px;" aria-sort="descending" aria-label="Position: activate to sort column ascending">Vertical</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" style="width:100px;" aria-label="Email: activate to sort column ascending">STATUS</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" style="width: 400px!important;" aria-label="Date: activate to sort column ascending">UNIVERSITY DEALING WITH</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" style="width: 400px!important;" aria-label="Salary: activate to sort column ascending">UNIQUE CENTER CODE</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" style="width: 400px!important;" aria-label="Status: activate to sort column ascending">UNIQUE STUDENT CODE</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 138.828px;" aria-label="Actions">Action</th>
                                        </tr>
                                    </thead>
                                    <p onchange=""></p>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-top.php') ?>

        <script type="text/javascript">
            $(document).ready(function() {
                var initialDisplayLength = $('#dataTablesLength').val(); // Get initial value from the dropdown
                
                var table = $('#DataTables_Table_3').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/app/universities/server',
                        type: 'POST' // Ensure correct HTTP method
                    },
                    columns: [{
                            data: "Logo",
                            render: function(data) {
                                return '<img src="' + data + '" width="60px" />';
                            }
                        },
                        {
                            data: "Short_Name"
                        },
                        {
                            data: "Vertical"
                        },
                        {
                            data: "Status",
                            render: function(data, type, row) {
                                var active = data == 1 ? 'Active' : 'Inactive';
                                var checked = data == 1 ? 'checked' : '';
                                return '<div class="form-check form-switch mb-2 d-flex flex-row">' +
                                    '<input class="form-check-input" onclick="changeStatus(\'Universities\', \'' + row.ID + '\')" type="checkbox" ' + checked + ' id="status-switch-' + row.ID + '">' +
                                    '<label class="ms-2 mt-1" for="status-switch-' + row.ID + '">' + active + '</label>' +
                                    '</div>';
                            }
                        },
                        {
                            data: "Is_B2C",
                            render: function(data) {
                                var typeText = '';
                                if (data == 1) {
                                    typeText = 'University is dealing with Students.';
                                } else if (data == 2) {
                                    typeText = 'University is dealing with both Outsourced Partners and Students.';
                                } else {
                                    typeText = 'University is dealing with Outsourced Partners.';
                                }
                                return typeText;
                            }
                        },
                        {
                            data: "Has_Unique_Center",
                            render: function(data, type, row) {
                                var active = data == 1 ? 'Has Unique Center Code' : 'Don\'t have Unique Center Code';
                                var checked = data == 1 ? 'checked' : '';
                                var character = 'XXXX';
                                var centerCode = row.Center_Suffix ? '<span>Center Code: <b>' + row.Center_Suffix + character + '</b></span>' : '<span>Please create Center Code</span>';
                                var edit = data == 1 ? '<span><i class="ri-settings-line" onclick="addCenterCode(\'' + row.ID + '\')"></i></span>' : '';
                                var generator = data == 1 ? centerCode + edit : edit;
                                return '<div class="form-check form-switch mb-2 d-flex flex-row">' +
                                    '<input class="form-check-input" onclick="changeColumnStatus(\'' + row.ID + '\', \'Has_Unique_Center\')" type="checkbox" ' + checked + ' id="center-switch-' + row.ID + '">' +
                                    '<label class="ms-2 " for="center-switch-' + row.ID + '">' + active + '</label>' +
                                    '</div><br><p>' + generator + '</p>';
                            }
                        },
                        {
                            data: "Has_Unique_StudentID",
                            render: function(data, type, row) {
                                var active = data == 1 ? 'Has unique Student ID' : 'Don\'t have a unique Student ID';
                                var checked = data == 1 ? 'checked' : '';
                                var studentID = row.Max_Character ? '<span>Student ID: <b>' + row.ID_Suffix + row.Max_Character + '</b></span>' : '<span>Please create Student ID</span>';
                                var edit = data == 1 ? '<span><i class="ri-settings-line" onclick="addStudentID(\'' + row.ID + '\')"></i></span>' : '';
                                var generator = data == 1 ? studentID + edit : edit;
                                return '<div class="form-check form-switch mb-2 d-flex flex-row">' +
                                    '<input class="form-check-input" onclick="changeColumnStatus(\'' + row.ID + '\', \'Has_Unique_StudentID\')" type="checkbox" ' + checked + ' id="student-switch-' + row.ID + '">' +
                                    '<label class="ms-2" for="student-switch-' + row.ID + '">' + active + '</label>' +
                                    '</div><br><p>' + generator + '</p>';
                            }
                        },
                        {
                            data: "ID",
                            render: function(data) {
                                return '<div class="button-list text-end">' +
                                    '<i class="ri-edit-box-fill text-success" onclick="edit(\'universities\', \'' + data + '\', \'md\')"></i>' +
                                    '<i class="ms-3 ri-delete-bin-5-line text-danger" onclick="destroy(\'universities\', \'' + data + '\')"></i>' +
                                    '</div>';
                            }
                        }
                    ],
                    sDom: "<t><'row'<p i>>",
                    destroy: true,
                    scrollCollapse: true,
                    oLanguage: {
                        sLengthMenu: "_MENU_ ",
                        sInfo: "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
                    },
                    aaSorting: [],
                    iDisplayLength: initialDisplayLength 
                });

                // Search input event handler
                $('#universities-search-table input').on('keyup', function() {
                    table.search(this.value).draw();
                });
            });

            function changeDisplayLength() {
                var length = $('#dataTablesLength').val();
                var table = $('#DataTables_Table_3').DataTable();
                table.page.len(length).draw();
            }

            function changeColumnStatus(id, column) {
                $.ajax({
                    url: '/app/universities/status',
                    type: 'post',
                    data: {
                        id: id,
                        column: column
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        if (data.status == 200) {
                            toastr.success(data.message);
                            $('#DataTables_Table_3').DataTable().ajax.reload(null, false);
                        } else {
                            toastr.danger(data.message);
                            $('#DataTables_Table_3').DataTable().ajax.reload(null, false);
                        }
                    }
                });
            }

            function addStudentID(id) {
                $.ajax({
                    url: '/app/universities/student-id?id=' + id,
                    type: 'GET',
                    success: function(data) {
                        $('#md-modal-content').html(data);
                        $('#mdmodal').modal('show');
                    }
                });
            }

            function addCenterCode(id) {
                $.ajax({
                    url: '/app/universities/center-code?id=' + id,
                    type: 'GET',
                    success: function(data) {
                        $('#md-modal-content').html(data);
                        $('#mdmodal').modal('show');
                    }
                });
            }
        </script>

        <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-bottom.php') ?>

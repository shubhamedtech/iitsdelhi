<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-top.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-bottom.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/side-menu.php') ?>
<?php require ($_SERVER['DOCUMENT_ROOT'] .'/includes/db-config.php'); ?>

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
          <!-- <button class="btn btn-link" aria-label="" title="" data-toggle="tooltip" data-original-title="Add Department" onclick="add('departments','md')"> <i class="uil uil-plus-circle"></i></button> -->
          <span type="button" class="btn btn-primary" onclick="add('departments','md')"> <i class="ri-apps-2-add-line"></i> </span>

        </div>
      </ol>
      <div class="card">
        <!-- <h5 class="card-header text-center text-md-start">Multilingual</h5> -->
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
                <div id="department-search-table" class="dataTables_filter">
                  <label>Search:
                    <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="DataTables_Table_3">
                  </label>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="dt-multilingual table table-bordered ctable  dataTable no-footer dtr-column ctable" id="DataTables_Table_3" aria-describedby="DataTables_Table_3_info">
                <thead>
                  <tr>
                    <th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" aria-label="">Name</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">University</th>
                    <th class="sorting sorting_desc" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" aria-sort="descending" aria-label="Position: activate to sort column ascending"> Status</th>
                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions">Action</th>
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

      var role = '<?= $_SESSION['Role'] ?>';
      var showUniversityColumn = role == 'Administrator'; // Adjust based on your role logic

      var table = $('#DataTables_Table_3').DataTable({
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
          url: '/app/departments/server',
        },
        columns: [{
            data: "Name"
          },
          {
            data: "University",
            visible: showUniversityColumn
          },
          {
            data: "Status",
            className: "text-center",
            render: function(data, type, row) {
              var active = data == 1 ? 'Active' : 'Inactive';
              var checked = data == 1 ? 'checked' : '';
              return '<div class="form-check form-switch mb-2 d-flex flex-row justify-content-center">\
                      <input class="form-check-input" onclick="changeStatus(\'Departments\', \'' + row.ID + '\')" type="checkbox" ' + checked + ' id="status-switch-' + row.ID + '">\
                      <label for="status-switch-' + row.ID + '">' + active + '</label>\
                    </div>';
            }
          },
          {
            data: "ID",
            className: "text-center",
            render: function(data) {
              return '<div class="button-list text-center">\
                        <i class="ri-edit-box-fill text-success" onclick="edit(\'departments\', \'' + data + '\', \'md\')"></i>\
                        <i class="ms-3 ri-delete-bin-5-line text-danger" onclick="destroy(\'departments\', \'' + data + '\')"></i>\
                      </div>';
            }
          },
        ],
        columnDefs: [{
            width: "40%",
            targets: 0
          }, // Column 1 width (Name)
          {
            width: "40%",
            targets: 1
          }, // Column 2 width (University)
          {
            width: "10%",
            targets: 2
          }, // Column 3 width (Status)
          {
            width: "10%",
            targets: 3
          } // Column 4 width (Actions)
        ],
        sDom: "<t><'row'<p i>>",
        destroy: true,
        scrollCollapse: true,
        language: {
          lengthMenu: "_MENU_ ",
          info: "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
        },
        aaSorting: [],
        iDisplayLength: initialDisplayLength 
      });

      // Search input event handler
      $('#department-search-table input').on('keyup', function() {
        table.search(this.value).draw();
      });
    });
  </script>



  <script type="text/javascript">
    function changeColumnStatus(id, column) {
      $.ajax({
        url: '/app/departments/status',
        type: 'post',
        data: {
          id: id,
          column: column
        },
        dataType: 'json',
        success: function(data) {
          if (data.status == 200) {
            toastr('success', data.message);
            $('#DataTables_Table_3').DataTable().ajax.reload(null, false);
          } else {
            toastr.error('danger', data.message);
            $('#DataTables_Table_3').DataTable().ajax.reload(null, false);
          }
        }
      })
    }
  </script>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-bottom.php') ?>
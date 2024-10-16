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
            echo '<li class="breadcrumb-item ' . $active . '">' . $crumb[0] . '</li>';
          endif;
        }
        ?>
        <div>
          <?php if (in_array($_SESSION['Role'], ['Administrator', 'University Head'])) { ?>
            <span type="button" class="btn btn-warning" onclick="exportData()"> <i class="ri-file-download-fill"></i> </span>
            <span type="button" class="btn btn-primary" onclick="add('sub-courses','lg')"> <i class="ri-apps-2-add-line"></i> </span>
          <?php } ?>
        </div>
      </ol>
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
                <div id="specialization-search-table" class="dataTables_filter">
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
                    <th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" aria-label="">Name</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Program</th>
                    <th class="sorting sorting_desc" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" aria-sort="descending" aria-label="Position: activate to sort column ascending"> Type</th>
                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions">Scheme</th>
                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" data-orderable="false">Mode</th>
                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" data-orderable="false">University</th>
                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" data-orderable="false">Status</th>
                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" data-orderable="false">Action</th>
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
    $(function() {
      var role = '<?= $_SESSION['Role'] ?>';
      var show = role == 'Administrator' ? true : false;
      var table = $('#DataTables_Table_3').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
          'url': '/app/sub-courses/server'
        },
        'columns': [{
            data: "Name"
          },
          {
            data: "Course"
          },
          {
            data: "CourseType"
          },
          {
            data: "Scheme"
          },
          {
            data: "Mode"
          },
          {
            data: "University",
            visible: show
          },
          {
            data: "Status",
            "render": function(data, type, row) {
              var active = data == 1 ? 'Active' : 'Inactive';
              var checked = data == 1 ? 'checked' : '';
              return '<div class="form-check form-switch mb-2 d-flex flex-row justify-content-center">\
                        <input class="form-check-input me-2" onclick="changeStatus(&#39;Sub-Courses&#39;, &#39;' + row.ID + '&#39;)" type="checkbox" ' + checked + ' id="status-switch-' + row.ID + '">\
                        <label for="status-switch-' + row.ID + '">' + active + '</label>\
                      </div>';
            }
          },
          {
            data: "ID",
            "render": function(data, type, row) {
              return '<div class="button-list text-end">\
                <i class="ri-edit-box-fill text-success" onclick="edit(&#39;sub-courses&#39;, &#39;' + data + '&#39;, &#39;lg&#39;)"></i>\
                 <i class="ms-3 ri-delete-bin-5-line text-danger" onclick="destroy(&#39;sub-courses&#39;, &#39;' + data + '&#39;)"></i>\
              </div>'
            },
            visible: ['Administrator', 'University Head'].includes(role) ? true : false
          },
        ],
        "sDom": "<t><'row'<p i>>",
        "destroy": true,
        "scrollCollapse": true,
        "oLanguage": {
          "sLengthMenu": "_MENU_ ",
          "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
        },
        "aaSorting": [],
        "iDisplayLength": 25
      });

      $('#specialization-search-table input').on('keyup', function() {
        table.search(this.value).draw();
      });
    });
  </script>

  <script type="text/javascript">
    function changeColumnStatus(id, column) {
      $.ajax({
        url: '/app/sub-courses/status',
        type: 'post',
        data: {
          id: id,
          column: column
        },
        dataType: 'json',
        success: function(data) {
          if (data.status == 200) {
            toastr.success(data.message);
            $('#DataTables_Table_3').DataTable().ajax.reload(null, false);
          } else {
            toastr.error(data.message);
            $('#DataTables_Table_3').DataTable().ajax.reload(null, false);
          }
        }
      })
    }
  </script>

  <script type="text/javascript">
    function exportData() {
      var search = $('#specialization-search-table input').val();
      var url = search.length > 0 ? "?search=" + search : "";
      window.open('/app/sub-courses/export' + url);
    }
  </script>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-bottom.php') ?>

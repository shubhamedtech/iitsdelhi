<?php 
 ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


?>
<div id="accordionDelivery" class="accordion mt-2">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" aria-expanded="true" data-bs-target="#exam-session" aria-controls="schemes">Exam Session</button>
    </h2>
    <div id="exam-session" class="accordion-collapse collapse">

      <div class="row p-b-20">
        <div class="col-lg-12 text-end">
          <!-- <button type="button" class="btn btn-primary" onclick="addComponents('exam-sessions', 'lg', <?= $university_id ?>)">Add</button> -->
          <button type="button" class="btn btn-primary mb-3 me-3" onclick="addComponents('exam-sessions', 'lg', <?= $university_id ?>)">
            <i class="ri-apps-2-add-line"></i>
          </button>
        </div>

      </div>

      <div class="card">
        <div class="card-datatable table-responsive">
          <div id="DataTables_Table_3_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <div class="row">
              <!-- <div class="col-sm-12 col-md-6">
                <div class="dataTables_length" id="DataTables_Table_3_length">
                  <label>Show
                    <select name="DataTables_Table_3_length" aria-controls="DataTables_Table_3" class="form-select form-select-sm">
                      <option value="7">7</option>
                      <option value="10">10</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                      <option value="75">75</option>
                      <option value="100">100</option>
                    </select> entries
                  </label>
                </div>
              </div>
              <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">
                <div id="DataTables_Table_3_filter" class="dataTables_filter">
                  <label>Search:
                    <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="DataTables_Table_3">
                  </label>
                </div>
              </div> -->
            </div>
            <div class="table-responsive">
              <table class="dt-multilingual table table-bordered dataTable no-footer dtr-column ctable" id="tableExamSessionDatatable" aria-describedby="DataTables_Table_3_info">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th class="text-center">ADMISSION SESSIONS</th>
                    <th class="text-center">RE REG</th>
                    <th class="text-center">BACK PAPER</th>
                    <th class="text-center">ACTION</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  var table = $('#tableExamSessionDatatable');
  var settings = {
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    'ajax': {
      'url': '/app/components/exam-sessions/server',
      type: 'POST',
      "data": function(data) {
        data.university_id = '<?= $university_id ?>';
      },
    },
    'columns': [{
        data: "Name"
      },
      {
        data: "Admission_Session"
      },
      {
        data: "RR_Status",
        "render": function(data, type, row) {
          var active = data == 1 ? 'Active' : 'Inactive';
          var checked = data == 1 ? 'checked' : '';
          return '<div class="form-check form-switch mb-2 d-flex flex-row justify-content-center">\
            <input class="form-check-input" onclick="changeRRStatus(\'' + row.ID + '\');" type="checkbox" ' + checked + ' id="le-status-switch-' + row.ID + '">\
            <label for="le-status-switch-' + row.ID + '">' + active + '</label>\
          </div>';
        }
      },
      {
        data: "BP_Status",
        "render": function(data, type, row) {
          var active = data == 1 ? 'Active' : 'Inactive';
          var checked = data == 1 ? 'checked' : '';
          return '<div class="form-check form-switch mb-2 d-flex flex-row justify-content-center">\
            <input  class="form-check-input" onclick="changeBPStatus(\'' + row.ID + '\');" type="checkbox" ' + checked + ' id="ct-status-switch-' + row.ID + '">\
            <label for="ct-status-switch-' + row.ID + '">' + active + '</label>\
          </div>';
        }
      },
      {
        data: "ID",
        "render": function(data, type, row) {
          return '<div class="text-end">\
            <i class="ri-edit-box-fill text-success" onclick="editComponents(\'exam-sessions\', \'' + data + '\', \'lg\');"></i>\
            <i class="ms-3 ri-delete-bin-5-line text-danger" onclick="destroyComponents(\'exam-sessions\', \'ExamSessionDatatable\', \'' + data + '\');"></i>\
          </div>'
        }
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
    "iDisplayLength": 5
  };

  table.dataTable(settings);

  function changeRRStatus(id) {
    $.ajax({
      url: '/app/components/exam-sessions/rr_status?id=' + id,
      type: 'GET',
      dataType: 'json',
      success: function(data) {
        if (data.status == 200) {
          toastr.success(data.message);
          $('#tableExamSessionDatatable').DataTable().ajax.reload(null, false);
        } else {
          toastr.error(data.message);
        }
      }
    })
  }

  function changeBPStatus(id) {
    $.ajax({
      url: '/app/components/exam-sessions/bp_status?id=' + id,
      type: 'GET',
      dataType: 'json',
      success: function(data) {
        if (data.status == 200) {
          tosatr.success(data.message);
          $('#tableExamSessionDatatable').DataTable().ajax.reload(null, false);
        } else {
          toastr.error(data.message);
        }
      }
    })
  }
</script>
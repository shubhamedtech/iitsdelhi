<div id="accordionDelivery" class="accordion mt-2">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" aria-expanded="true" data-bs-target="#admission-session" aria-controls="schemes">Admission Session</button>
    </h2>
    <div id="admission-session" class="accordion-collapse collapse">
      <div class="row p-b-20">
        <div class="col-lg-12 text-end">
          <button type="button" class="btn btn-primary mb-3 me-3" onclick="addComponents('admission-sessions', 'md', <?= $university_id ?>)">
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
              <table class="dt-multilingual table table-bordered dataTable no-footer dtr-column ctable" id="tableAdmissionSessionDatatable" aria-describedby="DataTables_Table_3_info">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th class="text-center">EXAM SESSION</th>
                    <th class="text-center">SCHEME</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center">CURRENT STATUS</th>
                    <th class="text-center">LE STATUS</th>
                    <th class="text-center">CT STATUS</th>
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
  $(document).ready(function() {
    var table = $('#tableAdmissionSessionDatatable').DataTable({
      processing: true,
      serverSide: true,
      serverMethod: 'post',
      ajax: {
        url: '/app/components/admission-sessions/server',
        type: 'POST',
        data: function(data) {
          data.university_id = '<?= $university_id ?>';
        }
      },
      stateSave: true,
      stateSaveParams: function(settings, data) {
       
        data.columns[5].visible = settings.json.permissions.LE_Status;
        data.columns[6].visible = settings.json.permissions.CT_Status;
      },
      columns: [
        { data: "Name" },
        { data: "Exam_Session", className: 'text-center' },
        { data: "Scheme", className: 'text-center' },
        { 
          data: "Status",
          className: 'text-center',
          render: function(data, type, row) {
            var active = data == 1 ? 'Active' : 'Inactive';
            var checked = data == 1 ? 'checked' : '';
            return `
              <div class="form-check form-switch mb-2 d-flex flex-row justify-content-center">
                <input class="form-check-input" type="checkbox" ${checked} id="session-status-switch-${row.ID}" onclick="changeComponentStatus('Admission-Sessions', 'AdmissionSessionDatatable', '${row.ID}')">
                <label for="session-status-switch-${row.ID}">${active}</label>
              </div>`;
          }
        },
        { 
          data: "Current_Status",
          className: 'text-center',
          render: function(data, type, row) {
            var active = data == 1 ? 'Active' : 'Inactive';
            var checked = data == 1 ? 'checked' : '';
            return `
              <div class="form-check form-switch mb-2 d-flex flex-row justify-content-center">
                <input class="form-check-input" type="checkbox" ${checked} id="current-session-status-switch-${row.ID}" onclick="changeCurrentStatus('${<?= $university_id ?>}', '${row.ID}')">
                <label for="current-session-status-switch-${row.ID}">${active}</label>
              </div>`;
          }
        },
        { 
          data: "LE_Status",
          className: 'text-center',
          render: function(data, type, row) {
         
            var active = data == 1 ? 'Active' : 'Inactive';
            
            var checked = data == 1 ? 'checked' : '';
            return `
              <div class="form-check form-switch mb-2 d-flex flex-row justify-content-center">
                <input class="form-check-input" type="checkbox" ${checked} id="le-status-switch-${row.ID}" onclick="changeLEStatus('${row.ID}')">
                <label for="le-status-switch-${row.ID}">${active}</label>
              </div>`;
          }
        },
        { 
          data: "CT_Status",
          className: 'text-center',
          render: function(data, type, row) {
            var active = data == 1 ? 'Active' : 'Inactive';
            var checked = data == 1 ? 'checked' : '';
            return `
              <div class="form-check form-switch mb-2 d-flex flex-row justify-content-center">
                <input class="form-check-input" type="checkbox" ${checked} id="ct-status-switch-${row.ID}" onclick="changeCTStatus('${row.ID}')">
                <label for="ct-status-switch-${row.ID}">${active}</label>
              </div>`;
          }
        },
        { 
          data: "ID",
          className: 'text-end',
          render: function(data, type, row) {
            return `
              <div class="text-end">
                <i class="ri-edit-box-fill text-success" onclick="editComponents('admission-sessions', '${data}', 'md');"></i>
                <i class="ms-3 ri-delete-bin-5-line text-danger" onclick="destroyComponents('admission-sessions', 'AdmissionSessionDatatable', '${data}');"></i>
              </div>`;
          }
        }
      ],
      columnDefs: [
        { width: "30%", targets: 0 },
        { width: "10%", targets: [1, 2, 3, 4, 5, 6, 7], className: 'text-center' }
      ],
      sDom: "<t><'row'<p i>>",
      destroy: true,
      scrollCollapse: true,
      language: {
        lengthMenu: "_MENU_ ",
        info: "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
      },
      order: [],
      pageLength: 5
    });
  });

  function changeCurrentStatus(university_id, id) {
    $.ajax({
      url: '/app/components/admission-sessions/current',
      type: 'GET',
      data: { id: id, university_id: university_id },
      dataType: 'json',
      success: function(data) {
        if(data.status == 200) {
          toastr.success(data.message);
          $('#tableAdmissionSessionDatatable').DataTable().ajax.reload(null, false);
        } else {
          toastr.error(data.message);
        }
      }
    });
  }

  function changeLEStatus(id) {
    $.ajax({
      url: '/app/components/admission-sessions/le_status',
      type: 'GET',
      data: { id: id },
      dataType: 'json',
      success: function(data) {
        if(data.status == 200) {
          toastr.success(data.message);
          $('#tableAdmissionSessionDatatable').DataTable().ajax.reload(null, false);
        } else {
          toastr.error(data.message);
        }
      }
    });
  }

  function changeCTStatus(id) {
    $.ajax({
      url: '/app/components/admission-sessions/ct_status',
      type: 'GET',
      data: { id: id },
      dataType: 'json',
      success: function(data) {
        if(data.status == 200) {
          toastr.success(data.message);
          $('#tableAdmissionSessionDatatable').DataTable().ajax.reload(null, false);
        } else {
          toastr.error(data.message);
        }
      }
    });
  }
</script>

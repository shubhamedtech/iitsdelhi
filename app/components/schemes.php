<div id="accordionDelivery" class="accordion mt-2">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" aria-expanded="true" data-bs-target="#schemes" aria-controls="schemes">Schemes</button>
    </h2>
    <div id="schemes" class="accordion-collapse collapse">
      <div class="accordion-body">
        <div class="card-body">
          <div class="row p-b-20">
            <div class="col-lg-12 text-end">
              <button type="button" class="btn btn-primary mb-3" onclick="addComponents('schemes', 'md', <?= $university_id ?>)">
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
                  <table class="dt-multilingual table table-bordered dataTable no-footer dtr-column ctable" id="tableSchemesDatatable" aria-describedby="DataTables_Table_3_info">
                    <thead>
                      <tr>
                        <th class="sorting_disabled" style="width: 50%;">Name</th>
                        <th class="sorting_disabled text-center" style="width: 30%;">Status</th>
                        <th class="sorting_disabled text-center" style="width: 20%;">Action</th>
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
  </div>
</div>

<script type="text/javascript">
  var table = $('#tableSchemesDatatable');
  var settings = {
    processing: true,
    serverSide: true,
    serverMethod: 'post',
    ajax: {
      url: '/app/components/schemes/server',
      type: 'POST',
      data: function(data) {
        data.university_id = '<?= $university_id ?>';
      },
    },
    columns: [
      { data: "Name" },
      { 
        data: "Status",
        render: function(data, type, row) {
          var active = data == 1 ? 'Active' : 'Inactive';
          var checked = data == 1 ? 'checked' : '';
          return '<div class="form-check form-switch mb-2 d-flex flex-row justify-content-center">\
            <input class="form-check-input me-3" onclick="changeComponentStatus(\'Schemes\', \'Schemes\', \'' + row.ID + '\');" type="checkbox" ' + checked + ' id="scheme-status-switch-' + row.ID + '">\
            <label for="scheme-status-switch-' + row.ID + '">' + active + '</label>\
          </div>';
        }
      },
      { 
        data: "ID",
        render: function(data, type, row) {
          return '<div class="text-end">\
            <i class="ri-edit-box-fill text-success" onclick="editComponents(\'schemes\', \'' + data + '\', \'md\');"></i>\
            <i class="ms-3 ri-delete-bin-5-line text-danger" onclick="destroyComponents(\'schemes\', \'SchemesDatatable\', \'' + data + '\');"></i>\
          </div>';
        }
      }
    ],
    columnDefs: [
      { width: "50%", targets: 0 }, // Column 1 width (Name)
      { width: "30%", targets: 1, className: 'text-center' }, // Column 2 width (Status)
      { width: "20%", targets: 2, className: 'text-center' } // Column 3 width (Action)
    ],
    sDom: "<t><'row'<p i>>",
    destroy: true,
    scrollCollapse: true,
    oLanguage: {
      sLengthMenu: "_MENU_ ",
      sInfo: "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
    },
    aaSorting: [],
    iDisplayLength: 5
  };

  table.dataTable(settings);
</script>

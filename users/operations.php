<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-top.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-bottom.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/side-menu.php') ?>

<div class="layout-page">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/top-menu.php') ?>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
        <ol class="breadcrumb d-flex flex-wrap justify-content-between align-self-start">
                  <?php $breadcrumbs = array_filter(explode("/", $_SERVER['REQUEST_URI']));
                    for($i=1; $i<=count($breadcrumbs); $i++) {
                      if(count($breadcrumbs)==$i): $active = "active";
                        $crumb = explode("?", $breadcrumbs[$i]);
                        echo '<li class="breadcrumb-item '.$active.'">'.strtoupper($crumb[0]).'</li>';
                      endif;
                    }
                  ?>
                  <div class="text-end">
                    <!-- <span class="text-muted bold cursor-pointer" onclick="add('operations','lg')"> Add</sapn> -->
                    <span type="button" class="btn btn-primary"  onclick="add('operations','lg')"> <i class="ri-apps-2-add-line"></i> </span>
                 
                </div>
                </ol>
                <div class="card">
                <!-- <h5 class="card-header text-center text-md-start">Multilingual</h5> -->
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
                                <div id="users-search-table" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="DataTables_Table_3"></label></div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="dt-multilingual table table-bordered  dataTable no-footer dtr-column ctable" id="DataTables_Table_3" aria-describedby="DataTables_Table_3_info">
                                <thead>
                                    <tr>
                                        <th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" style="width: 33.3281px; " aria-label="">LOGO</th>
                                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" style="width: 100px !important;" aria-label="Name: activate to sort column ascending">NAME</th>
                                        <th class="sorting sorting_desc" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" style="width: 154.312px;" aria-sort="descending" aria-label="Position: activate to sort column ascending"> EMPLOYEE ID</th>
                                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" style="width:100px;" aria-label="Email: activate to sort column ascending"> EMAIL</th>
                                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" style="width: 400px!important;" aria-label="Date: activate to sort column ascending">ALLOTED UNIVERSITIES</th>
                                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" style="width: 400px!important;" aria-label="Salary: activate to sort column ascending">PASSWORD</th>
                                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" style="width: 400px!important;" aria-label="Status: activate to sort column ascending">STATUS</th>
                                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_3" rowspan="1" colspan="1" style="width: 400px!important;" aria-label="Status: activate to sort column ascending">ACTION</th>
                                        <!-- <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 138.828px;" aria-label="Actions">Action</th> -->
                                        <!-- <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 138.828px;" aria-label="Actions"></th> -->
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
  $(function(){
    
      var table = $('#DataTables_Table_3');

      var settings = {
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
          'url':'/app/operations/server'
        },
        'columns': [  
          { data: "Photo",
            "render": function(data, type, row){
              return '<span class="thumbnail-wrapper d48 circular inline">\
      					<img src="'+data+'" alt="" data-src="'+data+'"\
      						data-src-retina="'+data+'" width="32" height="32">\
      				</span>';
            }
          },
          { data: "Name",
            "render": function(data, type, row){
              return '<strong>'+data+'</strong>';
            }
          },
          { data: "Code",
            "render": function(data, type, row){
              return '<strong>'+data+'</strong>';
            }
          },
          { data: "Email"},
          { data: "University"},
          { data: "Password",
            "render": function(data, type, row){
              return '<div class="row" style="width:250px !important;">\
                <div class="col-md-10">\
                  <input type="password" class="form-control" disabled="" style="border: 0ch;" value="'+data+'" id="myInput'+row.ID+'">\
                </div>\
                <div class="col-md-2 d-flex align-items-center">\
                   <i class="ri-eye-line pt-2 cursor-pointer" onclick="showPassword('+row.ID+')"></i>\
                </div>\
              </div>';
            }
          },
          { data: "Status",
            "render": function(data, type, row){
              var active = data==1 ? 'Active' : 'Inactive';
              var checked = data==1 ? 'checked' : '';
              return '<div class="form-check form-switch mb-2 d-flex flex-row">\
                <input class="form-check-input" onclick="changeStatus(&#39;Users&#39;, &#39;'+row.ID+'&#39;)" type="checkbox" '+checked+' id="status-switch-'+row.ID+'">\
                <label for="status-switch-'+row.ID+'">'+active+'</label>\
              </div>';
            }
          },
          { data: "ID",
            "render": function(data, type, row){
              return '<div class="button-list text-end">\
              <i class="ri-apps-2-add-line" title="Allot University" onclick="allot(&#39;'+data+'&#39, &#39;sm&#39;)"></i>\
               <i class="ri-edit-box-fill text-success  ms-3"  title="Edit" onclick="edit(&#39;operations&#39;, &#39;'+data+'&#39, &#39;lg&#39;)"></i>\
                  <i class="ms-3 ri-delete-bin-5-line text-danger"title="Delete" onclick="destroy(&#39;operations&#39;, &#39;'+data+'&#39)"></i>\
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
        "iDisplayLength": 25,
        "drawCallback": function( settings ) {
          $('[data-toggle="tooltip"]').tooltip();
        },
      };

      table.dataTable(settings);

      // search box for table
      $('#users-search-table').keyup(function() {
          table.fnFilter($(this).val());
      });
    
  })
</script>

<script>
  function allot(id, modal){
    $.ajax({
      url: '/app/operations/allot-universities?id='+id,
      type: 'GET',
      success: function(data){
        $('#'+modal+'-modal-content').html(data);
        $('#'+modal+'modal').modal('show');
      }
    })
  }
</script>

<script>
  function showPassword(id) {
    var x = document.getElementById("myInput".concat(id));
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }
</script>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-bottom.php') ?>


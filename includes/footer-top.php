<!-- / Content -->
<div class="modal fade" id="smmodal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-hidden="false">
  <div class="modal-dialog modal-sm modal-simple modal-add-new-address modal-dialog-scrollable">
    <div class="modal-content-wrapper">
      <div class="modal-content" id="sm-modal-content">
       
      </div>
    </div>
  </div>
</div>
<div class="modal fade fill-in" id="xlmodal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" id="xl-modal-content">
           
        </div>
    </div>
</div>

<div class="modal fade" id="mdmodal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-hidden="false">
  <div class="modal-dialog modal-md modal-simple modal-add-new-address modal-dialog-scrollable">
    <div class="modal-content-wrapper">
      <div class="modal-content" id="md-modal-content">
       
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="lgmodal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-hidden="false">
  <div class="modal-dialog modal-lg modal-simple modal-add-new-address modal-dialog-scrollable">
    <div class="modal-content-wrapper">
      <div class="modal-content" id="lg-modal-content">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body p-0">

        </div>
      </div>
    </div>
  </div>
</div>



<!-- Footer -->
<footer class="content-footer footer bg-footer-theme">
  <div class="container-xxl ">
    <div class="footer-container d-flex align-items-center justify-content-center py-4 flex-md-row flex-column">
      <div class="text-body mb-2 mb-md-0">
        © <script>
          document.write(new Date().getFullYear())
        </script> All Rights Reserved.

      </div>
     
    </div>
  </div>
</footer>
<!-- / Footer -->


<!-- <div class="content-backdrop fade"></div> -->
</div>
<!-- Content wrapper -->
</div>

</div>
<script src="/assets/vendor/libs/jquery/jquery.js"></script>
<script src="/assets/vendor/libs/popper/popper.js"></script>
<script src="/assets/vendor/js/bootstrap.js"></script>
<script src="/assets/vendor/libs/node-waves/node-waves.js"></script>
<script src="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="/assets/vendor/libs/hammer/hammer.js"></script>
<script src="/assets/vendor/libs/i18n/i18n.js"></script>
<script src="/assets/vendor/libs/typeahead-js/typeahead.js"></script>
<script src="/assets/vendor/js/menu.js"></script>
<script src="/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="/assets/vendor/libs/moment/moment.js"></script>
<script src="/assets/vendor/libs/flatpickr/flatpickr.js"></script>
<script src="/assets/js/main.js"></script>
<script src="/assets/js/tables-datatables-basic.js"></script>
<script src="/assets/vendor/libs/toastr/toastr.js"></script>
<script src="/assets/vendor/libs/jquery-validation/js/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="/assets/vendor/libs/select2/select2.js"></script>
<script src="/assets/js/form-layouts.js"></script>

<script src="/assets/vendor/libs/bs-stepper/bs-stepper.js"></script>
    <script src="/assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    <!-- <script src="/assets/vendor/libs/flatpickr/flatpickr.js"></script> -->
    <!-- <script src="/assets/js/form-validation.js"></script> -->
    <script src="/assets/js/form-wizard-icons.js"></script>

<script type="text/javascript">
  function add(url, modal) {
    $.ajax({
      url: '/app/' + url + '/create',
      type: 'GET',
      success: function(data) {
        $('#' + modal + '-modal-content').html(data);
        $('#' + modal + 'modal').modal('show');
      }
    })
  }
</script>

<script type="text/javascript">
  function upload(url, modal) {
    $.ajax({
      url: '/app/' + url + '/upload',
      type: 'GET',
      success: function(data) {
        $('#' + modal + '-modal-content').html(data);
        $('#' + modal + 'modal').modal('show');
      }
    })
  }
</script>

<script type="text/javascript">
  function edit(url, id, modal) {
    $.ajax({
      url: '/app/' + url + '/edit?id=' + id,
      type: 'GET',
      success: function(data) {
        $('#' + modal + '-modal-content').html(data);
        $('#' + modal + 'modal').modal('show');
      }
    })
  }
</script>

<script type="text/javascript">
function changeStatus(table, id, column = null,status=null) {
        if(status!=null){
            $(".modal").modal('hide');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/app/status/update',
                        type: 'post',
                        data: {
                            table,
                            id,
                            column,
                            status
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.status == 200) {
                              toastr.success( data.message);
                                //var datatable = table == 'Students' ? 'application' : table.toLowerCase();
                                $('#' + table + '-table').DataTable().ajax.reload(null, false);
                            } else {
                              toastr.error( data.message);
                                $('#' + table + '-table').DataTable().ajax.reload(null, false);
                            }
                        }
                    });

                }
            });
        }else{
            $.ajax({
                url: '/app/status/update',
                type: 'post',
                data: {
                    table,
                    id,
                    column,
                    status
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 200) {
                        toastr.success( data.message);
                        //var datatable = table == 'Students' ? 'application' : table.toLowerCase();
                        $('#' + table + '-table').DataTable().ajax.reload(null, false);
                    } else {
                      toastr.error( data.message);
                        $('#' + table + '-table').DataTable().ajax.reload(null, false);
                    }
                }
            });
        }
    }
    
    
    
  function changeStatus_old(table, id, column = null) {
    $.ajax({
      url: '/app/status/update',
      type: 'post',
      data: {
        table,
        id,
        column
      },
      dataType: 'json',
      success: function(data) {
        if (data.status == 200) {
          notification('success', data.message);
          var datatable = table == 'Students' ? 'application' : table.toLowerCase();
          $('#' + datatable + '-table').DataTable().ajax.reload(null, false);;
        } else {
          notification('danger', data.message);
          $('#' + table + '-table').DataTable().ajax.reload(null, false);;
        }
      }
    });
  }
</script>

<script type="text/javascript">
  function destroy(url, id) {
    $(".modal").modal('hide');
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "/app/" + url + "/destroy?id=" + id,
          type: 'DELETE',
          dataType: 'json',
          success: function(data) {
            if (data.status == 200) {
              toastr.success( data.message);
              $('.table').DataTable().ajax.reload(null, false);;
            } else {
              toastr.error( data.message);
            }
          }
        });
      }
    })
  }
</script>

<script type="text/javascript">
  function notification(type, message) {
    $('.page-content-wrapper').pgNotification({
      style: 'flip',
      message: message,
      position: 'top-right',
      timeout: 3000,
      type: type
    }).show();
  }
</script>

<script type="text/javascript">
  function changeUniversity(id) {
    $.ajax({
      url: '/app/login/change-university',
      type: 'POST',
      data: {
        id: id
      },
      dataType: 'json',
      success: function(data) {
        if (data.status == 'success') {
          window.location.reload();
        } else {
          notification('danger', data.message);
        }
      }
    })
  }
</script>

<script type="text/javascript">
  // function changeUniversity() {
  //   $.ajax({
  //     url: '/app/alloted-universities/universities',
  //     type: 'GET',
  //     success: function(data) {
  //       $('#lg-modal-content').html(data);
  //       $('#lgmodal').modal('show');
  //     }
  //   })
  // } 
</script>

<script type="text/javascript">
  function changePassword() {
    $.ajax({
      url: '/app/password/edit',
      type: 'GET',
      success: function(data) {
        $('#md-modal-content').html(data);
        $('#mdmodal').modal('show');
      }
    })
  }
</script>

<script type="text/javascript">
  function getStudentList(id) {
    $.ajax({
      url: '/app/students/student-list',
      type: 'GET',
      success: function(data) {
        $("#" + id).html(data);
      }
    })
  }

  function getCenterList(id) {
    $.ajax({
      url: '/app/students/center-list',
      type: 'GET',
      success: function(data) {
        $("#" + id).html(data);
      }
    })
  }
</script>

<?php if (isset($_SESSION['crm']) && $_SESSION['crm'] != 0) { ?>
  <script type="text/javascript">
    function addQuickLead() {
      $.ajax({
        url: '/app/leads/create_quick',
        type: 'GET',
        success: function(data) {
          $('#md-modal-content').html(data);
          $('#mdmodal').modal('show');
        }
      })
    }
  </script>

  <script type="text/javascript">
    function checkEmail(value, error_id) {
      const university_id = $('#quick_university_id').val();
      if (isEmail(value)) {
        $.ajax({
          url: '/app/leads/check_email?email=' + value + '&university_id=' + university_id,
          type: 'GET',
          dataType: 'JSON',
          success: function(data) {
            if (data.status == 302) {
              $('#' + error_id).html(data.message);
              $(':input[type="submit"]').prop('disabled', true);
            } else {
              $(':input[type="submit"]').prop('disabled', false);
              $('#' + error_id).html('');
            }
          }
        })
      } else {
        $(':input[type="submit"]').prop('disabled', false);
        $('#' + error_id).html('');
      }
    }

    function checkMobile(value, error_id) {
      const university_id = $('#quick_university_id').val();
      if (isMobile(value)) {
        $.ajax({
          url: '/app/leads/check_mobile?mobile=' + value + '&university_id=' + university_id,
          type: 'GET',
          dataType: 'JSON',
          success: function(data) {
            if (data.status == 302) {
              $('#' + error_id).html(data.message);
              $(':input[type="submit"]').prop('disabled', true);
            } else {
              $(':input[type="submit"]').prop('disabled', false);
              $('#' + error_id).html('');
            }
          }
        })
      } else {
        $(':input[type="submit"]').prop('disabled', false);
        $('#' + error_id).html('');
      }
    }

    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      if (regex.test(email)) {
        return true;
      } else {
        return false;
      }
    }

    function isMobile(mobile) {
      var regex = /[1-9]{1}[0-9]{9}/;
      if (regex.test(mobile)) {
        return true;
      } else {
        return false;
      }
    }
  </script>
<?php } ?>

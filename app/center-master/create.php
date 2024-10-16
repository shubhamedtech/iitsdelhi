<!-- Modal -->
<?php require '../../includes/db-config.php'; ?>
<div class="modal-header clearfix text-left m-0 p-0">
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

  <h5 class="fs-4 fw-bold text-black">Add <span class="semi-bold"></span>Center</h5>
</div>
<link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />

<style>
  ::-webkit-scrollbar {
    width: 2px;
  }

  .select2-selection__rendered {
    overflow: auto !important;
    display: flex !important;
    flex-direction: row !important;

  }
</style>

<form class="card-body" role="form" id="form-add-center-master" action="/app/center-master/store" method="POST"
  enctype="multipart/form-data">
  <div class="row mb-4 justify-content-center">
    <div class="col-sm-6 mb-3">
      <label class="col-form-label m-0 p-0" for="multicol-full-name">University Head <span
          class="text-danger">*</span></label>
      <select class="form-select" data-allow-clear="true" id="university_head" name="university_head"
        onchange="get_university(this.value)" required>
            <option value="">Choose University Manager</option>
        <?php
        require '../../includes/db-config.php';
        session_start();
        $session_query = '';
        $selected = '';
        if ($_SESSION['Role'] == "University Head") {
          $session_query = " AND ID = " . $_SESSION['ID'];
          $selected = "selected";
        }
        $get_uni_head = $conn->query("SELECT Users.ID, Users.Name FROM Users WHERE Users.Role = 'University Head' AND Users.Status = 1 $session_query");

        while ($uni_head = $get_uni_head->fetch_assoc()) { ?>
          <option value="<?= $uni_head['ID'] ?>" <?= $selected ?>><?= $uni_head['Name'] ?></option>
        <?php } ?>
      </select>
    </div>
    <div class="col-sm-6 mb-3 select2-primary">
      <label class="col-form-label m-0 p-0" for="multicol-language">
        University<span class="text-danger">*</span>
      </label>


      <div class="position-relative">
        <select id="university_id" class="m_sel select2 university_id  form-select" name="university_id[]"
          data-dropdown-parent="#form-add-center-master" multiple required>
          <option value="">Choose</option>
      
        </select>
      </div>
    </div>
    <div class="col-sm-6 mb-3" id="user-type-div">

      <label class="col-form-label m-0 p-0" for="multicol-full-name">User Type <span
          class="text-danger">*</span></label>
      <select class="form-select" data-allow-clear="true" name="user_type" id="user_type"
        onchange="checkType(this.value)" required>
        <!-- <option value="1">Outsourced</option> -->
        <option value="">Select User Type</option>

        <option value="0">Inhouse</option>
      </select>
    </div>
    <div class="col-sm-6 mb-3" id="center_code_manual">

    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-name">Name <span class="text-danger">*</span></label>
      <input type="text" id="name" name="name" class="form-control" placeholder="ex: Jhon Doe" required>

    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-name">Short Name <span
          class="text-danger">*</span></label>
      <input type="text" id="short_name" name="short_name" class="form-control" placeholder="ex: JD" required>

    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-name">Contact Person Name <span
          class="text-danger">*</span></label>
      <input type="text" id="contact_person_name" name="contact_person_name" class="form-control" placeholder="ex: Jhon"
        required>

    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-name">Email <span class="text-danger">*</span></label>
      <input type="email" id="email" name="email" class="form-control" placeholder="ex: user@example.com" required>

    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-name">Contact <span class="text-danger">*</span></label>
      <input type="tel" id="contact" name="contact" class="form-control" placeholder="ex: 9998777655"
        onkeypress="return isNumberKey(event)" required>

    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-name">Alternate Contact <span
          class="text-danger">*</span></label>
      <input type="tel" id="alternate_contact" name="alternate_contact" class="form-control"
        placeholder="ex: 01202123222" onkeypress="return isNumberKey(event)">

    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-name">Address <span class="text-danger">*</span></label>
      <input type="text" id="address" name="address" class="form-control" placeholder="ex: 23 Street, California"
        required>

    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-name">Pincode <span class="text-danger">*</span></label>
      <input type="tel" id="pincode" name="pincode" maxlength="6" class="form-control" placeholder="ex: 123456"
        onkeypress="return isNumberKey(event)" onkeyup="getRegion(this.value);" required>

    </div>
    <div class="col-sm-4 mb-3" id="user-type-div">

      <label class="col-form-label m-0 p-0" for="multicol-full-name">City <span class="text-danger">*</span></label>
      <select class="form-select" data-allow-clear="true" name="city" id="city" required>
      </select>
    </div>
    <div class="col-sm-4 mb-3" id="user-type-div">
      <label class="col-form-label m-0 p-0" for="multicol-full-name">District <span class="text-danger">*</span></label>
      <select class="form-select" data-allow-clear="true" name="district" id="district" required>

      </select>
    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-name">State <span class="text-danger">*</span></label>
      <input type="text" id="state" name="state" class="form-control" placeholder="ex: California" readonly required>

    </div>
    <div class="col-sm-4 mb-3">
      <label class="col-form-label m-0 p-0" for="multicol-full-name">Photo <span class="text-danger">*</span></label>
      <input class="form-control" type="file" id="photo" multiple="" name="photo"
        accept="image/png, image/jpg, image/jpeg, image/svg" required>
    </div>
  </div>
  <div class="pt-6">
    <div class="row justify-content-end">
      <div class="col-sm-12 text-end">
        <button type="submit" class="btn btn-primary me-4">Add</button>
        <button type="button" data-bs-dismiss="modal" aria-label="Close"
          class="btn btn-outline-secondary">Cancel</button>
      </div>
    </div>
  </div>
</form>
<script>
  $(document).ready(function () {
    $('.select2').select2({
      placeholder: "Select an option",
      dropdownParent: $('#form-add-center-master'),
      width: 'style'
    }).on('select2:select', function () {
      $(this).select2('close');
    });
  });
</script>
<script>
  $(function () {
    $(".university_id").select2({
      placeholder: 'Choose University'
    });
    $(".university_head").select2({
      placeholder: 'Choose University Manager'
    })

    $('#form-add-center-master').validate({
      rules: {
        university_head: {
          required: true
        },
        user_type: {
          required: true
        },
        university_id: {
          required: true
        },
        name: {
          required: true
        },
        short_name: {
          required: true
        },
        contact_person_name: {
          required: true
        },
        email: {
          required: true
        },
        contact: {
          required: true
        },
        address: {
          required: true
        },
        pincode: {
          required: true
        },
        city: {
          required: true
        },
        district: {
          required: true
        },
        state: {
          required: true
        },
      },
      highlight: function (element) {
        $(element).addClass('error');
        $(element).closest('.form-control').addClass('has-error');
      },
      unhighlight: function (element) {
        $(element).removeClass('error');
        $(element).closest('.form-control').removeClass('has-error');
      }
    });
  })

  function getRegion(pincode) {
    if (pincode.length == 6) {
      $.ajax({
        url: '/app/regions/cities?pincode=' + pincode,
        type: 'GET',
        success: function (data) {
          $('#city').html(data);
        }
      });

      $.ajax({
        url: '/app/regions/districts?pincode=' + pincode,
        type: 'GET',
        success: function (data) {
          $('#district').html(data);
        }
      });

      $.ajax({
        url: '/app/regions/state?pincode=' + pincode,
        type: 'GET',
        success: function (data) {
          $('#state').val(data);
        }
      })
    }
  }

  function checkType(value) {
    $('#center_code_manual').html('');
    if (value == 0) {
      $('#user-type-div').removeClass('col-md-12');
      $('#user-type-div').addClass('col-md-6');
      $('#center_code_manual').addClass('col-md-6');
      $('#center_code_manual').html('<div class="form-group form-group-default required">\
          <label>Code</label>\
          <input type="text" name="center_code" class="form-control" placeholder="ex: 0001" required>\
        </div>');
    } else {
      $('#user-type-div').removeClass('col-md-6');
      $('#user-type-div').addClass('col-md-12');
    }
  }

  $("#form-add-center-master").on("submit", function (e) {
    if ($('#form-add-center-master').valid()) {
      $(':input[type="submit"]').prop('disabled', true);
      var formData = new FormData(this);
      $.ajax({
        url: this.action,
        type: 'post',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (data) {
          if (data.status == 200) {
            $('.modal').modal('hide');
            toastr.success(data.message);
            $('#DataTables_Table_3').DataTable().ajax.reload(null, false);
          } else {
            $(':input[type="submit"]').prop('disabled', false);
            toastr.error(data.message);
          }
        }
      });
      e.preventDefault();
    }
  });

  function get_university(head_id) {

    $.ajax({
      url: '/app/center-master/get-university',
      type: 'POST',
      dataType: 'text',
      data: {
        'head_id': head_id,
      },
      success: function (data) {
        $('.university_id').html(data);
        <?php if (!empty($user['District'])) { ?>
          $('.university_id').val('<?= $user['District'] ?>');
        <?php } ?>
      }
    });
  }
</script>
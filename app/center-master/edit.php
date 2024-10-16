<?php
if (isset($_GET['id'])) {
  require '../../includes/db-config.php';
  session_start();
  $id = intval($_GET['id']);
  $user = $conn->query("SELECT Name, Short_Name, Contact_Name,Photo, Code, Email, Mobile, Alternate_Mobile, Address, Pincode, State, City, District,Vertical_type,University_ID FROM Users WHERE ID = $id");
  $user = mysqli_fetch_assoc($user);
  // echo('<pre>');print_r($user);die;
  $user['University_ID'] = explode(',', $user['University_ID']);
  // echo "SELECT Counsellor_ID, University_ID FROM Alloted_Center_To_Counsellor WHERE Code = $id ";
  $get_selected_uni_head = $conn->query("SELECT Counsellor_ID, University_ID FROM Alloted_Center_To_Counsellor WHERE Code = $id group by Counsellor_ID ");
  $get_selected_uni_head_arr = mysqli_fetch_assoc($get_selected_uni_head);
  $uni_id = isset($get_selected_uni_head_arr['Counsellor_ID']) ? $get_selected_uni_head_arr['Counsellor_ID'] : "";

}
?>
<!-- Modal -->
<link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
<style>
    /* .select2-selection {
    width: 230px !important;
  }

  .select2-container {
    width: auto !important;
    min-width: 100px;
  }

  .select2-dropdown {
    width: auto !important;
    min-width: 100px !important;
  }

  input.select2-search__field {
    width: 100% !important;
  } */
  ::-webkit-scrollbar {
    width: 2px;
  }

  .select2-selection__rendered {
    overflow: auto !important;
    display: flex !important;
    flex-direction: row !important;
    /* width: 206px !important; */
  }

  /* .light-style .select2-container--default .select2-selection--multiple {
    min-height: 0px !important;
    padding: 0px !important;
} */
</style>
<div class="modal-header clearfix text-left m-0 p-0">
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

  <h5 class="fs-4 text-black fw-bold">Edit <span class="semi-bold"></span>Center</h5>
</div>

<form class="card-body" role="form" id="form-edit-center-master" action="/app/center-master/update" method="POST"
  enctype="multipart/form-data">
  <div class="row mb-4 justify-content-center">
    <div class="col-sm-6 mb-3">
      <label class="col-form-label m-0 p-0" for="university_head">University Head <span
          class="text-danger">*</span></label>
      <select class="form-select" data-allow-clear="true" name="university_head" onchange="get_university(this.value)"
        required>
        <option value="">Choose</option>
        <?php

        $session_query = '';

        if ($_SESSION['Role'] == "University Head") {
          $session_query = " AND ID = " . $_SESSION['ID'];

        }
        $get_uni_head = $conn->query("SELECT Users.ID, Users.Name FROM Users WHERE Users.Role = 'University Head' AND Users.Status = 1 $session_query ");

        while ($uni_head = $get_uni_head->fetch_assoc()) { ?>
          <option value="<?= $uni_head['ID'] ?>" <?php if ($uni_head['ID'] == $uni_id) {
              echo 'selected';
            } else {
              '';
            } ?>>
            <?= $uni_head['Name'] ?></option>
        <?php } ?>
      </select>
    </div>
    <div class="col-sm-6 mb-2 select2-primary">
      <label class="col-form-label m-0 p-0" for="multicol-language">
        University<span class="text-danger">*</span>
      </label>

      <div class="position-relative">
        <select id="multicol-language" class="m-0 p-0 m_sel select2 university_id  form-select" name="university_id[]" multiple
          required>
          <option value="">Choose</option>
          <?php
          $universities = $conn->query("SELECT ID, CONCAT(Universities.Short_Name, ' (', Universities.Vertical, ')') as Name FROM Universities WHERE status=1");
          while ($university = $universities->fetch_assoc()) { ?>
            <option value="<?= $university['ID'] ?>" <?php print !empty($user['University_ID']) ? (in_array($university['ID'], $user['University_ID']) ? 'selected' : '') : '' ?>><?= $university['Name'] ?>
            </option>
          <?php } ?>
        </select>
      </div>
    </div>
    <!--   
    <div id="center_code_manual">

    </div> -->
    <div class="col-sm-6 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-name">Name <span class="text-danger">*</span></label>
      <input type="text" id="name" name="name" class="form-control" placeholder="ex: Jhon Doe"
        value="<?= $user['Name'] ?>" required>

    </div>
    <div class="col-sm-6 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-short_name">Short Name <span
          class="text-danger">*</span></label>
      <input type="text" id="short_name" name="short_name" class="form-control" placeholder="ex: JD"
        value="<?= $user['Short_Name'] ?>" required>

    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="contact_person_name">Contact Person Name <span
          class="text-danger">*</span></label>
      <input type="text" id="contact_person_name" name="contact_person_name" class="form-control" placeholder="ex: Jhon"
        value="<?= $user['Contact_Name'] ?>" required>

    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-email">Email <span class="text-danger">*</span></label>
      <input type="email" id="email" name="email" class="form-control" placeholder="ex: user@example.com"
        value="<?= $user['Email'] ?>" required>

    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-contact">Contact <span
          class="text-danger">*</span></label>
      <input type="tel" id="contact" name="contact" class="form-control" placeholder="ex: 9998777655"
        onkeypress="return isNumberKey(event)" value="<?= $user['Mobile'] ?>" required>

    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="alternate_contact">Alternate Contact <span
          class="text-danger">*</span></label>
      <input type="tel" id="alternate_contact" name="alternate_contact" class="form-control"
        placeholder="ex: 9998777656" onkeypress="return isNumberKey(event)" value="<?= $user['Alternate_Mobile'] ?>"
        required>

    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-address">Address <span
          class="text-danger">*</span></label>
      <input type="text" id="address" name="address" class="form-control" placeholder="ex: 23 Street, California"
        value="<?= $user['Address'] ?>" required>

    </div>
    <div class="col-sm-4 mb-3">
      <label class=" col-form-label m-0 p-0" for="multicol-full-pincode">Pincode <span
          class="text-danger">*</span></label>
      <input type="tel" id="pincode" name="pincode" maxlength="6" class="form-control" placeholder="ex: 123456"
        onkeypress="return isNumberKey(event)" onkeyup="getRegion(this.value);" value="<?= $user['Pincode'] ?>" required>

    </div>
    <div class="col-sm-4 mb-3" id="user-type-div">
      <label class="col-form-label m-0 p-0" for="multicol-full-city">City <span class="text-danger">*</span></label>
      <select class="form-select" data-allow-clear="true" name="city" id="city" required>
      </select>
    </div>
    <div class="col-sm-4 mb-3" id="user-type-div">
      <label class="col-form-label m-0 p-0" for="multicol-full-district">District <span
          class="text-danger">*</span></label>
      <select class="form-select" data-allow-clear="true" name="district" id="district" required>

      </select>
    </div>
    <div class="col-sm-4 mb-3">
      <label class="m-0 p-0 col-form-label" for="multicol-full-state">State <span class="text-danger">*</span></label>
      <input type="text" id="state" name="state" class="form-control" placeholder="ex: California" readonly required>

    </div>
    <div class="col-sm-12 mb-3">
      <?php if (!empty($id) && !empty($user['Photo'])) {
        $photo_required = "";
      } else {
        $photo_required = "required";
      } ?>
      <label class="col-form-label m-0 p-0" for="multicol-full-photo">Photo <span class="text-danger">*</span></label>
      <input class="form-control" type="file" id="photo" multiple="" name="photo"
        accept="image/png, image/jpg, image/jpeg, image/svg" <?= $photo_required ?>>

      <img src="<?= $user['Photo'] ?>" alt="" width="100" height="100">
    </div>
  </div>
  <div class="pt-6">
    <div class="row justify-content-end">
      <div class="col-sm-12 text-end">
        <button type="submit" class="btn btn-primary me-4">Update</button>
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
      width: '50%',
      dropdownParent: $('.modal')
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
    $('#form-edit-center-master').validate({
      rules: {
        name: { required: true },
        short_name: { required: true },
        contact_person_name: { required: true },
        email: { required: true },
        contact: { required: true },
        address: { required: true },
        pincode: { required: true },
        city: { required: true },
        district: { required: true },
        state: { required: true },
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
          <?php if (!empty($user['City'])) { ?>
            $('#city').val('<?= $user['City'] ?>');
          <?php } ?>
        }
      });

      $.ajax({
        url: '/app/regions/districts?pincode=' + pincode,
        type: 'GET',
        success: function (data) {
          $('#district').html(data);
          <?php if (!empty($user['District'])) { ?>
            $('#district').val('<?= $user['District'] ?>');
          <?php } ?>
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

  <?php if (!empty($user['Pincode'])) { ?>
    getRegion('<?= $user['Pincode'] ?>');
  <?php } ?>

  $("#form-edit-center-master").on("submit", function (e) {
    if ($('#form-edit-center-master').valid()) {
      $(':input[type="submit"]').prop('disabled', true);
      var formData = new FormData(this);
      formData.append('id', '<?= $id ?>');
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
    // var head_id = Array.from(selectElement.selectedOptions, option => option.value);

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
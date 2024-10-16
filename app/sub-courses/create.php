<!-- Modal -->
<div class="modal-header clearfix text-left m-0 p-0">
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

  <h5 class="fs-4 fw-bold text-black">Add <span class="semi-bold">Specialization</span></h5>
</div>
<style>
  .select2-selection {
    width: 230px !important;
  }

  .select2-container {
    width: auto !important;
    /* min-width: 100px; */
  }

  .select2-dropdown {
    width: auto !important;
    min-width: 100px !important;
  }
</style>
<?php
require '../../includes/db-config.php';
session_start();
?>
<!-- <form role="form" id="form-add-sub-course" action="/app/sub-courses/store" method="POST" enctype="multipart/form-data">
  <div class="modal-body">
    
    <div class="row">
      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>University</label>
          <select class="full-width" style="border: transparent;" id="university_id" name="university_id" onchange="getDetails(this.value);">
            <option value="">Choose</option>
            <?php
            require '../../includes/db-config.php';
            session_start();
            $university_query = $_SESSION['Role'] != 'Administrator' ? " AND ID =" . $_SESSION['university_id'] : '';
            $universities = $conn->query("SELECT ID, CONCAT(Universities.Short_Name, ' (', Universities.Vertical, ')') as Name FROM Universities WHERE ID IS NOT NULL $university_query");
            while ($university = $universities->fetch_assoc()) { ?>
              <option value="<?= $university['ID'] ?>"><?= $university['Name'] ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Program</label>
          <select class="full-width" style="border: transparent;" id="course" name="course">
            <option value="">Choose</option>

          </select>
        </div>
      </div>
    </div>

   
    <div class=" row">
      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Name</label>
          <input type="text" name="name" class="form-control" placeholder="ex: Mechanical Engineering" required>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Short Name</label>
          <input type="text" name="short_name" class="form-control" placeholder="ex: ME" required>
        </div>
      </div>
    </div>

   
    <div class="row">
      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Scheme</label>
          <select class="full-width" style="border: transparent;" id="scheme" name="scheme">

          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Mode</label>
          <select class="full-width" style="border: transparent;" id="mode" name="mode" onchange="getFeeSructures()">

          </select>
        </div>
      </div>
    </div>

    
    <?php $eligibilities = array("High School", "Intermediate", "UG", "PG", "Other"); ?>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group form-group-default form-group-default-select2 required">
          <label style="z-index:9999">Academic Eligibility</label>
          <select class=" full-width" data-init-plugin="select2" id="eligibilities" name="eligibilities[]" multiple>
            <?php foreach ($eligibilities as $eligibility) { ?>
              <option value="<?= $eligibility ?>"><?= $eligibility ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>

   
    <div class="row">
      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Min Duration</label>
          <input type="tel" name="min_duration[]" id="min_duration" class="form-control" placeholder="ex: 8" onkeypress="return isNumberKey(event)" onkeyup="getFeeSructures()" required>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Max Duration</label>
          <input type="tel" name="max_duration" class="form-control" placeholder="ex: 8" onkeypress="return isNumberKey(event)" required>
        </div>
      </div>
    </div>

    <div id="fee">
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="form-group form-group-default required">
          <label>Lateral</label>
          <select class="full-width" style="border: transparent;" id="lateral" name="lateral">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </select>
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group form-group-default">
          <label>LE Start</label>
          <input type="text" id="le_start" name="le_start" class="form-control" placeholder="ex: 3,5">
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group form-group-default">
          <label>LE SOL</label>
          <input type="tel" id="le_sol" name="le_sol" class="form-control" placeholder="ex: 8" onkeypress="return isNumberKey(event)">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="form-group form-group-default required">
          <label>Credit Transfer</label>
          <select class="full-width" style="border: transparent;" id="ct_transfer" name="ct_transfer">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </select>
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group form-group-default">
          <label>CT Start</label>
          <input type="tel" id="ct_start" name="ct_start" class="form-control" placeholder="ex: 3" onkeypress="return isNumberKey(event)">
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group form-group-default">
          <label>CT SOL</label>
          <input type="tel" id="ct_sol" name="ct_sol" class="form-control" placeholder="ex: 8" onkeypress="return isNumberKey(event)">
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer clearfix text-end">
    <div class="col-md-4 m-t-10 sm-m-t-10">
      <button aria-label="" type="submit" class="btn btn-primary btn-cons btn-animated from-left">
        <span>Save</span>
        <span class="hidden-block">
          <i class="pg-icon">tick</i>
        </span>
      </button>
    </div>
  </div>
</form> -->

<form class="card-body" role="form" id="form-add-sub-course" action="/app/sub-courses/store" method="POST" enctype="multipart/form-data">
  <div class="row mb-4">
    <div class="col-sm-12 mb-3">
      <label class="m-0 p-0 col-form-label" for="multicol-country">University <span class="text-danger">*</span></label>
      <select class=" form-select" data-allow-clear="true" id="university_id" name="university_id" onchange="getDetails(this.value);">
        <option value="">Choose</option>
        <?php
        require '../../includes/db-config.php';
        session_start();
        $university_query = $_SESSION['Role'] != 'Administrator' ? " AND ID =" . $_SESSION['university_id'] : '';
        $universities = $conn->query("SELECT ID, CONCAT(Universities.Short_Name, ' (', Universities.Vertical, ')') as Name FROM Universities WHERE ID IS NOT NULL $university_query");
        while ($university = $universities->fetch_assoc()) { ?>
          <option value="<?= $university['ID'] ?>"><?= $university['Name'] ?></option>
        <?php } ?>
      </select>
    </div>
    <div class="col-sm-4 mb-3">
      <label class=" m-0 p-0 col-form-label" for="multicol-country">Program </label>
      <select class=" form-select" data-allow-clear="true" id="course" name="course">
        <option value="">Choose</option>

      </select>
    </div>
    <div class="col-sm-4 mb-3">
      <label class="m-0 p-0 col-form-label" for="multicol-country">Name </label>
      <input type="text" id="multicol-full-name" name="name" class="form-control" placeholder="ex: Mechanical Engineering" required />

    </div>
    <div class="col-sm-4 mb-3">
      <label class="m-0 p-0 col-form-label" for="multicol-full-name">Short Name <span class="text-danger">*</span></label>

      <input type="text" id="multicol-full-name" name="short_name" class="form-control" placeholder="ex: ME" required />
    </div>
    <div class="col-sm-4 mb-3">
      <label class="m-0 p-0 col-form-label" for="multicol-full-name">Scheme <span class="text-danger">*</span></label>
      <select class=" form-select" data-allow-clear="true" id="scheme" name="scheme">
        <option value="">Choose</option>

      </select>
    </div>
    <div class="col-sm-4 mb-3">
      <label class="m-0 p-0 col-form-label" for="multicol-full-name">Mode <span class="text-danger">*</span></label>
      <select class=" form-select" data-allow-clear="true" id="mode" name="mode" onchange="getFeeSructures()">
        <option value="">Choose</option>

      </select>
    </div>
    <?php $eligibilities = array("High School", "Intermediate", "UG", "PG", "Other"); ?>
    <div class="col-sm-4 mb-3 select2-primary">
      <label class="m-0 p-0 col-form-label" for="multicol-language">
        Academic Eligibility <span class="text-danger">*</span>
      </label>
      <div class="position-relative">
        <select id="multicol-language" class="m_sel select2  form-select" name="eligibilities[]" multiple>
          <option value="">Choose</option>

          <?php foreach ($eligibilities as $eligibility) { ?>
            <option value="<?= htmlspecialchars($eligibility) ?>"><?= htmlspecialchars($eligibility) ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="col-sm-4 mb-3">
      <label class="m-0 p-0 col-form-label" for="multicol-full-name">Min Duration <span class="text-danger">*</span></label>

      <input type="tel" name="min_duration[]" id="min_duration" class="form-control" placeholder="ex: 8" onkeypress="return isNumberKey(event)" onkeyup="getFeeSructures()" required />
    </div>
    <div class="col-sm-4 mb-3">
      <label class="m-0 p-0 col-form-label" for="multicol-full-name">Max Duration<span class="text-danger">*</span></label>

      <input type="tel" name="max_duration" class="form-control" placeholder="ex: 8" onkeypress="return isNumberKey(event)" required>
    </div>
    <!-- <div id="fee">
    </div> -->
    <div class="col-sm-4 mb-3">
      <label class="m-0 p-0 col-form-label" for="multicol-country">Lateral </label>
      <select class=" form-select" data-allow-clear="true" id="lateral" name="lateral">
        <option value="">Choose</option>

        <option value="0">No</option>
        <option value="1">Yes</option>
      </select>

    </div>
    <div class="col-sm-4 mb-3">
      <label class="m-0 p-0 col-form-label" for="multicol-full-name">LE Start <span class="text-danger">*</span></label>

      <input type="text" id="le_start" name="le_start" class="form-control" placeholder="ex: 3,5" onkeypress="return isNumberKey(event)" />
    </div>
    <div class="col-sm-4 mb-3">
      <label class="m-0 p-0 col-form-label" for="multicol-country">LE SOL </label>
      <!-- <input type="tel" id="le_sol" name="le_sol" class="form-control" placeholder="ex: 8" onkeypress="return isNumberKey(event)"> -->
      <input type="text" id="le_sol" name="le_sol" class="form-control" placeholder="ex: 8" onkeypress="return isNumberKey(event)" />

    </div>
    <div class="col-sm-4 mb-3">
      <label class="m-0 p-0 col-form-label" for="multicol-country">Credit Transfer </label>
      <select class=" form-select" data-allow-clear="true" id="ct_transfer" name="ct_transfer">
        <option value="">Choose</option>

        <option value="0">No</option>
        <option value="1">Yes</option>
      </select>
    </div>

    <div class="col-sm-4 mb-3">
      <label class=" m-0 p-0 col-form-label" for="multicol-country">CT Start</label>
      <input type="tel" id="ct_start" name="ct_start" class="form-control" placeholder="ex: 3" onkeypress="return isNumberKey(event)" />

    </div>
    <div class="col-sm-4 mb-3">
      <label class="m-0 p-0 col-form-label" for="multicol-country">CT SOL</label>
      <input type="tel" id="ct_sol" name="ct_sol" class="form-control" placeholder="ex: 8" onkeypress="return isNumberKey(event)" />

    </div>
    <div id="fee">
    </div>
  </div>
  <div class="pt-6">
    <div class="row ">
      <div class="col-sm-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary me-4">ADD</button>
        <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-outline-secondary">Cancel</button>
      </div>
    </div>
  </div>
</form>

<script>
  $(document).ready(function() {
    $('.select2').select2({
      placeholder: "Select an option",
      dropdownParent: $('.modal'),
      width: 'auto', // Set width to auto
      dropdownAutoWidth: true // Enable auto width for the dropdown
    }).on('select2:select', function() {
      $(this).select2('close');
    });
  });
</script>
<script>
  $(function() {
    $("#eligibilities").select2();
    // $("#duractions").select2();
    $("#course_category").select2();
  })

  function getDetails(id) {

    $.ajax({
      url: '/app/sub-courses/courses?id=' + id,
      type: 'GET',
      success: function(data) {
        $('#course').html(data);
      }
    });

    $.ajax({
      url: '/app/sub-courses/schemes?id=' + id,
      type: 'GET',
      success: function(data) {
        $('#scheme').html(data);
      }
    });

    $.ajax({
      url: '/app/sub-courses/modes?id=' + id,
      type: 'GET',
      success: function(data) {
        $('#mode').html(data);
      }
    });
  }

  function getFeeSructures() {
    const durations = $('#min_duration').val();
    const university_id = $('#university_id').val();
    const mode = $('#mode').val();
    $.ajax({
      url: '/app/sub-courses/fee-structures?durations=' + durations + '&university_id=' + university_id + '&mode=' + mode,
      type: 'GET',
      success: function(data) {
        $('#fee').html(data);
      }
    });
  }

  $(function() {
    $('#form-add-sub-course').validate({
      rules: {
        name: {
          required: true
        },
        short_name: {
          required: true
        },
        university_id: {
          required: true
        },
        course: {
          required: true
        },
        scheme: {
          required: true
        },
        mode: {
          required: true
        },
        lateral: {
          required: true
        },
        ct_transfer: {
          required: true
        },
        'eligibilities[]': {
          required: true
        }
      },
      highlight: function(element) {
        $(element).addClass('error');
        $(element).closest('.form-control').addClass('has-error');
      },
      unhighlight: function(element) {
        $(element).removeClass('error');
        $(element).closest('.form-control').removeClass('has-error');
      }
    });
  })

  $("#form-add-sub-course").on("submit", function(e) {
    if ($('#form-add-sub-course').valid()) {
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
        success: function(data) {
          if (data.status == 200) {
            $('.modal').modal('hide');
            toastr.succes(data.message);
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

  function chooseDuration(selectElement) {
    var selectedValues = Array.from(selectElement.selectedOptions, option => option.value);
    $('#set_course_category').removeClass('d-none');

    // Mapping of conditions to HTML content
    var conditions = [{
        values: ['certification', 'certified', 'advance_diploma'],
        content: '<option value="3">3 Months</option><option value="6">6 Months</option><option value="11/certified">11 Months Certified</option><option value="11/advance-diploma">11 Months Advance Diploma</option>'
      },
      {
        values: ['certification', 'certified'],
        content: '<option value="3">3 Months</option><option value="6">6 Months</option><option value="11/certified">11 Months Certified</option>'
      },
      {
        values: ['certification', 'advance_diploma'],
        content: '<option value="3">3 Months</option><option value="11/advance-diploma">11 Months Advance Diploma</option>'
      },
      {
        values: ['certified', 'advance_diploma'],
        content: '<option value="6">6 Months</option><option value="11/certified">11 Months Certified</option><option value="11/advance-diploma">11 Months Advance Diploma</option>'
      },
      {
        values: ['advance_diploma'],
        content: '<option value="6" selected>6 Months'
      },
      {
        values: ['certified'],
        content: '<option value="6" selected >6 Months</option><option value="11/advance-diploma">11 Months Certified</option>'
      },
      {
        values: ['certification'],
        content: '<option value="3" selected>3 Months</option>'
      }
    ];

    // Find the first condition that matches
    var matchedCondition = conditions.find(condition => condition.values.every(value => selectedValues.includes(value)));

    // Apply the matched HTML content
    $('#set_course_category').html('<div class="form-group form-group-default form-group-default-select2 required">\
        <label style="z-index:9999">Durations</label>\
        <select class="full-width" data-init-plugin="select2" id="duractions" name="duractions[]" multiple>\
            ' + matchedCondition.content + '\
        </select>\
    </div>');

    $("#duractions").select2();
  }
</script>
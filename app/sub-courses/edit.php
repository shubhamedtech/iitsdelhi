<?php
if (isset($_GET['id'])) {
  require '../../includes/db-config.php';
  session_start();

  $id = intval($_GET['id']);
  $sub_course = $conn->query("SELECT * FROM Sub_Courses WHERE id = $id");
  $sub_course = mysqli_fetch_assoc($sub_course);
  // print_r($sub_course);die;
?>
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
  <link href="../../assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" media="screen" />
  <link href="../../assets/plugins/bootstrap-tag/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
  <!-- Modal -->
  <div class="modal-header clearfix text-left m-0 p-0">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

    <h5 class="fs-4 text-black fw-bold">Edit <span class="semi-bold">Specialization</span></h5>
  </div>


  <form class="card-body" role="form" id="form-edit-sub-course" action="/app/sub-courses/update" method="POST" enctype="multipart/form-data">
    <div class="row mb-4">
      <div class="col-sm-12 mb-3">
        <label class=" m-0 p-0 col-form-label" for="multicol-country">University <span class="text-danger">*</span></label>
        <select class=" form-select" data-allow-clear="true" id="university_id" name="university_id" onchange="getDetails(this.value);">
          <option value="">Choose</option>
          <?php
          $university_query = $_SESSION['Role'] != 'Administrator' ? " AND ID =" . $_SESSION['university_id'] : '';
          $universities = $conn->query("SELECT ID, CONCAT(Universities.Short_Name, ' (', Universities.Vertical, ')') as Name FROM Universities WHERE ID IS NOT NULL $university_query");
          while ($university = $universities->fetch_assoc()) { ?>
            <option value="<?= $university['ID'] ?>" <?php print $university['ID'] == $sub_course['University_ID'] ? 'selected' : '' ?>><?= $university['Name'] ?></option>
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
        <label class=" m-0 p-0 col-form-label" for="multicol-country">Name </label>
        <input type="text" id="name" name="name" class="form-control" placeholder="ex: Mechanical Engineering" value="<?= $sub_course['Name'] ?>" />

      </div>
      <div class="col-sm-4 mb-3">
        <label class=" m-0 p-0col-form-label" for="multicol-full-name">Short Name <span class="text-danger">*</span></label>

        <input type="text" id="short_name" name="short_name" class="form-control" placeholder="ex: ME" value="<?= $sub_course['Short_Name'] ?>" />
      </div>
      <div class="col-sm-4 mb-3">
        <label class=" m-0 p-0col-form-label" for="multicol-full-name">Scheme <span class="text-danger">*</span></label>
        <select class=" form-select" data-allow-clear="true" id="scheme" name="scheme">

        </select>
      </div>
      <div class="col-sm-4 mb-3">
        <label class=" m-0 p-0col-form-label" for="multicol-full-name">Mode <span class="text-danger">*</span></label>
        <select class=" form-select" data-allow-clear="true" id="mode" name="mode" onchange="getFeeSructures()">

        </select>
      </div>
      <?php $eligibilities = array("High School", "Intermediate", "UG", "PG", "Other");
      $selected_eligibility = !empty($sub_course['Eligibility']) ? json_decode($sub_course['Eligibility'], true) : [];
      $sub_course['Min_Duration'] = isset($sub_course['Min_Duration']) ? json_decode($sub_course['Min_Duration'], true) : [];

      ?>
      <div class="col-sm-4 mb-3 select2-primary">
        <!-- <label for="multicol-language" class="text-black" style="z-index:9999">Academic Eligibility</label> -->
        <label class=" m-0 p-0col-form-label" for="multicol-full-name">Academic Eligibility <span class="text-danger">*</span></label>

        <div class="position-relative">
          <select class="m_sel select2 form-select" id="eligibilities" name="eligibilities[]" multiple>
            <?php foreach ($eligibilities as $eligibility) { ?>
              <option value="<?= $eligibility ?>" <?php echo in_array($eligibility, $selected_eligibility) ? 'selected' : '' ?>><?= $eligibility ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="col-sm-4 mb-3">
        <label class=" m-0 p-0col-form-label" for="multicol-full-name">Min Duration <span class="text-danger">*</span></label>

        <input type="tel" name="min_duration" id="min_duration" class="form-control" value="<?= $sub_course['Min_Duration'] ?>" onchange="getFeeSructures()" />
      </div>
      <div class="col-sm-4 mb-3">
        <label class=" m-0 p-0col-form-label" for="max_duration">Max Duration<span class="text-danger">*</span></label>

        <input type="tel" name="max_duration" id="max_duration" class="form-control" placeholder="ex: 8" onkeypress="return isNumberKey(event)" value="<?= $sub_course['SOL'] ?>">
      </div>
      <div id="fee">
      </div>
      <div class="col-sm-4 mb-3">
        <label class=" m-0 p-0 col-form-label" for="multicol-country">Lateral </label>
        <select class="select2 form-select" data-allow-clear="true" id="lateral" name="lateral">
          <option value="0" <?php print $sub_course['Lateral'] == 0 ? 'selected' : '' ?>>No</option>
          <option value="1" <?php print $sub_course['Lateral'] == 1 ? 'selected' : '' ?>>Yes</option>
        </select>

      </div>
      <div class="col-sm-4 mb-3">
        <label class=" m-0 p-0col-form-label" for="multicol-full-name">LE Start <span class="text-danger">*</span></label>

        <input type="text" id="le_start" name="le_start" class="form-control" placeholder="ex: 3,5" value="<?php print $sub_course['LE_Start'] == '' ? '' : $sub_course['LE_Start'] ?>" onkeypress="return isNumberKey(event)" />
      </div>

      <div class="col-sm-4 mb-3">
        <label class=" m-0 p-0 col-form-label" for="multicol-country">LE SQL </label>
        <input type="text" id="le_sol" name="le_sol" class="form-control" placeholder="ex: 8" onkeypress="return isNumberKey(event)" value="<?php print $sub_course['LE_SOL'] == 0 ? '' : $sub_course['LE_SOL'] ?>" />

      </div>
      <div class="col-sm-4 mb-3">
        <label class=" m-0 p-0 col-form-label" for="multicol-country">Credit Transfer </label>
        <select class="select2 form-select" data-allow-clear="true" id="ct_transfer" name="ct_transfer">
          <option value="0" <?php print $sub_course['Credit_Transfer'] == 0 ? 'selected' : '' ?>>No</option>
          <option value="1" <?php print $sub_course['Credit_Transfer'] == 1 ? 'selected' : '' ?>>Yes</option>
        </select>
      </div>
      <div class="col-sm-4 mb-3">
        <label class=" m-0 p-0 col-form-label" for="multicol-country">CT Start</label>
        <input type="tel" id="ct_start" name="ct_start" class="form-control" placeholder="ex: 3" onkeypress="return isNumberKey(event)" value="<?php print $sub_course['CT_Start'] == 0 ? '' : $sub_course['CT_Start'] ?>" />

      </div>
      <div class="col-sm-4 mb-3">
        <label class=" m-0 p-0 col-form-label" for="multicol-country">CT SOL</label>
        <input type="tel" id="ct_sol" name="ct_sol" class="form-control" placeholder="ex: 8" onkeypress="return isNumberKey(event)" value="<?php print $sub_course['CT_SOL'] == 0 ? '' : $sub_course['CT_SOL'] ?>" />

      </div>
    </div>
    <div class="pt-6">
      <div class="row ">
        <div class="col-sm-12 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary me-4">Update</button>
          <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-outline-secondary">Cancel</button>
        </div>
      </div>
    </div>
  </form>
  <script type="text/javascript" src="../../assets/plugins/select2/js/select2.full.min.js"></script>
  <script src="/assets/js/form-validation.js"></script>

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


    $(function() {
      $("#eligibilities").select2({
        placeholder: "Select an option",
        dropdownParent: $('.modal'),
        width: 'style'
      }).on('select2:select', function() {
        $(this).select2('close');
        $('.select2-results').css('display', 'none');
      });

      $("#course_category").select2({
        placeholder: "Select an option",
        dropdownParent: $('.modal'),
        width: 'style'
      }).on('select2:select', function() {
        $(this).select2('close');
        $('.select2-results').css('display', 'none');
      });
      $(".min_duration_skill").select2({
        placeholder: "Select an option",
        dropdownParent: $('.modal'),
        width: 'style'
      }).on('select2:select', function() {
        $(this).select2('close');
        $('.select2-results').css('display', 'none');
      });
    })

    function getDetails(id) {

      $.ajax({
        url: '/app/sub-courses/courses?id=' + id,
        type: 'GET',
        success: function(data) {
          $('#course').html(data);
          $('#course').val(<?= $sub_course['Course_ID'] ?>);
        }
      });

      $.ajax({
        url: '/app/sub-courses/schemes?id=' + id,
        type: 'GET',
        success: function(data) {
          $('#scheme').html(data);
          $('#scheme').val(<?= $sub_course['Scheme_ID'] ?>);
        }
      });

      $.ajax({
        url: '/app/sub-courses/modes?id=' + id,
        type: 'GET',
        success: function(data) {
          $('#mode').html(data);
          $('#mode').val(<?= $sub_course['Mode_ID'] ?>);
          getFeeSructures();
        }
      });
    }

    getDetails(<?= $sub_course['University_ID'] ?>);

    function getFeeSructures() {
      const id = '<?= $sub_course['ID'] ?>';
      const durations = $('#min_duration').val();
      const university_id = $('#university_id').val();
      const mode = $('#mode').val();
      $.ajax({
        url: '/app/sub-courses/fee-structures-edit?id=' + id + '&durations=' + durations + '&university_id=' + university_id + '&mode=' + mode,
        type: 'GET',
        success: function(data) {
          $('#fee').html(data);
        }
      });
    }

    $(function() {
      $('#form-edit-sub-course').validate({
        rules: {
          name: {
            required: true
          },
          short_name: {
            required: true
          },
          eligibilities: {
            required: true
          },
          university_id: {
            required: true
          },
          min_duration: {
            required: true
          },
          max_duration: {
            required: true
          },
          course: {
            required: true
          },
          le_start: {
            required: true
          },
          le_sol: {
            required: true
          },
          ct_transfer: {
            required: true
          },
          ct_start: {
            required: true
          },
          ct_sol: {
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

    $("#form-edit-sub-course").on("submit", function(e) {
      if ($('#form-edit-sub-course').valid()) {
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
          success: function(data) {
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

    function chooseDuration(selectElement) {
      var selectedValues = Array.from(selectElement.selectedOptions, option => option.value);
      $('#set_course_category').removeClass('d-none');

      // Mapping of conditions to HTML content
      var conditions = [{
          values: ['certification', 'certified', 'advance_diploma'],
          content: '<option value="3">3 Months</option><option value="6">6 Months</option><option value="11-1">11 Months Certified</option><option value="11-2">11 Months Advance Diploma</option>'
        },
        {
          values: ['certification', 'certified'],
          content: '<option value="3">3 Months</option><option value="6">6 Months</option><option value="11-1">11 Months Certified</option>'
        },
        {
          values: ['certification', 'advance_diploma'],
          content: '<option value="3">3 Months</option><option value="11-2">11 Months Advance Diploma</option>'
        },
        {
          values: ['certified', 'advance_diploma'],
          content: '<option value="6">6 Months</option><option value="11-1">11 Months Certified</option><option value="11-2">11 Months Advance Diploma</option>'
        },
        {
          values: ['advance_diploma'],
          content: '<option value="6" selected>6 Months'
        },
        {
          values: ['certified'],
          content: '<option value="6" selected >6 Months</option><option value="11-1">11 Months Certified</option>'
        },
        {
          values: ['certification'],
          content: '<option value="3" selected>3 Months</option>'
        }
      ];

      // Find the first condition that matches
      var matchedCondition = conditions.find(condition => condition.values.every(value => selectedValues.includes(value)));

      // Apply the matched HTML content
      $('#set_course_category').html('<div class="form-group form-group-default form-group-default-select2 ">\
        <label style="z-index:9999">Durations</label>\
        <select class="full-width" data-init-plugin="select2" id="min_duration" name="min_duration[]" multiple>\
            ' + matchedCondition.content + '\
        </select>\
    </div>');

      $("#min_duration").select2();
    }
  </script>
<?php } ?>
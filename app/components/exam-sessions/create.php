<?php if (isset($_GET['university_id'])) {
  require '../../../includes/db-config.php';
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);
?>

  <!-- Modal -->
  <div class="modal-header clearfix text-left m-0 p-0">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    <h6 class="fs-4 text-black fw-bold">Add <span class="semi-bold">Exam Session</span></h6>
  </div>

  <form class="card-body" role="form" id="form-add-exam-sessions" action="/app/components/exam-sessions/store" method="POST">
    <div class="row mb-4 ">
      <div class="col-sm-12">
        <label class="col-form-label" for="name">Name <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control" placeholder="ex: Jan-22" required>
      </div>
    </div>
    <div class="row session_row">
      <div class="col-sm-3 mb-3">
        <label class="col-form-label m-0 p-0" for="session[1]">Session <span class="text-danger">*</span></label>
        <select id="session[1]" class="form-select" name="session[1]" required>
          <option value="">Choose</option>
          <?php
          $sessions = $conn->query("SELECT ID, Name FROM Admission_Sessions WHERE Status = 1 AND University_ID = " . $_GET['university_id']);
          while ($session = $sessions->fetch_assoc()) {
            echo "<option value='{$session['ID']}'>{$session['Name']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-sm-3 mb-3">
        <label class="col-form-label m-0 p-0" for="admission_type[1]">Admission Type <span class="text-danger">*</span></label>
        <select id="admission_type[1]" class="form-select" name="admission_type[1]" required>
          <option value="">Choose</option>
          <?php
          $types = $conn->query("SELECT ID, Name FROM Admission_Types WHERE Status = 1 AND University_ID = " . $_GET['university_id']);
          while ($type = $types->fetch_assoc()) {
            echo "<option value='{$type['ID']}'>{$type['Name']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-sm-4 mb-3">
        <label class="col-form-label m-0 p-0" for="semesters[1]">Semesters<span class="text-danger">*</span></label>
        <input type="text" id="semesters[1]" name="semesters[1]" class="form-control" placeholder="ex: 1,2,3,4" required>
      </div>
      <div class="col-sm-2 d-flex align-items-center mb-3">
        <i class="ri-add-circle-line fs-2 text-primary" onclick="appendDiv()"></i>
      </div>
    </div>
    <div>
      <div class="row justify-content-end mt-5">
        <div class="col-sm-9">
          <button type="submit" class="btn btn-primary me-4">Add</button>
          <button type="button"  data-bs-dismiss="modal" aria-label="Close" class="btn btn-outline-secondary">Cancel</button>
        </div>
      </div>
    </div>
  </form>

  <script>
    function appendDiv() {
      var uniqid = $(".session_row").length + 1;
      var div = `
    <div class="row mb-3 session_row" id="session_row_${uniqid}">
      <div class="col-sm-3">
        <label class="col-form-label" for="session[${uniqid}]">Session <span class="text-danger">*</span></label>
        <select id="session[${uniqid}]" class="form-select" name="session[${uniqid}]" required>
          <option value="">Choose</option>
          <?php
          $sessions = $conn->query("SELECT ID, Name FROM Admission_Sessions WHERE Status = 1 AND University_ID = " . $_GET['university_id']);
          while ($session = $sessions->fetch_assoc()) {
            echo "<option value='{$session['ID']}'>{$session['Name']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-sm-3">
        <label class="col-form-label" for="admission_type[${uniqid}]">Admission Type <span class="text-danger">*</span></label>
        <select id="admission_type[${uniqid}]" class="form-select" name="admission_type[${uniqid}]" required>
          <option value="">Choose</option>
          <?php
          $types = $conn->query("SELECT ID, Name FROM Admission_Types WHERE Status = 1 AND University_ID = " . $_GET['university_id']);
          while ($type = $types->fetch_assoc()) {
            echo "<option value='{$type['ID']}'>{$type['Name']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-sm-4">
        <label class="col-form-label" for="semesters[${uniqid}]">Semesters<span class="text-danger">*</span></label>
        <input type="text" id="semesters[${uniqid}]" name="semesters[${uniqid}]" class="form-control" placeholder="ex: 1,2,3,4" required>
      </div>
      <div class="col-sm-2 d-flex align-items-center">
        <i class="ri-delete-bin-5-line fs-2 text-danger" onclick="removeDiv(${uniqid})"></i>
      </div>
    </div>`;
      $(".session_row").append(div);
    }

    function removeDiv(id) {
      $("#session_row_" + id).remove();
    }

    $(function() {
      $('#form-add-exam-sessions').validate({
        rules: {
          name: {
            required: true
          },
          'session[1]': {
            required: true
          },
          'semesters[1]': {
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
    });

    $("#form-add-exam-sessions").on("submit", function(e) {
      if ($('#form-add-exam-sessions').valid()) {
        $(':input[type="submit"]').prop('disabled', true);
        var formData = new FormData(this);
        formData.append('university_id', '<?= $_GET['university_id'] ?>');
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
              $('#tableExamSessionDatatable').DataTable().ajax.reload(null, false);
            } else {
              $(':input[type="submit"]').prop('disabled', false);
              toastr.error(data.message);
            }
          }
        });
        e.preventDefault();
      }
    });
  </script>

<?php } ?>
<?php
if (isset($_GET['id'])) {
  require '../../../includes/db-config.php';

  $id = intval($_GET['id']);
  $exam_session = $conn->query("SELECT * FROM Exam_Sessions WHERE ID = $id");
  $exam_session = $exam_session->fetch_assoc();
  $admission_sessions = json_decode($exam_session['Admission_Session'], true);
?>
  <!-- Modal -->
  <div class="modal-header clearfix text-left m-0 p-0">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    <h6 class="fs-4 text-black fw-bold">Edit <span class="semi-bold">Exam Session</span></h6>
  </div>
  <form class="card-body" role="form" id="form-add-exam-sessions" action="/app/components/exam-sessions/update" method="POST">
    <div class="row mb-4">
      <div class="col-sm-12 mb-3">
        <label class="col-form-label m-0 p-0" for="name">Name <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control" value="<?= $exam_session['Name'] ?>" placeholder="ex: Jan-22" required>
      </div>
    </div>
    <?php $i = 1;
    foreach ($admission_sessions as $key => $value) { ?>
      <div class="row mb-4 session_row" id="session_row_<?= $i ?>">
        <div class="col-sm-5 mb-3">
          <label class="col-form-label m-0 p-0" for="session[<?= $i ?>]">Session <span class="text-danger">*</span></label>
          <select id="session[<?= $i ?>]" class="form-select" name="session[<?= $i ?>]" required>
            <option value="">Choose</option>
            <?php
            $sessions = $conn->query("SELECT ID, Name FROM Admission_Sessions WHERE Status = 1 AND University_ID = " . $exam_session['University_ID']);
            while ($session = $sessions->fetch_assoc()) {
              echo "<option value='{$session['ID']}'" . ($session['ID'] == $key ? ' selected' : '') . ">{$session['Name']}</option>";
            }
            ?>
          </select>
        </div>
        <div class="col-sm-5 mb-3">
          <label class="col-form-label m-0 p-0" for="semesters[<?= $i ?>]">Semesters<span class="text-danger">*</span></label>
          <input type="text" id="semesters[<?= $i ?>]" name="semesters[<?= $i ?>]" class="form-control" value="<?= implode(",", $value) ?>" placeholder="ex: 1,2,3,4" required>
        </div>
        <div class="col-sm-2 d-flex align-items-center mb-3">
          <?php echo $i == 1 ? '<i class="ri-add-circle-line fs-2 text-primary" onclick="appendDiv()"></i>' : '<i class="ri-delete-bin-5-line fs-2 text-danger" onclick="removeDiv(' . $i . ')"></i>'; ?>
        </div>
      </div>
    <?php $i++;
    } ?>
    <div class="row justify-content-end">
      <div class="col-sm-9">
        <button type="submit" class="btn btn-primary me-4">Update</button>
        <button type="button"  data-bs-dismiss="modal" aria-label="Close" class="btn btn-outline-secondary">Cancel</button>
      </div>
    </div>
  </form>
  <script>
    function appendDiv() {
      var uniqid = $(".session_row").length + 1;
      var div = `<div class="row mb-4 session_row" id="session_row_${uniqid}">
        <div class="col-sm-5">
          <label class="col-form-label" for="session[${uniqid}]">Session <span class="text-danger">*</span></label>
          <select id="session[${uniqid}]" class="form-select" name="session[${uniqid}]" required>
            <option value="">Choose</option>
            <?php
            $sessions = $conn->query("SELECT ID, Name FROM Admission_Sessions WHERE Status = 1 AND University_ID = " . $exam_session['University_ID']);
            while ($session = $sessions->fetch_assoc()) {
              echo "<option value='{$session['ID']}'>{$session['Name']}</option>";
            }
            ?>
          </select>
        </div>
        <div class="col-sm-5">
          <label class="col-form-label" for="semesters[${uniqid}]">Semesters<span class="text-danger">*</span></label>
          <input type="text" id="semesters[${uniqid}]" name="semesters[${uniqid}]" class="form-control" placeholder="ex: 1,2,3,4" required>
        </div>
        <div class="col-sm-2 d-flex align-items-center">
          <i class="ri-delete-bin-5-line fs-2 text-danger" onclick="removeDiv(${uniqid})"></i>
        </div>
      </div>`;
      $(div).insertBefore('.row.justify-content-end');
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
        formData.append('id', '<?= $exam_session['ID'] ?>');
        formData.append('university_id', '<?= $exam_session['University_ID'] ?>');
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

<?php
if (isset($_POST['student_id']) && isset($_POST['course_id']) && isset($_POST['sub_course_id'])) {
  require '../../includes/db-config.php';
  session_start();
  $id = mysqli_real_escape_string($conn, $_POST['student_id']);
  $id = base64_decode($id);
  $student_id = intval(str_replace('W1Ebt1IhGN3ZOLplom9I', '', $id));
  $course_id = intval($_POST['course_id']);
  $sub_course_id = intval($_POST['sub_course_id']);

  $subcourse_sql = $conn->query("SELECT Name, ID FROM Sub_Courses WHERE Course_ID = '$course_id' AND ID ='$sub_course_id'");
  $subcourse_arr = $subcourse_sql->fetch_assoc();

  $fee_sql = $conn->query("SELECT Fee,Type,University_ID FROM Student_Ledgers WHERE Student_ID = '$student_id'  AND Type=1");
  $fee_arr = $fee_sql->fetch_assoc();

?>

  <link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" media="screen" />
  <link href="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
  <style>
    .select2-container--default.select2-container--focus .select2-selection--multiple {
      border: unset !important;
      outline: 0;
    }
  </style>
  <div class="modal-header clearfix text-left m-0 p-0">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

    <h5 class="fs-4 text-black fw-bold">Allot<span class="semi-bold"></span>Course Fee</h5>
  </div>
  <!-- <form role="form" id="form-allot-fee" action="/app/application-form/store-fee" method="POST" enctype="multipart/form-data">
    <div class="modal-body">
      <div class="row">
        <div class="col-md-8">
          <div class="form-group form-group-default" style="border:unset;font-weight:700">
            Course Name<span style="color:red">*</span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group form-group-default " style="border:unset">
            Fee<span style="color:red">*</span>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <div class="form-group form-group-default" style="border:unset;font-weight:700">
            <?= $subcourse_arr['Name'] ?>
            <input type="hidden" name="student_id" value="<?= $student_id ?>">
            <input type="hidden" name="university_id" value="<?= $fee_arr['University_ID'] ?>">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group form-group-default">

            <input type="number" min="0" step="500" placeholder="Fee" name="fee[1]" value="<?= $fee_arr['Fee'] ?>" class="form-control" />
          </div>
        </div>
      </div>
      <?php if ($fee_sql->num_rows > 0) { ?>
        <?php
        $check_credited = $conn->query("SELECT Fee,Type FROM Student_Ledgers WHERE Student_ID = '$student_id' AND University_ID= '" . $fee_arr['University_ID'] . "' AND Type=2");
        $i = 1;
        $pending_fee = [];
        $total_fee = 0;
        while ($row = $check_credited->fetch_assoc()) {
          $pending_fee[] = $row['Fee'];
        ?>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group form-group-default" style="border:unset;font-weight:700">
                Credit Amount <?= $i++; ?>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group form-group-default">
                <?= $row['Fee'] ?>
              </div>
            </div>
          </div>
        <?php } ?>
        <?php $total_fee = $fee_arr['Fee'] - array_sum($pending_fee); ?>
        <?php if ($total_fee != 0) { ?>
          <span style="color:red;">(Pending Fees-  <?= "&#8377;" . number_format($total_fee, 2);  ?>)</span>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group form-group-default" style="border:unset;font-weight:700">
                Credit Amount
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group form-group-default">
                <input type="number" min="0" step="500"  placeholder="Credit Amount" name="fee[2]" value="" class="form-control" />
              </div>
            </div>
          </div>
      <?php } else { ?>
        <span style="color:darkgreen;">Course Fee Submitted !</span>
    <?php }
      } ?>

      <div id="subcourse">

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
  <form class="card-body" role="form" id="form-allot-fee" action="/app/application-form/store-fee" method="POST" enctype="multipart/form-data">
    <div class="row ">
      <label class="col-sm-8 col-form-label fw-bold text-black" for="multicol-full-name">Course Name <span class="text-danger">*</span></label>
      <div class="col-sm-4">
        <label class=" col-form-label fw-bold text-black" for="multicol-full-name">Fee <span class="text-danger">*</span></label>
      </div>
    </div>
    <div class="row ">
      <div class="col-sm-8">
        <label class=" col-form-label text-black fw-bold" for="multicol-full-name"> <?= $subcourse_arr['Name'] ?> <span class="text-danger">*</span></label>
        <input type="hidden" name="student_id" value="<?= $student_id ?>">
        <input type="hidden" name="university_id" value="<?= $fee_arr['University_ID'] ?>">
      </div>
      <div class="col-sm-4">
        <input type="number" min="0" step="500" placeholder="Fee" name="fee[1]" value="<?= $fee_arr['Fee'] ?>" class="form-control"  required>
      </div>
    </div>
    <?php if ($fee_sql->num_rows > 0) { ?>
        <?php
        $check_credited = $conn->query("SELECT Fee,Type FROM Student_Ledgers WHERE Student_ID = '$student_id' AND University_ID= '" . $fee_arr['University_ID'] . "' AND Type=2");
        $i = 1;
        $pending_fee = [];
        $total_fee = 0;
        while ($row = $check_credited->fetch_assoc()) {
          $pending_fee[] = $row['Fee'];
        ?>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group form-group-default text-black fw-bold" style="border:unset;">
                Credit Amount <?= $i++; ?>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group form-group-default ms-3 mt-2">
                <?= $row['Fee'] ?>
              </div>
            </div>
          </div>
        <?php } ?>
        <?php $total_fee = $fee_arr['Fee'] - array_sum($pending_fee); ?>
        <?php if ($total_fee != 0) { ?>
          <span style="color:red;" class="fw-bold">(Pending Fees-  <?= "&#8377;" . number_format($total_fee, 2);  ?>)</span>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group form-group-default text-black fw-bold" style="border:unset;font-weight:700">
                Credit Amount
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group form-group-default">
                <input type="number" min="0" step="500"  placeholder="Credit Amount" name="fee[2]" value="" class="form-control" />
              </div>
            </div>
          </div>
      <?php } else { ?>
        <span style="color:darkgreen;">Course Fee Submitted !</span>
    <?php }
      } ?>

      <div id="subcourse">

      </div>
    <div class="pt-6">
      <div class="row justify-content-end">
        <div class="col-sm-9">
          <button type="submit" class="btn btn-primary me-4">Add</button>
          <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-outline-secondary">Cancel</button>
        </div>
      </div>
    </div>
  </form>
  <script type="text/javascript" src="/assets/plugins/select2/js/select2.full.min.js"></script>
  <script type="text/javascript" src="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
  <script>
    $(function() {
      $('#form-allot-fee').validate({
        rules: {
          fee: {
            required: true
          },
        },
        highlight: function(element) {
          $(element).closest('.form-control').addClass('has-error');
        },
        unhighlight: function(element) {
          $(element).closest('.form-control').removeClass('has-error');
        }
      });
    })

    $("#form-allot-fee").on("submit", function(e) {
      if ($('#form-allot-fee').valid()) {
        var formData = new FormData(this);
        e.preventDefault();
        $.ajax({
          url: $(this).attr('action'),
          type: "POST",
          data: formData,
          contentType: false,
          cache: false,
          processData: false,
          dataType: 'json',
          success: function(data) {
            if (data.status == 200) {
              toastr.success( data.message);
              $('.modal').modal('hide');
              $('.table').DataTable().ajax.reload(null, false);
            } else {
              toastr.error(data.message);
            }
          },
          error: function(data) {
            toastr.error( 'Server is not responding. Please try again later');
          }
        });
      }
    });
  </script>
<?php } ?>
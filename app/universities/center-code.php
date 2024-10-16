<?php if (isset($_GET['id'])) {
  require '../../includes/db-config.php';
  $center_code = $conn->query("SELECT Center_Suffix FROM Universities WHERE ID = '" . $_GET['id'] . "'");
  $center_code = mysqli_fetch_assoc($center_code);
?>
  <!-- Modal -->
  <div class="modal-header clearfix text-left m-0 p-0">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

    <h5 class="fs-4 text-black fw-bold">Center Code</h5>
  </div>
  <!-- <form role="form" id="form-add-center-code" action="/app/universities/store-center-code" method="POST">
    <div class="modal-body">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group form-group-default required">
            <label>Starting with</label>
            <input type="text" name="suffix" id="suffix" onkeyup="showStudentID()" value="<?php print !empty($center_code['Center_Suffix']) ? $center_code['Center_Suffix'] : '' ?>" class="form-control" placeholder="ex: XYZ" required>
          </div>
        </div>
      </div>
      
      <div class="row" id="generated-center-code">
        <?php
        if (!empty($center_code['Center_Suffix'])) : echo '<p>Student ID: <b>' . $center_code['Center_Suffix'] . 'XXXX</b></p>';
        endif;
        ?>
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
  <form class="card-body" role="form" id="form-add-center-code" action="/app/universities/store-center-code" method="POST">
    <div class="row ">
      <div class="col-sm-12 mb-4">
        <label class="col-form-label m-0 p-0" for="multicol-full-name">Name <span class="text-danger">*</span></label>

        <input type="text" id="suffix" name="suffix" class="form-control" placeholder="ex: XYZ" onkeyup="showStudentID()" value="<?php print !empty($center_code['Center_Suffix']) ? $center_code['Center_Suffix'] : '' ?>" required />
      </div>

    </div>
    <div class="row" id="generated-center-code">
        <?php
        if (!empty($center_code['Center_Suffix'])) : echo '<p>Student ID: <b>' . $center_code['Center_Suffix'] . 'XXXX</b></p>';
        endif;
        ?>
      </div>
    <div class="pt-3">
      <div class="row ">
        <div class="col-sm-12 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary me-4">Submit</button>
          <button type="button"  data-bs-dismiss="modal" aria-label="Close" data-bs-dismiss="modal" aria-label="Close" class="btn btn-outline-secondary">Cancel</button>
        </div>
      </div>
    </div>
  </form>
  <script>
    $(function() {
      $('#form-add-center-code').validate({
        rules: {
          suffix: {
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

    function showStudentID() {
      var suffix = $('#suffix').val();
      if (suffix != '') {
        var character = 'XXXX';
        $('#generated-center-code').html('<p>Center Code: <b>' + suffix + character + '</b></p>');
      } else {
        $('#generated-center-code').html('');
      }

    }

    $("#form-add-center-code").on("submit", function(e) {
      if ($('#form-add-center-code').valid()) {
        var formData = new FormData(this);
        formData.append('id', '<?= $_GET['id'] ?>');
        $.ajax({
          url: this.action,
          type: 'post',
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          dataType: "json",
          success: function(data) {
            $('.modal').modal('hide');
            $('#DataTables_Table_3').DataTable().ajax.reload(null, false);
            if (data.status == 200) {
              toastr.success(data.message);
            } else {
              toastr.error(data.message);
            }
          }
        });
        e.preventDefault();
      }
    });
  </script>
<?php } ?>
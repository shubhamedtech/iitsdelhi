<?php if (isset($_GET['id'])) {
  require '../../includes/db-config.php';
  $university = $conn->query("SELECT Name, Short_Name, Vertical, Address, Logo, Is_B2C FROM Universities WHERE ID = '" . $_GET['id'] . "'");
  $university = mysqli_fetch_assoc($university);
?>
  <!-- Modal -->
  <div class="modal-header clearfix text-left">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

    <h5 class="fs-4 fw-bold text-black">Update <span class="semi-bold"></span>University</h5>
  </div>

  <form class="card-body" role="form" id="form-edit-university" action="/app/universities/update" method="POST" enctype="multipart/form-data">
    <div class="row mb-4">
      <label class="col-sm-3 col-form-label" for="multicol-country">Country <span class="text-danger">*</span></label>
      <div class="col-sm-9">
        <select class="select2 form-select" data-allow-clear="true" name="university_type" id="university_type">
          <option value="0" <?php print $university['Is_B2C'] == 0 ? 'selected' : '' ?>>Outsourced Partners</option>
          <option value="1" <?php print $university['Is_B2C'] == 1 ? 'selected' : '' ?>>Inhouse i.e. Students</option>
          <option value="2" <?php print $university['Is_B2C'] == 2 ? 'selected' : '' ?>>Both</option>
        </select>
      </div>
    </div>
    <div class="row mb-4">
      <label class="col-sm-3 col-form-label" for="multicol-full-name">Name <span class="text-danger">*</span></label>
      <div class="col-sm-9">
        <input type="text" id="multicol-full-name" name="name" class="form-control" value="<?php echo $university['Name'] ?>" placeholder="ex: XYZ University" required/>
      </div>
    </div>
    <div class="row mb-4">
      <label class="col-sm-3 col-form-label" for="multicol-birthdate">Short Name <span class="text-danger">*</span></label>
      <div class="col-sm-9">
        <input type="text" name="short_name" class="form-control" value="<?php echo $university['Short_Name'] ?>" placeholder="ex: XYZU" required />

      </div>
    </div>
    <div class="row mb-4">
      <label class="col-sm-3 col-form-label" for="multicol-phone">Vertical <span class="text-danger">*</span></label>
      <div class="col-sm-9">
        <input type="text" name="vertical" class="form-control phone-mask"  value="<?php echo $university['Vertical'] ?>" placeholder="ex: Technical" required />
      </div>
    </div>
    <div class="row mb-4">
      <label class="col-sm-3 col-form-label" for="multicol-phone">Logo <span class="text-danger">*</span></label>
      <div class="col-sm-9">
        <input class="form-control" type="file"  multiple="" name="logo" accept="image/png, image/jpg, image/jpeg, image/svg" >
        <img src="<?php echo    $university['Logo'] ?>" width="60px">

      </div>
    </div>
    <div class="row mb-4">
      <label class="col-sm-3 col-form-label" for="multicol-phone">Address <span class="text-danger">*</span></label>
      <div class="col-sm-9">
        <textarea name="address" id="basic-default-message" class="form-control"  placeholder="ex: 23 Street, California, USA 681971" required style="height: 60px;"><?php echo $university['Address'] ?></textarea>
      </div>
    </div>

    <div class="pt-6">
      <div class="row justify-content-end">
        <div class="col-sm-12 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary me-4">Update</button>
          <button  class="btn btn-outline-secondary" type="button"  data-bs-dismiss="modal" aria-label="Close">Cancel</button>
        </div>
      </div>
    </div>
  </form>
  <script>
    $(function() {
      $('#form-edit-university').validate({
        rules: {
          name: {
            required: true
          },
          short_name: {
            required: true
          },
          vertical: {
            required: true
          },
          address: {
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

    $("#form-edit-university").on("submit", function(e) {
      if ($('#form-edit-university').valid()) {
        $(':input[type="submit"]').prop('disabled', true);
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
            if (data.status == 200) {
              $('.modal').modal('hide');
              toastr.success( data.message);
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
  </script>
<?php } ?>
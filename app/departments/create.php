<!-- Modal -->
<div class="modal-header clearfix text-left p-0 m-0">
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

  <h5 class="fs-4 fw-bold text-black">Add <span class="semi-bold">Department</span></h5>
</div>
<form class="card-body" role="form" id="form-add-department" action="/app/departments/store" method="POST" enctype="multipart/form-data">
  <div class="row mb-4">
    <label class="col-sm-3 col-form-label" for="multicol-country">Country <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="university_id" onchange="getCourseType(this.value);">
        <option value="">Choose</option>
        <?php
        require '../../includes/db-config.php';
        $universities = $conn->query("SELECT ID, CONCAT(Universities.Short_Name, ' (', Universities.Vertical, ')') as Name FROM Universities WHERE ID IS NOT NULL " . $_SESSION['UniversityQuery']);
        while ($university = $universities->fetch_assoc()) { ?>
          <option value="<?= $university['ID'] ?>"><?= $university['Name'] ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="row mb-4">
    <label class="col-sm-3 col-form-label" for="multicol-full-name">Name <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="text" id="multicol-full-name" name="name" class="form-control" placeholder="ex: Department of Engineering & Technology" required>
    </div>
  </div>

  <div class="pt-6">
    <div class="row justify-content-end">
      <div class="col-sm-9">
        <button type="submit" class="btn btn-primary me-4">ADD</button>
        <button type="button"  data-bs-dismiss="modal" aria-label="Close" class="btn btn-outline-secondary">Cancel</button>
      </div>
    </div>
  </div>
</form>
<script>
  $(function() {
    $('#form-add-department').validate({
      rules: {
        name: {
          required: true
        },
        university_id: {
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

  $("#form-add-department").on("submit", function(e) {
    if ($('#form-add-department').valid()) {
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
          $('.modal').modal('hide');
          if (data.status == 200) {
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
</script>

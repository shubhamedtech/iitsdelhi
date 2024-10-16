<!-- Modal -->
<div class="modal-header clearfix text-left m-0 p-0">
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

  <h5 class="fs-4 text-black fw-bold">Add <span class="semi-bold"></span>Program</h5>
</div>

<form class="card-body" role="form" id="form-add-course" action="/app/courses/store" method="POST" enctype="multipart/form-data">
  <div class="row mb-4">
    <div class="col-sm-12 mb-4">
      <label class=" col-form-label m-0 p-0" for="multicol-country">University <span class="text-danger">*</span></label>
      <select class="select2 form-select" data-allow-clear="true" name="university_id" onchange="getCourseType(this.value); getDepartments(this.value);">
        <option value="">Choose</option>
        <?php
        require '../../includes/db-config.php';
        $universities = $conn->query("SELECT ID, CONCAT(Universities.Short_Name, ' (', Universities.Vertical, ')') as Name FROM Universities WHERE ID IS NOT NULL " . $_SESSION['UniversityQuery']);
        while ($university = $universities->fetch_assoc()) { ?>
          <option value="<?= $university['ID'] ?>"><?= $university['Name'] ?></option>
        <?php } ?>
      </select>
    </div>
    <div class="col-sm-6 mb-4">
      <label class=" col-form-label m-0 p-0" for="multicol-country">Department </label>
      <select class="select2 form-select" data-allow-clear="true" id="department" name="department">

      </select>
    </div>
    <div class="col-sm-6 mb-4">
      <label class=" col-form-label m-0 p-0" for="multicol-country">Program Type </label>
      <select class="select2 form-select" data-allow-clear="true" id="course_type" name="course_type">

      </select>
    </div>
    <div class="col-sm-12 mb-4">
      <label class="col-form-label m-0 p-0" for="multicol-full-name">Name <span class="text-danger">*</span></label>

      <input type="text" id="multicol-full-name" name="name" class="form-control" placeholder="ex: Bachelor of Technology" required />
    </div>
    <div class="col-sm-12 mb-4">
      <label class="col-form-label m-0 p-0" for="multicol-full-name">Short Name <span class="text-danger">*</span></label>

      <input type="text" id="multicol-full-name" name="short_name" class="form-control" placeholder="ex: B.Tech" required />
    </div>
  </div>
  


  <div class="pt-3">
    <div class="row ">
      <div class="col-sm-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary me-4">Add</button>
        <button type="button"  data-bs-dismiss="modal" aria-label="Close" class="btn btn-outline-secondary">Cancel</button>
      </div>
    </div>
  </div>
</form>
<script src="/assets/vendor/libs/toastr/toastr.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" ></script>
<script>
  function getCourseType(id) {
    $.ajax({
      url: '/app/courses/course-types?id=' + id,
      type: 'GET',
      success: function(data) {
        $('#course_type').html(data);
      }
    });
  }

  function getDepartments(id) {
    $.ajax({
      url: '/app/courses/departments?id=' + id,
      type: 'GET',
      success: function(data) {
        $('#department').html(data);
      }
    });
  }

  $(function() {
    $('#form-add-course').validate({
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
        department: {
          required: true
        },
        course_type: {
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
 
  $("#form-add-course").on("submit", function(e) {
    if ($('#form-add-course').valid()) {
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
 
            toastr.success(data.message);
            $('.modal').modal('hide');
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
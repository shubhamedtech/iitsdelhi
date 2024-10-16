<?php
if (isset($_GET['id'])) {
  require '../../includes/db-config.php';
  $id = intval($_GET['id']);
  $course = $conn->query("SELECT * FROM Courses WHERE ID = $id");
  $course = $course->fetch_assoc();
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Modal -->
<div class="modal-header clearfix text-left m-0 p-0">
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  <h5 class="fs-4 fw-bold text-black">Edit <span class="semi-bold"></span>Program</h5>
</div>

<form class="card-body" role="form" id="form-edit-course" action="/app/courses/update" method="POST" enctype="multipart/form-data">
  <div class="row mb-4">
    <div class="col-sm-12 mb-4">
      <label class=" col-form-label m-0 p-0" for="multicol-country">University <span class="text-danger">*</span></label>
      <select class="select2 form-select" data-allow-clear="true" name="university_id" onchange="getCourseType(this.value); getDepartments(this.value);">
        <option value="">Choose</option>
        <?php
        $universities = $conn->query("SELECT ID, CONCAT(Universities.Short_Name, ' (', Universities.Vertical, ')') as Name FROM Universities WHERE ID IS NOT NULL " . $_SESSION['UniversityQuery']);
        while ($university = $universities->fetch_assoc()) { ?>
          <option value="<?= $university['ID'] ?>" <?php print $university['ID'] == $course['University_ID'] ? 'selected' : '' ?>><?= $university['Name'] ?></option>
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

      <input type="text" id="name" name="name" class="form-control" placeholder="ex: Bachelor of Technology" value="<?= $course['Name'] ?>"  required />
    </div>
    <div class="col-sm-12 mb-4">
      <label class="col-form-label m-0 p-0" for="multicol-full-name">Short Name <span class="text-danger">*</span></label>

      <input type="text" id="short_name" name="short_name" class="form-control" placeholder="ex: B.Tech"  value="<?= $course['Short_Name'] ?>" required />
    </div>
  </div>
  <div class="pt-3">
    <div class="row ">
      <div class="col-sm-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary me-4"> Update</button>
        <button type="button"  data-bs-dismiss="modal" aria-label="Close" class="btn btn-outline-secondary">Cancel</button>
      </div>
    </div>
  </div>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  function getCourseType(id) {
    $.ajax({
      url: '/app/courses/course-types?id=' + id,
      type: 'GET',
      success: function(data) {
        $('#course_type').html(data);
        $('#course_type').val('<?= $course['Course_Type_ID'] ?>');
      }
    });
  }

  function getDepartments(id) {
    $.ajax({
      url: '/app/courses/departments?id=' + id,
      type: 'GET',
      success: function(data) {
        $('#department').html(data);
        $('#department').val('<?= $course['Department_ID'] ?>');
      }
    });
  }

  getCourseType(<?= $course['University_ID'] ?>);
  getDepartments(<?= $course['University_ID'] ?>);

  $(function() {
    $('#form-edit-course').validate({
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

  $("#form-edit-course").on("submit", function(e) {
    if ($('#form-edit-course').valid()) {
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
            // $('#DataTables_Table_3').DataTable().ajax.reload(null, false);
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

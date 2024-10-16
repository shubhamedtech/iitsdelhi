<?php if (isset($_GET['university_id'])) { ?>
  <!-- Modal -->
  <div class="modal-header clearfix text-left m-0 p-0">
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

    <h6 class="fs-4 text-black fw-bold">Add <span class="semi-bold">Program Type</span></h6>
  </div>
  <!-- <form role="form" id="form-add-course-types" action="/app/components/course-types/store" method="POST">
    <div class="modal-body">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group form-group-default required">
            <label>Name</label>
            <input type="text" name="name" class="form-control" placeholder="ex: UG">
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
  <form class="card-body" role="form" id="form-add-course-types" action="/app/components/course-types/store" method="POST">
    <div class="row mb-4">
      <div class="col-sm-12 mb-3">
        <label class="col-form-label m-0 p-0" for="multicol-full-name">Name <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control" placeholder="ex: UG" required>
      </div>
    </div>
    <div >
      <div class="row justify-content-end">
        <div class="col-sm-9">
          <button type="submit" class="btn btn-primary me-4">Add</button>
          <button type="button"  data-bs-dismiss="modal" aria-label="Close" class="btn btn-outline-secondary">Cancel</button>
        </div>
      </div>
    </div>
  </form>
  <script>
    $(function() {
      $('#form-add-course-types').validate({
        rules: {
          name: {
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

    $("#form-add-course-types").on("submit", function(e) {
      if ($('#form-add-course-types').valid()) {
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
              toastr.success( data.message);
              $('#tableProgramTypeDatatable').DataTable().ajax.reload(null, false);
            } else {
              $(':input[type="submit"]').prop('disabled', false);
              toastr.error( data.message);
            }
          }
        });
        e.preventDefault();
      }
    });
  </script>
<?php } ?>

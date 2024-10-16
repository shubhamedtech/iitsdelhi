<!-- Modal -->
<div class="modal-header clearfix text-left m-0 p-0">
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

  <h5 class="fs-4 fw-bold text-center">Add <span class="semi-bold">University Manager</span></h5>
</div>
<!-- <form role="form" id="form-add-university-heads" action="/app/university-heads/store" method="POST" enctype="multipart/form-data">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Name</label>
          <input type="text" name="name" class="form-control" placeholder="ex: Jhon Doe" required>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Employee ID</label>
          <input type="text" name="code" class="form-control" placeholder="ex: EM0001" required>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Email</label>
          <input type="email" name="email" class="form-control" placeholder="ex: user@example.com" required>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Contact</label>
          <input type="tel" name="contact" class="form-control" placeholder="ex: 9998777655" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)" required>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <label>Photo*</label>
        <input type="file" name="photo" accept="image/png, image/jpg, image/jpeg, image/svg">
      </div>

      <div class="col-md-6" id="logo-view"></div>
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
<form class="card-body" role="form" id="form-add-university-heads" action="/app/university-heads/store" method="POST" enctype="multipart/form-data">
 
  <div class="row mb-4">
    <label class="col-sm-3 col-form-label" for="multicol-full-name">Name <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="text" id="name" name="name" class="form-control" placeholder="ex: Jhon Doe" required>
    </div>
  </div>
  <div class="row mb-4">
    <label class="col-sm-3 col-form-label" for="multicol-full-name">Employee ID <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="text" id="code" name="code" class="form-control" placeholder="ex: EM0001" required>
    </div>
  </div>
  <div class="row mb-4">
    <label class="col-sm-3 col-form-label" for="multicol-full-name">Email <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="email" id="email" name="email" class="form-control" placeholder="ex: user@example.com" required>
    </div>
  </div>
  <div class="row mb-4">
    <label class="col-sm-3 col-form-label" for="multicol-full-name">Contact <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="tel" id="contact" name="contact" class="form-control" placeholder="ex: 9998777655" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)" required>
    </div>
  </div>
  <div class="row mb-4">
    <label class="col-sm-3 col-form-label" for="multicol-full-name">Photo <span class="text-danger">*</span></label>
    <div class="col-sm-9">
    <input class="form-control" type="file" id="photo" multiple="" name="photo" accept="image/png, image/jpg, image/jpeg, image/svg" required>
    </div>
  </div>
  <div class="pt-6">
    <div class="row justify-content-end">
      <div class="col-sm-9">
        <button type="submit" class="btn btn-primary me-4">ADD</button>
        <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-outline-secondary">Cancel</button>
      </div>
    </div>
  </div>
</form>
<script>
  $(function() {
    $('#form-add-university-heads').validate({
      rules: {
        name: {
          required: true
        },
        code: {
          required: true
        },
        email: {
          required: true
        },
        contact: {
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

  $("#form-add-university-heads").on("submit", function(e) {
    if ($('#form-add-university-heads').valid()) {
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
            $('.modal').modal('hide');
            toastr.success( data.message);
            $('#DataTables_Table_3').DataTable().ajax.reload(null, false);
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

<!-- Modal -->
<div class="modal-header clearfix text-left p-0 m-0 mb-3">
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

  <h5 class="fw-bold text-black fs-3">Add <span class="semi-bold"></span>University</h5>
</div>

<!-- <form role="form" id="form-add-university" action="/app/universities/store" method="POST" enctype="multipart/form-data">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group form-group-default required">
          <label>University Dealing with</label>
          <select class="full-width" style="border: transparent;" name="university_type" id="university_type">
            <option value="0">Outsourced Partners</option>
            <option value="1">Inhouse i.e. Students</option>
            <option value="2">Both</option>
          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Name</label>
          <input type="text" name="name" class="form-control" placeholder="ex: XYZ University" required>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Short Name</label>
          <input type="text" name="short_name" class="form-control" placeholder="ex: XYZU" required>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Vertical</label>
          <input type="text" name="vertical" class="form-control" placeholder="ex: Technical" required>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group form-group-default required">
          <label>Address</label>
          <textarea name="address" class="form-control" rows="2" placeholder="ex: 23 Street, California, USA 681971" required></textarea>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <label>Logo*</label>
        <input type="file" name="logo" accept="image/png, image/jpg, image/jpeg, image/svg" required>
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
<form class="card-body" role="form" id="form-add-university" action="/app/universities/store" method="POST" enctype="multipart/form-data">
  <div class="row mb-4">
    <label class="col-sm-3 col-form-label" for="multicol-country">Country <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="university_type" id="university_type">
        <option value="0">Outsourced Partners</option>
        <option value="1">Inhouse i.e. Students</option>
        <option value="2">Both</option>
      </select>
    </div>
  </div>
  <div class="row mb-4">
    <label class="col-sm-3 col-form-label" for="multicol-full-name">Name <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="text" id="multicol-full-name" name="name" class="form-control" placeholder="ex: XYZ University" required />
    </div>
  </div>
  <div class="row mb-4">
    <label class="col-sm-3 col-form-label" for="multicol-birthdate">Short Name  <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="text" name="short_name" class="form-control" placeholder="ex: XYZU" required />

    </div>
  </div>
  <div class="row mb-4">
    <label class="col-sm-3 col-form-label" for="multicol-phone">Vertical <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="text" name="vertical" class="form-control phone-mask" placeholder="ex: Technical" required />
    </div>
  </div>
  <div class="row mb-4">
    <label class="col-sm-3 col-form-label" for="multicol-phone">Logo <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input class="form-control" type="file" id="formFileMultiple" multiple="" name="logo" accept="image/png, image/jpg, image/jpeg, image/svg" required>
    </div>
  </div>
  <div class="row mb-4">
    <label class="col-sm-3 col-form-label" for="multicol-phone">Address <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <textarea name="address" id="basic-default-message" class="form-control" placeholder="ex: 23 Street, California, USA 681971" required style="height: 60px;"></textarea>
    </div>
  </div>
  
  <div class="pt-6">
    <div class="row justify-content-end">
      <div class="col-sm-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary me-4">Save</button>
        <button  class="btn btn-outline-secondary"type="button"  data-bs-dismiss="modal" aria-label="Close">Cancel</button>
      </div>
    </div>
  </div>
</form>
<script>
  $(function() {
    $('#form-add-university').validate({
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
        logo: {
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

  $("#form-add-university").on("submit", function(e) {
    if ($('#form-add-university').valid()) {
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
            toastr.error(data.message);
          }
        }
      });
      e.preventDefault();
    }
  });
</script>
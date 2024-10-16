<?php
if (isset($_GET['id'])) {
  require '../../includes/db-config.php';
  $id = intval($_GET['id']);
  $alloted = array();
  $alloted_universities = $conn->query("SELECT University_ID FROM University_User WHERE `User_ID` = $id");
  while ($alloted_university = $alloted_universities->fetch_assoc()) {
    $alloted[] = $alloted_university['University_ID'];
  }
?>
  <!-- Modal -->
  <div class="modal-header clearfix justify-content-center m-0 p-0">
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

    <h5 class="fs-4 text-black fw-bold mb-0">Allot <span class="semi-bold"></span>Universities</h5>
  </div>
  <!-- <form role="form" id="form-allot-universities" action="/app/operations/allot" method="POST" enctype="multipart/form-data">
    <div class="modal-body">
      <?php
      $universities = $conn->query("SELECT ID, CONCAT(Universities.Short_Name, ' (', Universities.Vertical, ')') as Name FROM Universities WHERE ID IN (SELECT University_User.University_ID FROM University_User)");
      while ($university = $universities->fetch_assoc()) { ?>
            <div class="row">
              <div class="form-check complete">
                <input type="checkbox" id="allot-university-<?= $university['ID'] ?>" <?php print in_array($university['ID'], $alloted) ? 'checked' : '' ?> name="allot[]" value="<?= $university['ID'] ?>">
                <label for="allot-university-<?= $university['ID'] ?>">
                  <?= $university['Name'] ?>
                </label>  
              </div>
            </div>  
        <?php }
        ?>
    </div>
    <div class="modal-footer clearfix text-end">
      <div class="col-md-4 m-t-10 sm-m-t-10">
        <button aria-label="" type="submit" class="btn btn-primary btn-cons btn-animated from-left">
          <span>Allot</span>
          <span class="hidden-block">
            <i class="pg-icon">tick</i>
          </span>
        </button>
      </div>
    </div>
  </form> -->
  <form class="card-body" role="form" id="form-allot-universities" action="/app/operations/allot" method="POST" enctype="multipart/form-data">
  <?php
      $universities = $conn->query("SELECT ID, CONCAT(Universities.Short_Name, ' (', Universities.Vertical, ')') as Name FROM Universities WHERE ID IN (SELECT University_User.University_ID FROM University_User)");
      while ($university = $universities->fetch_assoc()) { ?>
    <div class="row mb-2">
      <div class="col-sm-12 d-flex justify-content-center">
        <input type="checkbox" id="allot-university-<?= $university['ID'] ?>" <?php print in_array($university['ID'], $alloted) ? 'checked' : '' ?> name="allot[]" value="<?= $university['ID'] ?>">
        <label class="col-form-label fs-6 ms-3 text-black fw-bold" for="allot-university-<?= $university['ID'] ?>"> <?= $university['Name'] ?></label>
     
      </div>
    </div>
    <?php }
        ?>
    <div>
  <div class="row justify-content-center">
      <div class="col-sm-12 d-flex justify-content-center">
        <button type="submit" class="btn btn-primary me-4">Submit</button>
        <button type="reset" class="btn btn-outline-secondary">Cancel</button>
      </div>
    </div>
  </div>
  </form>
  <script>
    $(function() {
      $('#form-allot-universities').validate({
        rules: {
          allot: {
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

    $("#form-allot-universities").on("submit", function(e) {
      if ($('#form-allot-universities').valid()) {
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
<?php } ?>
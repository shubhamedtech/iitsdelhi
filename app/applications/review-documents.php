<?php
if (isset($_GET['id'])) {
  session_start();
  require '../../includes/db-config.php';
  $student_id = mysqli_real_escape_string($conn, $_GET['id']);
  $id = base64_decode($student_id);
  $id = intval(str_replace('W1Ebt1IhGN3ZOLplom9I', '', $id));
  $documents = $conn->query("SELECT * FROM Student_Documents WHERE Student_ID = $id ORDER BY ID DESC");

  $pending = array();
  $pendency = $conn->query("SELECT Pendency FROM Student_Pendencies WHERE Student_ID = $id AND Status = 2");
  if ($pendency->num_rows > 0) {
    $pendency = $pendency->fetch_assoc();
    $pending = !empty($pendency['Pendency']) ? json_decode($pendency['Pendency'], true) : [];
  }

  $heading = array(
    'High School' => 'High_School_Marksheet',
    'Intermediate' => 'Intermediate_Marksheet',
    'UG' => 'Graduation_Marksheet',
    'PG' => 'Post_Graduation_Marksheet',
    'Other' => 'Other_Marksheet'
  )

?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.5.0/viewer.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.5.0/viewer.js"></script>
  <style>
    .modal.fade.fill-in .modal-content {
      background: #fff !important;
    }
  </style>
  <!-- <div class="modal-header">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    <div>
      <h5 class="text-start fs-4 text-black fw-bold  p-b-5"><span class="semi-bold">Review Documents</span></h5>
    </div> -->
  <div class="modal-header clearfix text-left ">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

    <!-- <h5 class="fs-4 text-black fw-bold"><span class="semi-bold">Review Documents</span></h5> -->
  </div>
  </div>
  <div class="modal-body">
    <h5 class="fs-4 text-black fw-bold"><span class="semi-bold">Review Documents</span></h5>

    <div class="row">
      <div class="col-md-12">
        <?php while ($document = $documents->fetch_assoc()) {
          $uniqid = uniqid(); ?>
          <div class="col-md-3 m-b-20" id="<?= $uniqid ?>" style="float:left">

            <h6 class="m-b-4 mt-3"><?= $document['Type'] ?> <?php echo in_array(str_replace(" ", "_", $document['Type']), array_keys($pending)) ? ' <span class="text-danger font-weight-bold">(Re-Uploaded)</span>' : '' ?><?php echo array_key_exists($document['Type'], $heading) && in_array($heading[$document['Type']], array_keys($pending)) ? ' <span class="text-danger font-weight-bold">(Re-Uploaded)</span>' : '' ?></h6>
            <?php $images = explode("|", $document['Location']);
            foreach ($images as $image) {
            ?>

              <img src="<?= $image ?>?v=<?= uniqid() ?>" onclick="viewImage('<?= $uniqid ?>')" class="cursor-pointer" height="150px">
          </div>
        <?php } ?>

      <?php } ?>
      </div>
    </div>
  </div>
  <div class="modal-footer clearfix text-end">
    <div class="col-md-4 m-t-10 sm-m-t-10">
      <!-- <button aria-label="" type="button" onclick="reportPendency('<?= $student_id ?>')" class="btn btn-primary btn-cons btn-animated from-left">
        <span>Mark Pendency</span>
        <span class="hidden-block">
          <i class="pg-icon">edit</i>
        </span>
      </button>
      <button aria-label="" type="button" onclick="approveDocuments(<?= $id ?>)" class="btn btn-primary btn-cons btn-animated from-left">
        <span>Approved</span>
        <span class="hidden-block">
          <i class="pg-icon">tick</i>
        </span>
      </button> -->
      <button type="button" onclick="reportPendency('<?= $student_id ?>')" class="btn btn-primary me-4">Mark Pendency</button>
      <button type="button" onclick="approveDocuments(<?= $id ?>)" class="btn btn-primary">Approved</button>

    </div>
  </div>

  <script type="text/javascript">
    function viewImage(id) {
      var viewer = new Viewer(document.getElementById(id), {
        inline: false,
        toolbar: false,
        viewed() {
          viewer.zoomTo(0.6);
        },
      });
    }

    function approveDocuments(id) {
      $(".modal").modal("hide");
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Approve'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: '/app/applications/approve-documents',
            type: 'POST',
            data: {
              id
            },
            dataType: 'json',
            success: function(data) {
              if (data.status) {
                
                toastr.success(data.message);
                $('.table').DataTable().ajax.reload(null, false);
              } else {
                toastr.error(data.message);
              }
            }
          })
        } else {
          $('.table').DataTable().ajax.reload(null, false);
        }
      })
    }
  </script>
<?php }
?>
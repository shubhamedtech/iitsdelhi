<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-top.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-bottom.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/side-menu.php') ?>
<?php require ($_SERVER['DOCUMENT_ROOT'] .'/includes/db-config.php'); ?>

<div class="layout-page">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/top-menu.php') ?>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <ol class="breadcrumb d-flex flex-wrap justify-content-between align-self-start">
                <?php $breadcrumbs = array_filter(explode("/", $_SERVER['REQUEST_URI']));
                for ($i = 1; $i <= count($breadcrumbs); $i++) {
                    if (count($breadcrumbs) == $i) : $active = "active";
                        $crumb = explode("?", $breadcrumbs[$i]);
                        echo '<li class="breadcrumb-item ' . $active . '">' . strtoupper($crumb[0]) . '</li>';
                    endif;
                }
                ?>
            </ol>
            <div class="row mt-5 justify-content-center"  id="main-content">
                <?php if ($_SESSION['Role'] == 'Administrator') {
                    $universities = $conn->query("SELECT ID, Short_Name, Vertical, Logo FROM Universities");
                    if ($universities->num_rows > 0) {
                        while ($university = $universities->fetch_assoc()) { ?>
                            <div class="col-sm-3 text-center" onclick="getComponents('<?php echo base64_encode($university['ID']) ?>')">
                                <div class="card">
                                    <div class="card-body  m-auto">
                                        
                                        <div class="text-center">
                                            <img src="<?php echo $university['Logo'] ?>" style="max-width:100% !important" height="100px">
                                            <div>
                                                <p>
                                                    <?php echo $university['Short_Name'] . " (" . $university['Vertical'] . ")" ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="col-sm-6 mt-5 pt-5">
                            <div class="card pt-5 pb-5">
                                <div class="card-body mt-5 mb-5">
                                    <h1 class="semi-bold text-center pt-5">Please add <a href="/academics/universities"><span class="btn btn-primary waves-effect waves-light">University</span></a></h1>

                                </div>
                            </div>
                        </div>
                <?php }
                }
                ?>
            </div>
         
        </div>
    </div>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-top.php') ?>
    
<script type="text/javascript">

function getComponents(id){
  $.ajax({
    url:'/app/components/main?id='+id,
    type: "GET",
    success: function(data){
      $("#main-content").html(data);
    }
  })
}

<?php if($_SESSION['Role']=='University Head'){ ?>
  getComponents('<?php echo base64_encode($_SESSION['university_id']) ?>');
<?php } ?>

function addComponents(url, modal, university_id){
  $.ajax({
    url: '/app/components/'+url+'/create?university_id='+university_id,
    type: 'GET',
    success: function(data){
      $('#'+modal+'-modal-content').html(data);
      $('#'+modal+'modal').modal('show');
    }
  })
}

function editComponents(url, id, modal){
  $.ajax({
    url: '/app/components/'+url+'/edit?id='+id,
    type: 'GET',
    success: function(data){
      $('#'+modal+'-modal-content').html(data);
      $('#'+modal+'modal').modal('show');
    }
  })
}

function changeComponentStatus(table, datatable, id){
  $.ajax({
    url: '/app/status/update',
    type: 'post',
    data: {"table": table, "id": id},
    dataType: 'json',
    success: function(data) {
      if(data.status==200){
        toastr.success( data.message);
        $('#table'+datatable).DataTable().ajax.reload(null, false);;
      }else{
        toastr.error(data.message);
        $('#table'+datatable).DataTable().ajax.reload(null, false);;
      }
    }
  });
}

function destroyComponents(url, table, id){
  // console.log(id);return false;

  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
  // console.log("/app/components/"+url+"/destroy?id="+id);return false;

    if (result.isConfirmed) {
      $.ajax({
        url: "/app/components/"+url+"/destroy?id="+id,
        type: 'DELETE',
        dataType: 'json',
        success: function(data) {
  // console.log(id);return false;

          if(data.status==200){
            toastr.success( data.message);
            $('#table'+table).DataTable().ajax.reload(null, false);;
          }else{
            toastr.error(data.message);
          }
        }
      });
    }
  })
}
</script>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-bottom.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-top.php') ?>
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css"> -->

<style>
    .carousel-container {
        height: 400px;
        overflow: hidden;
        position: relative;
    }

    .carousel {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        animation: scroll 60s linear infinite;
    }

    .carousel-item {
        flex-shrink: 0;
        height: 64px;
    }

    @keyframes scroll {
        0% {
            transform: translateY(0);
        }

        100% {
            transform: translateY(-100%);
        }
    }
</style>

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
            <div class="row gy-6 justify-content-center">
                <!-- <div class="col-lg-4 col-sm-6">
                    <div class="card">
                        <div class="row">
                            <div class="col-6">
                                <div class="card-body">
                                    <div class="card-info mb-5">
                                        <h6 class="mb-2 text-nowrap fw-bold text-black">Total centers</h6>
                                    </div>
                                    <?php
                                    $all_count = $conn->query("SELECT COUNT(ID) as allcount FROM Users WHERE Role = 'Center' ");
                                    $records = mysqli_fetch_assoc($all_count);
                                    $totalRecords = $records['allcount'];
                                    ?>
                                    <div class="d-flex align-items-center">
                                        <h4 class="mb-0 me-2 text-black fw-bold" style="font-size: 60px;"><?= $totalRecords ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 text-end d-flex align-items-end">
                                <div class="card-body pb-0 pt-4">
                                    <img src="../../assets/img/illustrations/card-ratings-illustration.png" alt="Ratings" class="img-fluid" width="95">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="col-lg-4 col-sm-6">
                    <div class="card">
                        <div class="row">
                            <div class="col-6">
                                <div class="card-body">
                                    <div class="card-info mb-5">
                                        <h6 class="mb-2 text-nowrap text-black fw-bold">Total students</h6>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <?php
                                        $all_count = $conn->query("SELECT COUNT(ID) as allcount FROM Students");
                                        $records = mysqli_fetch_assoc($all_count);
                                        $totalRecords = $records['allcount'];
                                        ?>
                                        <h4 class="mb-0 me-2 text-black fw-bold" style="font-size: 60px;"><?= $totalRecords ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 text-end d-flex align-items-end">
                                <div class="card-body pb-0 pt-4">
                                    <img src="../../assets/img/other-web/student.jpg" alt="Ratings" class="img-fluid" width="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="card">
                        <div class="row">
                            <div class="col-6">
                                <div class="card-body">
                                    <div class="card-info mb-5">
                                        <h6 class="mb-2 text-nowrap fw-bold text-black">Totals Programs</h6>
                                    </div>
                                    <div class="d-flex align-items-end d-flex align-items-center">
                                        <?php
                                        $all_count = $conn->query("SELECT COUNT(ID) as allcount FROM Sub_Courses WHERE Status=1");
                                        $records = mysqli_fetch_assoc($all_count);
                                        $totalRecords = $records['allcount'];
                                        ?>
                                        <h4 class="mb-0 me-2 text-black fw-bold" style="font-size: 60px;"><?= $totalRecords ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 text-end d-flex align-items-end">
                                <div class="card-body pb-0 pt-4">
                                    <img src="../../assets/img/other-web/students-2.webp" alt="Ratings" class="img-fluid" width="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gy-6 mt-4 ">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <div id="DataTables_Table_3_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="row">


                                </div>
                                <div class="table-responsive">
                                    <table class="dt-multilingual ctable table table-bordered dataTable no-footer dtr-column" id="DataTables_Table_3" aria-describedby="DataTables_Table_3_info">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Student Code</th>
                                            <th>DOB</th>
                                            <th>Created At</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $centers = $conn->query("SELECT * FROM Students WHERE University_ID IN (" . $_SESSION['university_ids'] . ") AND Added_For =  " . $_SESSION['ID'] . " ");
                                            if($centers->num_rows>0){
                                            while ($row = $centers->fetch_assoc()) {
                                        ?>
                                            <tr>
                                                <td><?= $row['First_Name']?></td>
                                                <td><?= $row['Unique_ID']?></td>
                                                <td><?= $row['DOB']?></td>
                                                <td><?= $row['Created_At']?></td>
                                                <td><?php  if( $row['Status'] == 1){  ?>  <span class="badge bg-label-success">Active</span> 
                                                    <?php  } else {  ?>  <span class="badge bg-label-danger">Inactive</span>
                                                    <?php  } ?>  
                                                </td>
                                            </tr>
                                        <?php } } ?>
                                    </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-top.php') ?>

    <script>
        $(document).ready(function() {
            $('#DataTables_Table_3').DataTable({
                "iDisplayLength": 2
            });
            document.getElementById('admin_dashboard_search').addEventListener('keyup', function() {
                $('#DataTables_Table_3').DataTable().search(this.value).draw();
            });
        });
        function view_content(id) {
      $.ajax({
        url: '/app/notifications/contents?id=' + id,
        type: 'GET',
        success: function(data) {
          $("#md-modal-content").html(data);
          $("#mdmodal").modal('show');
        }
      })
    }

    function show_notification(id){
        $.ajax({
        url: '/app/notifications/current-notification?id=' + id,
        type: 'GET',
        success: function(data) {
            $("#md-modal-content").html(data);
            $("#mdmodal").modal('show');
        }
      })
    }
    </script>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer-bottom.php') ?>
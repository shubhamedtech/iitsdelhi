<?php 
session_start();
include('./includes/header-top.php');
?>
<?php include('./includes/header-bottom.php'); ?>
<?php include('./includes/side-menu.php'); ?>

<div class="layout-page">
  <?php include('./includes/top-menu.php'); ?>
  <div class="content-wrapper">

    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
      <?php
      if ($_SESSION['Role'] == 'Student') {
        include('dashboards/student.php');
      }
      if ($_SESSION['Role'] == 'Center') { ?>
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="card">
              <div class="table-responsive mb-4">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                  <div class="card-header pt-sm-0 pb-0 flex-column flex-sm-row">
                    <div class="head-label text-center">
                      <h5 class="card-title mb-0 text-nowrap">Course you are taking</h5>
                    </div>
                    <div id="DataTables_Table_0_filter" class="dataTables_filter">
                      <label>
                        <input type="search" class="form-control form-control-sm" placeholder="Search Course" aria-controls="DataTables_Table_0">
                      </label>
                    </div>
                  </div>
                  <table class="table datatables-academy-course dataTable no-footer dtr-column" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                    <thead>
                      <?php
                      $head = $conn->query("SELECT UPPER(Name) as Name, LOWER(Email) as Email, Mobile, Role FROM Users LEFT JOIN University_User ON Users.ID = University_User.User_ID WHERE University_User.University_ID = " . $_SESSION['university_id'] . " AND Users.Role = 'University Head'");
                      if ($head->num_rows > 0) {
                        $head = $head->fetch_assoc();
                      ?>
                        <tr>
                          <th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" style="width: 52.1875px; display: none;" aria-label=""><?= $head['Role'] ?></th>
                          <th class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all" rowspan="1" colspan="1" style="width: 52.1875px;" data-col="1" aria-label=""><?= $head['Name'] ?></th>
                          <th class="sorting sorting_desc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 285.172px;" aria-sort="descending" aria-label="Course Name: activate to sort column ascending"><?= $head['Email'] ?></th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 134.453px;" aria-label="Time: activate to sort column ascending"><?= $head['Mobile'] ?></th>
                        </tr>
                      <?php
                      }
                      ?>
                      <?php
                      $counsellor = $conn->query("SELECT UPPER(Name) as Name, LOWER(Email) as Email, Mobile, Role FROM Alloted_Center_To_Counsellor LEFT JOIN Users ON Alloted_Center_To_Counsellor.Counsellor_ID = Users.ID WHERE Alloted_Center_To_Counsellor.University_ID = " . $_SESSION['university_id'] . " AND Alloted_Center_To_Counsellor.Code = " . $_SESSION['ID']);
                      if ($counsellor->num_rows > 0) {
                        $counsellor = $counsellor->fetch_assoc();
                      ?>
                        <tr>
                          <td class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" style="width: 52.1875px; display: none;" aria-label=""><?= $counsellor['Role'] ?></td>
                          <td class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all" rowspan="1" colspan="1" style="width: 52.1875px;" data-col="1" aria-label=""><?= $counsellor['Name'] ?></td>
                          <td class="sorting sorting_desc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 285.172px;" aria-sort="descending" aria-label="Course Name: activate to sort column ascending"><?= $counsellor['Email'] ?></td>
                          <td class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 134.453px;" aria-label="Time: activate to sort column ascending"><?= $counsellor['Mobile'] ?></td>
                        </tr>
                      <?php
                      }
                      ?>
                      <?php
                      $accountant = $conn->query("SELECT UPPER(Name) as Name, LOWER(Email) as Email, Mobile, Role FROM Users LEFT JOIN University_User ON Users.ID = University_User.User_ID WHERE University_User.University_ID = " . $_SESSION['university_id'] . " AND Users.Role = 'Accountant'");
                      if ($accountant->num_rows > 0) {
                        $accountant = $accountant->fetch_assoc();
                      ?>
                        <tr>
                          <td class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" style="width: 52.1875px; display: none;" aria-label=""><?= $accountant['Role'] ?></td>
                          <td class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all" rowspan="1" colspan="1" style="width: 52.1875px;" data-col="1" aria-label=""><?= $accountant['Name'] ?></td>
                          <td class="sorting sorting_desc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 285.172px;" aria-sort="descending" aria-label="Course Name: activate to sort column ascending"><?= $accountant['Email'] ?></td>
                          <td class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 134.453px;" aria-label="Time: activate to sort column ascending"><?= $accountant['Mobile'] ?></td>
                        </tr>
                      <?php
                      }
                      ?>
                    </thead>
                    <tbody>
                      <tr class="odd">
                        <td valign="top" colspan="5" class="dataTables_empty">Loading...</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="row mx-4">
                    <div class="col-md-6 col-12 text-center text-md-start pb-2 pb-xl-0 px-0">
                      <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 0 to 0 of 0 entries</div>
                    </div>
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end px-0">
                      <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                        <ul class="pagination">
                          <li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous"><a aria-controls="DataTables_Table_0" aria-disabled="true" role="link" data-dt-idx="previous" tabindex="-1" class="page-link">Previous</a></li>
                          <li class="paginate_button page-item next disabled" id="DataTables_Table_0_next"><a aria-controls="DataTables_Table_0" aria-disabled="true" role="link" data-dt-idx="next" tabindex="-1" class="page-link">Next</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } else if ($_SESSION['Role'] == 'Exam Student') {
        include('dashboards/exam-student-dashborad.php');
      } ?>
    </div>

  </div>
  <?php include('./includes/footer-top.php'); ?>
  <?php include('./includes/footer-bottom.php'); ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    var table = $('#DataTables_Table_0').DataTable();
    
    $('#DataTables_Table_0_filter input').on('keyup', function() {
      table.search(this.value).draw();
    });
  });
</script>

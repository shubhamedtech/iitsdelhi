<?php
if (isset($_POST['id']) && isset($_POST['university_id']) && isset($_POST['name'])) {
    require '../../includes/db-config.php';
    session_start();
    $id = intval($_POST['id']);
    $university_id = mysqli_real_escape_string($conn, $_POST['university_id']);
    $university_id_arr = explode(',', $university_id);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $durations = '';
    $university_is_vocational = 0;
    $is_vocational = $conn->query("SELECT ID FROM Universities WHERE ID IN ($university_id) AND Is_Vocational = 1");
    if ($is_vocational->num_rows > 0) {
        $university_is_vocational = 1;
    }

    $university_array = array();
    $course_types_array = [];
    $existingCources = array();
    $alloted_course_types = $conn->query("SELECT Center_Sub_Courses.Course_ID, Center_Sub_Courses.University_ID,Courses.Name, Courses.University_ID as c_university FROM Center_Sub_Courses left join Courses on Courses.ID=Center_Sub_Courses.Course_ID WHERE Center_Sub_Courses.`User_ID` = $id AND Center_Sub_Courses.University_ID IN ($university_id) GROUP BY Center_Sub_Courses.Course_ID");
    while ($alloted_course_type = $alloted_course_types->fetch_assoc()) {
        $existingCources[] = array('ID' => $alloted_course_type['Course_ID'], "Name" => $alloted_course_type['Name'], 'University_ID' => $alloted_course_type['c_university']);
        // $existingCources[] = array('ID' => $alloted_course_type['Course_ID']);

        $course_types_array[] = $alloted_course_type['Course_ID'];
        $university_array[] = $alloted_course_type['University_ID'];
    }
    $university_ids = implode(',', $university_array);
    $course_ids = implode(',', $course_types_array);
    // echo "<pre>"; print_r($existingCources);
?>

    <style>
        .modal-dialog.modal-lg {
            width: 100% !important;
        }
    </style>
    <!-- Modal -->
    <div class="modal-header clearfix text-left m-0 p-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <h5 class="fs-4 fw-bold text-black">Allot Specializations <span class="semi-bold"></span></h5>
    </div>

    <form class="card-body" role="form" id="form-allot-center-master" action="/app/center-master/allot-sub-courses" method="POST" enctype="multipart/form-data">
        <div class="row mb-4">
            <div class="col-sm-12 mb-2 select2-primary">
                <label class="col-form-label" for="university_id">
                    University<span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                    <select id="university_id" class="form-select select2" name="university_id[]" multiple onchange="getCourseType(this);" data-dropdown-parent="#form-allot-center-master">
                        <?php
                        $universities = $conn->query("SELECT Universities.ID, CONCAT(Universities.Short_Name, ' (', Universities.Vertical, ')') as Name FROM Universities LEFT JOIN Alloted_Center_To_Counsellor as ac ON Universities.ID = ac.University_ID  WHERE ac.Code = $id AND Universities.status=1");
                        while ($university = $universities->fetch_assoc()) { ?>
                            <option value="<?= $university['ID'] ?>" <?= !empty($university_array) ? (in_array($university['ID'], $university_array) ? 'selected' : '') : '' ?>><?= $university['Name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-12 mb-2 select2-primary">
                <label class="col-form-label" for="course_type">
                    Academic Eligibility <span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                    <select id="course_type" class="form-select" name="course_type[]" multiple onchange="getSubCourse(this);" data-dropdown-parent="#form-allot-center-master">
                        <?php if (count($existingCources) > 0) {
                            foreach ($existingCources as $course) { 
                       
                            ?>
                                <option value="<?= $course['ID'] . '|' . $course['University_ID'] ?>" selected> <?= $course['Name'] ?> </option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row mt-3 mb-2">
            <div class="col-md-3">
                <div class="form-group">
                    <dt class="pt-1">University</dt>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <dt class="pt-1">Sub Course Name</dt>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <dt class="pt-1">Session</dt>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <input type="checkbox" name="select_all" id="select_all">
                    <label for="select_all"> All</label>
                </div>
            </div>
        </div>
        <div id="subcourse"></div>

        <div class="pt-6">
            <div class="row">
                <div class="col-sm-12 d-flex justify-content-between">
                    <span type="submit" class="mt-2 text-start" onclick="removeAllotment()"><i class="ms-3 ri-delete-bin-5-line text-danger" title="Allotment remove"></i></span>
                    <div class="justify-content-end">
                        <button type="submit" class="btn btn-primary me-4">Submit</button>
                        <button type="button"  data-bs-dismiss="modal" class="btn btn-outline-secondary">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(function() {
            $('#university_id').select2({
                placeholder: "Select an option",
                dropdownParent: $('#form-allot-center-master'),
                width: 'style'
            });

            $('#course_type').select2({
                placeholder: "Select an option",
                dropdownParent: $('#form-allot-center-master'),
                width: 'style'
            });

            // If there are existing courses, populate the subcourses
    
            <?php if (count($existingCources) > 0) { ?>
                getCourseType(document.getElementById('university_id'),'<?= $course_ids ?>' );
            <?php } ?>
            <?php if (count($university_array) > 0) { ?>
                getSubCourse(document.getElementById('course_type'));
            <?php } ?>
        });

        function getCourseType(selectElement, course_id=null) {
            var university_ids = Array.from(selectElement.selectedOptions, option => option.value);
            $.ajax({
                url: '/app/center-master/cources',
                type: 'POST',
                dataType: 'text',
                data: {
                    'university_ids': university_ids, course_id:course_id,
                },
                success: function(result) {
                    $('#course_type').html(result).trigger('change');
                }
            });
        }

        function getSubCourse(selectElement) {
            var ids = Array.from(selectElement.selectedOptions, option => option.value);

            var center_id = '<?= $id ?>';
            var university_id = '<?= $university_id ?>';
            $.ajax({
                url: '/app/center-master/sub-cources',
                type: 'POST',
                dataType: 'text',
                data: {
                    'course_id': ids,
                    'center_id': center_id,
                    'university_id': university_id
                },
                success: function(result) {
                    $('#subcourse').html(result);
                    $('.adm_session').select2({
                        placeholder: "Select an option",
                        dropdownParent: $('#form-allot-center-master'),
                        width: 'style'
                    });
                }
            });
        }

        function removeAllotment() {
            const id = '<?= $id ?>';
            const university_id = '<?= $university_id ?>';
            $.ajax({
                url: '/app/center-master/remove-allotment',
                type: 'POST',
                data: {
                    id: id,
                    university_id: university_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 200) {
                        $('.modal').modal('hide');
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }

        $(function() {
            $('#form-allot-center-master').validate({
                rules: {
                    counsellor: {
                        required: true
                    },
                    'fee[]': {
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
        });

        $("#form-allot-center-master").on("submit", function(e) {
            if ($('#form-allot-center-master').valid()) {
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
                            $('#sub-courses-table').DataTable().ajax.reload(null, false);
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
<?php } ?>

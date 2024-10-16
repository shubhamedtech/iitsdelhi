                     <div id="basic-details" class="content" style="display:block">
                         <form id="step_1" role="form" autocomplete="off" action="/app/application-form/step-1" enctype="multipart/form-data">
                             <div class="content-header mb-4">
                                 <h6 class="mb-0 fs-5 text-black fw-bold">APPLYING FOR</h6>
                                 <!-- <small>Enter Your Account Details.</small> -->
                             </div>
                             <?php
                                $inserted_id = 0;
                                $id = 0;

                                if (isset($_GET['id'])) {
                                    $inserted_id = $_GET['id'];
                                    $id = mysqli_real_escape_string($conn, $_GET['id']);
                                    $id = base64_decode($id);
                                    $id = intval(str_replace('W1Ebt1IhGN3ZOLplom9I', '', $id));
                                }
                                // echo $student['First_Name'];
                                $student_name = !empty($id) ? $student['First_Name'] . " " . $student['Middle_Name'] . " " . $student['Last_Name'] : '';
                                ?>
                             <div class="row g-5">
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label m-0 p-0" for="multicol-full-name">Center <span class="text-danger">*</span></label>
                                     <select class="select2 form-select center" data-allow-clear="true" name="center" id="center" onchange="getUniversity(this.value)">
                                         <option value="">Select</option>
                                         <?php
                                            $role_query = '';
                                            $selected = '';
                                            if ($_SESSION['Role'] == "Center") {
                                                $role_query = 'AND ID =' . $_SESSION['ID'];
                                                $selected = "selected";
                                            }
                                            $get_center_users = $conn->query("SELECT ID, Name FROM Users WHERE Role = 'Center' AND Status = 1 AND University_ID IS NOT NULL $role_query");
                                            while ($row = $get_center_users->fetch_assoc()) {
                                                $student = isset($student) ? $student : null;
                                                $student_added_for = $student !== null && isset($student['Added_For']) ? $student['Added_For'] : null;

                                            ?>
                                             <option value="<?= $row['ID'] ?>" <?= $student_added_for === $row['ID'] ? "selected" : "" ?> <?= $selected; ?>> <?= $row['Name'] ?></option>
                                         <?php }
                                            ?>

                                     </select>
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">University <span class="text-danger">*</span></label>
                                     <select id="university_id" class="select2 form-select university_id" data-allow-clear="true" name="university_id" onchange="getAdmissionSession(this.value);">
                                         <option value="">Choose</option>
                                     </select>
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Admission Session <span class="text-danger">*</span></label>
                                     <select class="select2 form-select " data-allow-clear="true" name="admission_session" id="admission_session" onchange="getAdmissionType(this.value)">
                                         <option value="">Select</option>

                                     </select>
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Admission Type <span class="text-danger">*</span></label>
                                     <select class="select2 form-select" data-allow-clear="true" name="admission_type" id="admission_type" onchange="getCourse()">
                                         <option value="">Select</option>
                                     </select>
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Course <span class="text-danger">*</span></label>
                                     <select class="select2 form-select" data-allow-clear="true" name="course" id="course" onchange="getSubCourse()">
                                         <option value="">Select</option>

                                     </select>
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Sub Course <span class="text-danger">*</span></label>
                                     <select class="select2 form-select" data-allow-clear="true" name="sub_course" id="sub_course" onchange="getDuration(), getEligibility();">
                                         <option value="">Select</option>
                                     </select>
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Mode <span class="text-danger">*</span></label>
                                     <select class="select2 form-select" data-allow-clear="true" name="duration" id="duration">
                                         <option value="">Select</option>
                                     </select>
                                 </div>
                                 <div class="content-header mb-1">
                                     <h6 class="mb-0 fs-5 text-black fw-bold">BASIC DETAILS</h6>
                                     <!-- <small>Enter Your Account Details.</small> -->
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Full Name <span class="text-danger">*</span></label>
                                     <?php //$student_name = !empty($id) ? array_filter(array($student['First_Name'], $student['Middle_Name'], $student['Last_Name'])) : [] 
                                        ?>

                                     <input type="text" id="full_name" name="full_name" class="form-control" placeholder="ex: Jhon Doe" value="<?= $student_name ?>" required />
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Father Name <span class="text-danger">*</span></label>
                                     <input type="text" id="father_name" name="father_name" class="form-control" value="<?php print !empty($id) ? $student['Father_Name'] : "" ?>" placeholder="enter father name" required />
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Mother Name <span class="text-danger">*</span></label>
                                     <input type="text" id="mother_name" name="mother_name" class="form-control" value="<?php print !empty($id) ? $student['Mother_Name'] : "" ?>" class="form-control" placeholder="enter mother name" required />
                                 </div>
                                 <!--<div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">DOB <span class="text-danger">*</span></label>
                                     <div class="form-floating form-floating-outline mb-6">
                                         <input class="form-control" type="date" id="html5-date-input" name="dob" value="<?php print !empty($id) ? date('d-m-Y', strtotime($student['DOB'])) : "" ?>" placeholder="dd-mm-yyyy" required>

                                     </div>
                                 </div>-->
                                <div class="col-sm-3 mb-3">
                                     <label class="col-form-label m-0 p-0" for="dob">DOB <span class="text-danger">*</span></label>
                                     <div class="form-floating form-floating-outline input-group mb-6">
                                         <input class="form-control" type="tel" id="dob" name="dob" value="<?php echo !empty($id) ? date('d-m-Y', strtotime($student['DOB'])) : ''; ?>" placeholder="DD-MM-YYYY" data-input>
                                         <a class="input-button input-group-text" title="toggle" data-toggle>
                                             <i class="ri-calendar-line"></i>
                                         </a>
                                     </div>
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Gender <span class="text-danger">*</span></label>
                                     <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="gender">
                                         <option value="">Select</option>
                                         <option value="Male" <?php print !empty($id) ? ($student['Gender'] == 'Male' ? 'selected' : '') : '' ?>>Male</option>
                                         <option value="Female" <?php print !empty($id) ? ($student['Gender'] == 'Female' ? 'selected' : '') : '' ?>>Female</option>
                                         <option value="Other" <?php print !empty($id) ? ($student['Gender'] == 'Other' ? 'selected' : '') : '' ?>>Other</option>
                                     </select>
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Category <span class="text-danger">*</span></label>
                                     <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="category">
                                         <option value="">Select</option>
                                         <option value="General" <?php print !empty($id) ? ($student['Category'] == 'General' ? 'selected' : '') : '' ?>>General</option>
                                         <option value="OBC" <?php print !empty($id) ? ($student['Category'] == 'OBC' ? 'selected' : '') : '' ?>>OBC</option>
                                         <option value="SC" <?php print !empty($id) ? ($student['Category'] == 'SC' ? 'selected' : '') : '' ?>>SC</option>
                                         <option value="ST" <?php print !empty($id) ? ($student['Category'] == 'ST' ? 'selected' : '') : '' ?>>ST</option>
                                     </select>
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Employment Status <span class="text-danger">*</span></label>
                                     <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="employment_status">
                                         <option value="">Select</option>
                                         <option value="Employed" <?php print !empty($id) ? ($student['Employement_Status'] == 'Employed' ? 'selected' : '') : '' ?>>Employed</option>
                                         <option value="Unemployed" <?php print !empty($id) ? ($student['Employement_Status'] == 'Unemployed' ? 'selected' : '') : '' ?>>Unemployed</option>
                                     </select>
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Marital Status <span class="text-danger">*</span></label>
                                     <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="marital_status">
                                         <option value="">Select</option>
                                         <option value="Married" <?php print !empty($id) ? ($student['Marital_Status'] == 'Married' ? 'selected' : '') : '' ?>>Married</option>
                                         <option value="Unmarried" <?php print !empty($id) ? ($student['Marital_Status'] == 'Unmarried' ? 'selected' : '') : '' ?>>Unmarried</option>
                                     </select>
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Religion <span class="text-danger">*</span></label>
                                     <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="religion">
                                         <option value="">Select</option>
                                         <option value="Hindu" <?php print !empty($id) ? ($student['Religion'] == 'Hindu' ? 'selected' : '') : '' ?>>Hindu</option>
                                         <option value="Muslim" <?php print !empty($id) ? ($student['Religion'] == 'Muslim' ? 'selected' : '') : '' ?>>Muslim</option>
                                         <option value="Sikh" <?php print !empty($id) ? ($student['Religion'] == 'Sikh' ? 'selected' : '') : '' ?>>Sikh</option>
                                         <option value="Christian" <?php print !empty($id) ? ($student['Religion'] == 'Christian' ? 'selected' : '') : '' ?>>Christian</option>
                                         <option value="Jain" <?php print !empty($id) ? ($student['Religion'] == 'Jain' ? 'selected' : '') : '' ?>>Jain</option>
                                     </select>
                                 </div>

                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Aadhar <span class="text-danger">*</span></label>
                                     <input type="tel" maxlength="14" minlength="14" name="aadhar" value="<?php print !empty($id) ? $student['Aadhar_Number'] : '' ?>" class="form-control" placeholder="ex: XYZ Aadhar no." id="aadhar" required />
                                 </div>
                                 <div class="col-sm-3 mb-3">
                                     <label class=" col-form-label  m-0 p-0" for="multicol-full-name">Nationality <span class="text-danger">*</span></label>
                                     <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="nationality">
                                         <option value="">Select</option>
                                         <option value="Indian" <?php print !empty($id) ? ($student['Nationality'] == 'Indian' ? 'selected' : '') : '' ?>>Indian</option>
                                         <option value="NRI" <?php print !empty($id) ? ($student['Nationality'] == 'NRI' ? 'selected' : '') : '' ?>>NRI</option>
                                     </select>
                                 </div>

                             </div>
                             <div class="col-12 d-flex justify-content-between mt-4">
                                <!-- <button class="btn btn-outline-secondary btn-prev" disabled> <i class="ri-arrow-left-line me-sm-1"></i>
                                     <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                 </button>-->
                                 <button type="submit" class="btn btn-primary me-4 btn-next"><span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="ri-arrow-right-line"></i></button>
                                 <!-- <button type="submit" class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="ri-arrow-right-line"></i></button> -->
                             </div>

                         </form>

                     </div>
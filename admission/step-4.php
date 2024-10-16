<div id="documents" class="content" style="display: block;">


    <form id="step_4" role="form" action="/app/application-form/step-4" enctype="multipart/form-data">
        <?php
        $inserted_id = '';
        $id = '';

        if (isset($_GET['id'])) {
            $inserted_id = $_GET['id'];
            $id = mysqli_real_escape_string($conn, $_GET['id']);
            $id = base64_decode($id);
            $id = intval(str_replace('W1Ebt1IhGN3ZOLplom9I', '', $id));
        }
        ?>
        <input type="hidden" name="inserted_id" id="inserted_id" value="<?php echo $id ?>">
        <?php

        if (!empty($id)) {
            $photo = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Photo'");
            $photo = mysqli_fetch_array($photo);
        }
        ?>
        <div class="content-header mb-1">
            <h6 class="mb-0 fs-5 mb-5 text-black fw-bold">DOCUMENT</h6>
            <!-- <small>Enter Your Account Details.</small> -->
        </div>
        <div class="row g-5">
            <div class="col-sm-3 mb-3">
                <label class=" col-form-label m-0 p-0" for="multicol-full-name">Photo <span
                        class="text-danger">*</span></label>
                        <?php if(!empty($id) && !empty($photo)) {
                            $photo_required = "";
                        }else{
                            $photo_required = "required";
                        } ?>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                    onchange="fileValidation('photo');" id="photo" name="photo" <?=   $photo_required ?>>
                <?php if (!empty($id) && !empty($photo)) { ?>
                    <img src="<?php print !empty($id) ? $photo['Location'] : '' ?>" height="100" />
                <?php } ?>
            </div>
              <?php if (!empty($id)) {
                $aadhaars = array();
                $aadhaar = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Aadhar'");
                if ($aadhaar->num_rows > 0) {
                    $aadhaar = mysqli_fetch_array($aadhaar);
                    $aadhaars = explode("|", $aadhaar['Location']);
                }
              }
              ?>
            <div class="col-sm-3 mb-3 ">
                <label class=" col-form-label m-0 p-0 " for="multicol-full-name">Aadhar <span
                        class="text-danger">*</span></label>
                        <?php if(!empty($id) && !empty($aadhaars)) {
                            $aadhar_required = "";
                        }else{
                            $aadhar_required = "required";
                        } ?>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                    onchange="fileValidation('aadhar');" id="aadhar" name="aadhar[]" multiple="multiple" <?=  $aadhar_required  ?>>
                <?php if (!empty($id) && !empty($aadhaars)) {
                    foreach ($aadhaars as $aadhar) { ?>
                        <img src="<?php print !empty($id) ? $aadhar : '' ?>" height="80" />
                    <?php }
                } ?>
            </div>
            <?php
            if (!empty($id)) {
                $students_signature = "";
                $student_signature = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Student Signature'");
                if ($student_signature->num_rows > 0) {
                    $student_signature = mysqli_fetch_array($student_signature);
                    $students_signature = $student_signature['Location'];
                }
            }
            ?>
            <div class="col-sm-3  mb-3">
                <label class=" col-form-label m-0 p-0" for="multicol-full-name">Student's Signature <span
                        class="text-danger">*</span></label>
                        <?php if(!empty($id) && !empty($students_signature)) {
                            $students_signature_required = "";
                        }else{
                            $students_signature_required = "required";
                        } ?>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                    onchange="fileValidation('student_signature');" id="student_signature" name="student_signature"
                    <?=  $students_signature_required ?>>
                <?php if (!empty($id) && !empty($students_signature)) { ?>
                    <img src="<?php print !empty($id) ? $students_signature : '' ?>" height="100" />
                <?php } ?>
            </div>
            <?php
            if (!empty($id)) {
                $parents_signature = "";
                $parent_signature = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Parent Signature'");
                if ($parent_signature->num_rows > 0) {
                    $parent_signature = mysqli_fetch_array($parent_signature);
                    $parents_signature = $parent_signature['Location'];
                }
            }
            ?>
            <div class="col-sm-3  mb-3">
                <label class=" col-form-label m-0 p-0" for="multicol-full-name">Parent's Signature <label>
                        <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                            onchange="fileValidation('parent_signature');" id="parent_signature"
                            name="parent_signature">
                        <?php if (!empty($id) && !empty($parents_signature)) { ?>
                            <img src="<?php print !empty($id) ? $parents_signature : '' ?>" height="100" />
                        <?php } ?>
            </div>
            <?php
            if (!empty($id)) {
                $migrations = array();
                $migration = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Migration'");
                if ($migration->num_rows > 0) {
                    $migration = mysqli_fetch_array($migration);
                    $migrations = explode("|", $migration['Location']);
                }
            }
            ?>
            <div class="col-sm-3  mb-3">
                <label class=" col-form-label m-0 p-0" for="multicol-full-name">Migration </label>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                    onchange="fileValidation('migration');" id="migration" name="migration[]" multiple="multiple">
                <?php if (!empty($id) && !empty($migrations)) {
                    foreach ($migrations as $migration) { ?>
                        <img src="<?php print !empty($id) ? $migration : '' ?>" height="80" />
                    <?php }
                } ?>
            </div>
            <?php
            if (!empty($id)) {
                $affidavits = array();
                $affidavit = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Affidavit'");
                if ($affidavit->num_rows > 0) {
                    $affidavit = mysqli_fetch_array($affidavit);
                    $affidavits = explode("|", $affidavit['Location']);
                }
            }
            ?>
            <div class="col-sm-3  mb-3">
                <label class=" col-form-label m-0 p-0" for="multicol-full-name">Affidavit </label>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                    onchange="fileValidation('affidavit');" id="affidavit" name="affidavit[]" multiple="multiple">
                <?php if (!empty($id) && !empty($affidavits)) {
                    foreach ($affidavits as $affidavit) { ?>
                        <img src="<?php print !empty($id) ? $affidavit : '' ?>" height="80" />
                    <?php }
                } ?>
            </div>
            <?php
            if (!empty($id)) {
                $other_certificates = array();
                $other_certificate = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Other Certificate'");
                if ($other_certificate->num_rows > 0) {
                    $other_certificate = mysqli_fetch_array($other_certificate);
                    $other_certificates = explode("|", $other_certificate['Location']);
                }
            }
            ?>
            <div class="col-sm-3  mb-3">
                <label class=" col-form-label m-0 p-0" for="multicol-full-name">Other Certificate </label>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                    onchange="fileValidation('other_certificate');" id="other_certificate" name="other_certificate[]"
                    multiple="multiple">
                <?php if (!empty($id) && !empty($other_certificates)) {
                    foreach ($other_certificates as $other_certificate) { ?>
                        <img src="<?php print !empty($id) ? $other_certificate : '' ?>" height="80" />
                    <?php }
                } ?>
            </div>
            <?php
            if (!empty($id)) {
                $degree_mark_list = "";
                $degree_mark_list = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Degree Mark List'");
                if ($degree_mark_list->num_rows > 0) {
                    $degree_mark_list = mysqli_fetch_array($degree_mark_list);
                    $degree_mark_lists = $degree_mark_list['Location'];
                }
            }
            ?>
            <div class="col-sm-3  mb-3">
                <label class=" col-form-label m-0 p-0" for="multicol-full-name"> Degree Mark List</label>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('degree_mark_list');" id="degree_mark_list" name="degree_mark_list">
                <?php if (!empty($id) && !empty($degree_mark_lists)) { ?>
                    <img src="<?php print !empty($id) ? $degree_mark_lists : '' ?>" height="100" />
                <?php } ?>
            </div>

            <?php if (!empty($id)) {
                $plus_two_arr = array();
                $plus_two = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Plus Two'");
                if ($plus_two->num_rows > 0) {
                    $plus_two = mysqli_fetch_array($plus_two);
                    $plus_two_arr = explode("|", $plus_two['Location']);
                }
              }
              ?>
            <div class="col-sm-3 mb-3 ">
                <label class=" col-form-label m-0 p-0 " for="multicol-full-name">Plus Two <span
                        class="text-danger">*</span></label>
                        <?php if(!empty($id) && !empty($plus_two_arr)) {
                            $plus_two_required = "";
                        }else{
                            $plus_two_required = "required";
                        } ?>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                    onchange="fileValidation('plus_two');" id="plus_two" name="plus_two[]" multiple="multiple" <?=  $plus_two_required  ?>>
                <?php if (!empty($id) && !empty($plus_two_arr)) {
                    foreach ($plus_two_arr as $plus_two) { ?>
                        <img src="<?php print !empty($id) ? $plus_two : '' ?>" height="80" />
                    <?php }
                } ?>
            </div>
            <?php if (!empty($id)) {
                $sslc_arr = array();
                $sslc = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'SSLC'");
                if ($sslc->num_rows > 0) {
                    $sslc = mysqli_fetch_array($sslc);
                    $sslc_arr = explode("|", $sslc['Location']);
                }
              }
              ?>
            <div class="col-sm-3 mb-3 ">
                <label class=" col-form-label m-0 p-0 " for="multicol-full-name">SSLC <span
                        class="text-danger">*</span></label>
                        <?php if(!empty($id) && !empty($sslc_arr)) {
                            $sslc_required = "";
                        }else{
                            $sslc_required = "required";
                        } ?>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                    onchange="fileValidation('sslc');" id="sslc" name="sslc[]" multiple="multiple" <?=  $sslc_required  ?>>
                <?php if (!empty($id) && !empty($sslc_arr)) {
                    foreach ($sslc_arr as $sslc) { ?>
                        <img src="<?php print !empty($id) ? $sslc : '' ?>" height="80" />
                    <?php }
                } ?>
            </div>

            <?php if (!empty($id)) {
                $app_form_arr = array();
                $app_form = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Application Form'");
                if ($app_form->num_rows > 0) {
                    $app_form = mysqli_fetch_array($app_form);
                    $app_form_arr = explode("|", $app_form['Location']);
                }
              }
              ?>
            <div class="col-sm-3 mb-3 ">
                <label class=" col-form-label m-0 p-0 " for="multicol-full-name">Application Form </label>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                    onchange="fileValidation('app_form');" id="app_form" name="app_form[]" multiple="multiple">
                <?php if (!empty($id) && !empty($app_form_arr)) {
                    foreach ($app_form_arr as $app_form) { ?>
                        <img src="<?php print !empty($id) ? $app_form : '' ?>" height="80" />
                    <?php }
                } ?>
            </div>

            <?php if (!empty($id)) {
                $birth_certificate_arr = array();
                $birth_certificate = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Birth Certificate'");
                if ($birth_certificate->num_rows > 0) {
                    $birth_certificate = mysqli_fetch_array($birth_certificate);
                    $birth_certificate_arr = explode("|", $birth_certificate['Location']);
                }
              }
              ?>
            <div class="col-sm-3 mb-3 ">
                <label class=" col-form-label m-0 p-0 " for="multicol-full-name">Birth Certificate</label>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                    onchange="fileValidation('birth_certificate');" id="birth_certificate" name="birth_certificate[]" multiple="multiple">
                <?php if (!empty($id) && !empty($birth_certificate_arr)) {
                    foreach ($birth_certificate_arr as $birth_certificate) { ?>
                        <img src="<?php print !empty($id) ? $birth_certificate : '' ?>" height="80" />
                    <?php }
                } ?>
            </div>


            <?php if (!empty($id)) {
                $certificate_arr = array();
                $certificate = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Certificate'");
                if ($certificate->num_rows > 0) {
                    $certificate = mysqli_fetch_array($certificate);
                    $certificate_arr = explode("|", $certificate['Location']);
                }
              }
              ?>
            <div class="col-sm-3 mb-3 ">
                <label class=" col-form-label m-0 p-0 " for="multicol-full-name"> Certificate</label>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                    onchange="fileValidation('certificate');" id="certificate" name="certificate[]" multiple="multiple">
                <?php if (!empty($id) && !empty($certificate_arr)) {
                    foreach ($certificate_arr as $certificate) { ?>
                        <img src="<?php print !empty($id) ? $certificate : '' ?>" height="80" />
                    <?php }
                } ?>
            </div>

            <?php if (!empty($id)) {
                $degree_arr = array();
                $degree = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Degree'");
                if ($degree->num_rows > 0) {
                    $degree = mysqli_fetch_array($degree);
                    $degree_arr = explode("|", $degree['Location']);
                }
              }
              ?>
            <div class="col-sm-3 mb-3 ">
                <label class=" col-form-label m-0 p-0 " for="multicol-full-name"> Degree</label>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                    onchange="fileValidation('degree');" id="degree" name="degree[]" multiple="multiple">
                <?php if (!empty($id) && !empty($degree_arr)) {
                    foreach ($degree_arr as $degree) { ?>
                        <img src="<?php print !empty($id) ? $degree : '' ?>" height="80" />
                    <?php }
                } ?>
            </div>

            <?php if (!empty($id)) {
                $deploma_arr = array();
                $deploma = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Deploma'");
                if ($deploma->num_rows > 0) {
                    $deploma = mysqli_fetch_array($deploma);
                    $deploma_arr = explode("|", $deploma['Location']);
                }
              }
              ?>
            <div class="col-sm-3 mb-3 ">
                <label class=" col-form-label m-0 p-0 " for="multicol-full-name"> Deploma</label>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                    onchange="fileValidation('deploma');" id="deploma" name="deploma[]" multiple="multiple">
                <?php if (!empty($id) && !empty($deploma_arr)) {
                    foreach ($deploma_arr as $deploma) { ?>
                        <img src="<?php print !empty($id) ? $deploma : '' ?>" height="80" />
                    <?php }
                } ?>
            </div>
            <?php if (!empty($id)) {
                $provisional_certificate_arr = array();
                $provisional_certificate = $conn->query("SELECT Location FROM Student_Documents WHERE Student_ID = $id AND Type = 'Provisional Certificate'");
                if ($provisional_certificate->num_rows > 0) {
                    $provisional_certificate = mysqli_fetch_array($provisional_certificate);
                    $provisional_certificate_arr = explode("|", $provisional_certificate['Location']);
                }
              }
              ?>
            <div class="col-sm-3 mb-3 ">
                <label class=" col-form-label m-0 p-0 " for="multicol-full-name"> Provisional Certificate</label>
                <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg"
                    onchange="fileValidation('provisional_certificate');" id="provisional_certificate" name="provisional_certificate[]" multiple="multiple">
                <?php if (!empty($id) && !empty($provisional_certificate_arr)) {
                    foreach ($provisional_certificate_arr as $provisional_certificate) { ?>
                        <img src="<?php print !empty($id) ? $provisional_certificate : '' ?>" height="80" />
                    <?php }
                } ?>
            </div>
            

            <div class="col-12 d-flex justify-content-between">
                <button class="btn btn-outline-secondary btn-prev"> <i class="ri-arrow-left-line me-sm-1"></i>
                    <span class="align-middle d-sm-inline-block d-none"><a
                            href="/admission/application-form?step=3&id=<?= $inserted_id ?>">Previous</a></span>
                </button>
                <button type="submit" class="btn btn-primary me-4 btn-next"><span
                        class="align-middle d-sm-inline-block d-none me-sm-1">Submit</span> <i
                        class="ri-arrow-right-line"></i></button>
            </div>
        </div>
    </form>

</div>
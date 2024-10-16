<div id="academics" class="content" style="display: block;">

    <form id="step_3" role="form" autocomplete="off" action="/app/application-form/step-3" method="POST" enctype="multipart/form-data">

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

        <input type="hidden" name="inserted_id" id="inserted_id" value="<?php echo $id  ?>">

        <?php
        $eligibility = $conn->query("SELECT Eligibility FROM Sub_Courses WHERE ID = '" . $student['Sub_Course_ID'] . "'");
        $eligibility = $eligibility->fetch_assoc();
        $eligibility = !empty($eligibility['Eligibility']) ? json_decode($eligibility['Eligibility'], true) : [];
        $eligibility = array_filter($eligibility);
        // echo "<pre>"; print_r($eligibility);

        $high_class =   in_array('High School', $eligibility) ? 'block' : 'none';
        $intermediate_class =   in_array('Intermediate', $eligibility) ? 'block' : 'none';
        $ug_class =   in_array('UG', $eligibility) ? 'block' : 'none';
        $pg_class =   in_array('PG', $eligibility) ? 'block' : 'none';
        $other_class = in_array('Other', $eligibility) ? 'block' : 'none';




        $high_school = [];
        if (!empty($id)) {
            $high_school = $conn->query("SELECT Student_Academics.*, Location FROM Student_Academics LEFT JOIN Student_Documents ON Student_Academics.Student_ID = Student_Documents.Student_ID AND Student_Documents.`Type` = 'High School' WHERE Student_Academics.Student_ID = $id AND Student_Academics.Type = 'High School' GROUP BY Student_ID");
            if ($high_school->num_rows > 0) {
                $high_school = mysqli_fetch_assoc($high_school);
                $high_marksheet = !empty($high_school['Location']) ? explode('|', $high_school['Location']) : [];
            } else {
                $high_school = [];
            }
        }
        ?>
        <div class="g-5 doc3">
            <div id="high_school_column" style="display:<?= $high_class ?>">
                <div class="row">
                    <div class="col-sm-12 content-header mb-1">
                        <h6 class="mb-0 fs-5  text-black fw-bold">HIGH SCHOOL</h6>
                    </div>

                    <div class="col-sm-4 mt-5 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Subjects <span class="text-danger">*</span></label>
                        <input type="text" name="high_subject" id="high_subject" class="form-control" value="<?php print !empty($high_school) ? (array_key_exists('Subject', $high_school) ? $high_school['Subject'] : '') : 'All Subjects' ?>" placeholder="ex: All">
                    </div>
                    <div class="col-sm-4 mt-5 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Year <span class="text-danger">*</span></label>
                        <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="high_year" id="high_year">
                            <option value="">Select</option>
                            <?php for ($i = date('Y'); $i >= 1947; $i--) { ?>
                                <option value="<?= $i ?>" <?php print !empty($high_school) ? ($high_school['Year'] == $i ? 'selected' : '') : '' ?>><?= $i ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-4 mt-5 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Board/University <span class="text-danger">*</span></label>
                        <input type="text" name="high_board" id="high_board" value="<?php print !empty($high_school) ? $high_school['Board/Institute'] : '' ?>" class="form-control" placeholder="ex: CBSE" required />
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Result <span class="text-danger">*</span></label>
                        <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="high_total" id="high_total">
                            <option value="">Select</option>
                            <option value="Passed" <?php print !empty($high_school) && $high_school['Total_Marks'] == 'PASSED' ? 'selected' : '' ?>>Passed</option>
                            <option value="Fail" <?php print !empty($high_school) && $high_school['Total_Marks'] == 'FAIL' ? 'selected' : '' ?>>Fail</option>
                            <option value="Discontinued" <?php print !empty($high_school) && $high_school['Total_Marks'] == 'DISCONTINUED' ? 'selected' : '' ?>>Discontinued</option>
                        </select>
                    </div>
                    <div class="col-sm-4  mb-3">
                        <label class="m-0 p-0 col-form-label" for="multicol-full-name">Marksheet <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('high_marksheet');" id="high_marksheet" name="high_marksheet[]" multiple="multiple">
                        <dt><?php print !empty($high_marksheet) ?  count($high_marksheet) . " Marksheet(s) Uploaded" : ''; ?></dt>
                        <?php if (!empty($high_marksheet)) {
                            foreach ($high_marksheet as $hm) { ?>
                                <img src="<?= $hm ?>" class="cursor-pointer mr-2" onclick="window.open('<?= $hm ?>')" width="40" height="40" />
                        <?php }
                        } ?>
                    </div>
                </div>
            </div>
            <div class="  mt-5" id="intermediate_column" style="display:<?= $intermediate_class ?>">
                <div class="row">
                    <div class="col-sm-12 content-header mb-1">
                        <h6 class="mb-0 fs-5  text-black fw-bold">INTERMEDIATE</h6>
                    </div>

                    <?php
                    $intermediate = [];
                    if (!empty($id)) {
                        $intermediate = $conn->query("SELECT Student_Academics.*, Location FROM Student_Academics LEFT JOIN Student_Documents ON Student_Academics.Student_ID = Student_Documents.Student_ID AND Student_Documents.`Type` = 'Intermediate' WHERE Student_Academics.Student_ID = $id AND Student_Academics.Type = 'Intermediate'");
                        if ($intermediate->num_rows > 0) {
                            $intermediate = mysqli_fetch_assoc($intermediate);
                            $inter_marksheet = !empty($intermediate['Location']) ? explode('|', $intermediate['Location']) : [];
                        } else {
                            $intermediate = [];
                        }
                    }
                    ?>

                    <div class="col-sm-4 mt-5 mb-3 ">
                        <label class=" p-0 m-0 col-form-label" for="multicol-full-name">Subjects <span class="text-danger">*</span></label>
                        <input type="text" name="inter_subject" class="form-control" value="<?php print !empty($intermediate) ? (array_key_exists('Subject', $intermediate) ? $intermediate['Subject'] : '') : '' ?>" id="inter_subject" placeholder="ex: PCM" required />
                    </div>
                    <div class="col-sm-4 mt-5 mb-3">
                        <label class=" p-0 m-0 col-form-label" for="multicol-full-name">Year <span class="text-danger">*</span></label>
                        <select class="select2 form-select" data-allow-clear="true" name="inter_year" id="inter_year">
                            <option value="">Select</option>
                            <?php for ($i = date('Y'); $i >= 1947; $i--) { ?>
                                <option value="<?= $i ?>" <?php print !empty($intermediate) ? ($intermediate['Year'] == $i ? 'selected' : '') : '' ?>><?= $i ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-4 mt-5 mb-3">
                        <label class=" p-0 m-0 col-form-label" for="multicol-full-name">Board/University <span class="text-danger">*</span></label>
                        <input type="text" name="inter_board" id="inter_board" value="<?php print !empty($intermediate) ? (array_key_exists('Board/Institute', $intermediate) ? $intermediate['Board/Institute'] : '') : '' ?>" class="form-control" placeholder="ex: CBSE" />

                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="p-0 m-0 col-form-label" for="multicol-full-name">Result <span class="text-danger">*</span></label>
                        <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="inter_total" id="inter_total">
                            <option value="">Select</option>
                            <option value="Passed" <?php print !empty($intermediate) && $intermediate['Total_Marks'] == 'PASSED' ? 'selected' : '' ?>>Passed</option>
                            <option value="Fail" <?php print !empty($intermediate) && $intermediate['Total_Marks'] == 'FAIL' ? 'selected' : '' ?>>Fail</option>
                            <option value="Discontinued" <?php print !empty($intermediate) && $intermediate['Total_Marks'] == 'DISCONTINUED' ? 'selected' : '' ?>>Discontinued</option>
                        </select>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="p-0 m-0 col-form-label" for="multicol-full-name">Marksheet <span class="text-danger">*</span></label>
                        <!-- <input type="file" class="form-control" id="basic-default-upload-file" required=""> -->
                        <input type="file" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('inter_marksheet');" id="inter_marksheet" name="inter_marksheet[]" multiple="multiple" class="form-control ">
                        <dt><?php print !empty($inter_marksheet) ? count($inter_marksheet) . " Marksheet Uploaded" : '' ?></dt>
                        <?php if (!empty($inter_marksheet)) {
                            foreach ($inter_marksheet as $im) { ?>
                                <img src="<?= $im ?>" class="cursor-pointer mr-2" onclick="window.open('<?= $im ?>')" width="40" height="40" />
                        <?php }
                        } ?>
                    </div>
                </div>
            </div>
            <?php
            $ug = [];
            if (!empty($id)) {
                $ug = $conn->query("SELECT Student_Academics.*, Location FROM Student_Academics LEFT JOIN Student_Documents ON Student_Academics.Student_ID = Student_Documents.Student_ID AND Student_Documents.`Type` = 'UG' WHERE Student_Academics.Student_ID = $id AND Student_Academics.Type = 'UG'");
                if ($ug->num_rows > 0) {
                    $ug = mysqli_fetch_assoc($ug);
                    $ug_marksheet = !empty($ug['Location']) ? explode('|', $ug['Location']) : [];
                } else {
                    $ug = [];
                }
            }
            ?>

            <div id="ug_column" style="display:<?= $ug_class ?>">
                <div class="row">
                    <div class="col-sm-12 content-header mb-1">
                        <h6 class="mb-0 fs-5  text-black fw-bold">Under Graduate</h6>
                    </div>
                    <div class="col-sm-4 mt-5 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Subjects <span class="text-danger">*</span></label>
                        <input type="text" name="ug_subject" id="ug_subject" class="form-control" class="form-control" value="<?php print !empty($ug) ? (array_key_exists('Subject', $ug) ? $ug['Subject'] : '') : '' ?>" placeholder="ex: BBA" />
                    </div>
                    <div class="col-sm-4 mt-5 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Year <span class="text-danger">*</span></label>
                        <select class="select2 form-select" data-allow-clear="true" name="ug_year" id="ug_year">
                            <option value="">Select</option>
                            <?php for ($i = date('Y'); $i >= 1947; $i--) { ?>
                                <option value="<?= $i ?>" <?php print !empty($ug) ? ($ug['Year'] == $i ? 'selected' : '') : '' ?>><?= $i ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-4 mt-5 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Board/University <span class="text-danger">*</span></label>
                        <input type="text" name="ug_board" id="ug_board" value="<?php print !empty($ug) ? (array_key_exists('Board/Institute', $ug) ? $ug['Board/Institute'] : '') : '' ?>" class="form-control" placeholder="ex: DU" />

                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Marks Obtained<span class="text-danger">*</span></label>
                        <input type="text" name="ug_obtained" id="ug_obtained" value="<?php print !empty($ug) ? (array_key_exists('Marks_Obtained', $ug) ? $ug['Marks_Obtained'] : '') : '' ?>" onblur="checkUGMarks()" placeholder="ex: 400" class="form-control" />

                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Max Marks <span class="text-danger">*</span></label>
                        <input type="text" name="ug_max" id="ug_max" value="<?php print !empty($ug) ? (array_key_exists('Max_Marks', $ug) ? $ug['Max_Marks'] : '') : '' ?>" onblur="checkUGMarks()" placeholder="ex: 600" class="form-control" />

                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class=" col-form-label p-0 m-0" for="multicol-full-name">Grade/Percentage <span class="text-danger">*</span></label>
                        <input type="text" name="ug_total" value="<?php print !empty($ug) ? (array_key_exists('Total_Marks', $ug) ? $ug['Total_Marks'] : '') : '' ?>" id="ug_total" class="form-control" placeholder="ex: 66%" />

                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Result <span class="text-danger">*</span></label>
                        <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="ug_total" id="ug_total">
                            <option value="">Select</option>
                            <option value="Passed" <?php print !empty($ug) && $ug['Total_Marks'] == 'PASSED' ? 'selected' : '' ?>>Passed</option>
                            <option value="Fail" <?php print !empty($ug) && $ug['Total_Marks'] == 'FAIL' ? 'selected' : '' ?>>Fail</option>
                            <option value="Discontinued" <?php print !empty($ug) && $ug['Total_Marks'] == 'DISCONTINUED' ? 'selected' : '' ?>>Discontinued</option>
                        </select>
                    </div>
                    <div class="col-sm-6 mb-3 ">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Marksheet <span class="text-danger">*</span></label>

                        <input type="file" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('ug_marksheet');" id="ug_marksheet" name="ug_marksheet[]" multiple="multiple" class="form-control ">
                        <dt><?php print !empty($ug_marksheet) ? count($ug_marksheet) . " Marksheet Uploaded" : '' ?></dt>
                        <?php if (!empty($ug_marksheet)) {
                            foreach ($ug_marksheet as $um) { ?>
                                <img src="<?= $um ?>" class="cursor-pointer mr-2" onclick="window.open('<?= $um ?>')" width="40" height="40" />
                        <?php }
                        } ?>
                    </div>
                </div>
            </div>
            <?php
            $pg = [];
            if (!empty($id)) {
                $pg = $conn->query("SELECT Student_Academics.*, Location FROM Student_Academics LEFT JOIN Student_Documents ON Student_Academics.Student_ID = Student_Documents.Student_ID AND Student_Documents.`Type` = 'PG' WHERE Student_Academics.Student_ID = $id AND Student_Academics.Type = 'PG'");
                if ($pg->num_rows > 0) {
                    $pg = mysqli_fetch_assoc($pg);
                    $pg_marksheet = !empty($pg['Location']) ? explode('|', $pg['Location']) : [];
                } else {
                    $pg = [];
                }
            }
            ?>
            <div class="mt-5" id="pg_column" style="display:<?= $pg_class ?>">
                <div class="row">
                    <div class="col-sm-12 content-header mb-1">
                        <h6 class="mb-0 fs-5  text-black fw-bold">Post Graduate</h6>
                    </div>
                    <div class="col-sm-4 mt-5 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Subjects <span class="text-danger">*</span></label>
                        <input type="text" name="pg_subject" id="pg_subject" value="<?php print !empty($pg) ? (array_key_exists('Subject', $pg) ? $pg['Subject'] : '') : '' ?>" class="form-control" placeholder="ex: MBA" required />
                    </div>
                    <div class="col-sm-4 mt-5 mb-3">
                        <label class=" col-form-label p-0 m-0" for="multicol-full-name">Year <span class="text-danger">*</span></label>
                        <select class="select2 form-select" data-allow-clear="true" name="pg_year" id="pg_year">
                            <option value="">Select</option>
                            <?php for ($i = date('Y'); $i >= 1947; $i--) { ?>
                                <option value="<?= $i ?>" <?php print !empty($pg) ? ($pg['Year'] == $i ? 'selected' : '') : '' ?>><?= $i ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-4 mt-5 mb-3">
                        <label class=" col-form-label p-0 m-0" for="multicol-full-name">Board/University <span class="text-danger">*</span></label>
                        <input type="text" name="pg_board" id="pg_board" value="<?php print !empty($pg) ? (array_key_exists('Board/Institute', $pg) ? $pg['Board/Institute'] : '') : '' ?>" class="form-control" placeholder="ex: DU" />
                        <!-- <input type="text" id="multicol-full-name" name="name" class="form-control" placeholder="ex: XYZ University" required /> -->
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Result <span class="text-danger">*</span></label>
                        <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="pg_total" id="pg_total">
                            <option value="">Select</option>
                            <option value="Passed" <?php print !empty($pg) && $pg['Total_Marks'] == 'PASSED' ? 'selected' : '' ?>>Passed</option>
                            <option value="Fail" <?php print !empty($pg) && $pg['Total_Marks'] == 'FAIL' ? 'selected' : '' ?>>Fail</option>
                            <option value="Discontinued" <?php print !empty($pg) && $pg['Total_Marks'] == 'DISCONTINUED' ? 'selected' : '' ?>>Discontinued</option>
                        </select>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Marksheet <span class="text-danger">*</span></label>
                        <!-- <input type="file" class="form-control" id="basic-default-upload-file" required=""> -->
                        <input type="file" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('pg_marksheet');" name="pg_marksheet[]" id="pg_marksheet" multiple="multiple" class="form-control ">
                        <dt><?php print !empty($pg_marksheet) ? count($pg_marksheet) . " Marksheet Uploaded" : '' ?></dt>
                        <?php if (!empty($pg_marksheet)) {
                            foreach ($pg_marksheet as $pm) { ?>
                                <img src="<?= $pm ?>" class="cursor-pointer mr-2" onclick="window.open('<?= $pm ?>')" width="40" height="40" />
                        <?php }
                        } ?>
                    </div>
                </div>
            </div>

            <?php
            $other = [];
            if (!empty($id)) {
                $other = $conn->query("SELECT Student_Academics.*, Location FROM Student_Academics LEFT JOIN Student_Documents ON Student_Academics.Student_ID = Student_Documents.Student_ID AND Student_Documents.`Type` = 'Other' WHERE Student_Academics.Student_ID = $id AND Student_Academics.Type = 'Other' GROUP BY Student_ID");
                if ($other->num_rows > 0) {
                    $other = mysqli_fetch_assoc($other);
                    $other_marksheet = !empty($other['Location']) ? explode('|', $other['Location']) : [];
                } else {
                    $other = [];
                }
            }
            ?>
            <div id="other_column" style="display:<?= $other_class ?>">
                <div class="row">
                    <div class="content-header mb-1">
                        <h6 class="mb-0 fs-5  text-black fw-bold">Other</h6>
                    </div>
                    <div class="col-sm-4 mb-3 mt-5">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Subjects <span class="text-danger">*</span></label>
                        <input type="text" name="other_subject" class="form-control" value="<?php print !empty($other) ? (array_key_exists('Subject', $other) ? $other['Subject'] : '') : '' ?>" id="other_subject" placeholder="ex: Diploma" required />
                    </div>
                    <div class="col-sm-4 mb-3 mt-5">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Year <span class="text-danger">*</span></label>
                        <select class="select2 form-select" data-allow-clear="true" name="other_year" id="other_year">
                            <option value="">Select</option>
                            <?php for ($i = date('Y'); $i >= 1947; $i--) { ?>
                                <option value="<?= $i ?>" <?php print !empty($other) ? ($other['Year'] == $i ? 'selected' : '') : '' ?>><?= $i ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-4 mb-3 mt-5">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Board/University <span class="text-danger">*</span></label>
                        <input type="text" name="other_board" id="other_board" value="<?php print !empty($other) ? (array_key_exists('Board/Institute', $other) ? $other['Board/Institute'] : '') : '' ?>" class="form-control" placeholder="ex: DU" />

                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Result <span class="text-danger">*</span></label>
                        <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="other_total" id="other_total">
                            <option value="">Select</option>
                            <option value="Passed" <?php print !empty($other) && $other['Total_Marks'] == 'PASSED' ? 'selected' : '' ?>>Passed</option>
                            <option value="Fail" <?php print !empty($other) && $other['Total_Marks'] == 'FAIL' ? 'selected' : '' ?>>Fail</option>
                            <option value="Discontinued" <?php print !empty($other) && $other['Total_Marks'] == 'DISCONTINUED' ? 'selected' : '' ?>>Discontinued</option>
                        </select>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class=" col-form-label m-0 p-0" for="multicol-full-name">Marksheet <span class="text-danger">*</span></label>

                        <input type="file" accept="image/png, image/jpeg, image/jpg" onchange="fileValidation('other_marksheet');" id="other_marksheet" name="other_marksheet[]" multiple="multiple" class="form-control ">
                        <dt><?php print !empty($other_marksheet) ? count($other_marksheet) . " Marksheet Uploaded" : '' ?></dt>
                        <?php if (!empty($other_marksheet)) {
                            foreach ($other_marksheet as $om) { ?>
                                <img src="<?= $om ?>" class="cursor-pointer mr-2" onclick="window.open('<?= $om ?>')" width="40" height="40" />
                        <?php }
                        } ?>
                    </div>
                </div>

            </div>


            <div class="col-12 mt-4 d-flex justify-content-between">
                <button class="btn btn-outline-secondary btn-prev"> <i class="ri-arrow-left-line me-sm-1"></i>
                    <span class="align-middle d-sm-inline-block d-none"><a href="/admission/application-form?step=2&id=<?= $inserted_id ?>">Previous</a></span>
                </button>
                <button type="submit" class="btn btn-primary me-4 btn-next"><span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="ri-arrow-right-line"></i></button>
            </div>
        </div>
    </form>
</div>
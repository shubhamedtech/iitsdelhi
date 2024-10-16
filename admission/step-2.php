<div id="personal-details" class="content" style="display: block;">
 <?php 
 $inserted_id='';
 $id='';

 if(isset($_GET['id'])){
     $inserted_id = $_GET['id'];
     $id = mysqli_real_escape_string($conn, $_GET['id']) ;
     $id = base64_decode($id);
    $id = intval(str_replace('W1Ebt1IhGN3ZOLplom9I', '', $id));
 }
 ?>
    <form id="step_2" role="form" autocomplete="off" action="/app/application-form/step-2">
   <input type="hidden" name="inserted_id" id="inserted_id" value="<?php echo $id  ?>">
        <div class="content-header mb-1">
            <h6 class="mb-0 fs-5 mb-5 text-black fw-bold">SOCIAL</h6>
            <!-- <small>Enter Your Account Details.</small> -->
        </div>

        <div class="row g-5">
            <div class="col-sm-3 mb-3">
                <label class=" col-form-label m-0 p-0" for="multicol-full-name">Email <span class="text-danger">*</span></label>
                <input type="email" id="multicol-full-name" name="email" class="form-control" value="<?php print !empty($id) ? $student['Email'] : '' ?> <?php print !empty($lead_id) ? $lead['Email'] : '' ?>" placeholder="ex: jhon@example.com" required />
            </div>
            <div class="col-sm-3 mb-3">
                <label class=" col-form-label m-0 p-0" for="alternate_email">Alternate Email <span class="text-danger">*</span></label>
                <input type="email" id="alternate_email" name="alternate_email" class="form-control" value="<?php print !empty($id) ? $student['Alternate_Email'] : '' ?><?php print !empty($lead_id) ? $lead['Alternate_Email'] : '' ?>" class="form-control" placeholder="ex: jhondoe@example.com" required />
            </div>
            <div class="col-sm-3 mb-3">
                <label class=" col-form-label m-0 p-0" for="contact">Mobile <span class="text-danger">*</span></label>
                <input type="tel" id="contact" name="contact" class="form-control" onkeypress="return isNumberKey(event);" maxlength="10" value="<?php print !empty($id) ? $student['Contact'] : '' ?><?php print !empty($lead_id) ? $lead['Mobile'] : '' ?>" class="form-control" placeholder="ex: 9977886655" required />
            </div>
            <div class="col-sm-3 mb-3">
                <label class=" col-form-label m-0 p-0" for="alternate_contact">Alternate Mobile <span class="text-danger">*</span></label>
                <input type="tel" id="alternate_contact" name="alternate_contact" class="form-control" maxlength="10" value="<?php print !empty($id) ? $student['Alternate_Contact'] : '' ?><?php print !empty($lead_id) ? $lead['Alternate_Mobile'] : '' ?>" placeholder="ex: 9988776654" required />
            </div>
            <div class="content-header ">
                <h6 class="mb-0 fs-5  text-black fw-bold">ADDRESS</h6>
                <!-- <small>Enter Your Account Details.</small> -->
            </div>
            <div class="col-sm-3 mb-2">
                <label class=" col-form-label m-0 p-0" for="address">Address <span class="text-danger">*</span></label>
                <input type="text" id="address" name="address" class="form-control" value="<?php print !empty($id) ? (!empty($address) ? $address['present_address'] : '') : '' ?>" placeholder="ex: 23 Street, California" required />
            </div>
            <div class="col-sm-3 mb-2">
                <label class=" col-form-label m-0 p-0" for="pincode">Pincode <span class="text-danger">*</span></label>
                <input type="tel" name="pincode" maxlength="6" class="form-control" placeholder="ex: 123456" value="<?php print !empty($address) ? (array_key_exists('present_pincode', $address) ? $address['present_pincode'] : '') : '' ?>" onkeypress="return isNumberKey(event)" onkeyup="getRegion(this.value);" required />
            </div>
            <div class="col-sm-3 mb-2">
                <label class=" col-form-label m-0 p-0" for="multicol-full-name">City <span class="text-danger">*</span></label>
                <select class="select2 form-select" data-allow-clear="true" name="city" id="city">
                    <option value="">Choose</option>
                </select>
            </div>
            <div class="col-sm-3 mb-3">
                <label class=" col-form-label m-0 p-0" for="multicol-full-name">District <span class="text-danger">*</span></label>
                <select class="select2 form-select" data-allow-clear="true" name="district" id="district">
                    <option value="">Choose</option>
                </select>
            </div>
            <div class="col-sm-3 mb-3">
                <label class=" col-form-label m-0 p-0" for="multicol-full-name">State <span class="text-danger">*</span></label>
                <input type="text" name="state" class="form-control" placeholder="ex: California" id="state" readonly />
            </div>
            <div class="col-12 d-flex justify-content-between">
                <button class="btn btn-outline-secondary btn-prev"> <i class="ri-arrow-left-line me-sm-1"></i>
                    <span class="align-middle d-sm-inline-block d-none"><a href="/admission/application-form?step=1&id=<?= $inserted_id ?>">Previous</a></span>
                </button>
                <button type="submit" class="btn btn-primary me-4 btn-next"><span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="ri-arrow-right-line"></i></button>

                <!-- <button class="btn btn-primary "> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="ri-arrow-right-line"></i></button> -->
            </div>
        </div>
    </form>
</div>
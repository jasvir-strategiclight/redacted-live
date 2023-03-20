<div class="col-md-12 ">
    <div class="row g-bg-lightblue py-2" style="max-width: 100%; margin-left: 0%;">
        <div class="col-md-4">
            <label>Program Name</label>
            <input name="name"
                   id="routineName_<?= $routine->id; ?>"
                   placeholder="Program Name"
                   class="form-control routine-name"
                   data-field="name"
                   value="<?= $routine->name; ?>"
            >
        </div>
        <div class="col-md-1 mt-4">
            <div class="d-inline-block g-pos-rel g-mb-20" style="float: left; margin-right: 20px;">
                <a
                    class="u-badge-v2--lg u-badge--bottom-right g-width-32 g-height-32 g-bg-lightblue-v3 g-bg-primary--hover g-mb-40 g-mr-30 load-media"
                    href="#mediaModal"
                    data-modal-target="#mediaModal"
                    data-modal-effect="blur"
                    data-model="Routines"
                    data-category="Routines"
                    data-user_id="<?= $authUser['id']; ?>"
                    data-image_id="0"
                >
                    <input type="hidden" name="image_id"
                           id="ImageId"
                           value="<?= $authUser['logo_id']; ?>"
                           class="loaded-image-id" value="<?= $routine->image_id; ?>"/> <i
                        class="fa fa-plus g-absolute-centered g-font-size-16 g-color-white"></i>
                </a>
                <?php
                $image = ($routine->has('image')) ? $routine->image->small_thumb : "files/images/default.png";
                ?>
                <img class="loaded-image"
                     src="<?= SITE_URL . $image; ?>"
                     alt="Image description"
                     style="width:100px; height: 100px;">
                <h3 class="mt-2">Logo</h3>
            
            </div>
        </div>
        <div class="col-md-3 mt-4">
            <div class="form-group ml-3" style="float: left">
                <label>Program Date</label>
                <div id="moveDateWrapper"
                     class="u-datepicker-right g-pos-rel g-cursor-pointer">
                    <input class="js-range-datepicker g-pl-15 g-py-5 not-ignore"
                           name="routine_date"
                           id="moveDate"
                           type="text"
                           placeholder="Select Date"
                           data-rp-wrapper="moveDateWrapper"
                           data-rp-date-format="Y-m-d"
                           value="<?= (empty($routine->routine_date)) ? date("M d, Y") : date("M d, Y", strtotime($routine->routine_date)); ?>"
                    >
                    <div
                        class="d-flex align-items-center g-absolute-centered--y g-right-0 g-color-gray-light-v6 g-color-lightblue-v9--sibling-opened g-mr-15">
                        <i class="fa fa-calendar g-font-size-18 g-mr-10"></i>
                    </div>
                
                </div>
                <label for="moveDate" class="error" id="moveDateError" style="display: none">Please select move
                    date.</label>
            </div>
            <br/>
            <br/>
            <h5 class="google-translate ml-3"
                style=" width: 100%; float: left; color: #007eef !important; cursor: pointer "><i
                    class="fa fa-google"></i> Google Translate</h5>
        </div>
        <div class="col-md-1 text-center g-color-blue mt-5 save-routine g-cursor-pointer"
             data-id="<?= $routine->id; ?>">
            <i class="fa fa-save g-font-size-48"></i> <br/>
            Save Program
        </div>
        <div class="col-md-1 text-center g-color-blue mt-5 add-more-btn g-cursor-pointer"
             data-id="<?= $routine->id; ?>">
            <i class="fa fa-plus-circle g-font-size-48"></i> <br/>
            Add More
        </div>
        <div class="col-md-1 text-center g-color-blue mt-5 show-layout g-cursor-pointer" data-id="<?= $routine->id; ?>">
            <i class="fa fa-file-pdf-o g-font-size-48"></i> <br/>
            PDF
        </div>
        <div class="col-md-1 text-center g-color-blue mt-5 g-cursor-pointer share-routine"
             data-id="<?= $routine->id; ?>">
            <i class="fa fa-share-alt-square g-font-size-48"></i> <br/>
            Share
        </div>
    </div>
</div>
<div class="col-md-12 ">
    <div class="row g-bg-lightblue py-2" style="max-width: 100%; margin-left: 0%;">
        <div class="col-md-12">
            <label>Special Instructions</label>
            <textarea name="description"
                      id="routineDescription_<?= $routine->id; ?>"
                      placeholder="Notes"
                      class="form-control routine-description"
                      data-field="description"><?= $routine->description; ?></textarea>

            <label class="error routine-description-error" style="display: none">Notes must be less than <?= $descriptionLimit; ?> characters.</label><br />
        
        </div>
    </div>
</div>

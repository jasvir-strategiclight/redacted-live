<?php
echo $this->Html->css(['uploadfile']);
echo $this->Html->script(['jquery-ui', 'jquery.uploadfile', 'jquery.imgareaselect.min']);
?>
<div class="row">
    <div class="col-md-2">
        <!-- Nav tabs -->
        <ul class="nav text-center flex-column u-nav-v2-1" role="tablist"
            data-target="nav-2-1-accordion-default-ver-big-icons" data-tabs-mobile-type="accordion"
            data-btn-classes="btn btn-md btn-block rounded-0 u-btn-outline-lightgray g-mb-20">
            <li class="nav-item active " id="uploadNewImage">
                <a class="nav-link active show py-5" data-toggle="tab"
                   href="#nav-2-1-accordion-default-ver-big-icons--1"
                   role="tab">
                    <i class="fa fa-upload d-block g-font-size-25 u-tab-line-icon-pro"></i>
                    Upload
                </a>
            </li>
            <li class="nav-item" id="getGalleryImages">
                <a class="nav-link py-5" data-toggle="tab" href="#nav-2-1-accordion-default-ver-big-icons--2"
                   role="tab">
                    <i class="hs-admin-layout-media-left-alt d-block g-font-size-25 u-tab-line-icon-pro"></i>
                    Admin Media
                </a>
            </li>
            <li class="nav-item" id="getUserImages">
                <a class="nav-link py-5" data-toggle="tab" href="#nav-2-1-accordion-default-ver-big-icons--3"
                   role="tab">
                    <i class="hs-admin-layout-media-left-alt d-block g-font-size-25 u-tab-line-icon-pro"></i>
                    User Media
                </a>
            </li>
        </ul>
        <!-- End Nav tabs -->
    </div>
    
    <div class="col-md-10">
        <!-- Tab panes -->
        <div id="nav-2-1-accordion-default-ver-big-icons" class="tab-content">
            <div class="tab-pane fade show active" id="nav-2-1-accordion-default-ver-big-icons--1" role="tabpanel">
                <div id="multipleFileUploader" style="display: none"></div>
                <input type="hidden" name="type" id="imageType" value="<?= $type; ?>"/>
                <input type="hidden" name="user" id="imageUserId" value="<?= $userId; ?>"/>
                <input type="hidden" name="category" id="imageCategory" value="<?= $category; ?>"/>
                <div class="file-upload-btn rounded-2x" id="fileUploadBtn">
                    <div
                        class="g-parent g-pos-rel g-height-230 g-bg-gray-light-v8--hover g-brd-around g-brd-style-dashed g-brd-gray-light-v7 g-brd-lightblue-v3--hover g-rounded-4 g-transition-0_2 g-transition--ease-in g-pa-15 g-pa-30--md g-mb-60">
                        <div class="d-md-flex align-items-center g-absolute-centered--md w-100 g-width-auto--md">
                            <div>
                                <div
                                    class="g-pos-rel g-width-80 g-width-100--lg g-height-80 g-height-100--lg g-bg-gray-light-v8 g-bg-white--parent-hover rounded-circle g-mb-20 g-mb-0--md g-transition-0_2 g-transition--ease-in mx-auto mx-0--md">
                                    <i class="fa fa-upload g-absolute-centered g-font-size-30 g-font-size-36--lg g-color-lightblue-v3"></i>
                                </div>
                            </div>
                            <div class="text-center text-md-left g-ml-20--md"><h3
                                    class="g-font-weight-400 g-font-size-16 g-color-black g-mb-10">Upload Your
                                    Images</h3>
                                <p class="g-font-weight-300 g-color-gray-dark-v6 mb-0">Only JPG, JPEG AND PNG are
                                    supported.</p></div>
                        </div>
                    </div>
                </div>
                
                <div class="ajax-file-upload-container" id="ajaxContainer"></div>
            
            </div>
            
            <div class="tab-pane fade" id="nav-2-1-accordion-default-ver-big-icons--2" role="tabpanel">
                <div class="row" id="galleryImages"></div>
            </div>
            <div class="tab-pane fade" id="nav-2-1-accordion-default-ver-big-icons--3" role="tabpanel">
                <div class="row" id="userImages"></div>
            </div>
            <button class="btn btn-primary" id="chooseSelectedImage" style="display: none;">Choose</button>
        
        </div>
        
        <div id="imagesCropSection" style="display: none">
            <h2>Crop Image</h2>
            <div align="center" class="row">
                <div class="col-md-9">
                    <img src="" style="float: left; margin-right: 10px;" id="cropFrom" alt="Create Thumbnail"/>
                </div>
                <div class="col-md-3">
                    <div
                        style="border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:200px; height:200px;">
                        <img src="" style="position: relative;" alt="Thumbnail Preview" id="croppedSection"/>
                    </div>
                    <br/>
                    <div style="float: left; margin-top: 20px;">
                        <form name="thumbnail" action="javascript:void(0);" method="post" id="saveCroppedImageFrom">
                            <input type="hidden" name="type" id="imageCropType" value="<?= $type; ?>"/>
                            <input type="hidden" name="user" id="imageCropUserId" value="<?= $userId; ?>"/>
                            <input type="hidden" name="category" id="imageCropCategory" value="<?= $category; ?>"/>
                            <input type="hidden" name="id" value="" id="imageId"/>
                            <input type="hidden" name="x1" value="" id="x1"/>
                            <input type="hidden" name="y1" value="" id="y1"/>
                            <input type="hidden" name="x2" value="" id="x2"/>
                            <input type="hidden" name="y2" value="" id="y2"/>
                            <input type="hidden" name="w" value="" id="w"/>
                            <input type="hidden" name="h" value="" id="h"/>
                            <button type="submit" class="btn btn-primary" name="upload_thumbnail"
                                    id="saveCroppedImageBtn"><i class="fa fa-save"></i> Save
                            </button>
                            <a href="javascript:void(0);" id="cancelCrop" class="btn btn-danger"><i
                                    class="fa fa-close"></i> Cancel</a>
                            
                            <br/>
                            <label class="error" id="cropError" style="display: none;">You must make a selection
                                first.</label>
                            <label class="error" id="anyErrorFromServer" style="display: none;">Something went wrong,
                                please try again.</label>
                        </form>
                    </div>
                </div>
            
            </div>
        </div>
    
    </div>

</div>
<template id="imageTmpl">
    <div class="col-md-2 g-mb-30 g-cursor-pointer select-image hover ehover10" id="selectedImage_${id}"
         data-image-id="${id}" data-image="<?= SITE_URL; ?>${small_thumb}">
        <a class="select-image-icon u-badge-v2--lg u-badge--top-right g-width-32 g-height-32 g-mt-20 g-mr-20">
            <i class="hs-admin-check g-absolute-centered g-font-size-16 g-color-white"></i>
        </a>
        <a class="d-block u-block-hover" id="selectImgBtn_${id}">
            <img class="img-fluid u-block-hover__main--mover-right"
                 src="<?= SITE_URL; ?>${small_thumb}" alt="Image Description">
        </a>
        <div class="overlay">
            <button class="btn btn-primary crop-image" data-image-id="${id}"
                    data-image="<?= SITE_URL; ?>${small_thumb}">
                <i class="fa fa-crop"></i> Crop
            </button>
            <button class="btn btn-primary delete-image" data-image-id="${id}"
                    data-image="<?= SITE_URL; ?>${small_thumb}">
                <i class="fa fa-trash"></i> Delete
            </button>
        </div>
    </div>
</template>

<script>
    $(function () {
        
        var loadPage = 1;
        var loadingData = false;
        var thumbWidth = 200;
        var thumbHeight = 200;
        var $ias = null;
        
        $('#fileUploadBtn').on('click', function () {
            $('#ajax-upload-id').click();
        });
        
        var settings = {
            url: SITE_URL + "admin/images/upload",
            method: "POST",
            formData: {
                type: $('#imageType').val(),
                user_id: $('#imageUserId').val(),
                category: $('#imageCategory').val()
            },
            allowedTypes: "jpg,jpeg,png",
            fileName: "file",
            multiple: false,
            showQueueDiv: 'ajaxContainer',
            showError: true,
            dragdropWidth: '100%',
            statusBarWidth: '100%',
            showFileCounter: false,
            maxFileCount: 10,
            onSuccess: function (files, data, xhr, pd) {
                var d = JSON.parse(data);
                if (d.code == 400) {
                    pd.progressbar.removeClass('ajax-file-upload-bar').addClass('ajax-file-upload-red').html("Failed");
                } else {
                    $('#getGalleryImages').click();
                    $('.nav-item, .nav-link, .tab-pane').removeClass('active').removeClass('show')
                    $('#getGalleryImages').addClass('active');
                    $('#getGalleryImages').children('a').addClass('active show');
                    $('#galleryImages').parent().addClass('active show');
    
                    $('#ajaxContainer').html('');
                    localStorage.setItem('uploadedImageId', d.data.id);
                }
            },
            onSelect: function (files) {
            },
            onError: function (files, status, errMsg) {
                $("#finalStatus").html("<font color='red'>Upload is Failed</font>");
            }
        }
        $("#multipleFileUploader").uploadFile(settings);
        
        
        function getImages() {
            loadingData = true;
            $.ajax({
                url: SITE_URL + "admin/images/getImages/" + loadPage,
                type: "POST",
                data: {
                    model_id: $('#imageUserId').val(),
                    category: $('#imageCategory').val(),
                },
                dataType: "json",
                success: function (response) {
                    if (response.code == 200) {
                        $("#galleryImages").html("");
                        if (loadPage == 1) {
                            $('#imagesCropSection').hide();
                            $('#nav-2-1-accordion-default-ver-big-icons').show('scale', {}, 700);
                        }
                        loadPage = parseInt(loadPage) + 1;
                        $.template("imageTmpl", $('#imageTmpl').html());
                        $.tmpl("imageTmpl", response.data.images).appendTo("#galleryImages");
                        loadingData = false;
                        setTimeout(function () {
                            $('#selectImgBtn_' + localStorage.getItem('uploadedImageId')).click();
                            $('#chooseSelectedImage').fadeIn();
                        }, 500);
                        
                    }
                }
            });
            
        }

        function getUserImages() {
            loadingData = true;
            $.ajax({
                url: SITE_URL + "admin/images/getUserImages/" + loadPage,
                type: "POST",
                data: {
                    model_id: $('#imageUserId').val(),
                    category: $('#imageCategory').val(),
                },
                dataType: "json",
                success: function (response) {
                    if (response.code == 200) {
                        $("#userImages").html("");
                        if (loadPage == 1) {
                            $('#imagesCropSection').hide();
                            $('#nav-2-1-accordion-default-ver-big-icons').show('scale', {}, 700);
                        }
                        loadPage = parseInt(loadPage) + 1;
                        $.template("imageTmpl", $('#imageTmpl').html());
                        $.tmpl("imageTmpl", response.data.images).appendTo("#userImages");
                        loadingData = false;
                    }
                }
            });
        }
        
        $(window).scroll(function () {
            if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                if (!loadingData) {
                    getImages();
                }
            }
        });
    
        $('#galleryImages').on('click', '.delete-image', function () {
            var id = $(this).attr('data-image-id');
            $.ajax({
                url: SITE_URL + "admin/images/delete/"+id,
                type: "POST",
                data: $("#saveCroppedImageFrom").serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.code == 200) {
                        $('#selectedImage_'+id).remove();
                    } else {
                        //Not Deleted
                    }
                }
            });
        });
        
        $('#getGalleryImages').click(function () {
            loadPage = 1;
            $('#nav-2-1-accordion-default-ver-big-icons--1, #nav-2-1-accordion-default-ver-big-icons--3').hide();
            $('#nav-2-1-accordion-default-ver-big-icons--2').fadeIn();
            getImages();
        });

        $('#getUserImages').click(function () {
            loadPage = 1;
            $('#nav-2-1-accordion-default-ver-big-icons--1, #nav-2-1-accordion-default-ver-big-icons--2').hide();
            $('#nav-2-1-accordion-default-ver-big-icons--3').fadeIn();
            getUserImages();
        });
        
        $('#galleryImages').on('click', '.select-image a', function () {
            if ($(this).parent().hasClass('selected-image')) {
                $(this).parent().removeClass('selected-image');
                $('#chooseSelectedImage').fadeOut();
            } else {
                $('.select-image').removeClass('selected-image');
                $(this).parent().addClass('selected-image');
                $('#chooseSelectedImage').fadeIn();
            }
        });
        
        $('#galleryImages').on('click', '.crop-image', function () {
            $('#saveCroppedImageBtn').html('<i class="fa fa-save"></i> Save').removeAttr('disabled');
            $('#cropError').hide();
            var id = $(this).attr('data-image-id');
            var image = $(this).attr('data-image').replace("small", "large");
            
            $('#imageId').val(id);
            $('#cropFrom, #croppedSection').attr('src', image);
            
            $('#nav-2-1-accordion-default-ver-big-icons').hide('scale', {}, 700, function () {
                $("#imagesCropSection").fadeIn();
            });
            
            var ratio = '1:1';
            var cat = $('#imageCategory').val();
            if (( cat == "Profile")
                || (cat == "Admin Profile")
                || (cat == "Testimonial")) {
                ratio = '1:1';
                thumbWidth = 200;
                thumbHeight = 200;
                
            } else {
                ratio = '3:2'
                thumbWidth = 200;
                thumbHeight = 150;
            }
            
            $('#croppedSection').parent().css({
                width: thumbWidth + 'px',
                height: thumbHeight + 'px'
            });
            
            $ias = $('#cropFrom').imgAreaSelect({aspectRatio: ratio, onSelectChange: preview});
            
        });
        
        $('#cancelCrop').click(function (e) {
            $('#imagesCropSection').hide();
            $('#nav-2-1-accordion-default-ver-big-icons').show('scale', {}, 700);
            
        });
        
        $('#chooseSelectedImage').click(function () {
            $('.current-image-id').val($('.selected-image').attr('data-image-id'));
            $('.current-image').attr('src', $('.selected-image').attr('data-image'));
            
            Custombox.modal.close();
        });
        
        $('#uploadNewImage').click(function () {
            $('.imgareaselect-outer, .imgareaselect-selection, .imgareaselect-border1, .imgareaselect-border2').hide();
            $('#nav-2-1-accordion-default-ver-big-icons--2').hide();
            $('#nav-2-1-accordion-default-ver-big-icons--1').fadeIn();
            
        });
        
        function preview(img, selection) {
            var realWidth = img.width;
            var realHeight = img.height;
            var scaleX = thumbWidth / selection.width;
            var scaleY = thumbHeight / selection.height;
            
            $('#croppedSection').css({
                width: Math.round(scaleX * realWidth) + 'px',
                height: Math.round(scaleY * realHeight) + 'px',
                marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
                marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
            });
            $('#x1').val(selection.x1);
            $('#y1').val(selection.y1);
            $('#x2').val(selection.x2);
            $('#y2').val(selection.y2);
            $('#w').val(selection.width);
            $('#h').val(selection.height);
        }
        
        $('#saveCroppedImageBtn').click(function () {
            var x1 = $('#x1').val();
            var y1 = $('#y1').val();
            var x2 = $('#x2').val();
            var y2 = $('#y2').val();
            var w = $('#w').val();
            var h = $('#h').val();
            if (x1 == "" || y1 == "" || x2 == "" || y2 == "" || w == "" || h == "") {
                $('#cropError').fadeIn();
                return false;
            } else {
                $('#cropFrom').imgAreaSelect({remove: true});
                $('#saveCroppedImageBtn').html('<i class="fa fa-spinner fa-spin"></i> processing...').attr('disabled', 'disabled');
                $.ajax({
                    url: SITE_URL + "admin/images/crop",
                    type: "POST",
                    data: $("#saveCroppedImageFrom").serialize(),
                    dataType: "json",
                    success: function (response) {
                        if (response.code == 200) {
                            $('#cropFrom').imgAreaSelect({remove: true});
                            localStorage.setItem('uploadedImageId', response.data.id);
                            $('.imgareaselect-outer, .imgareaselect-selection, .imgareaselect-border1, .imgareaselect-border2').hide();
                            
                            $('#imagesCropSection').hide('scale', {}, 700, function () {
                                $("#getGalleryImages").click();
                            });
                        } else {
                            $('#anyErrorFromServer').html(response.message);
                            $('#saveCroppedImageBtn').html('<i class="fa fa-save"></i> Save').removeAttr('disabled');
                        }
                    }
                });
            }
        });
        
        
    })
    ;
</script>

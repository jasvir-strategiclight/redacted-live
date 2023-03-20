<?php date_default_timezone_set("America/New_York"); ?>
<div class="g-bg-lightblue-v10-opacity-0_5 g-pa-20">
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-xl-4 g-mb-0">
            <!-- Panel -->
            <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                    class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-lightblue-v4 g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="fa fa-envelope g-absolute-centered"></i>
                            </div>
                        </div>

                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black"><?= $totalEmails; ?></span>
                            </div>

                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">Scheduled Emails</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>

        <div class="col-sm-6 col-lg-6 col-xl-4 g-mb-0">
            <!-- Panel -->
            <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                    class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-darkblue-v2 g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="fa fa-send g-absolute-centered"></i>
                            </div>
                        </div>

                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span
                                        class="g-font-size-24 g-line-height-1 g-color-black"><?= $sentEmails; ?></span>
                            </div>

                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">Sent Emails</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>

        <div class="col-sm-6 col-lg-6 col-xl-4 g-mb-0">
            <!-- Panel -->
            <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                    class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-orange  g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="fa fa-envelope-open g-absolute-centered"></i>
                            </div>
                        </div>

                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span
                                        class="g-font-size-24 g-line-height-1 g-color-black"><?= $openedEmails; ?></span>
                            </div>

                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">Opened Emails</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
    </div>
</div>
<?= $this->Html->css(['jquery.datetimepicker']) ?>
<?= $this->Html->script(['php-date-formatter.min', 'jquery.mousewheel', 'jquery.datetimepicker']); ?>
<script type="text/javascript">
    $(function () {

        $('#sendAt').datetimepicker({
            formatTime: 'H:i',
            formatDate: 'y-m-d',
            step:5,
            minDateTime: '<?= date('y-m-d H:i'); ?>',
            onSelectDate: function (datetime) {

            }
        });

    });

</script>
<section class="g-pa-10">
    <form method="post" accept-charset="utf-8" novalidate="novalidate" id="scheduleNewEmailForm"
          action="javascript:void(0);">
        <div style="display:none;"><input type="hidden" name="_method" value="POST"></div>
        <div class="card g-brd-gray-light-v7 g-rounded-3 g-mb-30">
            <header class="card-header g-brd-bottom-none g-px-15 g-px-30--sm g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                <div class="media">
                    <h3 class="d-flex align-self-center text-uppercase g-font-size-12 g-font-size-default--md g-color-primary font-weight-bold g-mr-10 mb-0">
                        Schedule a New Email </h3>
                </div>
            </header>

            <div class="card-block g-pa-15 g-pa-30--sm">
                <div class="row p-lg-4">
                    <div class="col-md-3">
                        <label class="g-mb-10">Select User List:</label>
                        <div class="input select">
                            <select name="list_type"
                                    class="form-control u-select--v3-select u-sibling w-100 u-select--v3 g-pos-rel g-brd-gray-light-v7"
                                    id="UserList" style="height:42px !important;"
                                    title="Select State">
                                <option value="">Select User List</option>
                                <option value="Subscribed">Subscribed User List</option>
                                <option value="UnSubscribed">Unsubscribers</option>
                                <!--<option value="NewsLetter">Sunday Newsletter User List</option>-->
                                <option value="NotSeen_5_7">Not seen last 5 Emails in last 1 Week</option>
                                <option value="NotSeen_10_14">Not seen last 10 Emails in last 2 Weeks</option>
                                <option value="opt_out_campaign_users">Optout Campaign users</option>
                            </select>
                        </div>
                        <label for="userlistData" class="error"></label>
                       <!-- <label for="UserId" class="error"></label>-->
                        <br/>


                        <div id="userFetch" style="display:none">
                        <label class="g-mb-10">Select User :</label>&nbsp;<input type="radio" id="all" name="userlistData" value="allUser">&nbsp;All 
                       
                       &nbsp; <input type="radio" id="single" name="userlistData" value="singleUser">&nbsp;Single 
                       
                        </div>


                        <!-- <div class="hide" id="UsersBox" style="display: none;">
                            <label class="g-mb-10">Select Users:</label>
                            <div class="input select">
                                <select name="user_id"
                                        class="form-control u-select--v3-select u-sibling w-100 u-select--v3 g-pos-rel g-brd-gray-light-v7"
                                        id="UserId" style="height:42px !important;"
                                        title="Select State">
                                </select>
                            </div>
                            <label for="UserId" class="error"></label>
                        </div> -->
                        <div class="hide" id="UsersBox" style="display: none;">
                            <label class="g-mb-10">Select Users:</label>
                            <div class="input select">
                                <!-- <select name="user_id"
                                        class="form-control u-select--v3-select u-sibling w-100 u-select--v3 g-pos-rel g-brd-gray-light-v7"
                                        id="UserId" style="height:42px !important;"
                                        title="Select State">
                                </select> -->

                                <select name="user_id"  class="form-control"
                                        id="UserId" style="height:42px !important;border:1px solid #000;"
                                        title="Select State"></select>
                            </div>
                            <label for="UserId" class="error"></label>
                        </div>
                        <div id="chooseTemplateDiv" style="display: none">
                            <label class="g-mb-10">Choose Email Template</label>
                            <div class="input select">
                                <select name="email_template_id"
                                        class="form-control u-select--v3-select u-sibling w-100 u-select--v3 g-pos-rel g-brd-gray-light-v7"
                                        id="emailTemplateId" style="height:42px !important;">
                                    <option value="">Select Email Template</option>
                                    <?php foreach ($emailTemplates as $id => $name) { ?>
                                        <option value="<?= $id; ?>"><?= $name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <label for="emailTemplateId" class="error"></label>

                            <br/>
                            <div class="form-group mb-0 g-max-width-400">
                                <label>Time is as per EST Timezone</label>
                                <input class="datetimepicker g-bg-transparent g-font-size-12 g-font-size-default--md g-color-gray-dark-v6 g-pr-80 g-pl-15 g-py-9 not-ignore"
                                       name="send_at"
                                       type="text"
                                       placeholder="Select Date"
                                       id="sendAt"
                                       value="<?= date('y-m-d H:i'); ?>"
                                       autocomplete="off"
                                       />
                            </div>

                            <br/>
                        </div>

                        <div id="btnDiv" style="display: none">
                            <button class="btn btn-primary pull-right" id="scheduleEmail"><i
                                        class="fa fa-envelope-o"></i>
                                Schedule Email
                            </button>
                        </div>

<script>
// $(function() {
//     $('#emailTemplateId').change(function(){
//         if($('#emailTemplateId').val() == '') {
//             $('#create_wp_post').hide(); 
//         } else {
//             $('#create_wp_post').show(); 
//         } 
//     });
// });

</script>
                        <br/>
                        <hr/>

                        <div id="previewDiv" style="display: none">

                            <h4>Preview Email</h4>
                            <div class="form-group mb-0 g-max-width-400">
                                <input class="fpv-min-rent form-control form-control-md g-brd-gray-light-v7 g-brd-gray-light-v3--focus rounded-0 g-px-14 g-py-10 w-100"
                                       name="send_to" type="text"
                                       placeholder="Enter Email"

                                       id="sendTo"
                                       value="">
                            </div>
                            <label id="previewEmailError" class="error hide"> </label>
                          

                        </div>

                        <div id="btnPreviewDiv" style="display: none">
                            <button class="btn btn-primary pull-right" id="previewEmail"><i
                                        class="fa fa-send-o"></i>
                                Send Email
                            </button>
                        </div>
                        
                       <!-- <div id="wpPost" style="display: none">
                        <br/>
                        <hr/>
                        <button class="btn btn-primary pull-right" id="create_WP_Post"><i
                                        class="fa fa-exchange"></i>
                                Create WP Post
                            </button>
                            
                        </div><br>-->
                        <div id="response"></div>
                        <div id="postresponse"></div>
                    </div>

                    <div class="col-md-9 p-5 box-shadow mt-5" id="emailTemplatePreview"
                         style="display: none; ">
                        <?= $this->element('Admin/email_preview') ?>
                    </div>
                </div>

            </div>
        </div>
    </form>
    <link href= "<?= SITE_URL; ?>css/newdropdown.css" rel="stylesheet" /> 
    
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function () {

            // initialization of range datepicker
            $.HSCore.components.HSRangeDatepicker.init('.js-range-datepicker');


            $("#scheduleNewEmailForm").validate({
                ignore: false,
                rules: {
                    userlistData: {required: true},
                    user_id: {required: true},
                    email_template_id: {required: true},
                    send_at: {required: true}
                },
                messages: {
                    userlistData: {required: "Please select radio button"},
                    user_id: {required: "Please select receivers."},
                    email_template_id: {required: "Please select email template."},
                    send_at: {required: "Please select send date."}
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: SITE_URL + 'admin/admins/saveScheduledEmail',
                        type: "POST",
                        data: $('#scheduleNewEmailForm').serialize(),
                        dataType: "json",
                        beforeSend: function () {
                            var newModal = new Custombox.modal({
                                overlay: {
                                    close: false
                                },
                                content: {
                                    target: '#pleaseWaitSavingModal',
                                    positionX: 'center',
                                    positionY: 'center',
                                    speedIn: 300,
                                    speedOut: 300,
                                    fullscreen: false,
                                    onClose: function () {
                                        //Send Message will be Here
                                    }
                                }
                            });
                            newModal.open();
                        },
                        success: function (response) {
                            Custombox.modal.close();
                            if (response.code == 200) {
                                var newModal = new Custombox.modal({
                                    overlay: {
                                        close: false
                                    },
                                    content: {
                                        target: '#savedModal',
                                        positionX: 'center',
                                        positionY: 'center',
                                        speedIn: 300,
                                        speedOut: 300,
                                        fullscreen: false,
                                        onClose: function () {
                                            //Send Message will be Here
                                        }
                                    }
                                });
                                newModal.open();
                            }


                        }
                    });

                    //Custombox.modal.close();
                }
            });

            $('#UserId').change(function () {
                if ($(this).val().length > 0) {
                    $('#chooseTemplateDiv, #btnDiv').fadeIn();
                } else {
                    $('#chooseTemplateDiv, #btnDiv').hide();
                }
            });


            $('#emailTemplateId').change(function () {

                // var uid=$('#UserId').val();
                //  alert(uid);
                
                if ($(this).val().length > 0) {
                    $.ajax({
                        url: SITE_URL + 'admin/admins/getEmailTemplate/' + $(this).val(),
                        type: "GET",
                        success: function (response) {
                            $('#emailTemplatePreview').fadeIn();
                            $('#emailDynamicContent').html(response);
                            //Custombox.modal.close();
                        }
                    });

                    $('#previewDiv, #btnPreviewDiv').fadeIn();
                   $('#wpPost').fadeIn();
                } else {
                    $('#emailDynamicContent').html('<h3>No Email Template Selected</h3>');
                    $('#previewDiv, #btnPreviewDiv').fadeOut();
                    $('#wpPost').fadeOut();
                }
            });

            $('#previewEmail').click(function (e) {
                e.preventDefault();
                $('.hide').hide();
                var to = $('#sendTo').val();
                var error = false;

                if (to.length <= 0) {
                    error = true;
                    $('#previewEmailError').html('Please enter email').show();
                } else {
                    if (!validateEmail(to)) {
                        error = true;
                        $('#previewEmailError').html('Please enter valid email').show();
                    }
                }

                if (!error) {

                    $.ajax({
                        url: SITE_URL + 'admin/admins/sendPreviewEmail/',
                        type: "POST",
                        data: {to: to, email_template_id: $('#emailTemplateId').val()},
                        dataType: "JSON",
                        beforeSend: function () {
                            var newModal = new Custombox.modal({
                                overlay: {
                                    close: false
                                },
                                content: {
                                    target: '#pleaseWaitSavingModal',
                                    positionX: 'center',
                                    positionY: 'center',
                                    speedIn: 300,
                                    speedOut: 300,
                                    fullscreen: false,
                                    onClose: function () {
                                        //Send Message will be Here
                                    }
                                }
                            });
                            newModal.open();
                        },
                        success: function (resp) {
                            //Do Something Here
                            Custombox.modal.close();
                        }
                    });
                }
            });




            $('#postconfirmBtn').click(function (e) {
                e.preventDefault();
                var tempID = $('#emailTemplateId').val();
                var responseDiv= document.getElementById('response');
                    $.ajax({
                        url: SITE_URL + 'admin/admins/sendWordpressPost/',
                        type: "POST",
                        data: {id: tempID},
                        dataType: "JSON",
                        beforeSend:function(){$('#postconfirmBtn').html('Please wait...<i class="fa fa-spinner fa-spin"></i>');},
                        success: function (resp) {
                            $('#postconfirmBtn').html('<i class="fa fa-check"></i> Yes');
                            if(resp.status=="Edit")
                            {
                                //var postId=resp.id;
                                $('#WPpostID').val(resp.id);
                                console.log(resp.id);
                                Custombox.modal.close();
                                var newModalpost = new Custombox.modal({
                                    overlay: {
                                        close: false
                                    },
                                    content: {
                                        target: '#editpost',
                                        positionX: 'center',
                                        id:'postConfirmEdit',
                                        positionY: 'center',
                                        speedIn: 300,
                                        speedOut: 300,
                                        fullscreen: false,
                                        onClose: function () {
                                            //Send Message will be Here
                                        }
                                    }
                                });
                                newModalpost.open();

                            }
                            else
                            {
                                console.log(resp.status);
                                Custombox.modal.close();
                                
                                var newModal = new Custombox.modal({
                                    overlay: {
                                        close: false
                                    },
                                    content: {
                                        target: '#createPOST',
                                        positionX: 'center',
                                        positionY: 'center',
                                        speedIn: 300,
                                        speedOut: 300,
                                        fullscreen: false,
                                        onClose: function () {
                                            //Send Message will be Here
                                        }
                                    }
                                });
                                newModal.open();
                               // $('#create_WP_Post').html('<i class="fa fa-exchange"></i> Create WP Post');
                               // responseDiv.innerHTML='<h6 style="color:green;margin-top: -22px;">Created Sucessfully</h6>';
                               // setInterval('location.reload()', 1000);
                            }
     
                   
                        }
                    });
                
            });


            $('#postEditBtn').click(function (e) {
                e.preventDefault();
                var tempID = $('#emailTemplateId').val();
                var wordpressPostID = $('#WPpostID').val();
                
               // var responseDiv= document.getElementById('response');
                var postresponseDiv= document.getElementById('postresponse');
                    $.ajax({
                        url: SITE_URL + 'admin/admins/editWPPost/',
                        type: "POST",
                        data: {id: tempID,wpPostid:wordpressPostID},
                        dataType: "JSON",
                        beforeSend:function(){$('#postEditBtn').html('Please wait...<i class="fa fa-spinner fa-spin"></i>');},
                        success: function (resp) { 
                            $('#postEditBtn').html('<i class="fa fa-check"></i> Yes');
                                Custombox.modal.close();
                                var newModal = new Custombox.modal({
                                    overlay: {
                                        close: false
                                    },
                                    content: {
                                        target: '#sucessPOST',
                                        positionX: 'center',
                                        positionY: 'center',
                                        speedIn: 300,
                                        speedOut: 300,
                                        fullscreen: false,
                                        onClose: function () {
                                            //Send Message will be Here
                                        }
                                    }
                                });
                                newModal.open();
                               // $('#create_WP_Post').html('<i class="fa fa-exchange"></i> Create WP Post');
                               // postresponseDiv.innerHTML='<h6 style="color:green;margin-top: -22px;">Overwrite Sucessfully</h6>';
                               // setInterval('location.reload()', 1000);
                        }
                    });
                
            });



            $('#create_WP_Post').click(function (e) {
                e.preventDefault();
            var confirmModalx = new Custombox.modal({
                overlay: {
                    close: false
                },
                content: {
                    target: '#WPPOSTConfirm',
                    effect: 'blur',
                    id:'postConfirm',
                    animateFrom: 'right',
                    animateTo: 'left',
                    positionX: 'center',
                    positionY: 'center',
                    speedIn: 300,
                    speedOut: 300,
                    fullscreen: false,
                    onClose: function () {
                        //Do Something here
                    }
                }
            });
            confirmModalx.open();
        });



            $('#UserList').change(function (e) {
                e.preventDefault();
               //  $("input[name=userlistData]:checked").val();
                 $('input[name=userlistData]:checked').prop("checked", false);
                  $('#UserId').html("");
                 //$("input[name=userlistData]").val('');
              //  var listType = $('#UserList').val();
               
                //var listType = $(this).val();

                // $.ajax({
                //     url: SITE_URL + 'admin/admins/getUserList/',
                //     type: "POST",
                //     data: {list_type: listType},
                //     dataType: "JSON",
                //     beforeSend: function () {
                //         var newModal = new Custombox.modal({
                //             overlay: {
                //                 close: false
                //             },
                //             content: {
                //                 target: '#pleaseWaitSavingModal',
                //                 positionX: 'center',
                //                 positionY: 'center',
                //                 speedIn: 300,
                //                 speedOut: 300,
                //                 fullscreen: false,
                //                 onClose: function () {
                //                     //Send Message will be Here
                //                 }
                //             }
                //         });
                //         newModal.open();
                //         $('#UserId').html("");
                //       //  $('#UsersBox').hide();
                //         $('#userFetch').hide();
                //     },
                //     success: function (resp) {
                //         //Do Something Here
                //         Custombox.modal.close();
                //         if(resp.users.length > 0){
                //             var os = [];
                //             os.push('<option value="">Select User</option>');
                //             os.push('<option value="All">All Users</option>');

                //             $.each(resp.users, function (ind, user) {
                //                 os.push('<option value="'+user.id+'">'+user.email+'</option>');
                //             });

                //             $('#UserId').html(os.join(""));


                //         }
                //        // $('#UsersBox').show();
                //         $('#userFetch').show();
                //     }
                // });

                $('#userFetch').show();
                $('#UsersBox').hide();
                

            });

            function validateEmail(email) {
                const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            }

        });

        $("#userFetch").click(function(){
            var radioValue = $("input[name=userlistData]:checked").val();


            if(radioValue == "allUser"){
                $('#UsersBox').hide();
                $('#chooseTemplateDiv').show();
               $('#UserId').val('All');
                $('#UserId').html('<option value="All">All Users</option>');
                $('#btnDiv').fadeIn();
                
            }else if(radioValue == "singleUser"){
                $('#UsersBox').show();
                $('#UserId').val('');
                var userIdVal = $('#UserId').val();
                
                //alert(userIdVal);
                if(userIdVal==null){

                //alert(JSON.stringify(userIdVal));

                var listType = $('#UserList').val();
               // alert(listType);
                // $.ajax({
                //     url: SITE_URL + 'admin/admins/getUserList/',
                //     type: "POST",
                //     data: {list_type: listType},
                //     dataType: "JSON",
                //     beforeSend: function () {
                //         var newModal = new Custombox.modal({
                //             overlay: {
                //                 close: false
                //             },
                //             content: {
                //                 target: '#pleaseWaitSavingModal',
                //                 positionX: 'center',
                //                 positionY: 'center',
                //                 speedIn: 300,
                //                 speedOut: 300,
                //                 fullscreen: false,
                //                 onClose: function () {
                //                     //Send Message will be Here
                //                 }
                //             }
                //         });
                //         newModal.open();
                //         $('#UserId').html("");
                //     },
                //     success: function (resp) {
                //         //Do Something Here
                //         Custombox.modal.close();
                //         if(resp.users.length > 0){
                //             var os = [];
                //             os.push('<option value="">Select User</option>');
                //             os.push('<option value="All">All Users</option>');

                //             $.each(resp.users, function (ind, user) {
                //                 os.push('<option value="'+user.id+'">'+user.email+'</option>');
                //             });

                //             $('#UserId').html(os.join(""));


                //         }
                //        // $('#UsersBox').show();
                //         $('#userFetch').show();
                //     }
                // });

                $('#UserId').select2({
                   // alert('okok');
                    ajax: {
                        url: SITE_URL + 'admin/admins/getUserList/',
                        type: "POST",
                        dataType: "JSON",
                        delay: 300,
                        data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            list_type: listType
                        }
                        return query;
                        },
                        processResults: function (data, params) {
                        params.page = params.page || 1;
                        $('#userFetch').show();
                            return {
                                results: data.users,
                                pagination: {
                                    more: (params.page * 10) < data.count_filtered
                                }
                            };
                        }
                    }
                });
                
            }
        }
        });


    </script>
</section>
<div id="pleaseWaitSavingModal"
     class="text-left g-color-gray-dark-v1 g-bg-white g-overflow-y-auto  g-pa-20"
     style="display: none; width: 300px; height: auto; padding: 10%; background-color: #007eef !important; color: #ffffff !important;">
    <h4 class="h4 text-center">Please wait... <i class="fa fa-spin fa-spinner"></i></h4>
    <div class="clear-both"></div>
</div>

<div id="savedModal"
     class="text-left g-color-gray-dark-v1 g-bg-white g-overflow-y-auto  g-pa-20"
     style="display: none; width: 300px; height: auto; padding: 10%; background-color: #007eef !important; color: #ffffff !important;">
    <img src="<?= SITE_URL; ?>img/hi5.png" style="width: 260px;">
    <h4 class="h4 text-center">
         Your emails has been scheduled.
    </h4>
    <div class="row">
        <div class="col-md-12 text-center">
            <a href="<?= SITE_URL; ?>admin/admins/scheduledEmails">
                <button type="button" class="btn btn-lg btn-dark">
                <i class="fa fa-check"></i> OK
            </button>
            </a>
        </div>
    </div>
    <div class="clear-both"></div>
</div>


<div id="sucessPOST"
class="text-left g-color-white g-bg-gray-dark-v1 g-overflow-y-auto  g-pa-20"
     style="display: none; width: 300px; height: auto; padding: 10%; color: #ffffff !important;">
    
    <h4 class="h4 text-center">
         Post is sucessfully Overwrite.
    </h4>
    <div class="row">
        <div class="col-md-12 text-center">
        <button type="button" class="btn btn-lg btn-dark" onclick="Custombox.modal.close();">
                <i class="fa fa-check"></i> OK
            </button>
        </div>
    </div>
    <div class="clear-both"></div>
</div>


<div id="createPOST"
class="text-left g-color-white g-bg-gray-dark-v1 g-overflow-y-auto  g-pa-20"
     style="display: none; width: 300px; height: auto; padding: 10%; color: #ffffff !important;">
    
    <h4 class="h4 text-center">
         Post is sucessfully created.
    </h4>
    <div class="row">
        <div class="col-md-12 text-center">
            <button type="button" class="btn btn-lg btn-dark" onclick="Custombox.modal.close();">
                <i class="fa fa-check"></i> OK
            </button>
        </div>
        
    </div>
    <div class="clear-both"></div>
</div>



<input type="hidden" value="" id="WPpostID">

<div id="editpost"
                     class="text-left g-color-white g-bg-gray-dark-v1 g-overflow-y-auto  g-pa-20"
                     style="display: none; width: auto; height: auto; padding: 10%;">
                  <button type="button" class="close" onclick="Custombox.modal.close();">
                        <i class="hs-icon hs-icon-close"></i>
                    </button>
                    <h4 class="h4 g-mb-20">Post Already Exist</h4>
                    <div calss="modal-body" style="position: relative;">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="h5">Do you want to overwrite this ?</h5>
                            </div>
                            <div class="col-md-5"></div>
                            <div class="col-md-7">

                            <button type="button" class="btn btn-danger pull-right "
                                        onclick="Custombox.modal.close();">
                                    <i class="fa fa-close"></i> No
                                </button>

                                <button type="button" class="btn btn-success pull-right mr-4" id="postEditBtn">
                                    <i class="fa fa-check"></i> Yes
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="clear-both"></div>
                </div>



<div id="WPPOSTConfirm"
                     class="text-left g-color-white g-bg-gray-dark-v1 g-overflow-y-auto  g-pa-20"
                     style="display: none; width: auto; height: auto; padding: 10%;">
                   <button type="button" class="close" onclick="Custombox.modal.close();">
                        <i class="hs-icon hs-icon-close"></i>
                    </button>

                    <h4 class="h4 g-mb-20">Confirm Wordpress POST</h4>
                    <div calss="modal-body" style="position: relative;">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="h5">Do you want to create the POST ?</h5>
                            </div>
                            <div class="col-md-5"></div>
                            <div class="col-md-7">

                            <button type="button" class="btn btn-danger pull-right "
                                        onclick="Custombox.modal.close();">
                                    <i class="fa fa-close"></i> No
                                </button>

                                <button type="button" class="btn btn-success pull-right mr-4" id="postconfirmBtn">
                                    <i class="fa fa-check"></i> Yes
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="clear-both"></div>
                </div>








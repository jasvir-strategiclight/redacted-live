<?php

namespace App\View\Helper;


use Cake\View\Helper;
use Cake\Routing\Router;
use Cake\Utility\Inflector;

class AdminListingHelper extends Helper
{

    public $controller;
    public $relatedModel;
    public $obj;
    public $object;
    public $objectFieldValue;
    public $fields;
    public $field;
    public $includeStatusScript = false;
    public $hasPagination = false;
    public $paging = false;
    public $bulk;
    public $search;
    public $request;
    public $view;
    public $srNo = true;
    public $showSearch = true;
    public $showPagination = true;
    public $showBulkActions = true;
    public $deleteMessage = "Are you sure you want delete this?";
    public $resetMessage = "Are you sure you want reset this template?";
    public $loadScripts = true;


    public function create($params = null, $actions = ['view', 'edit', 'delete'], $loadScripts = true)
    {

        $this->loadScripts = $loadScripts;
        $this->srNo = isset($params['srNo']) ? $params['srNo'] : true;
        $this->showSearch = isset($params['showSearch']) ? $params['showSearch'] : true;
        $this->showPagination = isset($params['showPagination']) ? $params['showPagination'] : true;
        $this->showBulkActions = isset($params['showBulkActions']) ? $params['showBulkActions'] : true;
        $inputClasses = "g-hidden-xs-up g-pos-abs g-top-0 g-left-0 not-ignore";
        $labelClasses = "u-check-icon-checkbox-v4 g-absolute-centered--y g-left-0";
        $this->view = $this->getView();
        $this->request = $this->view->getRequest();
        $this->controller = isset($params['controller']) ? $params['controller'] : $this->request->getParam('controller');
        $this->object = isset($params['object']) ? $params['object'] : $this->view->get($this->view->getVars()[0]);

        $this->fields = $params['fields'];


        if (!empty($this->getView()->getRequest()->getParam('paging'))) {

            $this->paging = array_values($this->getView()->getRequest()->getParam('paging'))[0];
            if ($this->paging['count'] > 0) {
                $this->hasPagination = true;
                $this->setBulk($params);

            }
        }

        $this->setSearch($params);

        if (!empty($this->fields)) {
            $this->createSearchAndBulkActions();
            ?>
                        <div class="faqs table-responsive g-mb-40">
                            <table cellpadding="0" cellspacing="0"
                                   class="table table-bordered table-hover u-table--v3 g-color-black">
                                <thead>
                                <tr>
                                    <?php if ($this->srNo) { ?>
                                            <th style="width: 6%;">Sr.No.</th>
                                    <?php } ?>
                                    <?php foreach ($this->fields as $field) { ?>
                                            <?php $field['type'] = empty($field['type']) ? 'text' : $field['type']; ?>
                                            <?php if (isset($field['sortable']) && $field['sortable'] == false) { ?>
                                                    <th scope="col"
                                                        class="<?= $field['type'] == "image" ? "text-center" : "" ?>"><?= __(empty($field['label']) ? $field['name'] : $field['label']) ?></th>
                                            <?php } else { ?>
                                                    <th scope="col"><?= $this->view->Paginator->sort($field['name'], empty($field['label']) ? null : $field['label']) ?></th>
                                            <?php } ?>
                                    <?php } ?>
                                    <?php if (!empty($actions)) { ?>
                                            <th scope="col" class="actions" style="width: 34%;"><?= __('Actions') ?></th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (count($this->object) <= 0) { ?>
                                        <tr>
                                            <td colspan="<?= count($this->fields) + 2; ?>">
                                                <h3>No Record found. </h3>
                                            </td>
                                        </tr>
                                <?php } else { ?>
                                        <?php foreach ($this->object as $srNo => $obj): ?>
                                                <?php $this->obj = $obj; ?>
                                                <tr>
                                                    <?php if ($this->srNo) { ?>
                                                            <td><?=($srNo + (($this->paging['page'] - 1) * $this->paging['perPage']) + 1); ?></td>
                                                    <?php } ?>
                                                    <?php
                                                    foreach ($this->fields as $field) {
                                                        $field['type'] = empty($field['type']) ? 'text' : $field['type'];
                                                        $this->field = $field;
                                                        $this->fieldValue();
                                                        ?>
                                                            <td class="<?= $field['type'] == "image" ? "text-center" : "" ?>">

                                                                <?php
                                                                if ($this->fieldConditions($field)) {
                                                                    switch ($field['type']) {
                                                                        case 'image': {
                                                                                $this->createImage();
                                                                                break;
                                                                            }
                                                                        case 'link': {
                                                                                $this->createLink();
                                                                                break;
                                                                            }
                                                                        case 'base64Encode': {
                                                                                $this->base64Encode();
                                                                                break;
                                                                            }
                                                                        case 'base64Decode': {
                                                                                $this->base64Decode();
                                                                                break;
                                                                            }
                                                                        case 'md5': {
                                                                                $this->md5();
                                                                                break;
                                                                            }
                                                                        case 'status': {
                                                                                $this->createStatus();
                                                                                $this->includeStatusScript = true;
                                                                                break;
                                                                            }
                                                                        case 'text': {
                                                                                $this->createText();
                                                                                break;
                                                                            }
                                                                        case 'date': {
                                                                                $this->createDate();
                                                                                break;
                                                                            }
                                                                        case 'datetime': {
                                                                                $this->createDateTime();
                                                                                break;
                                                                            }
                                                                        case 'percentage': {
                                                                                $this->createPercentage();
                                                                                break;
                                                                            }
                                                                    }
                                                                }
                                                                ?>
                                                            </td>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if (!empty($actions)) {
                                                        $this->createActions($actions);
                                                    }

                                                    ?>
                                                </tr>
                                        <?php endforeach; ?>
                                <?php } ?>
                                </tbody>
                            </table>
                            <?php if ($this->loadScripts) { ?>
                                    <?php $this->statusScript(); ?>
                                    <script>
                                        $(document).ready(function () {
                                            var deleteBtn = null;
                                            var postBtn = null;
                                            $('#selectAll').click(function (e) {
                                                $('.select-row').prop('checked', $(this).is(':checked'));
                                            });

                                            $('.select-row').click(function (e) {
                                                var totalChecks = $('.select-row').length;
                                                var checkedChecks = $('.select-row:checked').length;

                                                $('#selectAll').prop('checked', ((totalChecks == checkedChecks) ? true : false));
                                            });

                                            $('.js-select').selectpicker();

                                            $('.delete-btn').click(function (e) {
                                                e.preventDefault();
                                                deleteBtn = $(this).attr('id');
                                                console.log(deleteBtn);
                                            });

                                            $('#deleteIt').click(function (e) {
                                                e.preventDefault();
                                                console.log(deleteBtn);
                                                $('#' + deleteBtn.replace("btn", 'form')).submit();
                                            });

                                            $('.reset-btn').click(function (e) {
                                                e.preventDefault();
                                                deleteBtn = $(this).attr('id');
                                                console.log(deleteBtn);
                                            });

                                            $('#resetIt').click(function (e) {
                                                e.preventDefault();
                                                console.log(deleteBtn);
                                                $('#' + deleteBtn.replace("btn", 'form')).submit();
                                            });



                                            $('.post-btn').click(function (e) {
                                                e.preventDefault();
                                                postBtn = $(this).attr('id');
                                                console.log(postBtn);
                                            });

                                            // $('#postconfirmBtn').click(function (e) {
                                            //    // alert(postBtn);
                                            //     e.preventDefault();
                                            //     console.log(postBtn);

                                            //     $('#' + postBtn.replace("btn", 'form')).submit();
                                            // });

                                            $('#postEditBtn').click(function (e) {
                                               // e.preventDefault();
                               
                                                var tempID = postBtn;
                                                var wordpressPostID = $('#WPpostID').val();
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
                                            
                                                        }
                                                    });
                                
                                            });



                                    $('.postconfirmBtn').click(function (e) {
                                        e.preventDefault();
                                        var tempID = postBtn; 
                                        var btnpostid='.btnpost'+tempID;
                                        //alert(tempID);  
                                          $.ajax({
                                        url: SITE_URL + 'admin/admins/sendWordpressPost/',
                                        type: "POST",
                                        data: {id: tempID},
                                        dataType: "JSON",
                                        beforeSend:function(){$(btnpostid).html('Wait...<i class="fa fa-spinner fa-spin"></i>');},
                                        success: function (resp) {
                                            $('.postconfirmBtn').html('<i class="fa fa-exchange"></i> WP POST');
    
                                            if(resp.status=="Edit")
                                            {
                                                //var postId=resp.id;
                                                $('#WPpostID').val(resp.id);
                                                console.log(resp.id);
                                               // Custombox.modal.close();
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
                                               // Custombox.modal.close();
                                
                                                var newModal = new Custombox.modal({
                                                    overlay: {
                                                        close: false
                                                    },
                                                    content: {
                                                        target: '#wpPostConfirmModal',
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
                
                            });



                            $('#postconfirmBtnAdd').click(function (e) {
                                        e.preventDefault();
                                        var tempID = postBtn;   
                                          $.ajax({
                                        url: SITE_URL + 'admin/admins/sendWordpressPost/',
                                        type: "POST",
                                        data: {id: tempID},
                                        dataType: "JSON",
                                        beforeSend:function(){$('#postconfirmBtnAdd').html('Please wait...<i class="fa fa-spinner fa-spin"></i>');},
                                        success: function (resp) {
                                            $('#postconfirmBtnAdd').html('<i class="fa fa-check"></i> Yes');
                                            // if(resp.status=="Edit")
                                            // {
                                            //     //var postId=resp.id;
                                            //     $('#WPpostID').val(resp.id);
                                            //     console.log(resp.id);
                                            //     Custombox.modal.close();
                                            //     var newModalpost = new Custombox.modal({
                                            //         overlay: {
                                            //             close: false
                                            //         },
                                            //         content: {
                                            //             target: '#editpost',
                                            //             positionX: 'center',
                                            //             id:'postConfirmEdit',
                                            //             positionY: 'center',
                                            //             speedIn: 300,
                                            //             speedOut: 300,
                                            //             fullscreen: false,
                                            //             onClose: function () {
                                            //                 //Send Message will be Here
                                            //             }
                                            //         }
                                            //     });
                                            //     newModalpost.open();

                                            // }
                                            // else
                                            // {
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
                                           // }
     
                   
                                        }
                                    });
                
                            });



























                                            $.HSCore.components.HSModalWindow.init('[data-modal-target]');

                                            $('#applyAction').click(function (e) {
                                                e.preventDefault();
                                                e.stopImmediatePropagation();
                                                alert('This  feature is in progress..')
                                            });
                                        });
                                    </script>
                            <?php } ?>

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

                            <div id="deleteConfirmModal"
                                 class="text-left g-color-white g-bg-gray-dark-v1 g-overflow-y-auto  g-pa-20"
                                 style="display: none; width: auto; height: auto; padding: 10%;">
                                <button type="button" class="close" onclick="Custombox.modal.close();">
                                    <i class="hs-icon hs-icon-close"></i>
                                </button>
                                <h4 class="h4 g-mb-20">Delete</h4>
                                <div calss="modal-body" style="position: relative;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="h5"><?= $this->deleteMessage; ?></h5>
                                        </div>
                                        <div class="col-md-7"></div>
                                        <div class="col-md-5">

                                            <button type="button" class="btn btn-success pull-right "
                                                    onclick="Custombox.modal.close();">
                                                <i class="fa fa-close"></i> Cancel
                                            </button>

                                            <button type="button" class="btn btn-danger pull-right mr-4" id="deleteIt">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear-both"></div>
                            </div>

                            <div id="resetConfirmModal"
                                 class="text-left g-color-white g-bg-gray-dark-v1 g-overflow-y-auto  g-pa-20"
                                 style="display: none; width: auto; height: auto; padding: 10%;">
                                <button type="button" class="close" onclick="Custombox.modal.close();">
                                    <i class="hs-icon hs-icon-close"></i>
                                </button>
                                <h4 class="h4 g-mb-20">Reset</h4>
                                <div calss="modal-body" style="position: relative;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="h5"><?= $this->resetMessage; ?></h5>
                                        </div>
                                        <div class="col-md-7"></div>
                                        <div class="col-md-5">

                                            <button type="button" class="btn btn-success pull-right "
                                                    onclick="Custombox.modal.close();">
                                                <i class="fa fa-close"></i> No
                                            </button>

                                            <button type="button" class="btn btn-danger pull-right mr-4" id="resetIt">
                                                Yes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear-both"></div>
                            </div>


                            <div id="wpPostConfirmModal"
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

                                            <button type="button" class="btn btn-success pull-right mr-4" id="postconfirmBtnAdd">
                                                <i class="fa fa-check"></i> Yes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear-both"></div>
                            </div>



                            <div id="statusConfirmModal"
                                 class="text-left g-color-white g-bg-gray-dark-v1 g-overflow-y-auto  g-pa-20"
                                 style="display: none; width: auto; height: auto; padding: 10%;">
                                <button type="button" class="close" onclick="Custombox.modal.close();">
                                    <i class="hs-icon hs-icon-close"></i>
                                </button>
                                <h4 class="h4 g-mb-20">&nbsp;</h4>
                                <div calss="modal-body" style="position: relative;">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <h5 class="h5" id="statusMsg"></h5>
                                        </div>
                                        <div class="col-md-12">

                                            <button type="button" class="btn btn-success pull-right confirm-status-btn"
                                                    id="cancelStatusIt">
                                                <i class="fa fa-close"></i> Cancel
                                            </button>

                                            <button type="button" class="btn btn-danger pull-right mr-4 confirm-status-btn"
                                                    id="confirmIt">
                                                <i class="fa fa-check"></i> Confirm
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear-both"></div>
                            </div>
                        </div>
                        <?php
                        if ($this->showPagination) {
                            $this->pagination();
                        }
        }
    }

    public function fieldValue()
    {
        if (isset($this->field['join'])) {
            foreach ($this->field['join'] as $name) {
                $values[] = $this->getValue($name);
            }
            $this->objectFieldValue = implode(empty($this->field['separator']) ? " " : $this->field['separator'], $values);
        } else {
            if ($this->field['name'] == "phone") {
                $this->objectFieldValue = $this->phoneFormat($this->getValue($this->field['name']));
            } else {
                $this->objectFieldValue = $this->getValue($this->field['name']);
            }
        }


    }

    public function getValue($name)
    {
        $objectFieldValue = "";
        if (strpos($name, '_id') !== false) {
            $relatedModel = str_replace('_id', '', $name);

            if ($this->obj->has($relatedModel)) {
                if (!empty($this->field['related_model_fields'])) {
                    $values = [];
                    foreach ($this->field['related_model_fields'] as $f) {
                        $values[] = $this->obj->{$relatedModel}->{$f};
                    }

                    $objectFieldValue = implode(" ", $values);

                } else {
                    $objectFieldValue = $this->obj->{$relatedModel}->name;
                }
            } else {
                if (isset($this->field['id']) && $this->field['id'] == "show") {
                    $objectFieldValue = $this->obj->{$name};
                }
            }
        } else {
            $objectFieldValue = $this->obj->{$name};
        }

        return $objectFieldValue;
    }

    public function createImage()
    {
        $rounded = isset($this->field['rounded']) ? $this->field['rounded'] : true;
        $square = isset($this->field['square']) ? $this->field['square'] : true;
        $relatedModel = str_replace('_id', '', $this->field['name']);
        $image = SITE_URL . (($this->obj->has($relatedModel)) ? $this->obj->{$relatedModel}->small_thumb : 'files/images/default.jpg');
        ?>
                <img class="img-fluid detail-img-fluid <?= $rounded ? "rounded-circle" : ""; ?> " src="<?= $image; ?>"
                     style="<?php if ($square) { ?> width:60px; height: 60px; <?php } else { ?> width:80px; max-height: 80px; <?php } ?>"
                     alt="Profile Image">
            <?php
    }

    public function createLink()
    {
        $linkUrl = $this->field['url'];
        $linkUrl[] = empty($this->field['id_field']) ? $this->obj->id : $this->obj->{$this->field['id_field']};
        ?>
                <a
                        href="<?= Router::url($linkUrl); ?>"
                        class="<?= empty($this->field['class']) ? "" : $this->field['class']; ?>"
                        data-id="<?= $this->obj->id; ?>">
                    <?= empty($this->field['link_label']) ? $this->objectFieldValue : $this->field['link_label']; ?>
                </a>
            <?php

    }

    public function createDate()
    {
        echo empty($this->objectFieldValue) ? "NA" : date(DATE_PICKER, strtotime($this->objectFieldValue));
    }

    public function createDateTime()
    {
        echo empty($this->objectFieldValue) ? "NA" : date(SHORT_DATE, strtotime($this->objectFieldValue));
    }

    public function base64Encode()
    {
        echo empty($this->objectFieldValue) ? "NA" : base64_encode($this->objectFieldValue);
    }

    public function md5()
    {
        echo empty($this->objectFieldValue) ? "NA" : md5($this->objectFieldValue);
    }

    public function base64Decode()
    {
        echo empty($this->objectFieldValue) ? "NA" : base64_decode($this->objectFieldValue);
    }

    public function createPercentage()
    {
        echo empty($this->objectFieldValue) ? "NA" : round($this->objectFieldValue, 2) . "%";
    }

    public function createStatus()
    {

        $id = empty($this->field['id']) ? $this->obj->id : $this->obj->{$this->field['id']};

        $anchorClasses = "btn-u btn-u-sm rounded-3x";
        $activeText = empty($this->field['active_text']) ? "Active" : $this->field['active_text'];
        $inactiveText = empty($this->field['inactive_text']) ? "Inactive" : $this->field['inactive_text'];
        $reverse = isset($this->field['reverse']) ? $this->field['reverse'] : false;

        $activeMsg = empty($this->field['active_message']) ? "Are you sure you want to activate this?" : $this->field['active_message'];
        $inactiveMsg = empty($this->field['inactive_message']) ? "Are you sure you want to activate this?" : $this->field['inactive_message'];
        if ($this->objectFieldValue) {
            $label = $activeText;
        } else {
            $anchorClasses = $anchorClasses . " btn-u-orange ";
            $label = $inactiveText;
        }

        if ($reverse) {
            $anchorClasses = ($this->objectFieldValue) ? $anchorClasses . " btn-u-orange " : "btn-u btn-u-sm rounded-3x";
        }

        $readOnly = '';
        if (isset($this->field['readonly'])) {
            $readOnly = 'disabled';
            $anchorClasses = $anchorClasses . " disabled  btn-u btn-u-default";
        } else {
            $anchorClasses = $anchorClasses . ' active-deactive';
        }

        ?>
                <button class="<?= $anchorClasses; ?> "
                        id="<?= Inflector::camelize($this->field['name']); ?>_<?= $id ?>"
                        data-model="<?= $this->field['model']; ?>"
                        data-field="<?= $this->field['name'] ?>"
                        data-active-text="<?= $activeText ?>"
                        data-inactive-text="<?= $inactiveText ?>"
                        data-active-message="<?= $activeMsg ?>"
                        data-inactive-message="<?= $inactiveMsg ?>"
                        data-confirm="<?= empty($this->field['confirm']) ? "not-confirm" : "confirm" ?>"
                        data-current-status="<?= $label ?>"
                        data-reverse="<?= $reverse ?>"
                        title="Click to <?= $label ?>"
                    <?= $readOnly; ?>>
                    <?= $label ?>
                </button>

        <?php }

    public function createText()
    {
        echo $this->objectFieldValue;
    }

    public function createActions($actions)
    {
        ?>
                <td class="actions" style="width: 35%;">
                    <?php
                    foreach ($actions as $action => $actionParams) {
                        if ($this->fieldConditions($actionParams)) {
                            if (in_array(strtolower($action), ['edit', 'view', 'delete'])) {
                                $this->{$action}($actionParams);
                            } else {
                                if (is_array($actionParams)) {
                                    $this->customAction($actionParams);
                                } else {
                                    $this->{$actionParams}();
                                }
                            }
                        }
                    }
                    ?>
                </td>
            <?php
    }

    public function customAction($action = [])
    {


        if (!empty($action['id'])) {
            $id = $action['id'] === true ? $this->obj->id : $this->obj->{$action['id']};
        } else {

            $id = $this->obj->id;
        }


        $action['url'][] = $id;
        $url = Router::url($action['url']);
        $classes = empty($action['class']) ? "btn-u btn-u-sea btn-u-sm rounded" : $action['class'];
        $target = empty($action['target']) ? "_self" : $action['target'];
        ?>
                <a href="<?= $url; ?>" class=" <?= $classes; ?>"
                   style="float: left; margin-left: 10px;" target="<?= $target; ?>" data-id="<?= $id; ?>">
                    <i class='<?= empty($action['icon']) ? "fa fa-circle-o" : $action['icon']; ?>'></i> <?= $action['label']; ?>
                </a>
            <?php
    }

    public function view()
    {
        $url = Router::url(['controller' => $this->controller, 'action' => 'view', $this->obj->id]);
        ?>
                <a href="<?= $url; ?>" class="btn-u btn-u-sea btn-u-sm rounded" style="float: left; margin-left: 10px;">
                    <i class='hs-admin-eye'></i> Detail
                </a>
            <?php
    }

    /* public function edit() {
    $url = Router::url(['controller' => $this->controller, 'action' => 'edit', $this->obj->id]);
    
    ?>
    <a  class="btn-u btn-u-blue btn-u-sm rounded edit" data-temp_id = '<?=$this->obj->id ?>' style="float: left; margin-left: 10px;color:white;">
    <i class='hs-admin-pencil'></i> Edit
    </a>
    <?php
    }*/

    public function edit()
    {
        $url = Router::url(['controller' => $this->controller, 'action' => 'edit', $this->obj->id]);
        if ($this->controller != 'EmailTemplates') {
            ?>
                        <a  href="<?= $url; ?>" class="btn-u btn-u-blue btn-u-sm rounded edit" data-temp_id = '<?= $this->obj->id ?>' style="float: left; margin-left: 10px;color:white;">
                        <i class='hs-admin-pencil'></i> Edit
                    </a>
                <?php
        } else {
            ?>
                        <a  class="btn-u btn-u-blue btn-u-sm rounded edit" data-temp_id = '<?= $this->obj->id ?>' style="float: left; margin-left: 10px;color:white;">
                        <i class='hs-admin-pencil'></i> Edit
                    </a>
                <?php
        }
        ?>
        
            <?php
    }


    public function delete($actionParams = null)
    {

        if (!empty($actionParams['id'])) {
            $id = $actionParams['id'] === true ? $this->obj->id : $this->obj->{$actionParams['id']};
        } else {
            $id = $this->obj->id;
        }

        if (empty($actionParams['url'])) {
            $url = ['controller' => $this->controller, 'action' => 'delete', $id];
        } else {
            $url = $actionParams['url'];
            $url[] = $id;
        }
        if (!empty($actionParams['deleteMessage'])) {
            $this->deleteMessage = $actionParams['deleteMessage'];
        }
        //$url = 'javascript:void(0);';
        ?>
                <?= $this->view->Form->create(null, ['url' => $url, 'id' => 'delete_' . $id . '_form']); ?>
                <button data-modal-target="#deleteConfirmModal"
                        data-modal-effect="slide" class="btn-u btn-u-red btn-u-sm rounded delete-btn"
                        style="float: left; margin-left: 10px;" id="delete_<?= $id; ?>_btn"><i
                            class="hs-admin-close"></i> Delete
                </button>
                <?= $this->view->Form->end(); ?>


            <?php
    }

    public function reset($actionParams = null)
    {

        if (!empty($actionParams['id'])) {
            $id = $actionParams['id'] === true ? $this->obj->id : $this->obj->{$actionParams['id']};
        } else {
            $id = $this->obj->id;
        }

        if (empty($actionParams['url'])) {
            $url = ['controller' => $this->controller, 'action' => 'reset', $id];
        } else {
            $url = $actionParams['url'];
            $url[] = $id;
        }
        if (!empty($actionParams['resetMessage'])) {
            $this->resetMessage = $actionParams['resetMessage'];
        }
        //$url = 'javascript:void(0);';
        ?>
                <?= $this->view->Form->create(null, ['url' => $url, 'id' => 'reset_' . $id . '_form']); ?>
                <button data-modal-target="#resetConfirmModal"
                        data-modal-effect="slide" class="btn-u btn-u-red btn-u-sm rounded reset-btn"
                        style="float: left; margin-left: 10px;" id="reset_<?= $id; ?>_btn"><i
                            class="hs-admin-c`lose"></i> Reset
                </button>
                <?= $this->view->Form->end(); ?>


            <?php
    }

    public function post($actionParams = null)
    {

        if (!empty($actionParams['id'])) {
            $id = $actionParams['id'] === true ? $this->obj->id : $this->obj->{$actionParams['id']};
        } else {
            $id = $this->obj->id;
        }

        if (empty($actionParams['url'])) {
            $url = ['controller' => $this->controller, 'action' => 'createWPPost', $id];
        } else {
            $url = $actionParams['url'];
            $url[] = $id;
        }

        ?>
                <?= $this->view->Form->create(null, ['url' => $url, 'id' => 'post_' . $id . '_form']); ?>

           <!--<button data-modal-target="#postconfirmBtn"
                    data-modal-effect="slide" class="btn-u btn-u-dark btn-u-sm rounded post-btn"
                    style="float: left; margin-left: 10px;" id=<?php //echo $id; ?>><i
                        class="fa fa-exchange"></i> WP POST
            </button>-->
        &nbsp;
            <button type="button" style="float: left; margin-left: 10px;" class="btn-u btn-u-dark btn-u-sm rounded post-btn postconfirmBtn btnpost<?php echo $id; ?>" id=<?php echo $id; ?> ><i class="fa fa-exchange"></i> WP POST
                      
            </button>





            <?= $this->view->Form->end(); ?>


            <?php
    }

    public function pagination()
    {
        if ($this->view->Paginator->hasPage()) {
            ?>
                        <div class="paginator">
                            <ul class="pagination">
                                <?= $this->view->Paginator->first('<< ' . __('first')) ?>
                                <?= $this->view->Paginator->prev('< ' . __('previous')) ?>
                                <?= $this->view->Paginator->numbers() ?>
                                <?= $this->view->Paginator->next(__('next') . ' >') ?>
                                <?= $this->view->Paginator->last(__('last') . ' >>') ?>
                            </ul>
                            <p><?= $this->view->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
                        </div>
                    <?php
        }
    }

    public function statusScript()
    {

        if ($this->includeStatusScript) {
            ?>
                        <script>
                            $(document).ready(function () {

                                $('.active-deactive').click(function (e) {
                                    e.preventDefault();
                                    e.stopImmediatePropagation();
                                    var _this = $(this);
                                    var confirm = _this.attr('data-confirm');
                                    if (confirm == "confirm") {
                                        var id = _this.attr('id');

                                        $('.confirm-status-btn').attr('data-id', id);

                                        _this.attr('data-confirm', 'confirmed');

                                        if (_this.attr('data-current-status') == _this.attr('data-inactive-text')) {
                                            $('#statusMsg').html(_this.attr('data-inactive-message'));
                                        } else {
                                            $('#statusMsg').html(_this.attr('data-active-message'));
                                        }

                                        var newModal = new Custombox.modal({
                                            overlay: {
                                                close: false
                                            },
                                            content: {
                                                target: '#statusConfirmModal',
                                                effect: 'slit',
                                                animateFrom: 'left',
                                                animateTo: 'left',
                                                positionX: 'center',
                                                positionY: 'center',
                                                speedIn: 300,
                                                speedOut: 300,
                                                onClose: function () {

                                                }
                                            }
                                        });
                                        newModal.open();

                                    } else {
                                        var model = _this.attr('data-model');
                                        var field = _this.attr('data-field');
                                        var id = _this.attr('id').split('_')[1];

                                        $.ajax({
                                            url: SITE_URL + "admin/admins/changeStatus/",
                                            type: "POST",
                                            data: {model: model, field: field, id: id},
                                            dataType: "json",
                                            beforeSend: function () {
                                                _this.html(_this.html() + ' <i class="fa fa-spinner fa-spin"></i>');
                                            },
                                            success: function (response) {

                                                if (response.code == 200) {

                                                    _this.removeClass('btn-u-orange');

                                                    if (response.data.new_status) {
                                                        if (_this.attr('data-reverse') == true) {
                                                            _this.addClass('btn-u-orange');
                                                        }
                                                        _this.html(_this.attr('data-active-text'));
                                                        _this.attr('data-current-status', _this.attr('data-active-text'));
                                                    } else {
                                                        if (_this.attr('data-reverse') == false) {
                                                            _this.addClass('btn-u-orange');
                                                        }
                                                        _this.html(_this.attr('data-inactive-text'));
                                                        _this.attr('data-current-status', _this.attr('data-inactive-text'));

                                                    }

                                                    if (_this.attr('data-confirm') == "confirmed") {
                                                        _this.attr('data-confirm', 'confirm');
                                                    }
                                                } else {
                                                    $().showFlashMessage("error", response.message);
                                                }
                                            }
                                        });
                                    }
                                });

                                $('#confirmIt').click(function (e) {
                                    e.preventDefault();
                                    e.stopImmediatePropagation();
                                    $('#' + $(this).attr('data-id')).attr('data-id', 'confirmed');
                                    $('#' + $(this).attr('data-id')).click();
                                    Custombox.modal.close();
                                });

                                $('#cancelStatusIt').click(function (e) {
                                    e.preventDefault();
                                    e.stopImmediatePropagation();
                                    $('#' + $(this).attr('data-id')).attr('data-confirm', 'confirm');
                                    Custombox.modal.close();
                                });
                            });
                        </script>
                    <?php
        }
    }

    public function setBulk($params)
    {
        $bulk = empty($params['bulk']) ? [] : $params['bulk'];
        $defaultBulk = [
            'actions' => [
                'active' => 'Active',
                'inactive' => 'Inactive',
                'delete' => 'Delete',
            ],
        ];

        $bulk += $defaultBulk;

        $this->bulk = $bulk;

    }

    public function setSearch($params)
    {
        $search = empty($params['search']) ? [] : $params['search'];
        $model = empty($search['model']) ? array_keys($this->getView()->getRequest()->getParam('paging'))[0] : $search['model'];

        $match = [];
        if (empty($search['match'])) {
            $match = [$model . '.name'];
        } else {
            foreach ($search['match'] as $relatedModel => $m) {
                if (is_array($m)) {
                    foreach ($m as $f) {
                        $match[] = $relatedModel . '.' . $f;
                    }
                } else {
                    $match[] = $model . '.' . $m;
                }
            }
        }

        $finalSearch = [
            'controller' => empty($search['controller']) ? $this->request->getParam('controller') : $search['controller'],
            'action' => empty($search['action']) ? $this->request->getParam('action') : $search['action'],
            'model' => $model,
            'match' => $match,
            'placeholder' => empty($search['placeholder']) ? 'Search...' : $search['placeholder'],
        ];

        $finalSearch = array_merge($search, $finalSearch);
        $this->search = $finalSearch;
    }

    public function createSearchAndBulkActions()
    {
        if (($this->hasPagination || true) && $this->showBulkActions) {


            $classes = "js-select u-select--v3-select u-sibling w-100";
            $dropIconClasses = "d-flex align-items-center g-absolute-centered--y g-right-0 g-color-gray-light-v6 g-color-lightblue-v9--sibling-opened g-mr-15";
            $hasBulk = false;
            ?>
                        <div class="row g-mt-10">
                            <?php if ($this->search['action'] == "notSeenEmails") { ?>
                                    <div class="col-md-6">
                                        <?php
                                        $attributes = [
                                            'class' => "form-control u-select--v3-select u-sibling w-100 u-select--v3 g-pos-rel g-brd-gray-light-v7",
                                            'id' => "filterByWeeks",
                                            'type' => "select",
                                            'style' => "height:42px !important; width:400px !important; margin-top:30px;",
                                            'title' => "Last One week",
                                            'label' => false,
                                            'options' => [
                                                1 => 'Last One Week',
                                                2 => 'Last Two Weeks',
                                                3 => 'Last Three Weeks',
                                                4 => 'Last Four Weeks',
                                            ]
                                        ];
                                        ?>
                                        <?= $this->view->Form->control('filter_by_weeks', $attributes); ?>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3 ">
                                            <div class="card-block g-font-weight-300 g-pa-20">
                                                <div class="media">
                                                    <div class="d-flex g-mr-15">
                                                        <div class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-orange g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                                            <i class="fa fa-users g-absolute-centered"></i>
                                                        </div>
                                                    </div>

                                                    <div class="media-body align-self-center">
                                                        <div class="d-flex align-items-center g-mb-5">
                                                            <span class="g-font-size-24 g-line-height-1 g-color-black"><?= $this->view->Paginator->counter(['format' => __('{{count}}')]) ?></span>
                                                        </div>

                                                        <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">Total
                                                            Records Found</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php } ?>
                            <?php if (!empty($this->search['bulk']) && $this->search['bulk'] == "select") { ?>
                                    <?php $hasBulk = true; ?>
                                    <div class="col-md-4">
                                        <?php
                                        $attributes = [
                                            'class' => "form-control u-select--v3-select u-sibling w-100 u-select--v3 g-pos-rel g-brd-gray-light-v7 select-filter",
                                            'id' => $this->search['bulk_field']['id'],
                                            'style' => "height:42px !important;",
                                            'title' => "Filter by " . $this->search['bulk_field']['label'],
                                            'empty' => "Filter by " . $this->search['bulk_field']['label'],
                                            'options' => empty($this->search['bulk_field']['options']) ? [] : $this->search['bulk_field']['options'],
                                            'value' => empty($this->search['bulk_field']['value']) ? "" : $this->search['bulk_field']['value'],
                                            'label' => false,
                                        ];
                                        ?>
                                        <?= $this->view->Form->control($this->search['bulk_field']['name'], $attributes); ?>
                                    </div>
                            <?php } ?>
                            <?php if (!empty($this->search['export'])) { ?>
                                    <div class="col-md-2">
                                        <button class="btn btn-lg btn-dark export-csv"
                                                data-url="<?= SITE_URL . "admin/" . strtolower($this->search['export_controller']) ?>/exportCsv/?<?= $_SERVER['QUERY_STRING']; ?>">
                                            Export CSV
                                        </button>
                                    </div>
                                    <script>
                                        $(function () {
                                            $('.export-csv').click(function (e) {
                                                e.preventDefault();
                                                e.stopImmediatePropagation();
                                                window.location.href = $(this).attr('data-url');
                                            });
                                        });
                                    </script>
                            <?php } ?>
                
                            <?php if (in_array($this->search['action'], ["scheduledEmails", "referrals"])) { ?>
                                <?php $hasBulk = true; ?>
                                <div class="col-md-6">
                    
                                        <?php
                                        $wrapperClasses = "u-datepicker-right u-datepicker--v3 g-pos-rel w-100 g-cursor-pointer g-brd-around g-brd-gray-light-v7 g-rounded-4";
                                        $iconWrapperClasses = "d-flex align-items-center g-absolute-centered--y g-right-0 g-color-gray-light-v6 g-color-lightblue-v9--sibling-opened g-mr-15";
                                        $attributes = [
                                            'class' => "js-range-datepicker g-bg-transparent g-font-size-12 g-font-size-default--md g-color-gray-dark-v6 g-pr-80 g-pl-15 g-py-9 not-ignore",

                                            'id' => 'selectSendDate',
                                            'type' => "text",
                                            'placeholder' => "Filter by Date",
                                            'data-rp-wrapper' => "selectDateWrapper",
                                            'data-rp-date-format' => "d/m/Y",
                                            'label' => false,
                                        ];
                                        ?>
                                        <div class="form-group mb-0 g-max-width-400">

                                            <div id="selectDateWrapper" class="<?= $wrapperClasses; ?>">
                                                <?= $this->view->Form->control('select_date', $attributes); ?>
                                                <div class="<?= $iconWrapperClasses; ?>">
                                                    <i class="hs-admin-calendar g-font-size-18 g-mr-10"></i>
                                                    <i class="hs-admin-angle-down"></i>
                                                </div>
                                            </div>
                                        </div>
                    
                    
                                </div>
                            <?php } ?>

                            <?php if ($this->search['action'] == "campaigns") { ?>
                                    <?php $hasBulk = true; ?>
                                    <div class="col-md-6">

                                        <style>
                                            .flatpickr-wrapper {
                                                width: 100% !important;
                                            }
                                        </style>
                                        <?php
                                        $wrapperClasses = "u-datepicker-right u-datepicker--v3 g-pos-rel w-100 g-cursor-pointer g-brd-around g-brd-gray-light-v7 g-rounded-4";
                                        $iconWrapperClasses = "d-flex align-items-center g-absolute-centered--y g-right-0 g-color-gray-light-v6 g-color-lightblue-v9--sibling-opened g-mr-15";
                                        $attributes = [
                                            'class' => "js-range-datepicker g-bg-transparent g-font-size-12 g-font-size-default--md g-color-gray-dark-v6 g-pr-80 g-pl-15 g-py-9 not-ignore",
                                            'id' => 'selectSendDate',
                                            'type' => "text",
                                            'placeholder' => "Filter by Send Date",
                                            'data-rp-wrapper' => "selectDateWrapper",
                                            'data-rp-date-format' => "d/m/Y",
                                            'label' => false,
                                        ];
                                        ?>
                                        <div class="form-group mb-0 g-max-width-400">

                                            <div id="selectDateWrapper" class="<?= $wrapperClasses; ?>">
                                                <?= $this->view->Form->control('select_date', $attributes); ?>
                                                <div class="<?= $iconWrapperClasses; ?>">
                                                    <i class="hs-admin-calendar g-font-size-18 g-mr-10"></i>
                                                    <i class="hs-admin-angle-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php } ?>
                            <?php if (!$hasBulk) { ?>
                                    <div class="col-md-6"></div>
                            <?php } ?>
                            <div class="col-md-6">

                                <?php if ($this->showSearch) { ?>
                                        <?php

                                        if (empty($this->search['url'])) {
                                            $url = Router::url(null, true);
                                        } else {
                                            $url = Router::url($this->search['url']);

                                        } ?>
                                        <?= $this->view->Form->create(null, ['id' => 'searchFrom', 'url' => $url]) ?>
                                        <div class="row">
                                            <div class="col-md-2">&nbsp;</div>
                                            <div class="col-md-8 text-right pr-0">
                                                <div class="form-group">
                                                    <div class="g-pos-rel">
                                      <span class="g-pos-abs g-top-0 g-right-0 d-block g-width-50 h-100">
                                        <i class="hs-admin-search g-absolute-centered g-font-size-16 g-color-gray-light-v6"></i>
                                      </span>
                                                        <input id="listingSearchKey"
                                                               name="key"
                                                               class="form-control form-control-md g-brd-gray-light-v7 g-brd-gray-light-v3--focus g-rounded-4 g-px-14 g-py-10"
                                                               type="text" placeholder="<?= $this->search['placeholder']; ?>"
                                                               value="<?= $this->view->get('search_key'); ?>"
                                                        >
                                                    </div>
                                                </div>
                                                <input type="hidden" name="search_in_listings" value="true"/>
                                                <input type="hidden" name="match" value="<?= implode(",", $this->search['match']); ?>"
                                                       id="searchMatches"/>
                                            </div>

                                            <div class="col-md-2 text-right pl-0">
                                                <button type="submit" class="btn btn-primary btn-lg" id="searchInListing">Search
                                                </button>
                                            </div>
                                        </div>
                                        <?= $this->view->Form->end(); ?>
                                <?php } ?>
                            </div>
                            <script>
                                $(function () {
                                    $('#searchFrom').submit(function (e) {
                                        e.preventDefault();
                                        <?php if (empty($_SERVER['QUERY_STRING'])) { ?>
                                            window.location.href = "<?= $url; ?>/?keyword=" + $('#listingSearchKey').val() + "&match=<?= implode(',', $this->search['match']); ?>&";
                                        <?php } else {
                                            $key = 'page';
                                            $url = preg_replace('~(\?|&)' . $key . '=[^&]*~', '$1', $url);
                                            ?>
                                            window.location.href = "<?= explode("&keyword", $url)[0]; ?>&keyword=" + $('#listingSearchKey').val() + "&match=<?= implode(',', $this->search['match']); ?>&";
                                        <?php } ?>

                                    });

                                    $('.select-filter').change(function () {
                                        <?php if (empty($_SERVER['QUERY_STRING'])) { ?>
                                            window.location.href = "<?= $url; ?>/?category=" + $(this).val();
                                        <?php } else { ?>
                                            window.location.href = "<?= explode("category", $url)[0]; ?>category=" + $(this).val();
                                        <?php } ?>
                                    });
                                });
                            </script>

                        </div>
                    <?php
        }
    }

    public function phoneFormat($phone)
    {
        if (preg_match('/(\d{3})(\d{3})(\d{4})$/', $phone, $matches)) {
            return $matches[1] . '-' . $matches[2] . '-' . $matches[3];
        }
        return $phone;
    }

    public function neweditor()
    {
        $url = Router::url(['controller' => $this->controller, 'action' => 'edit', $this->obj->id]);
        $url = 'https://reacteditor.redacted.inc/editor/';
        //if($this->controller != 'EmailTemplates'){
        ?>
                    <a  href="<?= $url . '' . $this->obj->id; ?>" class="btn-u btn-u-blue btn-u-sm rounded"style="float: left; margin-left: 10px;color:white;" target="_blank">
                    <i class='hs-admin-pencil'></i> New Editor
                </a>
                <?php
                // }else{
                ?>
                    <!-- <a  class="btn-u btn-u-blue btn-u-sm rounded edit" data-temp_id = '<?= $this->obj->id ?>' style="float: left; margin-left: 10px;color:white;">
                    <i class='hs-admin-pencil'></i> New Editor
                </a> -->
                <?php
                // }
                ?>
        
            <?php
    }

    public function fieldConditions($field)
    {
        $result = true;
        if (!empty($field['conditions']) && is_array($field['conditions'])) {

            foreach ($field['conditions'] as $condition) {
                switch ($condition['operator']) {
                    case "==": {
                            $result = $result && $this->obj->{$condition['field']} == $condition['value'];
                            break;
                        }
                    case "!=": {
                            $result = $result && $this->obj->{$condition['field']} != $condition['value'];
                            break;
                        }
                    case "<": {
                            $result = $result && $this->obj->{$condition['field']} < $condition['value'];
                            break;
                        }
                    case ">": {
                            $result = $result && $this->obj->{$condition['field']} > $condition['value'];
                            break;
                        }
                    case "<=": {
                            $result = $result && $this->obj->{$condition['field']} <= $condition['value'];
                            break;
                        }
                    case ">=": {
                            $result = $result && $this->obj->{$condition['field']} >= $condition['value'];
                            break;
                        }
                }
            }
        }
        return $result;
    }
}
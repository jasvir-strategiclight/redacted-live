<?php

namespace App\View\Helper;


use Cake\View\Helper;
use Cake\Routing\Router;
use Cake\Utility\Inflector;

class ListingHelper extends Helper {
    
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
    
    public function create($params = null, $actions = ['view', 'edit', 'delete']) {
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
                            <th scope="col" class="actions"
                                style="max-width: <?= count($actions) * 7; ?>%;"><?= __('Actions') ?></th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (count($this->object) <= 0) { ?>
                        <tr>
                            <td colspan="<?= count($this->fields) + (!$this->hasPagination ? 2 : 2); ?>">
                                <h3>No Record found. </h3>
                            </td>
                        </tr>
                    <?php } else { ?>
                        <?php foreach ($this->object as $srNo => $obj): ?>
                            <?php $this->obj = $obj; ?>
                            <tr>
                                <?php
                                foreach ($this->fields as $field) {
                                    $field['type'] = empty($field['type']) ? 'text' : $field['type'];
                                    $this->field = $field;
                                    $this->fieldValue();
                                    
                                    ?>
                                    <td class="<?= $field['type'] == "image" ? "text-center" : "" ?>" style="<?= empty($field['style']) ? "" : $field['style']; ?>">
                                        <?php
                                        if (in_array($this->field['name'], ['created', 'modified']) || $this->field['type'] == "date") {
                                            $this->createDate();
                                        } else {
                                            switch ($field['type']) {
                                                case 'image':
                                                    {
                                                        $this->createImage();
                                                        break;
                                                    }
                                                case 'link':
                                                    {
                                                        $this->createLink();
                                                        break;
                                                    }
                                                case 'status':
                                                    {
                                                        $this->createStatus();
                                                        $this->includeStatusScript = true;
                                                        break;
                                                    }
                                                case 'text':
                                                    {
                                                        $this->createText();
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
                <?php $this->statusScript(); ?>
                <?php if ($this->hasPagination) { ?>
                    <script>
                        $(document).ready(function () {
                            var deleteBtn = null;
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
                            });
                            $('.reset-btn').click(function (e) {
                                e.preventDefault();
                                deleteBtn = $(this).attr('id');
                            });
                            
                            $('#deleteIt').click(function (e) {
                                e.preventDefault();
                                deleteBtn = $('#' + deleteBtn.replace("btn", 'form')).submit();
                            });
                            $.HSCore.components.HSModalWindow.init('[data-modal-target]');
                            
                            $('#applyAction').click(function (e) {
                                e.preventDefault();
                                alert('This  feature is in progress..');
                            });
                        });
                    </script>
                    <div id="deleteConfirmModal"
                         class="text-left g-color-white g-bg-gray-dark-v1 g-overflow-y-auto  g-pa-20"
                         style="display: none; width: auto; height: auto; padding: 10%;">
                        <button type="button" class="close" onclick="Custombox.modal.close();">
                            <i class="hs-icon hs-icon-close"></i>
                        </button>
                        <h4 class="h4 g-mb-20">
                            Delete <?= Inflector::humanize(Inflector::singularize(Inflector::underscore($this->controller))) ?></h4>
                        <div calss="modal-body" id="imageMedia" style="position: relative;">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="h5">Are you sure you want delete this?</h5>
                                </div>
                                <div class="col-md-7"></div>
                                <div class="col-md-5">
                                    <button type="button" class="btn btn-danger pull-left" id="deleteIt">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                    &nbsp;
                                    <button type="button" class="btn btn-primary pull-right"
                                            onclick="Custombox.modal.close();">
                                        <i class="fa fa-close"></i> Cancel
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
                        <h4 class="h4 g-mb-20">
                            Delete <?= Inflector::humanize(Inflector::singularize(Inflector::underscore($this->controller))) ?></h4>
                        <div calss="modal-body" id="imageMedia" style="position: relative;">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="h5">Are you sure you want to reset this template?</h5>
                                </div>
                                <div class="col-md-7"></div>
                                <div class="col-md-5">
                                    <button type="button" class="btn btn-danger pull-left" id="deleteIt">
                                        <i class="fa fa-trash"></i> yes
                                    </button>
                                    &nbsp;
                                    <button type="button" class="btn btn-primary pull-right"
                                            onclick="Custombox.modal.close();">
                                        <i class="fa fa-close"></i> No
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="clear-both"></div>
                    </div>
                <?php } ?>
            </div>
            <?php
            $this->pagination();
        }
    }
    
    public function fieldValue() {
        if (isset($this->field['join'])) {
            foreach ($this->field['join'] as $name) {
                $values[] = $this->getValue($name);
            }
            $this->objectFieldValue = implode(empty($this->field['separator']) ? " " : $this->field['separator'], $values);
        } else {
            $this->objectFieldValue = $this->getValue($this->field['name']);
        }
    }
    
    public function getValue($name) {
        $objectFieldValue = "";
        if (strpos($name, '_id') !== false) {
            $relatedModel = str_replace('_id', '', $name);
            
            if ($this->obj->has($relatedModel)) {
                if (!empty($this->field['related_model_fields'])) {
                    $values = [];
                    foreach ($this->field['related_model_fields'] as $f) {
                        $values[] = $this->getChildValue($this->obj->{$relatedModel}, $f);
                    }
                    $objectFieldValue = implode(empty($this->field['separator']) ? " " : $this->field['separator'], $values);
                    
                } else {
                    $objectFieldValue = $this->obj->{$relatedModel}->name;
                }
            }
        } else {
            $objectFieldValue = $this->obj->{$name};
        }
        
        return $objectFieldValue;
    }
    
    public function getChildValue($obj, $name) {
        $value = "";
        if (strpos($name, '_id') !== false) {
            $relatedModelInner = str_replace('_id', '', $name);
            if ($obj->has($relatedModelInner)) {
                $value = $obj->{$relatedModelInner}->name;
            }
        } else {
            $value = $obj->{$name};
        }
        
        return $value;
    }
    
    public function createImage() {
        $relatedModel = str_replace('_id', '', $this->field['name']);
        $image = SITE_URL . (($this->obj->has($relatedModel)) ? $this->obj->{$relatedModel}->small_thumb : 'files/images/default.jpg');
        ?>
        <img class="img-fluid detail-img-fluid rounded-circle " src="<?= $image; ?>" style="width:100px; height: 100px;"
             alt="Profile Image">
        <?php
    }
    
    public function createLink() {
    
    }
    
    public function createStatus() {
        $activeText = empty($this->field['active_text']) ? "Active" : $this->field['active_text'];
        $inactiveText = empty($this->field['inactive_text']) ? "Inactive" : $this->field['inactive_text'];
        $activeClass = empty($this->field['active_classes']) ? " btn-u btn-u-sm rounded-3x " : $this->field['active_classes'];
        $inactiveClass = empty($this->field['inactive_classes']) ? "btn-u btn-u-sm rounded-3x btn-u-orange " : $this->field['inactive_classes'];
        if ($this->objectFieldValue) {
            $label = $activeText;
            $anchorClasses = $activeClass;
        } else {
            $anchorClasses = $inactiveClass;
            $label = $inactiveText;
        }
        
        $readOnly = '';
        if (isset($this->field['readonly'])) {
            $readOnly = 'disabled';
            $anchorClasses = $anchorClasses . " disabled  btn-u btn-u-default";
        } else {
            $anchorClasses = $anchorClasses . ' active-deactive';
        }
        
        ?>
        <a class="<?= $anchorClasses; ?> " href="javascript:void(0);"
           id="<?= Inflector::camelize($this->field['name']); ?>_<?= $this->obj->id ?>"
           data-model="<?= $this->field['model']; ?>"
           data-field="<?= $this->field['name'] ?>"
           data-active-text="<?= $activeText ?>"
           data-inactive-text="<?= $inactiveText ?>"
           data-active-classes="<?= $activeClass ?>"
           data-inactive-classes="<?= $inactiveClass ?>"
            <?= $readOnly; ?>
        >
            <?= $label ?>
        </a>
    
    <?php }
    
    
    public function createDate() {
        echo date(SHORT_DATE, strtotime($this->objectFieldValue));
    }
    
    public function createText() {
        echo $this->objectFieldValue;
    }
    
    public function createActions($actions) {
        
        ?>
        <td class="actions">
            <?php
            foreach ($actions as $action) {
                if (is_array($action)) {
                    $this->customAction($action);
                } else {
                    $this->{$action}();
                }
            }
            ?>
        </td>
        <?php
    }
    
    public function customAction($action = []) {
        if (isset($action['id']) && $action['id']) {
            $action['url'][] = $this->obj->id;
        }
        if (isset($action['url']) && $action['url'] == false) {
            $url = "javascript:void(0);";
        } else {
            $url = Router::url($action['url']);
        }
    
        $target = empty($action['target']) ? "" : 'target="'.$action['target'].'"';
        
        ?>
        <a href="<?= $url; ?>" class=" <?= empty($action['class']) ? "btn btn-dark btn-sm" : $action['class']; ?>"
           style="float: left; margin-left: 10px;" data-id="<?= $this->obj->id; ?>" <?= $target; ?>>
            <i class='<?= empty($action['icon']) ? "fa fa-circle-o" : $action['icon']; ?>'></i> <?= $action['label']; ?>
        </a>
        <?php
    }
    
    public function view() {
        $url = Router::url(['controller' => $this->controller, 'action' => 'view', $this->obj->id]);
        ?>
        <a href="<?= $url; ?>" class="btn btn-success btn-sm" style="float: left; margin-left: 10px;">
            <i class='hs-admin-eye'></i> Detail
        </a>
        <?php
    }
    
    public function edit() {
        $url = Router::url(['controller' => $this->controller, 'action' => 'edit', $this->obj->id]);
        ?>
        <a href="<?= $url; ?>" class="btn btn-primary btn-sm" style="float: left; margin-left: 10px;">
            <i class='hs-admin-pencil'></i> Edit
        </a>
        <?php
    }
    
    public function delete() {
        $url = ['controller' => $this->controller, 'action' => 'delete', $this->obj->id];
        //$url = 'javascript:void(0);';
        ?>
        <?= $this->view->Form->create(null, ['url' => $url, 'id' => 'delete_' . $this->obj->id . '_form']); ?>
        <button data-modal-target="#deleteConfirmModal"
                data-modal-effect="slide" class="btn btn-danger btn-sm delete-btn"
                style="float: left; margin-left: 10px;" id="delete_<?= $this->obj->id; ?>_btn"><i
                class="hs-admin-close"></i> Delete
        </button>
        <?= $this->view->Form->end(); ?>
        <?php
    }
    public function reset() {
        $url = ['controller' => $this->controller, 'action' => 'reset', $this->obj->id];
        //$url = 'javascript:void(0);';
        ?>
        <?= $this->view->Form->create(null, ['url' => $url, 'id' => 'reset_' . $this->obj->id . '_form']); ?>
        <button data-modal-target="#resetConfirmModal"
                data-modal-effect="slide" class="btn btn-danger btn-sm reset-btn"
                style="float: left; margin-left: 10px;" id="reset_<?= $this->obj->id; ?>_btn"><i
                class="hs-admin-close"></i> Reset
        </button>
        <?= $this->view->Form->end(); ?>
        <?php
    }
    
    
    public function pagination() {
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
    
    public function statusScript() {
        
        if ($this->includeStatusScript) {
            ?>
            <script>
                $(document).ready(function () {
                    
                    $('.active-deactive').click(function () {
                        var _this = $(this);
                        var model = _this.attr('data-model');
                        var field = _this.attr('data-field');
                        var activeClasses = _this.attr('data-active-classes');
                        var inactiveClasses = _this.attr('data-inactive-classes');
                        
                        
                        var id = _this.attr('id').split('_')[1];
                        
                        $.ajax({
                            url: SITE_URL + "users/changeStatus/",
                            type: "POST",
                            data: {model: model, field: field, id: id},
                            dataType: "json",
                            beforeSend: function () {
                                _this.html(_this.html() + ' <i class="fa fa-spinner fa-spin"></i>');
                            },
                            success: function (response) {
                                
                                if (response.code == 200) {
                                    
                                    _this.removeClass(activeClasses);
                                    _this.removeClass(inactiveClasses);
                                    
                                    if (response.data.new_status) {
                                        _this.addClass(activeClasses);
                                        _this.html(_this.attr('data-active-text'));
                                    } else {
                                        _this.addClass(inactiveClasses);
                                        _this.html(_this.attr('data-inactive-text'));
                                    }
                                } else {
                                    $().showFlashMessage("error", response.message);
                                }
                            }
                        });
                    });
                    
                });
            </script>
            <?php
        }
    }
    
    public function setBulk($params) {
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
    
    public function setSearch($params) {
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
        $this->search = $finalSearch;
    }
    
    
    public function createSearchAndBulkActions() {
        //if ($this->hasPagination) {
        $classes = "js-select u-select--v3-select u-sibling w-100";
        $dropIconClasses = "d-flex align-items-center g-absolute-centered--y g-right-0 g-color-gray-light-v6 g-color-lightblue-v9--sibling-opened g-mr-15";
        ?>
        <div class="row g-mt-10">
            <div class="col-md-6">&nbsp;</div>
            <div class="col-md-6">
                <?= $this->view->Form->create(null, ['id' => 'searchFrom', 'url' => ['controller' => $this->search['controller'], 'action' => $this->search['action']]]) ?>
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
                        <input type="hidden" name="match"
                               value="<?= is_array($this->search['match']) ? implode(",", $this->search['match']) : ""; ?>"
                               id="searchMatches"/>
                    </div>
                    
                    <div class="col-md-2 text-right pl-0">
                        <button type="submit" class="btn btn-primary btn-lg" id="searchInListing">Search</button>
                    </div>
                </div>
                <?= $this->view->Form->end(); ?>
            </div>
        </div>
        <?php
        //}
    }
}

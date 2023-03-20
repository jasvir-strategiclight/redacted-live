<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Utility\Inflector;

class CustomFormHelper extends Helper {
    
    public $view;
    public $params;
    public $formId;
    public $field;
    public $fieldNo;
    public $fieldLabel;
    public $fieldValue;
    public $fieldId;
    public $obj;
    public $scripts = [];
    public $hasSelectBox = false;
    
    public $validate;
    public $setValidations = true;
    public $validations;
    public $validationLabel;
    public $validationRules;
    public $validationMessages;
    public $submitBtn = [];
    
    
    public function create($params = []) {
        $this->validate = [];
        $this->validations = [];
        $this->view = $this->getView();
        $this->obj = (isset($params['object']) && $params['object'] == false) ? null : $this->view->get($this->view->getVars()[0]);
        $this->params = $params;
        if (!empty($this->params['form'])) {
            ?>
            <?php $this->formId = $this->view->getRequest()->getParam('action') . $this->view->getRequest()->getParam('controller'); ?>
            <?php if (empty($this->params['form']['options'])) { ?>
                <?= $this->view->Form->create($this->obj, ['id' => $this->formId]) ?>
            <?php } else {
                if (empty($this->params['form']['options']['id'])) {
                    $this->params['form']['options']['id'] = $this->formId;
                } else {
                    $this->formId = $this->params['form']['options']['id'];
                }
                ?>
                <?= $this->view->Form->create($this->obj, $this->params['form']['options']) ?>
            <?php } ?>
            <?php
            if (empty($this->params['validate'])) {
                $this->validations['ignore'] = ":hidden:not(.not-ignore)";
            } else {
                $this->validate = $this->params['validate'];
                $this->setValidations = empty($validate['validations']) ? true : $validate['validations'];
                if ($this->setValidations) {
                    $this->validations['ignore'] = empty($validate['ignore']) ? ":hidden:not(.ignore)" : $validate['ignore'];
                    
                }
            }
            ?>
            <div class="card g-brd-gray-light-v7 g-rounded-3 g-mb-30">
                <header
                    class="card-header g-brd-bottom-none g-px-15 g-px-30--sm g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex align-self-center text-uppercase g-font-size-12 g-font-size-default--md g-color-primary font-weight-bold g-mr-10 mb-0">
                            <?= $this->params['form']['heading']; ?>
                        </h3>
                    </div>
                </header>
                
                <div class="card-block g-pa-15 g-pa-30--sm">
                    <div class="row ">
                        <?php
                        foreach ($this->params['fields'] as $index => $field) {
                            
                            $this->validationRules = [];
                            $this->validationMessages = [];
                            $this->fieldNo = $index;
                            $this->field = $field;
                            $this->getFieldLabel();
                            $this->getFieldId();
                            $this->getFieldValue();
                            if (isset($this->field['readonly'])) {
                                $this->setReadOnly();
                            }
                            
                            if ($field['name'] == "div") {
                                ?>
                                <div class="col-md-<?= $this->columns(); ?>">
                                    <div id="<?= $this->fieldId; ?>"
                                         class="<?= empty($field['class']) ? 'row' : $field['class']; ?>"></div>
                                </div>
                                <?php
                            } else if ($field['name'] == "empty") {
                                ?>
                                <div class="col-md-<?= $this->columns(); ?> <?= empty($field['class']) ? "": $field['class']; ?>" id="<?= $this->fieldId; ?>"><?= empty($field['text']) ? "": $field['text']; ?></div>
                                <?php
                            } else {
                                
                                ?>
                                <?php if (isset($this->field['type']) && $this->field['type'] == "hidden") { ?>
                                    <?php $this->createField(); ?>
                                
                                <?php } else { ?>
                                    <div class="col-md-<?= $this->columns(); ?>">
                                        <?php $this->createField(); ?>
                                    </div>
                                <?php } ?>
                                <?php if ($this->setValidations) {
                                    $this->validateField();
                                } ?>
                            <?php } ?>
                        <?php } ?>
                        <div class="col-md-12 text-right">
                            <?php
                            
                            $submit = isset($this->params['form']['submit']) ? $this->params['form']['submit'] : true;
                            if ($submit) {
                                $this->submit();
                            }
                            $cancel = isset($this->params['form']['cancel']) ? $this->params['form']['cancel'] : true;
                            if ($cancel) {
                                $this->cancel();
                            }
                            ?>
                        </div>
                    </div>
                    <?= $this->view->Form->end() ?>
                </div>
            </div>
            <?php
            
            
            if (!empty($this->validate['submitHandler'])) {
                $this->validations['submitHandler'] = 'function (form) { ' . $this->validate['submitHandler'] . ' }';
            }
            
            ?>
            <?php
            
            if ($this->setValidations) {
                $this->createValidations();
            }
            $this->script();
        }
    }
    
    public
    function createField() {
        $this->field['type'] = empty($this->field['type']) ? "text" : $this->field['type'];
        switch ($this->field['type']) {
            case 'image' :
                {
                    $this->image();
                    break;
                }
            case 'suggestions' :
                {
                    $this->scripts[] = $this->suggestionsScript();
                    $this->suggestions();
                    break;
                }
            case 'textarea' :
                {
                    $this->textArea();
                    break;
                }
            case 'select' :
                {
                    if (!empty($this->field['depend'])) {
                        $this->scripts[] = $this->selectDependOn();
                    }
                    $this->select();
                    break;
                }
            case 'advance select' :
                {
                    if (!$this->hasSelectBox) {
                        $this->scripts[] = "$('.js-select').selectpicker();";
                        $this->hasSelectBox = true;
                    }
                    if (!empty($this->field['depend'])) {
                        $this->scripts[] = $this->advanceSelectDependOn();
                    }
                    $this->advanceSelect();
                    break;
                }
            case 'checkbox' :
            case 'check' :
                {
                    $this->check();
                    break;
                }
            case 'radio' :
                {
                    $this->input();
                    break;
                }
            case 'toggle' :
                {
                    $this->toggle();
                    break;
                }
            case 'hidden' :
                {
                    $this->hidden();
                    break;
                }
            case 'number' :
                {
                    $this->number();
                    break;
                }
            default :
                {
                    $this->input();
                    break;
                }
        }
    }
    
    
    public
    function image() {
        $anchorClasses = "u-badge-v2--lg u-badge--bottom-right g-width-32 g-height-32 g-bg-lightblue-v3 g-bg-primary--hover g-mb-40 g-mr-30 load-media";
        $iconClasses = "fa fa-plus g-absolute-centered g-font-size-16 g-color-white";
        $name = (strpos($this->field['name'], '_id') !== false) ? str_replace('_id', '', $this->field['name']) : 'image';
        $image = SITE_URL . (($this->obj->has($name)) ? $this->obj->{$name}->small_thumb : 'files/images/default.png');
        ?>
        <section class="text-center g-mb-10 g-mb-10--md text-left">
            
            <div class="d-inline-block g-pos-rel g-mb-20">
                <a
                    class="<?= $anchorClasses ?>"
                    href="#mediaModal"
                    data-modal-target="#mediaModal"
                    data-modal-effect="blur"
                    data-model="<?= empty($this->field['model']) ? "Users" : $this->field['model']; ?>"
                    data-category="<?= empty($this->field['category']) ? "Profile" : $this->field['category']; ?>"
                    data-user_id="<?= empty($this->field['user_id']) ? 0 : $this->field['user_id']; ?>"
                    data-image_id="<?= empty($this->obj->image_id) ? 0 : $this->obj->image_id; ?>"
                >
                    <?= $this->view->Form->control($this->field['name'], [
                        'type' => 'hidden',
                        'id' => $this->fieldId,
                        'class' => 'loaded-image-id'
                    ]);
                    ?>
                    <i class="<?= $iconClasses; ?>"></i>
                </a>
                <img class="img-fluid rounded-circle loaded-image" src="<?= $image; ?>"
                     alt="Image description" style="width:200px; height: 200px;">
            </div>
        </section>
        <?php
    }
    
    public
    function input() {
        $inputClasses = "form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15 not-ignore";
        $iconClasses = "g-absolute-centered--y g-right-0 d-block g-width-50 g-height-22 g-brd-left g-brd-gray-light-v7";
        ?>
        <div class="form-group g-mb-30">
            <label class="g-mb-10" for="<?= $this->fieldId; ?>"><?= $this->fieldLabel ?></label>
            
            <div class="g-pos-rel">
                <?php if (!empty($this->field['icon'])) { ?>
                    <span
                        class="<?= $iconClasses; ?>">
	                  	<i class="<?= $this->field['icon']; ?> g-absolute-centered g-font-size-16 g-color-gray-light-v6"></i>
	                	</span>
                <?php } ?>
                <?= $this->view->Form->control($this->field['name'], [
                    'type' => empty($this->field['type']) ? 'text' : $this->field['type'],
                    'label' => false,
                    'class' => $inputClasses,
                    'id' => $this->fieldId,
                    'placeholder' => $this->fieldLabel,
                    'autocomplete' => 'off',
                    'value' => $this->fieldValue
                ]);
                ?>
            </div>
        </div>
        <?php
    }
    
    function number() {
        $inputClasses = "form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15 not-ignore";
        $iconClasses = "g-absolute-centered--y g-right-0 d-block g-width-50 g-height-22 g-brd-left g-brd-gray-light-v7";
        ?>
        <div class="form-group g-mb-30">
            <label class="g-mb-10" for="<?= $this->fieldId; ?>"><?= $this->fieldLabel ?></label>
            
            <div class="g-pos-rel">
                <?php if (!empty($this->field['icon'])) { ?>
                    <span
                        class="<?= $iconClasses; ?>">
	                  	<i class="<?= $this->field['icon']; ?> g-absolute-centered g-font-size-16 g-color-gray-light-v6"></i>
	                	</span>
                <?php } ?>
                <?= $this->view->Form->control($this->field['name'], [
                    'type' => empty($this->field['type']) ? 'number' : $this->field['type'],
                    'label' => false,
                    'class' => $inputClasses,
                    'id' => $this->fieldId,
                    'placeholder' => $this->fieldLabel,
                    'autocomplete' => 'off',
                    'min' => 0,
                    'value' => $this->fieldValue
                ]);
                ?>
            </div>
        </div>
        <?php
    }
    
    public
    function hidden() {
        echo $this->view->Form->control($this->field['name'], [
            'type' => 'hidden',
            'id' => $this->fieldId,
            'value' => empty($this->field['value']) ? "" : $this->field['value']
        ]);
    }
    
    public
    function textArea() {
        $classes = "form-control form-control-md g-brd-gray-light-v7 g-brd-gray-light-v3--focus g-rounded-4";
        ?>
        <div class="form-group g-mb-30">
            <label class="g-mb-10" for="<?php $this->fieldId ?>"><?= $this->fieldLabel ?></label>
            <?= $this->view->Form->control($this->field['name'], [
                'type' => 'textarea',
                'label' => false,
                'class' => $classes,
                'id' => $this->fieldId,
                'placeholder' => $this->fieldLabel,
            ]);
            ?>
        </div>
        <?php
    }
    
    public
    function select() {
        $classes = "form-control u-select--v3-select u-sibling w-100 u-select--v3 g-pos-rel g-brd-gray-light-v4 g-rounded-4 mb-2";
        ?>
        <div class="g-mb-30">
            <label class="g-mb-10"><?= $this->fieldLabel; ?></label>
            
            <select name="<?= $this->field['name']; ?>" class="<?= $classes; ?>" title="<?= $this->fieldLabel; ?>"
                    id="<?= $this->fieldId; ?>" style="height:52px !important;">
                <option value="">Select <?= $this->fieldLabel; ?></option>
                <?php foreach ($this->field['options'] as $value => $label) { ?>
                    <option value="<?= $value; ?>" <?= ($this->fieldValue == $value) ? 'selected' : "" ?>><?= $label; ?></option>
                <?php } ?>
            </select>
            <label for="<?= $this->fieldId; ?>" class="error"></label>
        </div>
        <?php
    }
    
    public
    function advanceSelect() {
        $classes = "js-select u-select--v3-select u-sibling w-100";
        $dropIconClasses = "d-flex align-items-center g-absolute-centered--y g-right-0 g-color-gray-light-v6 g-color-lightblue-v9--sibling-opened g-mr-15";
        ?>
        <div class="g-mb-30">
            <label class="g-mb-10"><?= $this->fieldLabel; ?></label>
            
            <div class="form-group u-select--v3 g-pos-rel g-brd-gray-light-v7 g-rounded-4 mb-0">
                <select name="<?= $this->field['name']; ?>" class="<?= $classes; ?>" title="<?= $this->fieldLabel; ?>"
                        style="display: none;" data-live-search="true" id="<?= $this->fieldId; ?>">
                    <?php foreach ($this->field['options'] as $value => $label) { ?>
                        <option value="<?= $value; ?>" <?= ($this->fieldValue == $value) ? 'selected' : "" ?>
                                data-content='<span class="d-flex align-items-center w-100"><span><?= $label; ?></span></span>'><?= $label; ?></option>
                    <?php } ?>
                </select>
                <div
                    class="<?= $dropIconClasses; ?>">
                    <i class="hs-admin-angle-down"></i>
                </div>
            </div>
            <label for="<?= $this->fieldId; ?>" class="error"></label>
        </div>
        <?php
    }
    
    public
    function check() {
        $inputClasses = "g-hidden-xs-up g-pos-abs g-top-0 g-left-0 not-ignore";
        $labelClasses = "u-check-icon-checkbox-v4 g-absolute-centered--y g-left-0";
        ?>
        <div class="row g-mb-30 ">
            <label class="g-mb-10" style="width: 100%;"><?= $this->fieldLabel; ?></label><br/>
            <?php foreach ($this->field['options'] as $value => $label) { ?>
                <div class="form-group g-mb-10 col-md-<?= $this->field['check_columns']; ?>">
                    <label class="u-check g-pl-25">
                        <input class="<?= $inputClasses; ?>" type="checkbox" name="<?= $this->field['name'] ?>[]"
                               id="<?= $this->fieldId; ?>"
                               value="<?= $value; ?>" <?= ($this->fieldValue == $value) ? 'checked' : "" ?>>
                        <div class="<?= $labelClasses; ?>">
                            <i class="fa" data-check-icon=""></i>
                        </div>
                        <?= $label; ?>
                    </label>
                </div>
                <?= empty($this->field['horizontal']) ? "" : ""; ?>
            <?php } ?>
        </div>
        <?php
    }
    
    public
    function radio() {
        $inputClasses = "g-hidden-xs-up g-pos-abs g-top-0 g-left-0 not-ignore";
        $labelClasses = "u-check-icon-font g-absolute-centered--y g-left-0";
        
        ?>
        <?php foreach ($this->field['options'] as $value => $label) { ?>
            <div class="form-group g-mb-10">
                <label class="u-check g-pl-25">
                    <input class="<?= $inputClasses; ?>" type="radio" name="<?= $this->field['name'] ?>"
                           id="<?= $this->fieldId; ?>"
                           value="<?= $value; ?>" <?= ($this->fieldValue == $value) ? 'checked' : "" ?>>
                    <div class="<?= $labelClasses; ?>">
                        <i class="fa" data-check-icon="" data-uncheck-icon=""></i>
                    </div>
                    <?= $label; ?>
                </label>
            </div>
            <?= empty($this->field['horizontal']) ? "" : ""; ?>
            <label for="<?= $this->fieldId; ?>" class="error"></label>
        <?php } ?>
        <?php
    }
    
    public
    function toggle() {
        $inputClasses = "g-hidden-xs-up g-pos-abs g-top-0 g-right-0 not-ignore";
        $labelClasses = "form-check-inline u-check mt-0 mb-0";
        ?>
        <div class="g-mb-30">
            <label class="<?= $labelClasses; ?>" style="top: -3px !important;">
                <input class="<?= $inputClasses; ?>" name="<?= $this->field['name'] ?>"
                       id="<?= $this->fieldId; ?>"
                       value="<?= empty($this->fieldValue) ? true : $this->fieldValue; ?>" <?= ($this->fieldValue) ? "checked" : ""; ?>
                       type="checkbox">
                <div class="u-check-icon-radio-v7">
                    <i class="fa" data-check-icon="&#xf00c"></i>
                </div>
            </label>
            <label class="mt-0 mb-0 mr-3"><?= $this->fieldLabel; ?> </label><br/>
            <label for="<?= $this->field['name']; ?>" class="error"></label>
            <label for="<?= $this->fieldId; ?>" class="error"></label>
        </div>
        
        
        <?php
    }
    
    public
    function datePicker() {
        $wrapperClasses = "u-datepicker-right u-datepicker--v3 g-pos-rel w-100 g-cursor-pointer g-brd-around g-brd-gray-light-v7 g-rounded-4";
        $iconWrapperClasses = "d-flex align-items-center g-absolute-centered--y g-right-0 g-color-gray-light-v6 g-color-lightblue-v9--sibling-opened g-mr-15";
        $inputClasses = "js-range-datepicker g-bg-transparent g-font-size-12 g-font-size-default--md g-color-gray-dark-v6 g-pr-80 g-pl-15 g-py-9 not-ignore";
        ?>
        <div class="form-group mb-0 g-max-width-400">
            <label class="g-mb-10"><?= $this->fieldLabel; ?></label>
            <div id="<?= $this->fieldId; ?>Wrapper" class="<?= $wrapperClasses; ?>">
                <input class="<?= $inputClasses; ?>"
                       name="<?= $this->field['name']; ?>"
                       id="<?= $this->fieldId; ?>"
                       type="text"
                       placeholder="Select Date"
                       data-rp-wrapper="<?= $this->fieldId; ?>Wrapper"
                       data-rp-date-format="d/m/Y"
                >
                <div class="<?= $iconWrapperClasses; ?>">
                    <i class="hs-admin-calendar g-font-size-18 g-mr-10"></i>
                    <i class="hs-admin-angle-down"></i>
                </div>
            </div>
        </div>
        <?php
    }
    
    public
    function suggestions() {
        $inputClasses = "form-control form-control-md g-brd-gray-light-v7 g-brd-gray-light-v3--focus rounded-0 g-px-14 g-py-10 not-ignore";
        $iconClasses = "g-absolute-centered--y g-right-0 d-block g-width-50 g-height-22 g-brd-left g-brd-gray-light-v7";
        ?>
        <div class="form-group g-mb-30">
            <label class="g-mb-10" for="<?= $this->fieldId; ?>"><?= $this->fieldLabel ?></label>
            
            <div class="g-pos-rel">
                <?php if (!empty($this->field['icon'])) { ?>
                    <span
                        class="<?= $iconClasses; ?>">
	                  	<i class="<?= $this->field['icon']; ?> g-absolute-centered g-font-size-16 g-color-gray-light-v6"></i>
	                	</span>
                <?php } ?>
                <?= $this->view->Form->control(str_replace('_id', '', $this->field['name']), [
                    'type' => 'text',
                    'label' => false,
                    'class' => $inputClasses,
                    'id' => $this->fieldId,
                    'placeholder' => $this->fieldLabel,
                    'value' => empty($this->field['value']) ? "" : $this->field['value'],
                ]);
                ?>
                <?= $this->view->Form->control($this->field['name'], [
                    'type' => 'hidden',
                    'id' => $this->fieldId . "ID",
                    'class' => "not-ignore"
                ]);
                ?>
            </div>
        </div>
        <?php
    }
    
    public
    function submit() {
        $this->submitBtn = empty($this->params['form']['submit']) ? [] : $this->params['form']['submit'];
        $this->submitBtn['label'] = empty($this->submitBtn['label']) ? "Save" : $this->submitBtn['label'];
        $this->submitBtn['classes'] = empty($submit['classes']) ? "btn-lg btn btn-success rounded" : $this->submitBtn['classes'];
        $this->submitBtn['icon'] = empty($this->submitBtn['icon']) ? "fa fa-save" : $this->submitBtn['icon'];
        $this->submitBtn['id'] = empty($this->submitBtn['id']) ? $this->formId . "Btn" : $this->submitBtn['id'];
        ?>
        <button type="submit" class="<?= $this->submitBtn['classes']; ?>" id="<?= $this->submitBtn['id']; ?>">
            <i class="<?= $this->submitBtn['icon']; ?>"></i> <?= $this->submitBtn['label']; ?>
        </button>
        <?php
    }
    
    public
    function cancel() {
        $label = empty($this->params['cancel']['label']) ? "Cancel" : $this->params['submit']['label'];
        $classes = empty($this->params['cancel']['classes']) ? "btn-lg btn btn-danger rounded  " : $this->params['submit']['classes'];
        $url = empty($this->params['cancel']['url']) ? $this->view->getRequest()->referer() : empty($this->params['cancel']['url']);
        $icon = empty($this->params['cancel']['icon']) ? "fa fa-close" : $this->params['cancel']['icon'];
        ?>
        <a href="<?= $url; ?>" class="<?= $classes; ?>">
            <i class="<?= $icon; ?>"></i> <?= $label; ?>
        </a>
        <?php
    }
    
    
    public
    function getFieldValue() {
        if (empty($this->field['value'])) {
            $this->fieldValue = isset($this->obj->{$this->field['name']}) ? $this->obj->{$this->field['name']} : false;
        } else {
            //die('here');
            $this->fieldValue = $this->field['value'];
        }
        
        if ($this->field['name'] == "phone") {
            $this->fieldValue = $this->phoneFormat($this->fieldValue);
        }
    }
    
    public
    function getFieldLabel() {
        if (empty($this->field['label'])) {
            $name = (strpos($this->field['name'], '_id') !== false) ? str_replace('_id', '', $this->field['name']) : $this->field['name'];
            $this->fieldLabel = Inflector::humanize($name);
        } else {
            $this->fieldLabel = $this->field['label'];
        }
    }
    
    public
    function getFieldId() {
        $this->fieldId = (empty($this->field['id'])) ? Inflector::camelize($this->field['name']) : $this->field['id'];
    }
    
    public
    function columns() {
        if (!empty($this->field['columns'])) {
            $columns = $this->field['columns'];
        } else if (!empty($this->params['columns'])) {
            $columns = $this->params['columns'];
        } else {
            $columns = 6;
        }
        return $columns;
    }
    
    public
    function script() {
        ?>
        <script>
            $(function () {
                <?= implode("\n", $this->scripts); ?>
            });
        </script>
        <?php
    }
    
    public
    function selectDependOn() {
        $depend = $this->field['depend'];
        return "$('#" . $depend['id'] . "').change(function () {
            $.ajax({
                url: SITE_URL + 'users/getOptions',
                type: \"POST\",
                data: {query: $(this).val(), find: '" . $depend['model'] . "', match: '" . $depend['match'] . "' },
                dataType: \"json\",
                success: function (response) {
                    if (response.suggestions.length > 0) {
                        var options = [];
                        options.push('<option value=\"\">" . $this->fieldLabel . "</option>');
                        $.each(response.suggestions, function (index, data) {
                            options.push('<option value=\"' + data.value + '\">' + data.label + '</option>');
                        });
                         $('#" . $this->fieldId . "').html(options.join(''));
                    }
                    
                }
            });
        });";
    }
    
    public
    function advanceSelectDependOn() {
        $depend = $this->field['depend'];
        return "$('#" . $depend['id'] . "').change(function () {
            $.ajax({
                url: SITE_URL + 'users/getOptions',
                type: \"POST\",
                data: {query: $(this).val(), find: '" . $depend['model'] . "', match: '" . $depend['match'] . "' },
                dataType: \"json\",
                success: function (response) {
                    if (response.suggestions.length > 0) {
                        var options = [];
                        options.push('<option class=\"bs-title-option\" value=\"\">" . $this->fieldLabel . "</option>');
                        $.each(response.suggestions, function (index, data) {
                            options.push('<option value=\"' + data.value + '\" data-content=\'<span class=\"d-flex align-items-center w-100\">' + data.label + '</span>\'>' + data.label + '</option>');
                        });
                        
                        $('#" . $this->fieldId . "').html(options.join(''));
    
                        $('#" . $this->fieldId . "').selectpicker('refresh');
                    }
                    
                }
            });
        });";
    }
    
    public
    function suggestionsScript() {
        if (empty($this->field['suggestions'])) {
            return "";
        } else {
            
            $params = [
                'find' => $this->field['suggestions']['model'],
                'select' => empty($this->field['suggestions']['select']) ? "name" : $this->field['suggestions']['select'],
                'match' => empty($this->field['suggestions']['match']) ? "name" : implode(",", $this->field['suggestions']['match']),
            ];
            
            if (!empty($this->field['suggestions']['conditions'])) {
                $params['conditions'] = $this->field['suggestions']['conditions'];
            }
            
            return "$('#" . $this->fieldId . "').autocomplete({
                serviceUrl: SITE_URL + 'users/getSuggestions',
                params: " . json_encode($params) . ",
                lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
                    var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
                    return re.test(suggestion.name);
                },
                onSelect: function (suggestion) {
                    $('#" . $this->fieldId . "ID').val(suggestion.id);
                },
                onInvalidateSelection: function () {
                    console.log('You selected: none');
                    $('#" . $this->fieldId . "ID').val('');
                } });";
        }
    }
    
    public
    function createValidations() {
        $validations = preg_replace('/"([a-zA-Z]+[a-zA-Z0-9_]*)":/', '$1:', json_encode($this->validations));
        $validations = str_replace("\"function (form)", "function (form)", $validations);
        $validations = str_replace("}\"", "}", $validations);
        
        $this->scripts[] = '$("#' . $this->formId . '").validate(' . $validations . ');';
    }
    
    public
    function validateField() {
        $label = strtolower($this->fieldLabel);
        if (empty($this->field['validate'])) {
            $this->validationRules['required'] = true;
        } else {
            $this->validationRules = empty($this->field['validate']['rules']) ? ['required' => true] : $this->field['validate']['rules'];
            $this->validationMessages = empty($this->field['validate']['messages']) ? [] : $this->field['validate']['messages'];
        }
        
        foreach ($this->validationRules as $type => $value) {
            
            $this->validations['rules'][$this->field['name']][$type] = $value;
            if (empty($this->validationMessages[$type])) {
                switch ($type) {
                    case 'required':
                        {
                            $text = in_array($this->field['type'], ['select', 'check', 'radio', 'toggle']) ? "select" : "enter";
                            $this->validations['messages'][$this->field['name']][$type] = "Please $text $label.";
                            break;
                        }
                    case 'email':
                        {
                            $this->validations['messages'][$this->field['name']][$type] = "Please enter valid email.";
                            break;
                        }
                    case 'remote':
                        {
                            $this->validations['messages'][$this->field['name']][$type] = "Email already exists.";
                            break;
                        }
                    case 'min':
                        {
                            $this->validations['messages'][$this->field['name']][$type] = ucfirst($label) . " must be greater than $value.";
                            break;
                        }
                    case 'max':
                        {
                            $this->validations['messages'][$this->field['name']][$type] = ucfirst($label) . " must be less than $value.";
                            break;
                        }
                    case 'minlength':
                        {
                            $this->validations['messages'][$this->field['name']][$type] = ucfirst($label) . " must be greater than $value characters.";
                            break;
                        }
                    case 'maxlength':
                        {
                            $this->validations['messages'][$this->field['name']][$type] = ucfirst($label) . " must be less than $value characters.";
                            break;
                        }
                    case 'url':
                        {
                            $this->validations['messages'][$this->field['name']][$type] = "Please enter valid url.";
                            break;
                        }
                    case 'pwcheck':
                    case 'password':
                        {
                            $this->validations['messages'][$this->field['name']][$type] = ucfirst($label) . " must contain atleast one capital character, one numeric and one special character.";
                            break;
                        }
                    case 'phone':
                    case 'phoneUS':
                        {
                            $this->validations['messages'][$this->field['name']][$type] = "Please specify a valid phone number.";
                            break;
                        }
                    case 'equalTo':
                        {
                            $this->validations['messages'][$this->field['name']][$type] = "Password does not match.";
                            break;
                        }
                    
                }
            } else {
                $this->validations['messages'][$this->field['name']][$type] = $this->validationMessages[$type];
            }
        }
        
        
    }
    
    public function setReadOnly() {
        $this->scripts[] = 'setTimeout(function(){$("#' . $this->fieldId . '").attr("readonly", ' . $this->field['readonly'] . ')}, 1000);';
    }
    
    
    public function phoneFormat($phone) {
        if (preg_match('/(\d{3})(\d{3})(\d{4})$/', $phone, $matches)) {
            return $matches[1] . '-' . $matches[2] . '-' . $matches[3];
        }
        return $phone;
    }
    
}

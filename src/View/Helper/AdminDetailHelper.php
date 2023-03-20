<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Utility\Inflector;

class AdminDetailHelper extends Helper {
    
    public $view;
    public $field;
    public $fieldLabel;
    public $fieldValue;
    public $obj;
    
    public $infoCardHeaderClasses = "card-header g-bg-primary g-brd-bottom-none g-px-15 g-px-30--sm g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm";
    public $infoCardHeadingClasses = "d-flex align-self-center text-uppercase g-font-size-12 g-font-size-default--md g-color-white g-mr-10 mb-0";
    
    public function info($info = [], $heading = "Detail") {
        $this->view = $this->getView();
        $this->obj = (isset($info['object']) && $info['object'] == false) ? null : $this->view->get($this->view->getVars()[0]);
        
        if (!empty($info)) {
            ?>
            <div class="card g-brd-primary g-rounded-3 g-mb-30">
                <header class="<?= $this->infoCardHeaderClasses; ?>">
                    <div class="media">
                        <h3 class="<?= $this->infoCardHeadingClasses; ?>">
                            <i class="fa fa-eye pt-2 pr-2"></i> <?= $heading; ?>
                        </h3>
                    </div>
                </header>
                
                <div class="card-block g-pa-15 g-pa-30--sm">
                    <table class="vertical-table">
                        <?php
                        foreach ($info['fields'] as $field) {
                            $this->field = $field;
                            ?>
                            <tr>
                                <th scope="row"><?= __($this->fieldLabel()) ?></th>
                                <td><?= h($this->fieldValue()) ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            
            <?php
        }
    }
    
    public function profile($info = [], $object, $heading = "Detail") {
        $this->obj = $object;
        $name = empty($this->obj->name) ? $this->obj->first_name . " " . $this->obj->last_name : $this->obj->name;
        $image = SITE_URL . (($this->obj->has('image')) ? $this->obj->image->small_thumb : 'files/images/default.png');
        if (!empty($info)) {
            ?>
            <div class="card g-brd-primary g-rounded-3 g-mb-30">
                <header class="<?= $this->infoCardHeaderClasses; ?>">
                    <div class="media">
                        <h3 class="<?= $this->infoCardHeadingClasses; ?>">
                            <i class="fa fa-eye pt-2 pr-2"></i> <?= empty($name) ? $heading : $name; ?>
                        </h3>
                    </div>
                </header>
                
                <div class="card-block g-pa-15 g-pa-30--sm">
                    <div class="row">
                        <div class="col-md-12">
                            <section class="text-center g-mb-30 g-mb-20--md">
                                <div class="d-inline-block g-pos-rel g-mb-10">
                                    <img class="detail-img-fluid rounded-circle" src="<?= $image; ?>"
                                         alt="<?= $name; ?>" style="width: 150px; height: 150px;">
                                </div>
                                
                                <h3 class="g-font-weight-300 g-font-size-20 g-color-black mb-0"><?= $name; ?></h3>
                            </section>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="vertical-table">
                                <?php
                                foreach ($info as $field) {
                                    $this->field = $field;
                                    ?>
                                    <tr>
                                        <th scope="row" style="width: 30%;"><?= __($this->fieldLabel()) ?></th>
                                        <td><?= h($this->fieldValue()) ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php
        }
    }
    
    public function fieldValue() {
        $values = [];
        if (is_array($this->field['name'])) {
            foreach ($this->field['name'] as $field) {
                $values[] = $this->getValue($field);
                $values = array_filter($values);
            }
        } else {
            if($this->field['name'] == "phone") {
                $values[] = $this->phoneFormat($this->getValue($this->field));
            } else{
                $values[] = $this->getValue($this->field);
            }
            
        }
        
        return implode(empty($this->field['separator']) ? ", " : $this->field['separator'], $values);
    }
    
    public function getValue($field) {
        $value = "";
        if (strpos($field['name'], '_id') !== false) {
            $relatedModel = str_replace('_id', '', $field['name']);
            if ($this->obj->has($relatedModel)) {
                if (!empty($field['related_model_fields'])) {
                    $values = [];
                    foreach ($field['related_model_fields'] as $f) {
                        $values[] = $this->obj->{$relatedModel}->{$f};
                    }
                    
                    $value = implode(" ", $values);
                    
                } else {
                    $value = $this->obj->{$relatedModel}->name;
                }
            } else {
                $value = $this->obj->{$field['name']};
            }
        } else {
            $value = $this->obj->{$field['name']};
        }
        
        return $value;
    }
    
    public function fieldLabel() {
        if (empty($this->field['label'])) {
            $name = (strpos($this->field['name'], '_id') !== false) ? str_replace('_id', '', $this->field['name']) : $this->field['name'];
            $label = Inflector::humanize($name);
        } else {
            $label = $this->field['label'];
        }
        
        return $label;
    }
    
    public function phoneFormat($phone) {
        if (preg_match('/(\d{3})(\d{3})(\d{4})$/', $phone, $matches)) {
            return $matches[1] . '-' . $matches[2] . '-' . $matches[3];
        }
        return $phone;
    }
}

<?php
$activeText = $activeText ?? "Active" ;
$inactiveText = $inactiveText ?? "Inactive";
$field = $field ?? "status";

?>
<?php if ($status) { ?>
    <a href="javascript:void(0);"
       class="u-tags-v1 text-center g-width-110 g-brd-around g-brd-teal-v2 g-bg-teal-v2 g-font-weight-600 g-color-white g-rounded-50 g-py-4 g-px-15 active-deactive"
       id="activeDeactive_<?= $id ?>" data-model="<?= $model ?>" data-field="<?= $field ?>"
       data-active-text="<?= $activeText ?>" data-inactive-text="<?= $inactiveText ?>"><?= $activeText ?></a>
<?php } else { ?>
    <a href="javascript:void(0);"
       class="u-tags-v1 text-center g-width-110 g-brd-around g-brd-primary g-bg-primary g-font-weight-600 g-color-white g-rounded-50 g-py-4 g-px-15 active-deactive"
       id="activeDeactive_<?= $id ?>" data-model="<?= $model ?>" data-field="<?= $field ?>" data-active-text="<?= $activeText ?>" data-inactive-text="<?= $inactiveText ?>"><?= $inactiveText ?></a>
<?php } ?>

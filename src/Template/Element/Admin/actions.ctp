<?php
$showView = $showView ?? true;
$showEdit = $showEdit ?? true;
$showDelete = $showDelete ?? true;
?>
<td class="actions">
    <?php if($showView) {  ?>
    <?= $this->Html->link('<i class=\'hs-admin-eye\'></i> View', ['action' => 'view', $id], ['class'=>'btn btn-success btn-sm', 'escape' => false]) ?>
    <?php } ?>
    <?php if($showEdit) {  ?>
    <?= $this->Html->link('<i class=\'hs-admin-pencil\'></i> Edit', ['action' => 'edit', $id], ['class'=>'btn u-btn-blue btn-sm', 'escape' => false]) ?>
    <?php } ?>
    <?php if($showDelete) {  ?>
    <?= $this->Form->postLink('<i class=\'hs-admin-close\'></i> Delete', ['action' => 'delete', $id], ['confirm' => __('Are you sure you want to delete # {0}?', $id), 'class'=>'btn btn-danger btn-sm', 'escape' => false]) ?>
    
    <?php } ?>
</td>


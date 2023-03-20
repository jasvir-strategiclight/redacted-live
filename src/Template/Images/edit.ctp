<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Image $image
 */
?>
<div class="images form large-9 medium-8 columns content">
    <?= $this->Form->create($image) ?>
    <fieldset>
        <legend><?= __('Edit Image') ?></legend>
        <?php
            echo $this->Form->control('user_id');
            echo $this->Form->control('category');
            echo $this->Form->control('image');
            echo $this->Form->control('small_thumb');
            echo $this->Form->control('medium_thumb');
            echo $this->Form->control('large_thumb');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<h3 class="g-font-weight-300 g-font-size-28 g-color-black g-mb-30"><?= __('Add Image') ?></h3>
<div class="row">
    <!-- left column -->
    <div class="col-md-1"></div>
    <div class="col-md-8">
        <?= $this->Form->create($image) ?>
        <?php
        echo $this->Form->control('user_id');
        echo $this->Form->control('category');
        echo $this->Form->control('image');
        echo $this->Form->control('small_thumb');
        echo $this->Form->control('medium_thumb');
        echo $this->Form->control('large_thumb');
        ?>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
    <div class="col-md-1"></div>
</div>

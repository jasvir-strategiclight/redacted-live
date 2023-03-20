<!-- Filters -->
<div class="col-md-2 order-md-1 g-brd-right--lg g-brd-gray-light-v4 g-pt-20">
    <div class="g-pr-15--lg">
        <!-- Categories -->
        <?= $this->Form->create(null, ['url' => 'javascript:void(0);', 'id' => 'chooseCategoriesForm']); ?>
        <?php foreach ($parentCategories as $category) { ?>
            <?php if ($category->has('child_categories')) { ?>
                <?php if (count($category->child_categories) > 0) { ?>
                    <h4 class="pl-1 mt-4 colorindent p-1 parent-cat"><?= $category->name; ?></h4>
                    <div class="parent-cat-box">
                        <?php foreach ($category->child_categories as $childCategory) { ?>
                            <div class="form-group g-pl-5" style="margin-bottom:0px; padding-bottom:0px;">
                                <label class="u-check g-pl-25 g-color-gray-dark-v4"
                                       for="category_<?= $childCategory->id; ?>">
                                    <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0 choose-category"
                                           type="checkbox"
                                           name="category_ids[]" value="<?= $childCategory->id; ?>"
                                           id="category_<?= $childCategory->id; ?>">
                                    <div class="u-check-icon-checkbox-v4 g-absolute-centered--y g-left-0">
                                        <i class="fa" data-check-icon="&#xf00c"></i>
                                    </div>
                                    <?= $childCategory->name; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } ?>
        <?= $this->Form->end(); ?>
        <!-- End Categories -->
        <hr>
    </div>
</div>
<!-- End Filters -->
<script>
    $(function () {
        $('.parent-cat').click(function () {
            $('.parent-cat-box').hide();
            $(this).next('.parent-cat-box').slideDown();
        });
        
        $('.parent-cat:first').click();
    });
</script>

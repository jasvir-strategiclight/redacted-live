<?php $this->assign('title', "Hep Builder"); ?>
<?= $this->Html->css(['styles.e-commerce']) ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?= $this->Form->create(null, ['url' => 'javascript:void(0);', 'id' => 'searchForm']); ?>
<div class="row">
    <div class="col-md-4"><h2> Choose exercises for Program: </h2></div>
    <div class="col-md-4">&nbsp;</div>
    <div class="col-md-4 text-right">
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <div class="g-pos-rel">
                      <span class="g-pos-abs g-top-0 g-right-0 d-block g-width-50 h-100">
	                    <i class="hs-admin-search g-absolute-centered g-font-size-16 g-color-gray-light-v6"></i>
	                  </span>
                        <input id="listingSearchKey" name="search_key"
                               class="form-control form-control-md g-brd-gray-light-v7 g-brd-gray-light-v3--focus g-rounded-4 g-px-14 g-py-10"
                               type="text" placeholder="Search..." value="">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-lg" id="searchInListing">Search</button>
            </div>
            <div class="col-md-3 mt-3">
                <a href="javascript:void(0)" class="mt-3" id="SearchClear">Clear Search</a>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end(); ?>
<div class="row ml-1 mb-1">
    <div class="col-md-2 g-bg-lightblue pb-5" style="height: auto;">
        <?= $this->Form->create(null, ['url' => 'javascript:void(0);', 'id' => 'chooseCategoriesForm']); ?>
        <?php foreach ($parentCategories as $category) { ?>
            <?php if ($category->has('child_categories')) { ?>
                <?php if (count($category->child_categories) > 0) { ?>
                    <h5 class=" p-2 mt-1 colorindent parent-gocat"><?= $category->name; ?></h5>
                    <div class="parent-cat-box" style="display: none;">
                        <?php foreach ($category->child_categories as $childCategory) { ?>
                            <div class="form-group g-mb-5 ">
                                <label class="u-check g-pl-25 g-color-text" for="category_<?= $childCategory->id; ?>">
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
        <button class="btn btn-danger font-weight-bold pull-right" id="ClearAll">Clear All</button>
    </div>
    <div class="col-md-10" style="height: 750px; float: left; overflow-y: auto" id="exerciseContainer">
        <div class="row" id="fetchedExercises"></div>
    </div>
</div>
<br/>
<div class="clear-both"></div>
<h2 class="colorindent">Selected exercises for Program: </h2>

<div class="row ml-2" id="selectedExercises"></div>
<div class="row">
    <div class="col-md-6">&nbsp;</div>
    <div class="col-md-3 text-right">
        <input name="name" class="form-control" placeholder="Program Name" id="programName">
        <label class="error" style="display: none;" id="programNameError">Please enter program name.</label>
    </div>
    <!--    <div class="col-md-5">-->
    <!--        <input name="description" class="form-control" placeholder="Program Description"-->
    <!--               id="programDescription">-->
    <!--        <label class="error" style="display: none;" id="programDescriptionError">Please enter program-->
    <!--            description.</label>-->
    <!--    </div>-->
    <div class="col-md-2 text-left">
        <button class="btn btn-primary" id="AddExercisesToProgram">Add Exercises to Program</button>
        <br/>
        <label class="error text-right" id="selectExerciseError" style="display: none; ">Please select atleast one
            exercise. </label>
    </div>


</div>
<template id="exerciseTemplate">
    <div class="" id="fetched_${id}" style="display:none; width: auto; height:auto; margin:1%;" title="${title}">
        <!-- Product -->
        <figure class="g-pos-rel g-mb-0" style="border: 1px solid #ededed; ">
            <img class="img-fluid" src="${image}"
                 style="width:200px; height:200px; "
                 alt="Image Description">

            <figcaption
                    class="w-100 g-bg-primary g-bg-black--hover text-center g-pos-abs g-bottom-0 g-transition-0_2 g-cursor-pointer g-py-5 add-to-program"
                    id="addToProgram_${id}">
                <a class="g-color-white g-font-size-11 text-uppercase g-letter-spacing-1 g-text-underline--none--hover "
                   href="javascript:void(0);"><i
                            class="fa fa-plus"></i> Add to Program</a>
            </figcaption>
        </figure>

        <div class="media">
            <!-- Product Info -->
            <div class="d-flex flex-column">
                <h4 class="h6 g-color-black mb-1">
                    <a class="u-link-v5 g-color-black g-color-primary--hover" href="javascript:void(0);">${name}</a>
                </h4>
                <!-- a class="d-inline-block g-color-gray-dark-v5 g-font-size-13"
                   href="javascript:void(0);">${description}</a -->
            </div>
        </div>
        <!-- End Product -->
    </div>
</template>
<template id="programExerciseTemplate">
    <div class="" id="selected_${id}" style=" width: auto; height:auto; margin:20px; ">
        <!-- Product -->
        <figure class="g-pos-rel g-mb-0">
            <img class="img-fluid" src="${image}"

                 style="width: 120px; height: 120px;"
                 alt="Image Description">

            <figcaption
                    class="w-100 g-bg-primary g-bg-black--hover text-center g-pos-abs g-bottom-0 g-transition-0_2 g-py-5 g-cursor-pointer remove-from-program"
                    id="removeFromProgram_${id}">
                <a class="g-color-white g-font-size-11 text-uppercase g-letter-spacing-1 g-text-underline--none--hover "
                   href="javascript:void(0);"><i
                            class="fa fa-times"></i> Remove </a>
            </figcaption>
        </figure>

        <div class="media">
            <!-- Product Info -->
            <div class="d-flex flex-column">
                <h4 class="h6 g-color-black mb-1">
                    <a class="u-link-v5 g-color-black g-color-primary--hover" href="javascript:void(0);">${name}</a>
                </h4>
                <!-- a class="d-inline-block g-color-gray-dark-v5 g-font-size-13"
                   href="javascript:void(0);">${description}</a -->
            </div>
        </div>
        <!-- End Product -->
    </div>
</template>

<script>
    var ES = {};
    var E = [];
    var selectedExerciseIds = [];
    var currentRequest = null;
    var PAGE = 1;
    var LOADING = false;
    $(function () {

        $('.parent-cat').click(function () {
            $('.parent-cat-box').hide();
            $(this).next('.parent-cat-box').slideDown();
        });

        $('.parent-cat:first').click();

        setTimeout(function () {
            getExercises();
        }, 100);

        $('.choose-category').click(function () {
            if (currentRequest != null) {
                currentRequest.abort();
            }
            PAGE = 1;
            getExercises();
        });

        $('#searchInListing').click(function () {
            if (currentRequest != null) {
                currentRequest.abort();
            }
            PAGE = 1;
            getExercises();

        });


        function getExercises() {

            currentRequest = $.ajax({
                url: SITE_URL + 'exercises/getExercises/' + PAGE,
                type: "POST",
                data: $('#chooseCategoriesForm').serialize() + "&" + $("#searchForm").serialize(),
                dataType: "JSON",
                beforeSend: function (xhr) {
                    $("#fetchedExercises").after($("<h4 class='loading text-center my-3' id='loadingExercises'>Loading... <i class='fa fa-spinner fa-spin'></i></h4>")).fadeIn('slow');
                    if (PAGE <= 1) {
                        E = [];
                        $('#fetchedExercises').html("");
                    }
                    LOADING = true;
                },
                success: function (resp) {
                    $('#loadingExercises').remove();
                    if (resp.data.exercises.length > 0) {
                        $.each(resp.data.exercises, function (i, e) {
                            if (ES[e.id] == undefined) {
                                ES[e.id] = e;
                            }

                            $.template("exerciseTemplate", $('#exerciseTemplate').html());
                            $.tmpl("exerciseTemplate", [e]).appendTo('#fetchedExercises');

                            if ($.inArray(e.id.toString(), selectedExerciseIds) == -1) {
                                E.push(e);
                                $('#fetched_' + e.id).fadeIn();
                            }

                        });
                        if (selectedExerciseIds.length > 0 && E.length == 0) {
                            $('#fetchedExercises').append('<h3 class="pl-3" id="allexerciesAddedAlready">All exercises have already been selected.</h3>');
                        }
                        PAGE = PAGE + 1;
                        LOADING = false;
                    } else {
                        if (PAGE <= 1) {
                            $('#fetchedExercises').html('<h3 class="w-100 text-center my-2">No Exercise Found.</h3>');
                        } else {
                            $('#fetchedExercises').append('<h3 class="w-100 text-center my-2">No More Exercise Found.</h3>');
                        }
                    }
                }
            });

        }

        $("#exerciseContainer").scroll(function () {
            var $this = $(this);
            var $results = $("#fetchedExercises");

            if (!LOADING) {
                if ($this.scrollTop() + $this.height() > ($results.height() - 50)) {
                    getExercises();
                }
            }
        });

        $('#fetchedExercises').on('click', '.add-to-program', function () {
            var id = $(this).attr('id').split('_')[1];

            if ($.inArray(id, selectedExerciseIds) == -1) {
                $('#fetched_' + id).fadeOut();
                $.template("programExerciseTemplate", $('#programExerciseTemplate').html());
                $.tmpl("programExerciseTemplate", [ES[id]]).appendTo('#selectedExercises');
                $('#selected_' + id).fadeIn();
                selectedExerciseIds.push(id);
            }
            if (selectedExerciseIds.length > 0) {
                $(window).bind('beforeunload', function () {
                    return 'Are you sure you want to leave?';
                });
            }
            $("html, body").animate({scrollTop: $("#selectedExercises").position().top}, 1000);
        });

        $('#selectedExercises').on('click', '.remove-from-program', function () {
            var id = $(this).attr('id').split('_')[1];

            var index = selectedExerciseIds.indexOf(id);
            if (index > -1) {
                selectedExerciseIds.splice(index, 1);
                $('#fetched_' + id).fadeIn();
                $('#selected_' + id).fadeOut();
                $('#selected_' + id).remove();
            }

            if (selectedExerciseIds.length <= 0) {
                $(window).unbind('beforeunload');
            }

            $("html, body").animate({scrollTop: $("#fetchedExercises").position().top}, 1000);
            $('#allexerciesAddedAlready').remove();

        });

        $('#AddExercisesToProgram, #AddExercisesToProgramUpper').click(function () {
            $('label.error').hide();
            var error = false;
            var name = $.trim($('#programName').val());
            var description = $.trim($('#programName').val());


            if (selectedExerciseIds.length <= 0) {
                $('#selectExerciseError, #selectExerciseErrorUpper').fadeIn();
                error = true;
            }


            // if (name.length <= 0) {
            //     $('#programNameError').fadeIn();
            //     error = true;
            //     $('#programName').focus();
            // }

            // if (description.length <= 0) {
            //     $('#programDescriptionError').fadeIn();
            //     error = true;
            //     $('#programDescription').focus();
            // }

            if (!error) {
                $.ajax({
                    url: SITE_URL + 'programs/addExercises',
                    type: "POST",
                    data: {exercise_ids: selectedExerciseIds, name: name, description: description},
                    dataType: "JSON",
                    beforeSend: function () {
                        $('#AddExercisesToProgram').html('<i class="fa fa-spin fa-spinner"></i> Saving...');
                    },
                    success: function (resp) {
                        if (resp.code == 200) {
                            $(window).unbind('beforeunload');
                            window.location.href = "<?= SITE_URL; ?>programs/edit/" + resp.data.routineId
                        }
                    }
                });
            }
        });

        $('#SearchClear').click(function () {
            $('#listingSearchKey').val('');
            $('#searchInListing').click();
        });

        $(document).tooltip({
            content: function () {
                return $(this).prop('title');
            },
            position: {my: "right+80 top+95", at: "right center"}
        });

        $('#ClearAll').click(function(e){
            e.preventDefault();

            $('.choose-category').removeAttr('checked');
            $('#listingSearchKey').val('');
            $('#searchInListing').click();
        });
    });
</script>


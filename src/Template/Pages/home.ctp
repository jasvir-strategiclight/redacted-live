<section class="g-brd-bottom g-brd-gray-light-v4 g-py-30">
    <div class="container">
        <div class="row">
            <div class="col-md-12 p-0">
                <div class="carousel slide" id="carousel-364531">
                    <ol class="carousel-indicators">
                        <li data-slide-to="0" data-target="#carousel-364531" class="active"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active"><img class="d-block" style="margin:0px auto;"
                                                               alt="<?= SITE_TITLE; ?>"
                                                               src="<?= SITE_URL . $content['image']->image; ?>">
                            <div class="carousel-caption">
                                
                                <div
                                    class="d-inline-block g-hidden-xs-down g-pos-rel g-valign-middle g-py-30 g-pl-0--lg ">
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carousel-364531" data-slide="prev"><span
                            class="carousel-control-prev-icon"></span> <span class="sr-only">Previous</span></a> <a
                        class="carousel-control-next" href="#carousel-364531" data-slide="next"><span
                            class="carousel-control-next-icon"></span> <span class="sr-only">Next</span></a>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <?= $content['content']; ?>
            </div>
        </div>
    </div>
</section>

<?php if ($Auth->user()) { ?>
    
    <div class="row">
        <div class="container">
            <div class="col-md-12" style="">
                <div class="row">
                    <?php echo $this->element('sidebar_categories'); ?>
                    <!-- Content -->
                    <div class="col-md-10 order-md-2">
                        <div class="g-pl-15--lg">
                            <!-- Products -->
                            <div class="row g-pt-30 g-mb-50" id="fetchedExercises"
                                 style="width:100%; float:left; height: 800px; overflow-y: auto;">
                            
                            </div>
                        </div>
                    </div>
                    <!-- End Content -->
                
                
                </div>
            </div>
        
        </div>
        <!-- End Products -->
        <template id="exerciseTemplate">
            <div class="col-6 col-lg-4 g-mb-30"
                 style="padding:; display: none;" id="exercise_${id}">
                <!-- Product -->
                <div class="g-brd-gray-light-v4 g-brd-1 g-brd-style-solid">
                    <figure class="g-pos-rel g-mb-20">
                        <a href="<?= $this->Url->build(['controller' => 'Exercises', 'action' => 'view']); ?>/${id}">
                            <img src="<?= SITE_URL; ?>${image}" class="img-fluid"
                                 style=" max-height: 244px; border-bottom: 1px solid #ededed"/>
                        </a>
                    
                    </figure>
                    
                    <div class="media px-2">
                        <!-- Product Info -->
                        <div class="d-flex flex-column">
                            <h4 class="h6 g-color-black">
                                <a id="addToNameRoutine_${id}"
                                   class="u-link-v5 g-color-black g-color-primary--hover add-to-routine"
                                   href="javascript:void(0);" title="${full_name}">${name}</a>
                            </h4>
                            <br/>
                            <p title="${full_description}">${description}</p>
                        </div>
                        <!-- End Product Info -->
                        
                        <!-- Products Icons -->
                        <ul class="list-inline media-body text-right">
                            <li class="list-inline-item align-middle mx-0">
                                <a class="u-icon-v1 u-icon-size--sm g-color-gray-dark-v5 g-color-primary--hover g-font-size-15 rounded-circle add-to-routine"
                                   href="javascript:void(0);"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Add to Routine" id="addToRoutine_${id}">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </li>
                            <li class="list-inline-item align-middle mx-0">
                                <a class="u-icon-v1 u-icon-size--sm g-color-gray-dark-v5 g-color-primary--hover g-font-size-15 rounded-circle mark-favorite heart"
                                   href="javascript:void(0);"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="Add to Favourite" id="markFavorite_${id}">
                                    <i class="${marked_favorite}"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
    </div>
    </template>
    
    <div style="clear:both;"></div>
    <script>
        var PAGE = 1;
        var LOADING_EXERCISES = false;
        $(function () {
            
            setTimeout(function () {
                getExercises();
            }, 100);
            
            $('.choose-category').click(function () {
                LOADING_EXERCISES = false;
                PAGE = 1;
                getExercises();
            });
            
            
            $('#fetchedExercises').scroll(function (e) {
                var scrollHeight = document.getElementById("fetchedExercises").scrollHeight;
                if (scrollHeight - $(this).scrollTop() < ($(this).height() + 100)) {
                    getExercises();
                }
            });
            
            $('#fetchedExercises').on('click', '.add-to-routine', function () {
                var id = $(this).attr('id').split('_')[1];
                window.location.href = "<?= SITE_URL; ?>exercises/view/" + id;
            });
        });
        
        function getExercises() {
            
            if (!LOADING_EXERCISES) {
                console.log('here');
                $.ajax({
                    url: SITE_URL + 'exercises/getExercises/' + PAGE,
                    type: "POST",
                    data: $('#chooseCategoriesForm').serialize(),
                    dataType: "JSON",
                    beforeSend: function () {
                        if (PAGE == 1) {
                            $('#fetchedExercises').html("");
                        }
                        LOADING_EXERCISES = true;
                        $('#fetchedExercises').append("<h5 id='searchLoader'><i class='fa fa-spin fa-spinner'></i> Loading ...</h5>");
                    },
                    success: function (resp) {
                        if (resp.code == "200") {
                            $('#searchLoader').remove();
                            if (resp.data.exercises.length > 0) {
                                $.each(resp.data.exercises, function (i, e) {
                                    $.template("exerciseTemplate", $('#exerciseTemplate').html());
                                    $.tmpl("exerciseTemplate", [e]).appendTo('#fetchedExercises');
                                    
                                    $('#exercise_' + e.id).fadeIn();
                                });
                                
                                PAGE = PAGE + 1;
                                LOADING_EXERCISES = false;
                            } else {
                                if (PAGE === 1) {
                                    $('#fetchedExercises').html("<div class='col-12'><h3>No Exercise Found</h3></div>");
                                } else {
                                    $('#fetchedExercises').append("<div class='col-12'><h3>No More Exercise Found</h3></div>");
                                    LOADING_EXERCISES = true;
                                }
                            }
                        }
                    }
                });
                
            }
        }
        
        $(function () {
            $(document).on("click", '.mark-favorite', function (event) {
                
                var exerciseId = $(this).attr('id').split("_")[1];
                
                $('label.error').hide();
                var error = false;
                if (!error) {
                    $.ajax({
                        url: SITE_URL + 'exercises/addFavorite',
                        type: "POST",
                        data: {exercise_id: exerciseId},
                        dataType: "JSON",
                        beforeSend: function () {
                            $('#markFavorite_').html('<i class="fa fa-spin fa-spinner"></i>');
                        },
                        success: function (resp) {
                            if (resp.code == 200) {
                                $('#markFavorite_' + exerciseId).html('<i class="fa fa-heart"></i>');
                            } else {
                                $('#markFavorite_' + exerciseId).html('<i class="fa fa-heart-o"></i>');
                            }
                        }
                    });
                }
            });
        });
    </script>

<?php } else { ?>

<?php } ?>


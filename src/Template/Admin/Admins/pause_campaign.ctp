<div class="row">
    <div class="col-md-8">
                                           <div class="card g-brd-gray-light-v7 g-rounded-3 g-mb-30">
                <header class="card-header g-brd-bottom-none g-px-15 g-px-30--sm g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex align-self-center text-uppercase g-font-size-12 g-font-size-default--md g-color-primary font-weight-bold g-mr-10 mb-0">
                            Pause Campaign                        </h3>
                    </div>
                </header>

                <div class="card-block g-pa-15 g-pa-30--sm">
                    <div class="row p-lg-4">
                        <div class="col-md-6" id="PauseCampaignOuter">
                            <div class="form-group">
                                <label class="d-flex align-items-center justify-content-between">
                                    <span>Pause Campaign</span>
                                    <div class="u-check">
                                        <input class="g-hidden-xs-up g-pos-abs g-top-0 g-right-0" name="radGroup3_1" id="pauseCampaignT" <?= $pauseCampaign->setting_value == "pause" ? "checked" : ""; ?> type="checkbox">
                                        <div class="u-check-icon-radio-v8">
                                            <i class="fa fa-pause"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                    </div>
                </div>
            </div>
    </div>
</div>

    <script>
        $(function () {
            $('#pauseCampaignT').click(function () {
                var playPause = "play";
                if($(this).is(':checked')){
                    playPause = 'pause';
                } else {
                    playPause = "play";
                }

                $.ajax({
                    url: SITE_URL + 'admin/admins/savePauseCampaign/'+playPause,
                    type: "POST",
                    dataType: "json",
                    success: function (response) {
                        window.location.reload();
                    }
                });
            });
        })
    </script>
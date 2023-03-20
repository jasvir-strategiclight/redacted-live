<?php
$fieldValues = json_decode($mc->merged_fields_json, true);
?>

<div class="row" xmlns="http://www.w3.org/1999/html">
    <div class="col-md-1">&nbsp;</div>
    <div class="col-md-10"><h3><b>Mail Chimp Settings: </b><?= ucwords(str_replace("-", " ", $slug)); ?></h3>
        <div class="row">
            <div class="col-md-12">
                <form id="getListsForm" action="javascript:void(0);">
                    <div class="row mt-5">
                        <div class="col-md-2 text-right">
                            <label class="mt-2"><b>API Key:</b></label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="api_key" id="mcApiKey" class="form-control"
                                   value="<?= $mc->api_key; ?>" style="height: 42px;">
                            <label class="error" id="loadListingError" style="display: none;"></label>
                        </div>
                        <div class="col-md-3 text-left pt-2" id="loadingListLoader" style="display: none;">
                            Loading Lists ... <i class="fa fa-spinner fa-spin"></i>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12 mt-5" id="chooseListSection" style="display: none">
                <div class="row">
                    <div class="col-md-2 text-right">
                        <label class="mt-2"><b>Choose List:</b></label>
                    </div>
                    <div class="col-md-6">
                        <select class="form-control" name="list_id" id="mcListId" style="height: 42px;"></select>
                        <label class="error" id="loadFieldsError" style="display: none;"></label>
                    </div>
                    <div class="col-md-3 text-left pt-2" id="loadingFieldsLoader" style="display: none;">
                        Loading Fields ... <i class="fa fa-spinner fa-spin"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-5" id="mapFieldsSection" style="display: none">
                <h4>Map Fields: </h4>
                <div class="row">
                    <div class="col-md-2 text-right">
                        <label class="mt-2"><b>Local Field</b></label>
                    </div>
                    <div class="col-md-3 text-left">
                        <label class="mt-2"><b>Mail Chimp Field</b></label>
                    </div>
                    <div class="col-md-7">&nbsp;</div>
                </div>
                <form id="saveMappingForm" action="javascript:void(0)">
                    <?php foreach ($localFields as $field => $label) { ?>
                        <div class="row mt-5">
                            <div class="col-md-2 text-right">
                                <label class="mt-2"><?= $label; ?></label>
                            </div>
                            <div class="col-md-3 text-center">
                                <select class="form-control list-field" name="<?= $field; ?>" id="select_<?= $field; ?>"
                                        style="height: 42px;"></select>
                            </div>
                            <div class="col-md-7">&nbsp;</div>

                        </div>
                    <?php } ?>
                    <div class="row mt-5">
                        <div class="col-md-2 text-right">
                            <label class="mt-2">&nbsp;</label>
                        </div>
                        <div class="col-md-3 text-right">
                            <button class="btn btn-lg btn-primary" id="saveMappingBtn"><i class="fa fa-save"></i> Save
                            </button>
                        </div>
                        <div class="col-md-7">&nbsp;</div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-12 text-center"><h4 id="saveMappingMsg"></h4></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-1">&nbsp;</div>
</div>
<script>
    $(function () {
        $('#mcApiKey').keyup(function () {
            loadListings();
        }).keydown(function () {
            loadListings();
        });

        setTimeout(function () {
            loadListings();
        }, 700);

        function loadListings() {
            $('#loadListingError').hide();
            $.ajax({
                url: SITE_URL + 'admin/mail_chimp/getMcLists',
                type: "POST",
                data: {api_key: $('#mcApiKey').val(), slug:"<?= $slug;  ?>"},
                dataType: "json",
                beforeSend: function () {
                    $('#loadingListLoader').show();
                },
                success: function (resp) {
                    $('#loadingListLoader').fadeOut();
                    if (resp.code == 200) {
                        $('#chooseListSection').fadeIn();
                        var options = [];
                        options.push('<option class="bs-title-option" value="">Select List</option>');

                        $.each(resp.data.lists, function (index, data) {
                            options.push('<option value="' + data.value + '" ' + ((data.value == "<?= $mc->list_id; ?>") ? "Selected" : "") + '>' + data.label + '</option>');
                        });

                        $('#mcListId').html(options.join(''));
                        setTimeout(function () {
                            $('#mcListId').change();
                        }, 500);

                    } else {
                        $('#loadListingError').html(resp.message).fadeIn();
                    }
                }
            });
        }

        $('#mcListId').change(function (e) {
            loadFields();
        });

        function loadFields() {
            $('#loadListingError').hide();
            $.ajax({
                url: SITE_URL + 'admin/mail_chimp/getMcListFields',
                type: "POST",
                data: {list_id: $('#mcListId').val(), slug:"<?= $slug;  ?>"},
                dataType: "json",
                beforeSend: function () {
                    $('#loadingFieldsLoader').show();
                },
                success: function (resp) {
                    $('#loadingFieldsLoader').fadeOut();

                    if (resp.code == 200) {
                        $('#mapFieldsSection').fadeIn();
                        <?php if(empty($fieldValues)) { ?>

                        var options = [];
                        options.push('<option class="bs-title-option" value="">Map Field</option>');
                        options.push('<option class="bs-title-option" value="EMAIL">EMAIL</option>');
                        $.each(resp.data.fields, function (index, data) {
                            options.push('<option value="' + data.value + '">' + data.label + '</option>');
                        });

                        $('.list-field').html(options.join(''));

                        <?php } else { ?>

                        <?php  foreach($localFields as $field => $label) {
                            $fv = empty($fieldValues[$field]) ? "" : $fieldValues[$field];
                            ?>
                        var options = [];
                        options.push('<option class="bs-title-option" value="">Map Field</option>');
                        options.push('<option class="bs-title-option" value="EMAIL"' + (("<?= $fv; ?>" == "EMAIL" ? "Selected" : "")) + '>EMAIL</option>');
                        $.each(resp.data.fields, function (index, data) {
                            options.push('<option value="' + data.value + '" ' + (("<?= $fv; ?>" == data.value ? "Selected" : "")) + '>' + data.label + '</option>');
                        });

                        $('#select_<?= $field; ?>').html(options.join(''));
                        <?php } ?>
                        <?php } ?>
                    } else {
                        $('#loadFieldsError').html(resp.message).fadeIn();
                    }
                }
            });
        }


        $('#saveMappingBtn').click(function (e) {
            e.preventDefault();

            $('#saveMappingMsg').html("");

            $.ajax({
                url: SITE_URL + 'admin/mail_chimp/saveMapping/<?= $slug;  ?>',
                type: "POST",
                data: $('#saveMappingForm').serialize(),
                dataType: "json",
                beforeSend: function () {
                    $('#saveMappingBtn').html('Saving ... <i class="fa fa-spinner fa-spin"></i>');
                },
                success: function (resp) {

                    $('#saveMappingBtn').html('<i class="fa fa-save"></i> Save');
                    $('#saveMappingMsg').html(resp.message);
                }
            });

        });
    });
</script>
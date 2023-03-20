<div class="col-md-12 p-5" id="emailTemplatePreview">
    <?= $this->element('Admin/email_preview') ?>
</div>
<script>
    $(function () {
        $('#emailDynamicContent').html("<h3>Loading <i class='fa fa-spinner fa-spin'></i> </h3>");
        $.ajax({
            url: SITE_URL + 'admin/admins/getEmailTemplate/<?= $emailTemplate->id; ?>' ,
            type: "GET",
            success: function (response) {
                $('#emailTemplatePreview').fadeIn();
                $('#emailDynamicContent').html(response);
            }
        });
    });
</script>
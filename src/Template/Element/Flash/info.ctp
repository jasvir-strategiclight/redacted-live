<?php

if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<div class="message success noty_bar noty_type__info noty_theme__unify--v1 g-mb-25 ntf message-ntf">
    <div class="noty_close_button pull-right" id="ntfMsg"><b>&times;</b></div>
    <div class="noty_body">
        <div class="g-mr-20">
            <div class="noty_body__icon">
                <i class="fa fa-info"></i>
            </div>
        </div>
        <div><b><?= $message ?></b></div>
    </div>
</div>
<script>
    $(function () {
        $('.message-ntf').click(function () {
            $(this).fadeOut();
        });
        setTimeout(function () {
            $('.message-ntf').fadeOut();
        }, 3000)
    });
</script>


<div style="padding:0px 20px 20px 20px">
    <p style="font-size:1em;font-weight:bold;margin-top:15px; font-family:Arial, SanSerif; font-size:16px;">Your
        friend, <?= $email ?>, thinks you'd enjoy <?= SITE_TITLE; ?>, a newsletter powered by <?= SITE_TITLE; ?>, and
        sent you a private email invite with this message:</p>

    <p style="font-size:1em;color:#4b4b4b"><?= $message; ?></p>

    <h3>Please <a href="<?= $url; ?>" target="_blank">click here</a> to start.</h3>
</div>
<div style="padding:0px 20px 10px 20px">
    <p style="padding:0px;font-weight:bold;color:#4b4b4b; font-family:Arial, SanSerif; font-size:16px;">Thanks,<br/>
        <?= SITE_TITLE ?> Team</p>
</div>

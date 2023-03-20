<div class="row">
    <div class="col-md-12">
        <h2>
            Cron Jobs
        </h2>
        <ul>
            <li>
                <!-- <h4><?= SITE_URL;?>scheduler/send-scheduled-emails</h4> -->
                <span>Minute */3</span><h4>wget <?= SITE_URL;?>scheduler/send-scheduled-emails/4/1  </h4>
                <span>Minute */3</span><h4>wget <?= SITE_URL;?>scheduler/send-scheduled-emails/4/2  </h4>
                <span>Minute */3</span><h4>wget <?= SITE_URL;?>scheduler/send-scheduled-emails/4/3  </h4>
                <span>Minute */3</span><h4>wget <?= SITE_URL;?>scheduler/send-scheduled-emails/4/4  </h4>
                <span>Once a Day Minutes 0 Hours 0 Days * Month * Weeks *
</span><h4>wget <?= SITE_URL;?>admin/email_templates/synctemplates  </h4>
                <span>Once a Day Minutes 0 Hours 0 Days * Month * Weeks *</span><h4>wget <?= SITE_URL;?>users/syncUsers</h4>
            </li>
        </ul>
    </div>
</div>

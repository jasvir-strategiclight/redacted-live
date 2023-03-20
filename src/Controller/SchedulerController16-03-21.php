<?php

namespace App\Controller;

use App\Application;
use Cake\Database\Expression\QueryExpression;

/**
 * SentEmails Controller
 *
 * @property \App\Model\Table\SentEmailsTable $SentEmails
 *
 * @method \App\Model\Entity\SentEmail[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SchedulerController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Auth->allow([
            'sendEmails',
            'eBlastReminder',
            'sendScheduledEmails',
            'realtorEmployments',
            'loginReminder',
            'updateApartmentsFromAptReg',
            'mysqlDatabaseDump',
            'subscriptionExpired',
        ]);

        $this->loadModel('EBlasts');
        $this->loadModel('EBlastReceivers');
        $this->loadModel('Users');
    }

    public function sendScheduledEmails($noOfChunks = 1, $chunkNo = 1) {
       // die();
        $this->loadModel('ScheduledEmails');
        $this->loadComponent('EmailManager');

        $this->loadModel('Settings');

        $pauseCampaign = $this->Settings->find('all')->where(['Settings.setting_name' => 'Play Pause Email Campaign'])->first();

        if ($pauseCampaign->setting_value == "play") {

            date_default_timezone_set("America/Denver");

            $scheduledEmail = $this->ScheduledEmails->find('all')
                ->where([
                    'ScheduledEmails.status' => "Pending",
                    'ScheduledEmails.send_at <= "' . date(SQL_DATETIME) . '"',
                ])
                ->order(["ScheduledEmails.send_at" => "ASC"])
                ->first();

            if (!empty($scheduledEmail)) {

                $campaignToken = date(SQL_DATETIME, strtotime($scheduledEmail->send_at));

                $minMaxIds = $this->ScheduledEmails->find('all')
                    ->select([
                        'ScheduledEmails__min_id' => 'MIN(id)',
                        'ScheduledEmails__max_id' => 'MAX(id)',
                    ])
                    ->where([
                        'ScheduledEmails.send_at' => $campaignToken,
                    ])
                    ->first();

                $minId = $minMaxIds->min_id;
                $maxId = $minMaxIds->max_id;

                $totalEmails = $maxId - $minId + 1;

                $emailsPerChunk = ceil($totalEmails / $noOfChunks);

                $betweenMinId = $minId + (($chunkNo - 1) * $emailsPerChunk);
                $betweenMaxId = $minId + ($chunkNo * $emailsPerChunk) - 1;

                $emails = $this->ScheduledEmails->find('all')
                    ->contain(
                        ['EmailTemplates', 'Users']
                    )
                    ->where([
                        'ScheduledEmails.status'          => "Pending",
                        'ScheduledEmails.send_after_type' => "Immediate",
                        'ScheduledEmails.send_at <= "' . date(SQL_DATETIME) . '"',
                        'ScheduledEmails.id >='           => $betweenMinId,
                        'ScheduledEmails.id <='           => $betweenMaxId,
                    ])
                    ->limit(1000)
                    ->order(["Users.first_name" => "ASC"])
                    ->all();

                // pr([
                //     'ScheduledEmails.status'          => "Pending",
                //     'ScheduledEmails.send_after_type' => "Immediate",
                //     'ScheduledEmails.send_at <= "' . date(SQL_DATETIME) . '"',
                //     'ScheduledEmails.id >=' => $betweenMinId,
                //     'ScheduledEmails.id <=' => $betweenMaxId,
                // ]);

                if (!empty($emails->toArray())) {

                    $sentEmailCount = 0;
                    $failedEmailCount = 0;

                    $this->loadModel('Users');

                    $url = SITE_URL . "sign-in";

                    //$stopEmail = false;
                    foreach ($emails as $i => $email) {

                        $referralUrl = AFFILIATE_URL . $email->user->reference_token;
                        $referralUrl = DASHBOARD_URL . $email->user->reference_token;
                        $affiliateUrl = AFFILIATE_URL . $email->user->reference_token;
                        $emailContent = str_replace("[AFFILIATE_URL]", $affiliateUrl, $email->email_template->template);
                        $emailContent = str_replace("[REFERRAL_URL]", $referralUrl, $emailContent);

                        $options = [
                            'emailFormat' => 'html',
                            'layout'      => 'designed',
                            'template'    => 'admin_scheduled_email',
                            'to'          => EMAIL_TEST_MODE ? ADMIN_EMAIL : $email->to_email,
                            'subject'     => $email->email_template->subject,
                            'viewVars'    => [
                                'emailContent'   => $emailContent,
                                'eooToken'       => base64_encode($email->to_email),
                                'mailCheckToken' => $email->id,
                                'previewLine'    => $email->email_template->preview_line,
                                'newsletterUrl'  => $email->email_template->newsletter_url,
                            ]
                        ];

                        try {
                            //$this->EmailManager->sendEmail($options);
                            $detail = [];
                            $detail[] = $email->to_email;

                            $sentEmailCount++;

                            echo "<br /><b>" . ($i + 1) . ") Email Detail: </b>" . $email->to_email . '<br />';
                        } catch (\Error $e) {
                            //Error Message will be here
                            $failedEmailCount++;
                            echo "<br /><b>Something Went Wrong, tried to send reminder to: </b>" . $email->to_email;
                        }
                        $email->status = "Sent";
                        $this->ScheduledEmails->save($email);
                    }

                    echo "Successfully Sent Count: " . $sentEmailCount;
                    echo "Failed Count: " . $failedEmailCount;
                } else {
                    echo "<h3>No email scheduled.</h3>";
                }
            } else {
                echo "<h3>No email scheduled.</h3>";
            }
        } else {
            echo "Campaign Paused by Admin";
        }
        exit;
    }
}
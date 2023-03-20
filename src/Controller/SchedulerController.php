<?php

namespace App\Controller;

use App\Application;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;

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
            'testTime',
            'deletePreviousData',
            'getreveue',
            'loadTemplatepages',
            'loadTemplatepagescontent',
            'loadTemplateassets',
            'saveHtml'
            
        ]);

        $this->loadModel('EBlasts');
        $this->loadModel('EBlastReceivers');
        $this->loadModel('Users');
    }
    
    public $redirectEnabled = true;

    public function redirect($url, $status = null, $exit = true) {
        if($this->redirectEnabled) {
            parent::redirect($url, $status, $exit);
        }
    }
    
    public function sendScheduledEmails($noOfChunks = 6, $chunkNo = 1) {
        // die();
        $this->loadModel('ScheduledEmails');
        $this->loadComponent('EmailManager');
        $this->loadModel('Settings');

        $pauseCampaign = $this->Settings->find('all')->where(['Settings.setting_name' => 'Play Pause Email Campaign'])->first();

        if ($pauseCampaign->setting_value == "play") {

            $isCronRunning = $this->Settings->find('all')->where(['Settings.setting_name' => 'Cron ' . $chunkNo . ' of ' . $noOfChunks.' is running'])->first();

            if (empty($isCronRunning)) {
                $isCronRunning                = $this->Settings->newEntity();
                $isCronRunning->setting_name  = 'Cron ' . $chunkNo . ' of ' . $noOfChunks.' is running';
                $isCronRunning->setting_value = 'no';
                $isCronRunning->default_value = 'no';
                $this->Settings->save($isCronRunning);
            }


            if ($isCronRunning->setting_value == "no") {

                $isCronRunning->setting_value = "yes";
                $this->Settings->save($isCronRunning);


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
                            'ScheduledEmails.status' => "Pending",
                            'ScheduledEmails.send_at <= "' . date(SQL_DATETIME) . '"',
                            'ScheduledEmails.id >='  => $betweenMinId,
                            'ScheduledEmails.id <='  => $betweenMaxId,
                        ])
                        ->limit(1000)
                        ->all();

                    // pr([
                    //     'ScheduledEmails.status'          => "Pending",
                    //     'ScheduledEmails.send_after_type' => "Immediate",
                    //     'ScheduledEmails.send_at <= "' . date(SQL_DATETIME) . '"',
                    //     'ScheduledEmails.id >=' => $betweenMinId,
                    //     'ScheduledEmails.id <=' => $betweenMaxId,
                    // ]);

                    if (!empty($emails->toArray())) {

                        $sentEmailCount   = 0;
                        $failedEmailCount = 0;

                        $this->loadModel('Users');

                        $url = SITE_URL . "sign-in";

                        //$stopEmail = false;
                        $sentEmailIDs = [];
                        $notSentEmailIDs = [];
                        foreach ($emails as $i => $email) {

                            //$referralUrl  = AFFILIATE_URL . $email->user->reference_token;
                            $verifyUrl = VERIFY_URL . $email->user->reference_token;
                            $referralUrl  = DASHBOARD_URL . $email->user->reference_token;
                            $affiliateUrl = AFFILIATE_URL . $email->user->reference_token;
                            $emailContent = str_replace("[AFFILIATE_URL]", $affiliateUrl, $email->email_template->template);
                            $emailContent = str_replace("[REFERRAL_URL]", $referralUrl, $emailContent);
                            $emailContent = str_replace("[VERIFY_URL]", $verifyUrl, $emailContent);
                           
                           
                                $options = [
                                    'emailFormat' => 'html',
                                    'layout' => 'designed',
                                    'template' => 'admin_scheduled_email',
                                    'to' => EMAIL_TEST_MODE ? ADMIN_EMAIL : $email->to_email,
                                    'subject' => $email->email_template->subject,
                                    'viewVars' => [
                                        'emailContent' => $emailContent,
                                        'eooToken' => base64_encode($email->to_email),
                                        'mailCheckToken' => $email->id,
                                        'previewLine' => $email->email_template->preview_line,
                                        'newsletterUrl' => $email->email_template->newsletter_url,
                                    ],
                                ];
                        
                            
                            

                            try {
                                
                               // $response = $this->EmailManager->sendEmail($options);
                                if (@$response['error']) {

                                    $failedEmailCount++;
                                    echo "<br /><b>Something Went Wrong, tried to send reminder to: </b>" . $email->to_email;

                                    $options = [
                                        'emailFormat' => 'html',
                                        'layout'      => 'designed',
                                        'template'    => 'admin_scheduled_email',
                                        'to'          => 'param@strategiclight.com',
                                        'subject'     => "MI Mail Bounced : ",
                                        'viewVars'    => [
                                            'emailContent'   => json_encode($response),
                                            'eooToken'       => "3453",
                                            'mailCheckToken' => 1,
                                            'previewLine'    => "Email Failed",
                                            'newsletterUrl'  => "http://google.com",
                                        ],
                                    ];
                                    $this->EmailManager->sendEmail($options);
                                    $sentEmailIDs[] = $email->id;
                                    $this->ScheduledEmails->updateAll([
                                        'status' => 'ERROR',
                                        'email_content' => $response['message'] ." - - ". ((empty($response['msg'])) ? '' : $response['msg'] )
                                        ], ['id' => $email->id]);
                                
                                } else {
                                    $detail   = [];
                                    $detail[] = $email->to_email;
                                    $sentEmailCount++;
                                    
                                    echo "<br /><b>" . ($i + 1) . ") Email Detail: </b>" . $email->to_email . '<br />';
                                    
                                    $sentEmailIDs[] = $email->id;
                                    
                                    $this->ScheduledEmails->updateAll(['status' => 'Sent'], ['id' => $email->id]);
                                }

                            } catch (\Error $e) {
                                //Error Message will be here
                                $failedEmailCount++;
                                echo "<br /><b>Something Went Wrong, tried to send reminder to: </b>" . $email->to_email;
                                $this->ScheduledEmails->updateAll([
                                    'status' => 'ERROR',
                                    'email_content' => $e->getMessage()
                                ], ['id' => $email->id]);
                            }
                            //$email->status = "Sent";
                            //$this->ScheduledEmails->save($email);
                            
                           
                        }

                        //$this->ScheduledEmails->updateAll(['status' => 'Sent'], ['id IN' => $emailIDs]);

                        echo "Successfully Sent Count: " . $sentEmailCount;
                        echo "Failed Count: " . $failedEmailCount;

                    } else {
                        echo "<h3>No email scheduled.</h3>";
                    }
                } else {
                    echo "<h3>No email scheduled.</h3>";
                }

             
                $isCronRunning->setting_value = "no";
                $this->Settings->save($isCronRunning);
            } else {
                echo $isCronRunning->setting_name;
            }

        } else {
            echo "Campaign Paused by Admin";
        }
        exit;
    }

    public function sendScheduledEmails1($noOfChunks = 1, $chunkNo = 1) {
    // die();
        $this->loadModel('ScheduledEmails');
        $this->loadComponent('EmailManager');

        $this->loadModel('Settings');

        $pauseCampaign = $this->Settings->find('all')->where(['Settings.setting_name' => 'Play Pause Email Campaign'])->first();

        if ($pauseCampaign->setting_value == "play") {
            
            $isCronRunning = $this->Settings->find('all')->where(['Settings.setting_name' => 'Cron ' . $chunkNo . ' of ' . $noOfChunks.' is running'])->first();

            if (empty($isCronRunning)) {
                $isCronRunning                = $this->Settings->newEntity();
                $isCronRunning->setting_name  = 'Cron ' . $chunkNo . ' of ' . $noOfChunks.' is running';
                $isCronRunning->setting_value = 'no';
                $isCronRunning->default_value = 'no';
                $this->Settings->save($isCronRunning);
            }


            if ($isCronRunning->setting_value == "no") {

                $isCronRunning->setting_value = "yes";
                $this->Settings->save($isCronRunning);

            
            

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
                
            $this->loadModel('EmailTemplates');
                
                $emailTemplate = $this->EmailTemplates->find()->where(['EmailTemplates.id'=>$scheduledEmail->email_template_id])->first();

                $emails = $this->ScheduledEmails->find('all')
                    ->contain(
                        ['Users']
                    )
                    ->where([
                        'ScheduledEmails.status'          => "Pending",
                        //'ScheduledEmails.send_after_type' => "Immediate",
                        'ScheduledEmails.send_at <= "' . date(SQL_DATETIME) . '"',
                        'ScheduledEmails.id >='           => $betweenMinId,
                        'ScheduledEmails.id <='           => $betweenMaxId,
                    ])
                    ->limit(1000)
                    //->order(["Users.first_name" => "ASC"]) 
                    ->all();


                if (!empty($emails->toArray())) {

                    $sentEmailCount = 0;
                    $failedEmailCount = 0;

                    $this->loadModel('Users');

                    $url = SITE_URL . "sign-in";

                    //$stopEmail = false;
                    $emailIDs = [];
                    foreach ($emails as $i => $email) {

                        //$referralUrl = AFFILIATE_URL . $email->user->reference_token;

                        $referralUrl = AFFILIATE_URL . $email->user->reference_token;

                        $referralUrl = DASHBOARD_URL . $email->user->reference_token;
                        $affiliateUrl = AFFILIATE_URL . $email->user->reference_token;
                        $emailContent = str_replace("[AFFILIATE_URL]", $affiliateUrl, $emailTemplate->template);
                        $emailContent = str_replace("[REFERRAL_URL]", $referralUrl, $emailContent);

                        $options = [
                            'emailFormat' => 'html',
                            'layout'      => 'designed',
                            'template'    => 'admin_scheduled_email',
                            'to'          => EMAIL_TEST_MODE ? ADMIN_EMAIL : $email->to_email,
                            'subject'     => $emailTemplate->subject,
                            'viewVars'    => [
                                'emailContent'   => $emailContent,
                                'eooToken'       => base64_encode($email->to_email),
                                'mailCheckToken' => $email->id,
                                'previewLine'    => $emailTemplate->preview_line,
                                'newsletterUrl'  => $emailTemplate->newsletter_url,
                            ]
                        ];

                        try {
                            //$this->EmailManager->sendEmail($options);
                        //     $detail = [];
                        //     $detail[] = $email->to_email;

                        //     $sentEmailCount++;

                        //     echo "<br /><b>" . ($i + 1) . ") Email Detail: </b>" . $email->to_email . '<br />';
                        // } catch (\Error $e) {
                        //     //Error Message will be here
                        //     $failedEmailCount++;
                        //     echo "<br /><b>Something Went Wrong, tried to send reminder to: </b>" . $email->to_email;
                        // }
                        
                        $response = $this->EmailManager->sendEmail($options);
                            if ($response['error']) {

                                $failedEmailCount++;
                                echo "<br /><b>Something Went Wrong, tried to send reminder to: </b>" . $email->to_email;

                                $options = [
                                    'emailFormat' => 'html',
                                    'layout'      => 'designed',
                                    'template'    => 'admin_scheduled_email',
                                    'to'          => 'satinder@strategiclight.com',
                                    'subject'     => "MI Mail Bounced - ",
                                    'viewVars'    => [
                                        'emailContent'   => json_encode($response),
                                        'eooToken'       => "3453",
                                        'mailCheckToken' => 1,
                                        'previewLine'    => "Email Failed",
                                        'newsletterUrl'  => "http://google.com",
                                    ],
                                ];
                                $this->EmailManager->sendEmail($options);
                                $emailIDs[] = $email->id;
                            } else {
                                $detail   = [];
                                $detail[] = $email->to_email;
                                $emailIDs[] = $email->id;
                                $sentEmailCount++;
                                echo "<br /><b>" . ($i + 1) . ") Email Detail: </b>" . $email->to_email . '<br />';
                            }
                        } catch (\Error $e) {
                            //Error Message will be here
                            $failedEmailCount++;
                            $emailIDs[] = $email->id;
                            echo "<br /><b>Something Went Wrong, tried to send reminder to: </b>" . $email->to_email;
                        }
                        //$email->status = "Sent";
                        //$this->ScheduledEmails->save($email);
                    }
                    
                    if(!empty($emailIDs)){
                        $this->ScheduledEmails->updateAll(['status'=>'Sent'], ['id IN'=>$emailIDs]);
                    }

                    echo "Successfully Sent Count: " . $sentEmailCount;
                    echo "Failed Count: " . $failedEmailCount;
                } else {
                    echo "<h3>No email scheduled.</h3>";
                }
                
             
                
            } else {
                echo "<h3>No email scheduled.</h3>";
            }
            
                   $isCronRunning->setting_value = "no";
                    $this->Settings->save($isCronRunning);
                } else {
                    echo $isCronRunning->setting_name;
                }
            
        } else {
            echo "Campaign Paused by Admin";
        }
        exit;
    }
    
    public function testTime(){
        
        //date_default_timezone_set("America/New_York");
        date_default_timezone_set("America/Denver");
        
        echo  date(SQL_DATETIME);
        exit;
    }
    /**
     * Function Name: deletePreviousData
     * Query Paramters: $days
     * return null
     * */
    public function deletePreviousData($days = 365){
		$this->loadModel('ScheduledEmails');
		$flag = true;
		if($flag){	
			$time = \Cake\I18n\Time::now();
			$time->modify('-'.$days.' days');
			
			$this->ScheduledEmails->deleteAll([       
			   'ScheduledEmails.status'=>'Sent',
			   'ScheduledEmails.send_at <=' => $time->format('Y-m-d H:i:s')
			]);
			
			echo $days." days old email data has been deleted sucessfully";
		}else{
			echo $days." days old email data not deleted";
		}
        exit;
    }
    
    public function getreveue(){
        $url = "https://www.getrevue.co/api/v2/subscribers";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
        "X-Custom-Header: value",
        "Content-Type: application/json",
        "Authorization: Token tzRmLjg9FwNu6AI5tV0ElrjPhUVLQMFE"
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        $this->register($resp);
        //print_r($resp);die;
        // curl_close($curl);
       // var_dump($resp);

    }
    
    
    public function register($resp)
    {
        //die('okok');
       
       // print_r($resp);
       // die('ff');
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;
        $ip = $this->getIp();
        $results = json_decode($resp);
        //print_r($results);die;
        foreach($results as $result){
   
            $this->request->data['email'] = $result->email;
            $this->request->data['first_name'] = $result->first_name;
            $this->request->data['last_name'] = $result->last_name;
            //print_r($this->request->getData());die;
        $user = $this->Users->find('all')->where(['Users.email' => $this->request->getData('email')])->first();
       // print_r($user);die;
        if (empty($user)) {
           // die('fff');
            $user = $this->Users->newEntity();
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->password = "Test@123";
            $user->reference_token = $this->encryptToken();
            $user->active = true;
            $user->ip = $ip;
            $user->opt_out = true;
            if ($this->getRequest()->getSession()->check('referredBy')) {
                $user->affiliated_by = $this->getRequest()->getSession()->read('referredBy');
                $this->getRequest()->getSession()->delete('referredBy');
            }
            if ($this->Users->save($user)) {
                $user = $this->Users->find('all')->where(['Users.id' => $user['id']])->first();
                $this->Auth->setUser($user);
                $this->loadComponent('EmailManager');
                $welcomeOptions = [
                    'template' => 'welcome',
                    'layout' => 'undesigned',
                    'to' => $user->email,
                    'subject' => "[IMPORTANT] Morning Invest Newsletter",
                    'viewVars' => [
                        'email' => $user->email,
                        'token' => $user->reference_token,
                        'eooToken' => base64_encode($user->email),
                        'mailCheckToken' => base64_encode($user->id),
                    ],
                ];
                $this->EmailManager->sendEmail($welcomeOptions);
                //Check on IP bases
                $ipCount = $this->Users->find('all')->where([
                        'Users.ip' => $ip,
                        'Users.created >= DATE_SUB(NOW(),INTERVAL 1 HOUR)',
                ])->count();
                    if ($ipCount >= 3) {
                        $options = [
                            'template' => 'fake_email',
                            'to' => ADMIN_EMAIL,
                            'subject' => "Alert - Fake Emails - " . SITE_TITLE,
                            'viewVars' => [
                                'email' => $user->email,
                                'reason' => 'ip',
                                'ip' => $ip,
                            ],
                        ];
                    $this->EmailManager->sendEmail($options);
                }

                    //Check on Email Domain Base
                    $domain = explode("@", $user->email)[1];
                    $domainCount = $this->Users->find('all')->where([
                        'Users.email' => '%' . $domain . '%',
                        'Users.created >= DATE_SUB(NOW(),INTERVAL 1 HOUR)',
                    ])->count();

                    if ($domainCount >= 3) {
                        $options = [
                            'template' => 'fake_email',
                            'to' => ADMIN_EMAIL,
                            'subject' => "Alert - Fake Emails - " . SITE_TITLE,
                            'viewVars' => [
                                'email' => $user->email,
                                'reason' => 'domain',
                                'ip' => $ip,
                                'domain' => $domain,
                            ],
                        ];

                        $this->EmailManager->sendEmail($options);
                    }

                    try {
                        //To Subscribe New User
                        // $this->mcSubscribe($user->id);
                        if ($user->affiliated_by != 0) {
                            $affiliateUser = $this->Users->find('all')->where(['Users.id' => $user->affiliated_by])->first();
                            //To Notify Affiliate User
                            $result = $this->maxReferralRequired($affiliateUser->no_of_affiliates);
                            $options = [
                                'template' => 'new_affiliation',
                                'to' => EMAIL_TEST_MODE ? ADMIN_EMAIL : $affiliateUser->email,
                                'subject' => "Congratulations! - " . SITE_TITLE,
                                'viewVars' => [
                                    'url' => DASHBOARD_URL . $affiliateUser->reference_token,
                                    'maxReferralRequired' => $result['maxReferralRequired'] - $affiliateUser->no_of_affiliates,
                                    'reward' => $result['reward'],
                                    'referralEmail' => $user->email,
                                ],
                            ];
                            $this->loadModel('UserRewards');
                            switch ($affiliateUser->no_of_affiliates) {
                                case 3:
                                    {
                                        //To Subscribe for Newsletter to  Affiliate User
                                        $options['template'] = "newsletter_subscribed";
                                        $options['subject'] = "Congratulations! You get Newsletter Subscription. - " . SITE_TITLE;
                                        //$this->mcSubscribe($affiliateUser->id, 'newsletter');
                                        $userReward = $this->UserRewards->newEntity();
                                        $userReward->user_id = $affiliateUser->id;
                                        $userReward->reward_id = 1;
                                        $userReward->delivery_status = "Delivered";
                                        $userReward->status = 1;
                                        $userReward->delivered_at = $user->modified;
                                        $userReward->will_deliver_by = $user->modified;
                                        $this->UserRewards->save($userReward);
                                        break;
                                    }
                                case 5:
                                    {
                                        //Affiliate User get First Physical Reward
                                        $options['template'] = "first_physical_reward";
                                        $options['subject'] = "Congratulations! You achieved your " . $result['reward'] . " reward. - " . SITE_TITLE;
                                        $userReward = $this->UserRewards->newEntity();
                                        $userReward->user_id = $affiliateUser->id;
                                        $userReward->reward_id = 2;
                                        $userReward->delivery_status = "Pending";
                                        $userReward->status = 1;
                                        $userReward->delivered_at = $user->modified;
                                        $userReward->will_deliver_by = $user->modified;
                                        $this->UserRewards->save($userReward);
                                        //$this->mcSubscribe($affiliateUser->id, '5-referral-rewards');
                                        break;
                                    }
                                case 10:
                                    {
                                        //Affiliate User get First Physical Reward
                                        $options['template'] = "first_physical_reward";
                                        $options['subject'] = "Congratulations! You achieved your " . $result['reward'] . " reward. - " . SITE_TITLE;
                                        //$this->mcSubscribe($affiliateUser->id, '5-referral-rewards');
                                        $userReward = $this->UserRewards->newEntity();
                                        $userReward->user_id = $affiliateUser->id;
                                        $userReward->reward_id = 3;
                                        $userReward->delivery_status = "Pending";
                                        $userReward->status = 1;
                                        $userReward->delivered_at = $user->modified;
                                        $userReward->will_deliver_by = $user->modified;
                                        $this->UserRewards->save($userReward);
                                        break;
                                    }
                                default:
                                    {
                                        $options['template'] = "new_affiliation";
                                        $options['subject'] = "Congratulations! - " . SITE_TITLE;
                                        break;
                                    }
                            }
                            $this->EmailManager->sendEmail($options);
                        }

                    } catch (\Exception $e) {
                        //Do something
                    }
                    $this->responseCode = SUCCESS_CODE;
                    $this->responseMessage = 'Successfully Registered.';
                } else {
                    if ($user->hasErrors()) {
                        foreach ($user->getErrors() as $errors) {
                            foreach ($errors as $err) {
                                $error = $err;
                            }
                        }
                    }
                    if (empty($error)) {
                        $this->responseMessage = 'The user could not be saved. Please, try again.';
                    } else {
                        $this->responseMessage = $error;
                    }
                }
            }
        }
       // $this->responseCode = SUCCESS_CODE;
        //$this->responseMessage = 'Successfully Opt In.';
            
        //print_r($this->responseFormat());die;
        echo $this->responseFormat();
        exit;
    }
    
    public function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    private function encryptToken($strlen = 10)
    {
        $str = '0123456789' . uniqid() . 'abcdefghijklmnopqrstuvwxyz' . uniqid();

        return substr(str_shuffle($str), 0, $strlen);
    }
    
     public function maxReferralRequired($noOfAffiliates)
    {
        $this->loadModel('Rewards');
        $rewards = $this->Rewards->find('all')->where(['Rewards.status' => true])->all();

        $maxReferralRequired = 0;

        $product = "";

        foreach ($rewards as $r) {
            if (($noOfAffiliates < $r->no_of_affiliate_required) && $maxReferralRequired <= 0) {
                $maxReferralRequired = $r->no_of_affiliate_required;
            }

            if (($noOfAffiliates <= $r->no_of_affiliate_required) && empty($product)) {
                $product = $r->name;
            }
        }

        return ['maxReferralRequired' => $maxReferralRequired, 'reward' => $product];
    }


    public function loadTemplatepages()
    {
        $id = 40;
        $this->loadModel('EmailTemplates');
        // $emailTemplate = $this->EmailTemplates->get($id, [
        //     'contain' => []
        // ]);
       //pr($this->request->query());die();
        $emailTemp = $this->EmailTemplates->find()->where(['id'=> $id])->first();
        $alert = 0;
        // if($emailTemp['is_open'] == 1){
        //     if(!($this->request->query('overtake'))){
        //         return $this->redirect(['action' => 'index','overtake'=>1]);    
        //     }
        // }

      // echo $emailTemp->template;die;

       //$data =array('id'=>'1','name'=>'jasss','slug'=>'sdfsdfsd');
       $data[] = ['_id'=>'1',
       'name'=>'jasss',
       'slug'=>'sdfsdfsd'
       ];
       $data[] = ['_id'=>'2',
       'name'=>'jasssss',
       'slug'=>'sdfsdfsdv'
       ];
      $rrr = $data;
                
       //print_r($rrr);die;
       echo json_encode($rrr);die;


    }
    
    public function loadTemplatepagescontent($id='')
    {
       // die('okkeeecc');
        //exit;
        //$this->autoRender = false;
        // $this->loadComponent('RequestHandler', [
        //     'enableBeforeRedirect' => true,
        // ]);
       $this->loadModel('EmailTemplates');
        //$id = 40;
       if ($this->request->is(['post'])) {
        //die('ddd');
       // echo $this->request->getData();die;
       // pr(json_decode(json_encode($this->request->getData())));die;
            $this->EmailTemplates->updateAll(['template_json'=>json_encode($this->request->getData())], ['id'=>$id]);
          echo 'saved';die;
        }else{
        $emailTemp = $this->EmailTemplates->find()->where(['id'=> $id])->first();
        $alert = 0;
     
  //  echo $emailTemp->template_json;die;
  $html = $this->replaceclass($emailTemp->template);
       echo $html;die;
    //  echo $emailTemp->template;die;
      
       //echo htmlspecialchars($emailTemp->template);die;
       //echo "<html><body><title>hello</title></body></html>";die;

       //$data =array('id'=>'1','name'=>'jasss','slug'=>'sdfsdfsd');
       $data[] = ['_id'=>'1',
       'name'=>'jasss',
       'slug'=>'sdfsdfsd'
       ];
       $data[] = ['_id'=>'2',
       'name'=>'jasssss',
       'slug'=>'sdfsdfsdv'
       ];
      $rrr = $data;
                
       //print_r($rrr);die;
       echo json_encode($rrr);die;
    }


    }
    
    public function replaceclass($html) {
        return preg_replace_callback('/class="([^"]+)"/', function($m) {
    
        if(strpos($m[1], "card") !== false) {
            $m[0] = preg_replace("/\bcard\b/",'card1',$m[0],1);
            }
    
        if(strpos($m[1], "content-border") !== false) {
        $m[0] = preg_replace("/\s*content-border\s*/",'product-content-border',$m[0],1);
            }
    
    // add as many if conditions with class replacement names as you like
    // if(strpos($m[1], "class-name") !== false) {
    //     $m[0] = preg_replace("/\s*class-name\s*/",'new-class-name',$m[0],1);
    //      }
    // add as many if conditions with class replacement names as you like
    
        return $m[0];
    
           }, $html);
    }
    
    public function saveHtml()
    {
      //die('lll');
        $this->autoRender = false;
        $this->loadModel('EmailTemplates');
        if ($this->request->is(['post'])) {
            $html = $this->request->getData('html');
            $html=preg_replace('/id=".*?"/', '', $html);
            $error =  $this->EmailTemplates->updateAll(['template'=>$html],['id'=>$this->request->getData('template_id')]);
            echo 'saved';die;
        }
    }
    
    public function loadTemplateassets()
    {
        //die('ddd');
        $id = 40;
        $this->loadModel('EmailTemplates');
        // $emailTemplate = $this->EmailTemplates->get($id, [
        //     'contain' => []
        // ]);
       //pr($this->request->query());die();
        $emailTemp = $this->EmailTemplates->find()->where(['id'=> $id])->first();
        $alert = 0;
        // if($emailTemp['is_open'] == 1){
        //     if(!($this->request->query('overtake'))){
        //         return $this->redirect(['action' => 'index','overtake'=>1]);    
        //     }
        // }
        die;
      //  echo $emailTemp->template;die;
      // echo htmlspecialchars($emailTemp->template);die;

       //$data =array('id'=>'1','name'=>'jasss','slug'=>'sdfsdfsd');
       $data[] = ['_id'=>'1',
       'name'=>'jasss',
       'slug'=>'sdfsdfsd'
       ];
       $data[] = ['_id'=>'2',
       'name'=>'jasssss',
       'slug'=>'sdfsdfsdv'
       ];
      $rrr = $data;
                
       //print_r($rrr);die;
       echo json_encode($rrr);die;


    }
     
}

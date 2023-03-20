<?php

namespace App\Controller;

use \DrewM\MailChimp\MailChimp;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow([
            'index',
            'register',
            'wpRegister',
            'login',
            'home',
            'isUniqueEmail',
            'referralR',
            'referral',
            'shareViaEmail',
            'importCsv',
            'fixUser',
            'sendTest',
            'verify',
            'thankYou',
            'live',
            'updatedb',
            'saveLead',
            'syncUsers'
        ]);

    }

    public function index()
    {
        if ($this->Auth->user()) {
            return $this->redirect(['controller' => 'Users', 'action' => 'daily']);
        }

        return $this->redirect(['controller' => 'Users', 'action' => 'home']);

    }

    public function home()
    {

        $referredEmail = "";
        if ($this->getRequest()->getSession()->check('referredEmail')) {
            $referredEmail = $this->getRequest()->getSession()->read('referredEmail');
            $referredEmail = base64_decode($referredEmail);
        }

        $this->set('referredEmail', $referredEmail);
    }

    public function referral($token = null)
    {
        $this->loadModel('Rewards');
        $rewards = $this->Rewards->find()->contain(['Images'])->where(['Rewards.status' => true])->all();

        $this->set('rewards', $rewards);
        if ($this->getRequest()->getSession()->check('referredBy')) {
            $this->getRequest()->getSession()->delete('referredBy');
        }

        if ($this->getRequest()->getSession()->check('referredEmail')) {
            $this->getRequest()->getSession()->delete('referredEmail');
        }

        if ($token != null) {

            $user = $this->Users->find()
                ->where(['reference_token' => $token])
                ->first();
            if (!empty($user)) {
                $this->Auth->setUser($user);

                if (
                    $user['no_of_affiliates'] >= 5 && (
                        empty($user['first_name'])
                        || empty($user['address'])
                        || empty($user['city'])
                        || empty($user['zip'])
                    )) {
                    $this->loadModel('Countries');
                    $countries = $this->Countries->find('list')->all()->toArray();
                    $this->set('countries', $countries);
                }

                $this->set('authUser', $user);
            }
        }

    }

    public function logout()
    {
        $this->Cookie->delete('loggedInUser');
        $this->Flash->success(__('You are now logged out'));
        return $this->redirect($this->Auth->logout());
    }

    public function login($token = null)
    {
        //if already logged-in, redirect
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }

        if ($token != null) {

            $user = $this->Users->find()
                ->where(['reference_token' => $token])
                ->first();
            if (empty($user)) {
                $this->Flash->error(__('Invalid login token, try again'));
            } else {
                $this->Auth->setUser($user);
                return $this->redirect(['controller' => 'Users', 'action' => 'refrerral', $user->reference_token]);
            }
        }
    }

    public function referralR($token = null, $email = null)
    {
        //if already logged-in, redirect

        if ($token != null) {

            if ($email != null) {
                $this->getRequest()->getSession()->write('referredEmail', $email);
            }

            $user = $this->Users->find()
                ->where(['reference_token' => $token])
                ->first();
            if (empty($user)) {
                //Do Nothing
            } else {
                $this->getRequest()->getSession()->write('referredBy', $user->id);
                return $this->redirect(['controller' => 'Users', 'action' => 'home']);
            }
        }
    }

    public function register()
    {
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;

        if ($this->request->is('post')) {

            // // Build POST request:
            // $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            // $recaptcha_secret = '6LdntOIUAAAAADic98S0-z9JSj9VUrf2U-n2C77V';
            // $recaptcha_response = $_POST['recaptcha_response'];

            // // Make and decode POST request:
            // $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
            // $recaptcha = json_decode($recaptcha);

            // // Take action based on the score returned:
            // if ($recaptcha->score >= 0.5) {
            // Verified - send email
            $ip = $this->getIp();
            $user = $this->Users->find('all')->where(['Users.email' => $this->request->getData('email')])->first();

            if (empty($user)) {

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
                        'subject' => "[IMPORTANT] Redacted Newsletter",
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

            } else {
                $user->opt_out = false;
                $this->Users->save($user);

                $this->responseCode = SUCCESS_CODE;

                $this->responseMessage = 'Successfully Opt In.';
            }

            // } else {
            //     $this->responseMessage = 'Incorrect Recaptcha.';
            // }
        }

        echo $this->responseFormat();
        exit;
    }

    public function wpRegister()
    {
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;

        if ($this->request->is('post')) {

            // Verified - send email
            if (empty($this->request->getData('IP'))) {
                $ip = $this->getIp();
            } else {
                $ip = $this->request->getData('IP');
            }

            $user = $this->Users->find('all')->where(['Users.email' => $this->request->getData('email')])->first();

            if (empty($user)) {
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
                    $this->syndatabase($user); 
                    $user = $this->Users->find('all')->where(['Users.id' => $user['id']])->first();
                    $this->Auth->setUser($user);

                    $this->loadComponent('EmailManager');

                    $welcomeOptions = [
                        'template' => 'welcome',
                        'layout' => 'undesigned',
                        'to' => EMAIL_TEST_MODE ? ADMIN_EMAIL : $user->email,
                        'subject' => "Redacted Verification Email [IMPORTANT]",
                        'viewVars' => [
                            'email' => $user->email,
                            'eooToken' => base64_encode($user->email),
                            'mailCheckToken' => base64_encode($user->id),
                            'token' => $user->reference_token,
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
                    $options = [
                        'template' => 'new_user',
                        'to' => "param@strategiclight.com",
                        'subject' => "New User - " . SITE_TITLE,
                        'viewVars' => [
                            'email' => $user->email,
                        ],
                    ];

                    $this->EmailManager->sendEmail($options);
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
                        //$this->mcSubscribe($user->id);
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
                                        //$this->mcSubscribe($affiliateUser->id, '5-referral-rewards');
                                        $userReward = $this->UserRewards->newEntity();

                                        $userReward->user_id = $affiliateUser->id;
                                        $userReward->reward_id = 2;
                                        $userReward->delivery_status = "Pending";
                                        $userReward->status = 1;
                                        $userReward->delivered_at = $user->modified;
                                        $userReward->will_deliver_by = $user->modified;

                                        $this->UserRewards->save($userReward);
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
            } else {
                $user->opt_out = false;
                $this->Users->save($user);

                $this->responseCode = SUCCESS_CODE;

                $this->responseMessage = 'Successfully Opt In.';
            }
        }

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

    public function isUniqueEmail($id = null)
    {
        $this->autoRender = false;
        if ($id === null) {
            $email = $this->request->getQuery('email');
            if ($this->Users->find('all')->where(['Users.email' => $email, 'Users.opt_out' => false])->count()) {
                $alreadyExists = "false";
            } else {
                $alreadyExists = "true";
            }
        } else {
            $count = $this->Users->find()
                ->where(['id !=' => $id, 'email' => $this->request->getQuery('email')])
                ->count();
            if ($count) {
                $alreadyExists = "false";
            } else {
                $alreadyExists = "true";
            }
        }
        echo $alreadyExists;
        exit;
    }

    public function mcSubscribe($userId, $slug = "new-subscription")
    {
        $this->loadModel('McDetails');

        $mc = $this->McDetails->find()->where(['slug' => $slug])->first();
        $user = $this->Users->find()->where(['id' => $userId])->first();
        $mcFields = json_decode($mc->merged_fields_json, true);
        $mcMergeFields = json_decode($mc->mc_merge_fields, true);

        $MailChimp = new MailChimp($mc->api_key);

        $finalFields = [];
        $mergeFields = [];

        foreach ($mcMergeFields as $field) {
            $mergeFields[$field['tag']] = ($field['type'] == "text") ? "NA" : 1;
        }

        foreach ($mcFields as $field => $match) {
            switch ($field) {
                case "email":
                    {
                        $mergeFields[$match] = $user->email;
                        break;
                    }
                case "affiliate_url":
                    {
                        $mergeFields[$match] = AFFILIATE_URL . $user->reference_token;
                        break;
                    }
                case "dashboard_url":
                    {
                        $mergeFields[$match] = DASHBOARD_URL . $user->reference_token;
                        break;
                    }

                case "first_name":
                    {
                        $mergeFields[$match] = empty($user->first_name) ? "NA" : $user->first_name;
                        break;
                    }
                case "last_name":
                    {
                        $mergeFields[$match] = empty($user->last_name) ? "NA" : $user->last_name;
                        break;
                    }
                case "address":
                    {
                        $mergeFields[$match] = empty($user->address) ? "NA" : $user->address;
                        break;
                    }
                case "city":
                    {
                        $mergeFields[$match] = empty($user->city) ? "NA" : $user->city;
                        break;
                    }
                case "state":
                    {
                        $mergeFields[$match] = empty($user->state) ? "NA" : $user->state;
                        break;
                    }
                case "zip":
                    {
                        $mergeFields[$match] = empty($user->zip) ? "NA" : $user->zip;
                        break;
                    }
                case "country":
                    {
                        $mergeFields[$match] = empty($user->country) ? "NA" : $user->country;
                        break;
                    }
            }
        }

        $finalFields['status'] = "subscribed";
        $finalFields['email_address'] = $user->email;
        $finalFields['merge_fields'] = $mergeFields;

        $result = $MailChimp->post("lists/" . $mc->list_id . "/members", $finalFields);

        return true;
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

    public function shareViaEmail()
    {
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;

        $user = $this->Users->find()->where(['reference_token' => $this->request->getData('token')])->first();

        $shareWith = $this->request->getData('share_with');

        $rawEmails = explode(",", $shareWith);

        $emails = [];

        foreach ($rawEmails as $rawEmail) {
            $email = trim($rawEmail);

            if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
                $emails[] = $email;
            }
        }

        if (!empty($emails)) {

            $this->loadComponent('EmailManager');
            foreach ($emails as $email) {
                try {
                    $options = [
                        'template' => 'share',
                        'to' => EMAIL_TEST_MODE ? ADMIN_EMAIL : $email,
                        'subject' => SITE_TITLE . " - Invitation",
                        'viewVars' => [
                            'url' => AFFILIATE_URL . $user->reference_token . "/" . base64_encode($email),
                            'message' => $this->request->getData('message'),
                            'email' => $user->email,
                        ],
                    ];

                    $this->EmailManager->sendEmail($options);
                    $this->responseCode = SUCCESS_CODE;
                    $this->responseMessage = "Thanks for spreading the word about the " . SITE_TITLE . ". Once they confirm their email you'll receive credit!";
                } catch (\Exception $e) {
                    $this->responseMessage = $e->getMessage();
                }
            }
        }

        echo $this->responseFormat();
        exit;
    }

    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('You detail has been saved.'));

                $this->mcSubscribe($user->id, '5-referral-rewards');

                return $this->redirect(['action' => 'referral', $user->reference_token]);
            }
            $this->Flash->error(__('You detail could not be saved. Please, try again.'));
        }
    }

    public function importCsv($file = "subscribed")
    {

        $this->loadComponent('EmailManager');

        $users = [];
        if (($handle = fopen($file . ".csv", "r")) !== false) {
            $row = 0;
            while (($data = fgetcsv($handle, 10000, ",")) !== false) {
                if ($row > 0) {

                    $token = "";

                    if (!empty($data[5])) {
                        $token = explode(AFFILIATE_URL, $data[5])[1];
                    }
                    $users[] =
                        [
                        'email' => $data[0],
                        'token' => $token,
                    ];
                }
                $row++;
            }
            fclose($handle);
        }

        foreach ($users as $user) {

            $user = $this->Users->find()->where(['email' => $user['email']])->first();
            if (empty($user)) {
                $user = $this->Users->newEntity();
                $user->email = $user['email'];
                $user->password = "Test@123";
                $user->reference_token = empty($user['token']) ? $this->encryptToken() : $user['token'];
                $user->active = true;
                if ($file == "unsubscribed") {
                    $user->optOut = true;
                }
                $this->Users->save($user);

                $options = [
                    'template' => 'welcome',
                    'layout' => 'undesigned',
                    'to' => (EMAIL_TEST_MODE) ? ADMIN_EMAIL : $user->email,
                    'subject' => "[IMPORTANT] Redacted Newsletter",
                    'viewVars' => [
                        'email' => ADMIN_EMAIL,
                    ],
                ];

                $this->EmailManager->sendEmail($options);
            }
        }
        exit;
    }

    public function fixUser($email = "satinder@strategiclight.com")
    {

        $this->loadModel('McDetails');
        $mc = $this->McDetails->find()->where(['slug' => 'new-subscription'])->first();
        $MailChimp = new MailChimp($mc->api_key);

        $user = $this->Users->find()->where(['email' => $email])->first();
        if (empty($user)) {
            $user = $this->Users->newEntity();
            $user->email = $email;
            $user->password = "Test@123";
            $user->reference_token = $this->encryptToken();
            $user->active = true;
            $this->Users->save($user);
        }

        $subscriberHash = MailChimp::subscriberHash($email);

        $result = $MailChimp->patch("lists/" . $mc->list_id . "/members/$subscriberHash", [
            'merge_fields' => [
                'MMERGE5' => AFFILIATE_URL . $user->reference_token,
                'MMERGE6' => DASHBOARD_URL . $user->reference_token,
            ],
        ]);

        if ($result['status'] == "subscribed") {

            echo $email . " Updated";
        }

        exit;
    }

    public function sendTest()
    {
        $this->loadComponent('EmailManager');

        $options = [
            'template' => 'welcome',
            'layout' => 'undesigned',
            'to' => ADMIN_EMAIL,
            'subject' => "[IMPORTANT] Redacted Newsletter",
            'viewVars' => [
                'email' => ADMIN_EMAIL,
            ],
        ];
        pr($options);

        $this->EmailManager->sendEmail($options);

        echo "Sent";
        exit;
    }

    public function verify($token = null)
    {
        //if already logged-in, redirect

        if ($token != null) {

            $user = $this->Users->find()
                ->where(['reference_token' => $token])
                ->first();
            if (empty($user)) {
                //Do Nothing
                $this->Flash->error(__('Email address does not exists.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'home']);
            } else {
                if($user->opt_out != 1){
                    return $this->redirect('https://redacted.inc/thanks/');
                }
                $user->opt_out = false;
                $this->Users->save($user);
                $gg = $this->syndatabase($user); 
                $this->loadComponent('EmailManager');

                $welcomeOptions = [
                    'template' => 'welcome_email',
                    'layout' => 'undesigned',
                    'to' => EMAIL_TEST_MODE ? ADMIN_EMAIL : $user->email,
                    'subject' => "Redacted Newsletter",
                    'viewVars' => [
                        'email' => $user->email,
                        'eooToken' => base64_encode($user->email),
                        'mailCheckToken' => base64_encode($user->id),
                        'token' => $user->reference_token,
                    ],
                ];

                $this->EmailManager->sendEmail($welcomeOptions);
                $this->Flash->success(__('Thank You. Your email address is now verified.'));
                //return $this->redirect(['controller' => 'Users', 'action' => 'thankYou']);
                return $this->redirect('https://redacted.inc/thanks/');
            }
        }
    }

    public function thankYou()
    {

        return $this->redirect('https://redacted.inc/thanks/');
        $this->viewBuilder()->setLayout('ajax');

        if ($token != null) {

            $user = $this->Users->find()
                ->where(['reference_token' => $token])
                ->first();
            if (empty($user)) {
                //Do Nothing
                $this->Flash->error(__('Email address does not exists.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'home']);
            } else {
                $this->loadComponent('EmailManager');

                $welcomeOptions = [
                    'template' => 'welcome_email',
                    'layout' => 'undesigned',
                    'to' => EMAIL_TEST_MODE ? ADMIN_EMAIL : $user->email,
                    'subject' => "Redacted Newsletter",
                    'viewVars' => [
                        'email' => $user->email,
                        'eooToken' => base64_encode($user->email),
                        'mailCheckToken' => base64_encode($user->id),
                        'token' => $user->reference_token,
                    ],
                ];

                $this->EmailManager->sendEmail($welcomeOptions);

                $user->opt_out = false;
                $this->Users->save($user);
                $this->Flash->success(__('Thank You. Your email address is now verified.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'home']);
            }
        }
    }
    
    // public function syndatabase($user){
    //   // pr($user);die;
    //   $userchk = $this->Users->find('all')->where(['Users.id'=>$user->affiliated_by])->first();
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL,SITE_URL_MORINVEST."/users/updatedb");
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS,"email=".$user->email."&IP=".$user->ip."&password=".$user->password."&reference_token=".$user->reference_token."&opt_out=".$user->opt_out."&affiliated_by=".$userchk->reference_token);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     $serverOutput = curl_exec($ch);
    //     curl_close ($ch);
    //     return $serverOutput;
    // }
    
    public function syndatabase($user){
        // pr($user);die;
       // return 1;
        $this->loadModel('Users');
        $userchk = $this->Users->find('all')->where(['Users.id'=>$user->affiliated_by])->first();
        //pr($userchk);die;
         $lead_from = !empty(@$user->lead_from) ? @$user->lead_from : 'native';
        $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL,SITE_URL_MORINVEST."/users/updatedb");
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS,"email=".$user->email."&IP=".$user->ip."&password=".$user->password."&reference_token=".@$user->reference_token."&opt_out=".$user->opt_out."&affiliated_by=".@$userchk->reference_token."&action=".$user->action."&campaign=".@$user->campaign."&lead_from=".$lead_from);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $serverOutput = curl_exec($ch);
         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         if($httpCode != 200) {
             return 0;
         }
         curl_close ($ch);
         return $serverOutput;
     }
    
    public function updatedb_old1()
    {
        
        $this->autoRender = false;
            $userchk = $this->Users->find('all')->where(['Users.email'=>$this->request->getData('email')])->first();
            if($this->request->getData('action') == 'delete'){
                $this->Users->delete($userchk);
                return 'deleted';
            }
            if (empty($userchk)) {
                $affiliated_by = $this->Users->find('all')->where(['Users.reference_token'=>$this->request->getData('affiliated_by')])->first();
                $usersave = $this->Users->newEntity();
                $usersave = $this->Users->patchEntity($usersave, $this->request->getData());
                $usersave->active = 1;
                $usersave->affiliated_by = !empty($affiliated_by->id) ? $affiliated_by->id : 0;
            $errp = $this->Users->save($usersave);
            // echo json_encode($errp);die;
                //die;
           }
         else{
        $userchk->opt_out = !empty($this->request->getData('opt_out')) ? $this->request->getData('opt_out') : 0;
          $this->Users->save($userchk);
           //  die('lll');
        }
    }
    
    public function updatedb()
    {
       // die('jjj');
        $this->autoRender = false;
       // die('vvvv');
        //if(empty($this->request->getData('update_opt_out_status'))){
            $userchk = $this->Users->find('all')->where(['Users.email'=>$this->request->getData('email')])->first();
            if($this->request->getData('action') == 'delete'){
                $this->Users->delete($userchk);
                return 'deleted';
            }
            if($this->request->getData('action') == 'unsubscribe'){
                $userchk->opt_out = !empty($this->request->getData('opt_out')) ? $this->request->getData('opt_out') : 0;
                $this->Users->save($userchk);
                return 'unsubscribed';
            }
            if (empty($userchk)) {
                $affiliated_by = $this->Users->find('all')->where(['Users.reference_token'=>$this->request->getData('affiliated_by')])->first();
                //pr($affiliated_by);die;
                $usersave = $this->Users->newEntity();
                $usersave = $this->Users->patchEntity($usersave, $this->request->getData());
                // $usersave->email = $this->request->getData('email');
                // $usersave->ip = $this->request->getData('IP');
                // $usersave->password = $this->request->getData('password');
                // $usersave->reference_token = $this->request->getData('reference_token');
                // $usersave->opt_out = $this->request->getData('opt_out');
                $usersave->active = 1;
                $usersave->affiliated_by = !empty($affiliated_by->reference_token) ? $affiliated_by->id : 0;
            //pr($usersave);die;
            $errp = $this->Users->save($usersave);
            // echo json_encode($errp);die;
                //die;
           }
         else{
          //  $usersave = $this->Users->newEntity();
           // $user = $this->Users->patchEntity($usersave, $this->request->getData());
            //$userchk->opt_out = $this->request->getData('opt_out');
            $userchk->opt_out = !empty($this->request->getData('opt_out')) ? $this->request->getData('opt_out') : 0;
         // pr($user);die; 
          $this->Users->save($userchk);
           //  die('lll');
        }
    }
    
    public function saveLead_old()
    {
        $this->autoRender = false;
      // pr($this->request->getData());die;
        $userchk = $this->Users->find('all')->where(['Users.email'=>$this->request->getData('email')])->first();
        if (empty($userchk)) {
            //$affiliated_by = $this->Users->find('all')->where(['Users.reference_token'=>$this->request->getData('affiliated_by')])->first();
            $usersave = $this->Users->newEntity();
            $usersave = $this->Users->patchEntity($usersave, $this->request->getData());
            $usersave->active = 1;
            $ip = $this->getIp();
            $usersave->password = "Test@123";
            $usersave->reference_token = $this->encryptToken();
            $usersave->active = true;
            $usersave->ip = $ip;
            $usersave->opt_out = true;

           // $usersave->affiliated_by = !empty($affiliated_by->reference_token) ? $affiliated_by->id : 0;
           // pr($usersave);die;
            $errp = $this->Users->save($usersave);
            $msg = ['status'=>1,'message'=>'Lead saved successfully.'];
            echo json_encode($msg);die;
            // die('okk');
           // return 1;
            // echo json_encode($errp);die;
            //die;
        }
        
    }
    
    public function saveLead_old2()
    {
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;

        if ($this->request->is('post')) {
            
            if($this->request->getData('secuirty_key_api') != SECURITY_KEY_API){
                 $this->responseCode = 401;
                 $this->responseMessage = 'Security key mismatch.';
                 echo $this->responseFormat();exit;
            }

            // Verified - send email
            if (empty($this->request->getData('IP'))) {
                $ip = $this->getIp();
            } else {
                $ip = $this->request->getData('IP');
            }

            $user = $this->Users->find('all')->where(['Users.email' => $this->request->getData('email')])->first();

            if (empty($user)) {
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
                   // $this->syndatabase($user); 
                    $user = $this->Users->find('all')->where(['Users.id' => $user['id']])->first();
                    $this->Auth->setUser($user);

                    $this->loadComponent('EmailManager');

                    $welcomeOptions = [
                        'template' => 'welcome',
                        'layout' => 'undesigned',
                        'to' => EMAIL_TEST_MODE ? ADMIN_EMAIL : $user->email,
                        'subject' => "Redacted Verification Email [IMPORTANT]",
                        'viewVars' => [
                            'email' => $user->email,
                            'eooToken' => base64_encode($user->email),
                            'mailCheckToken' => base64_encode($user->id),
                            'token' => $user->reference_token,
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
                    $options = [
                        'template' => 'new_user',
                        'to' => "param@strategiclight.com",
                        'subject' => "New User - From Third Party Website",
                        'viewVars' => [
                            'email' => $user->email,
                        ],
                    ];

                    $this->EmailManager->sendEmail($options);
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
                        //$this->mcSubscribe($user->id);
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
                                        //$this->mcSubscribe($affiliateUser->id, '5-referral-rewards');
                                        $userReward = $this->UserRewards->newEntity();

                                        $userReward->user_id = $affiliateUser->id;
                                        $userReward->reward_id = 2;
                                        $userReward->delivery_status = "Pending";
                                        $userReward->status = 1;
                                        $userReward->delivered_at = $user->modified;
                                        $userReward->will_deliver_by = $user->modified;

                                        $this->UserRewards->save($userReward);
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

                   // $this->responseMessage = 'Successfully Registered.';
                    $this->responseMessage = 'Lead Saved Successfully.';

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
            } else {
                $user->opt_out = false;
                $this->Users->save($user);

                $this->responseCode = SUCCESS_CODE;

                $this->responseMessage = 'Successfully Opt In.';
            }
        }

        echo $this->responseFormat();
        exit;
    }
    
    public function saveLead()
    {
     //  pr($this->request->getData());die;
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;
        $this->loadModel('Admins');
        $admin =$this->Admins->find('all')->where(['Admins.email'=>'admin@admin.com'])->first();
        if ($this->request->is('post')) {
             //SECURITY_KEY_API
           if($this->request->getData('secuirty_key_api') != $admin->api_key   ){
                 $this->responseCode = 401;
                 $this->responseMessage = 'Security key mismatch.';
                 echo $this->responseFormat();exit;
            }

            // Verified - send email
            if (empty($this->request->getData('IP'))) {
                $ip = $this->getIp();
            } else {
                $ip = $this->request->getData('IP');
            }

            $user = $this->Users->find('all')->where(['Users.email' => $this->request->getData('email')])->first();

            if (empty($user)) {
                $user = $this->Users->newEntity();
                $user = $this->Users->patchEntity($user, $this->request->getData());
                $user->password = "Test@123";
                $user->reference_token = $this->encryptToken();
                $user->active = true;
                $user->ip = $ip;
                $user->opt_out = true;
                $user->lead_from = !empty($this->request->getData('source')) ? $this->request->getData('source') : 'native';
                $user->campaign = !empty($this->request->getData('campaign')) ? $this->request->getData('campaign') : 'mowMedia' ;

                if ($this->getRequest()->getSession()->check('referredBy')) {
                    $user->affiliated_by = $this->getRequest()->getSession()->read('referredBy');
                    $this->getRequest()->getSession()->delete('referredBy');
                }

                if ($this->Users->save($user)) {
                    $this->syndatabase($user);
                    $user = $this->Users->find('all')->where(['Users.id' => $user['id']])->first();
                    $this->Auth->setUser($user);

                    $this->loadComponent('EmailManager');

                    $welcomeOptions = [
                        'template' => 'welcome',
                        'layout' => 'undesigned',
                        'to' => EMAIL_TEST_MODE ? ADMIN_EMAIL : $user->email,
                        'subject' => "Redacted Verification Email [IMPORTANT]",
                        'viewVars' => [
                            'email' => $user->email,
                            'eooToken' => base64_encode($user->email),
                            'mailCheckToken' => base64_encode($user->id),
                            'token' => $user->reference_token,
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
                    $options = [
                        'template' => 'new_user',
                        'to' => "param@strategiclight.com",
                        //'subject' => "New User - " . SITE_TITLE,
                        'subject' => "New User - From Third Party Website",
                        'viewVars' => [
                            'email' => $user->email,
                        ],
                    ];

                    $this->EmailManager->sendEmail($options);
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
                        //$this->mcSubscribe($user->id);
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
                                        //$this->mcSubscribe($affiliateUser->id, '5-referral-rewards');
                                        $userReward = $this->UserRewards->newEntity();

                                        $userReward->user_id = $affiliateUser->id;
                                        $userReward->reward_id = 2;
                                        $userReward->delivery_status = "Pending";
                                        $userReward->status = 1;
                                        $userReward->delivered_at = $user->modified;
                                        $userReward->will_deliver_by = $user->modified;

                                        $this->UserRewards->save($userReward);
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

                    $this->responseMessage = 'Lead saved successfully.';

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
            } else {
                $user->opt_out = false;
                $this->Users->save($user);

                $this->responseCode = SUCCESS_CODE;

                $this->responseMessage = 'Successfully Opt In.';
            }
        }

        echo $this->responseFormat();
        exit;
    }

    public function syncUsers(){
        // die('ggg');
         $this->autoRender = false;
         //pr($user);die;
         $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL,SITE_URL_MORINVEST."/users/getAllUsers");
        // curl_setopt($ch, CURLOPT_POST, 1);
       //  curl_setopt($ch, CURLOPT_POSTFIELDS,"email=".$user->email."&IP=".$user->ip."&password=".$user->password."&reference_token=".$user->reference_token."&opt_out=".$user->opt_out."&affiliated_by=".$user->affiliated_by."&action=".$user->action);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $serverOutput = curl_exec($ch);
         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
 
         $allUsers=json_decode($serverOutput,true);
         // pr($allUsers);
        //  die();
         if(count($allUsers)> 0){
             foreach($allUsers as $allUser){
                // pr($allUser);
                 //die();
                 $savedata = [];
             // pr($template);
                     $savedata['platform'] = $allUser['platform'];
                     $savedata['affiliated_by'] = $allUser['affiliated_by'];
                     $savedata['email'] = $allUser['email'];
                     $savedata['password'] = 'Test@123';
                     $savedata['first_name'] = $allUser['first_name'];
                     $savedata['last_name'] = $allUser['last_name'];
                     $savedata['address'] = $allUser['address'];
                     $savedata['city'] = $allUser['city'];
                     $savedata['state'] = $allUser['state'];
                     $savedata['zip'] = $allUser['zip'];
                     $savedata['country'] = $allUser['country'];
                     $savedata['reference_token'] = $allUser['reference_token'];
                     $savedata['active'] = $allUser['active'];
                     $savedata['no_of_affiliates'] = $allUser['no_of_affiliates'];
                     $savedata['ip'] = $allUser['ip'];
                     $savedata['lead_from'] = $allUser['lead_from'];
                     $savedata['campaign'] = $allUser['campaign'];
                    // $savedata['created'] = $allUser['created'];
                     $savedata['opt_out'] = !empty($allUser['opt_out']) ? $allUser['opt_out'] : '0' ;
                  //   $savedata['modified'] = $allUser['modified'];
                     $savedata['updated_on_mail_chimp'] = $allUser['updated_on_mail_chimp'];
             //pr($this->request->getData());
             $chkuser = $this->Users->find('all')->where(['email'=>$allUser['email']])->first();
             if(!empty($chkuser)){
                 $this->Users->updateAll($savedata ,['email'=>$allUser['email']]);
             }else{
                 $users = $this->Users->newEntity();
                 $users = $this->Users->patchEntity($users, $savedata);
               //  $emailTemplate->category =  'Admin';
                 $this->Users->save($users);
             }
             
             
             }
         }
          curl_close ($ch);
 
         echo 'Users synced successfully';
     }

}

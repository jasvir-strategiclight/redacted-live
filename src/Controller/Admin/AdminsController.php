<?php

namespace App\Controller\Admin;

use Cake\Network\Http\Client;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;


/**
 * Admins Controller
 *
 * @property \App\Model\Table\AdminsTable $Admins
 *  @property \App\Model\Table\EmailTemplatesTable $EmailTemplates
 *
 * @method \App\Model\Entity\Admin[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AdminsController extends AppController
{


    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['login', 'forgotPassword', 'resetPassword', 'add', 'changeStatus', 'saveScheduledEmailCron', 'markAsSent']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }
    }

    public function login()
    {
        //if already logged-in, redirect
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }
        if ($this->request->is('post')) {
            $admin = $this->Auth->identify();
            if ($admin) {
                $admin = $this->Admins->get($admin['id'], ['contain' => ['Images']]);

                $this->Auth->setUser($admin);
                if (isset($this->request->getData()['xx'])) {
                    $this->Cookie->write('aptnet_remember_token', $this->encryptpass($this->request->getData('email')) . "^" . base64_encode($this->request->getData('password')), true);
                }
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('Email or password is incorrect'));
                $this->redirect('/admin');
            }
        } elseif (empty($this->data)) {
            $rememberToken = $this->Cookie->read('aptnet_remember_token');
            if (!is_null($rememberToken)) {
                $rememberToken = explode("^", $rememberToken);
                $data = $this->Admins->find('all', ['conditions' => ['remember_token' => $rememberToken[0]]], ['fields' => ['email', 'password']])->first();

                $this->request->getData()['email'] = $data->email;
                $this->request->getData()['password'] = base64_decode($rememberToken[1]);
                $admin = $this->Auth->identify();
                if ($admin) {
                    $this->Auth->setUser($admin);
                    $this->redirect($this->Auth->redirectUrl());
                } else {
                    $this->redirect('/admin');
                }
            }
        }
    }

    public function dashboard()
    {

        $this->loadModel('Users');
        $this->loadModel('Rewards');
        $this->loadModel('ScheduledEmails');

        $totalUsers = $this->Users->find('all')->count();
        $totalRewards = $this->Rewards->find('all')->count();

        $this->set(compact('totalUsers', 'totalRewards'));

        $totalCondition = ['status' => 'Pending'];
        $sentCondition = ['status' => 'Sent'];
        $openedCondition = ['is_seen' => true];

        $this->loadModel('ScheduledEmails');
        $scheduledEmails = $this->ScheduledEmails->find('all')->where($totalCondition)->count();
        $sentEmails = $this->ScheduledEmails->find('all')->where($sentCondition)->count();
        $openedEmails = $this->ScheduledEmails->find('all')->where($openedCondition)->count();


        $newsletterCount = $this->Users->find('all')->where(['Users.opt_out' => false, 'Users.no_of_affiliates >=' => 3])->count();
        $unsubscriberCount = $this->Users->find('all')->where(['Users.opt_out' => true])->count();
        $newUserCount = $this->Users->find('all')->where(['Users.created > DATE_SUB(NOW(), INTERVAL 24 HOUR)'])->count();

        $latestScheduledEmail = $this->ScheduledEmails->find('all')->order(['ScheduledEmails.send_at' => 'DESC'])->first();


        $lastCampaignTotal = $this->ScheduledEmails->find('all')->where([
            'DATE(ScheduledEmails.send_at)' => date('Y-m-d', strtotime($latestScheduledEmail->send_at->nice()))
        ])->count();

        $lastCampaignOpened = $this->ScheduledEmails->find('all')->where([
            'DATE(ScheduledEmails.send_at)' => date('Y-m-d', strtotime($latestScheduledEmail->send_at->nice())),
            'ScheduledEmails.is_seen' => true,
        ])->count();


        $currentCampaignOpenRate = 0;

        if ($lastCampaignTotal > 0) {
            $currentCampaignOpenRate = ($lastCampaignOpened / $lastCampaignTotal) * 100;
        }

        $campaignCounts = $this->Users->find('all')->where(['Users.campaign !=' => ''])->count();
        $campaignLeadsCounts = $this->Users->find('all')->where(['Users.created > DATE_SUB(NOW(), INTERVAL 24 HOUR)','Users.lead_from !=' => 'native'])->count();

        $this->set(compact('scheduledEmails', 'sentEmails', 'openedEmails', 'newsletterCount', 'unsubscriberCount', 'currentCampaignOpenRate', 'newUserCount', 'campaignCounts','campaignLeadsCounts'));

    }

    public function changePassword()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $admin = $this->Admins->find()->where(['id' => $this->Auth->user('id')])->first();
            if ((new DefaultPasswordHasher)->check($this->request->getData('current_password'), $admin->password)) {
                if ($this->request->getData('new_password') == $this->request->getData('confirm_password')) {
                    $admin->password = $this->request->getData('new_password');
                    if ($this->Admins->save($admin)) {
                        $this->Flash->success(__('Password has been reset.'));
                    } else {
                        $this->Flash->error(__('Password has not been set.'));
                    }
                } else {
                    $this->Flash->error(__('Confirm Password does not match with New Password'));
                }
            } else {
                $this->Flash->error(__('Invalid Current Password'));
            }
        }
        return $this->redirect(['controller' => 'admins', 'action' => 'profile']);
    }

    public function logout()
    {
        $this->Auth->logout();
        $this->request->getSession()->destroy();
        $this->Cookie->delete('aptnet_remember_token');
        $this->Flash->success(__('You are now logged out'));
        return $this->redirect(['controller' => 'Admins', 'action' => 'login']);
    }

    public function profile()
    {

        $admin = $admin = $this->Admins->get($this->Auth->user('id'), ['contain' => ['Images']]);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $admin = $this->Admins->patchEntity($admin, $this->request->getData());

            if ($this->Admins->save($admin)) {
                $this->Flash->success(__('The admin has been saved.'));
                $admin = $this->Admins->get($admin->id, ['contain' => ['Images']]);
                $this->Auth->setUser($admin);

                return $this->redirect(['action' => 'profile']);
            } else {
                //pr($admin->errors()); die;
            }
            $this->Flash->error(__('The admin could not be saved. Please, try again.'));
        }
        unset($admin->password);
        $this->set(compact('admin'));
    }

    public function changeStatus()
    {

        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;
        if ($this->request->is('post')) {
            $model = $this->request->getData('model');
            $field = $this->request->getData('field');
            $id = $this->request->getData('id');

            $this->loadModel($model);

            $entity = $this->{$model}->find('all')->where(['id' => $id])->first();

            $entity->{$field} = !$entity->{$field};

            if ($this->{$model}->save($entity)) {
                if ($model == "Subscriptions") {
                    $this->loadModel('Users');
                    $user = $this->Users->find('all')->where(['id' => $entity->user_id])->first();
                    $user->has_plan = ($entity->{$field}) ? 1 : 0;
                    $this->Users->save($user);
                }

                if ($field == 'opt_out') {
                    // pr($entity->id);die;
                    $user = $this->Users->find('all')->where(['id' => $entity->id])->first();

                    $rr = $this->syndatabase($user);
                    // pr($rr);die;
                }
                $this->responseCode = SUCCESS_CODE;
                $this->responseData['new_status'] = $entity->{$field};
            }
        }

        echo $this->responseFormat();
    }

    public function getOptions()
    {
        $this->autoRender = false;
        $query = $this->request->getData('query');
        if (!empty($query)) {

            $value = empty($this->request->getData('value')) ? "id" : $this->request->getData('value');
            $label = empty($this->request->getData('label')) ? "name" : $this->request->getData('label');
            $match = $this->request->getData('match');
            $model = $this->request->getData('find');

            $this->loadModel($model);

            $options = $this->{$model}
                ->find('all')
                ->select(['value' => $model . "." . $value, 'label' => $model . "." . $label])
                ->where([$model . "." . $match => $query])
                ->where([$model . '.status' => true])
                ->all()
                ->toArray();
            echo json_encode(['suggestions' => $options]);
        } else {
            echo json_encode(['suggestions' => []]);
        }

        exit;
    }

    public function getSuggestions()
    {
        $this->autoRender = false;
        $query = $this->request->getQuery('query');
        if (!empty($query)) {
            $model = $this->request->getQuery('find');
            $this->loadModel($model);
            $match = empty($this->request->getQuery('match')) ? "name" : $this->request->getQuery('match');

            $matches = explode(",", $match);
            foreach ($matches as $m) {
                $conditions['OR'][$model . '.' . $m . ' LIKE'] = '%' . $query . '%';
            }

            $select = empty($this->request->getQuery('select')) ? $model . ".name" : $this->request->getQuery('select');

            if (!empty($this->request->getQuery('conditions'))) {
                foreach ($this->request->getQuery('conditions') as $field => $value) {
                    $conditions[$field] = $value;
                }
            }

            $cities = $this->$model
                ->find('all')
                ->select([$model . '.id', 'value' => $select])
                ->where($conditions)
                ->contain([])
                ->toArray();
            echo json_encode(['suggestions' => $cities]);
        } else {
            echo json_encode(['suggestions' => []]);
        }

        exit;
    }

    public function isUniqueEmail($id = null)
    {
        $this->autoRender = false;
        $this->loadModel('Users');
        if ($id === null) {
            $email = $this->request->getQuery('email');
            if ($this->Users->findByEmail($email)->count()) {
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

    public function getUserList_old()
    {
        $this->autoRender = false;
        $this->loadModel('Users');

        $listType = $this->request->getData('list_type');

        if (strpos($listType, 'NotSeen') !== false) {
            $explo = explode("_", $listType);


            $listType = "NotSeen";
            $emails = empty($explo[1]) ? 5 : $explo[1];
            $days = empty($explo[2]) ? 7 : $explo[2];
        }


        switch ($listType) {
            case "Subscribed": {
                    $users = $this->Users->find('all')
                        ->select(['id', 'email'])
                        ->where(['Users.opt_out' => 0])
                        ->toArray();
                    break;
                }
            case "UnSubscribed": {
                    $users = $this->Users->find('all')
                        ->select(['id', 'email'])
                        ->where(['Users.opt_out' => 1])
                        ->toArray();
                    break;
                }
            case "NewsLetter": {
                    $users = $this->Users->find('all')
                        ->select(['id', 'email'])
                        ->where([
                            'Users.opt_out' => 0,
                            'Users.no_of_affiliates >=' => 3,
                        ])
                        ->toArray();
                    break;
                }
            case "NotSeen": {
                    $this->loadModel('ScheduledEmails');
                    $notSeenUsers = $this->ScheduledEmails->find('all')->select([
                        //'ScheduledEmails.id',
                        'ScheduledEmails.user_id',
                        'ScheduledEmails.to_email',
                        'ScheduledEmails__not_seen_emails' => '(SELECT COUNT(*) as not_seen_count FROM scheduled_emails AS SE1 WHERE DATE(SE1.send_at) >= DATE(NOW()) - INTERVAL ' . $days . ' DAY AND SE1.is_seen = 0 AND SE1.to_email = ScheduledEmails.to_email)',
                    ])
                        ->where(['(DATE(ScheduledEmails.send_at) >= DATE(NOW()) - INTERVAL ' . $days . ' DAY)'])
                        ->group('ScheduledEmails.to_email, ScheduledEmails.user_id HAVING ScheduledEmails__not_seen_emails >= ' . $emails)
                        ->order(['ScheduledEmails.to_email' => 'ASC'])
                        ->all();

                    $users = [];
                    foreach ($notSeenUsers as $notSeenUser) {
                        $users[] = [
                            'id' => $notSeenUser->user_id,
                            'email' => $notSeenUser->to_email
                        ];
                    }
                    break;
                }
        }

        if (empty($users)) {
            echo json_encode(['users' => []]);
        } else {
            echo json_encode(['users' => $users]);
        }
        exit;


    }

    public function getUserList()
    {
        $this->autoRender = false;
        $this->loadModel('Users');

        $listType = $this->request->getData('list_type');
        $search = $this->request->getData('search');
        $page = $this->request->getData('page');

        if (strpos($listType, 'NotSeen') !== false) {
            $explo = explode("_", $listType);


            $listType = "NotSeen";
            $emails = empty($explo[1]) ? 5 : $explo[1];
            $days = empty($explo[2]) ? 7 : $explo[2];
        }


        switch ($listType) {
            case "Subscribed": {
                    $users = $this->Users->find()
                        ->select(['id', 'text' => 'email'])
                        ->where(['Users.opt_out' => 0, 'Users.email LIKE' => '%' . $search . '%'])->limit(1000)->page($page)
                        ->toArray();

                    break;
                }
            case "UnSubscribed": {
                    // $users = $this->Users->find('all')
                    //     ->select(['id', 'email'])
                    //     ->where(['Users.opt_out' => 1])
                    //     ->toArray();
                    $users = $this->Users->find()
                        ->select(['id', 'text' => 'email'])
                        ->where(['Users.opt_out' => 1, 'Users.email LIKE' => '%' . $search . '%'])->limit(1000)->page($page)
                        ->toArray();
                    break;
                }
            case "NewsLetter": {
                    // $users = $this->Users->find('all')
                    //     ->select(['id', 'email'])
                    //     ->where([
                    //         'Users.opt_out'             => 0,
                    //         'Users.no_of_affiliates >=' => 3,
                    //     ])
                    //     ->toArray();

                    $users = $this->Users->find()
                        ->select(['id', 'text' => 'email'])
                        ->where(['Users.opt_out' => 0, 'Users.no_of_affiliates >=' => 3, 'Users.email LIKE' => '%' . $search . '%'])->limit(1000)->page($page)
                        ->toArray();
                    break;
                }
            case "NotSeen": {
                    $this->loadModel('ScheduledEmails');
                    $notSeenUsers = $this->ScheduledEmails->find('all')->select([
                        //'ScheduledEmails.id',
                        'ScheduledEmails.user_id',
                        'ScheduledEmails.to_email',
                        'ScheduledEmails__not_seen_emails' => '(SELECT COUNT(*) as not_seen_count FROM scheduled_emails AS SE1 WHERE DATE(SE1.send_at) >= DATE(NOW()) - INTERVAL ' . $days . ' DAY AND SE1.is_seen = 0 AND SE1.to_email = ScheduledEmails.to_email)',
                    ])
                        ->where(['(DATE(ScheduledEmails.send_at) >= DATE(NOW()) - INTERVAL ' . $days . ' DAY)'])
                        ->group('ScheduledEmails.to_email, ScheduledEmails.user_id HAVING ScheduledEmails__not_seen_emails >= ' . $emails)
                        ->order(['ScheduledEmails.to_email' => 'ASC'])
                        ->all();

                    $users = [];
                    foreach ($notSeenUsers as $notSeenUser) {
                        $users[] = [
                            'id' => $notSeenUser->user_id,
                            'text' => $notSeenUser->to_email
                        ];
                    }
                    break;
                }
            case "opt_out_campaign_users": {
                    $users = $this->Users->find()
                        ->select(['id', 'text' => 'email'])
                        ->where(['Users.opt_out' => 1, 'Users.lead_from !=' => 'native', 'Users.email LIKE' => '%' . $search . '%'])->limit(1000)->page($page)
                        ->toArray();
                    break;
                }
        }
        // $users = array(['id' => 8, 'text' => 'edissonmayorga@gmail.com'],['id' => 12, 'text' => 'coccoc.net7@gmail.com']);


        if (empty($users)) {
            echo json_encode(['users' => []]);
        } else {
            echo json_encode(['users' => $users, 'count_filtered' => count($users)]);
        }
        exit;


    }

    public function scheduleEmail()
    {


        $this->loadModel('EmailTemplates');
        $emailTemplates = $this->EmailTemplates->find('list', ['keyField' => 'id', 'valueField' => 'label'])
            ->where(['EmailTemplates.user_id' => 0, 'EmailTemplates.category' => 'Admin '])
            ->order(['EmailTemplates.label' => 'ASC'])
            ->toArray();

        $totalCondition = ['status' => 'Pending'];
        $sentCondition = ['status' => 'Sent'];
        $openedCondition = ['is_seen' => true];

        $this->loadModel('ScheduledEmails');
        $totalEmails = $this->ScheduledEmails->find('all')->where($totalCondition)->count();
        $sentEmails = $this->ScheduledEmails->find('all')->where($sentCondition)->count();
        $openedEmails = $this->ScheduledEmails->find('all')->where($openedCondition)->count();

        $this->set(compact('totalEmails', 'sentEmails', 'openedEmails'));


        $this->set('emailTemplates', $emailTemplates);


    }

    public function scheduledEmails()
    {
        $this->loadModel('ScheduledEmails');

        $totalCondition = ['status' => 'Pending'];
        $sentCondition = ['status' => 'Sent'];
        $openedCondition = ['is_seen' => true];

        if (!empty($this->request->getQuery('send_at'))) {
            $sendAt = date('Y-m-d', strtotime($this->request->getQuery('send_at')));
            $totalCondition['DATE(send_at)'] = $sendAt;
            $sentCondition['DATE(send_at)'] = $sendAt;
            $openedCondition['DATE(send_at)'] = $sendAt;
            $this->paginate['conditions'][] = ['DATE(ScheduledEmails.send_at)' => $sendAt];
        }

        $this->paginate['contain'] = ['EmailTemplates'];
        $this->paginate['order'] = ['ScheduledEmails.send_at' => 'ASC'];
        $scheduledEmails = $this->paginate($this->ScheduledEmails);


        $this->loadModel('ScheduledEmails');
        $totalEmails = $this->ScheduledEmails->find('all')->where($totalCondition)->count();
        $sentEmails = $this->ScheduledEmails->find('all')->where($sentCondition)->count();
        $openedEmails = $this->ScheduledEmails->find('all')->where($openedCondition)->count();

        $this->set(compact('totalEmails', 'sentEmails', 'openedEmails'));

        $this->set(compact('scheduledEmails'));
    }

    public
        function getEmailTemplate(
        $emailTemplateId = null
    )
    {
        $this->loadModel('EmailTemplates');
        $emailTemplate = $this->EmailTemplates->get($emailTemplateId);

        //echo "<b>Subject: </b>" . $emailTemplate->subject . "<br />";
        echo $emailTemplate->template;

        exit;
    }

    public
        function saveScheduledEmail_old(
    )
    {

        $this->autoRender = false;
        $emails = [];
        $this->loadModel('Users');
        $d = $this->request->getData();


        // if (!empty($d['wp_post'])) 
// {
//     $tempID=$d['email_template_id'];
//     $this->loadModel('EmailTemplates');
//     $this->loadComponent('EmailManager');
//     $emailTemplate = $this->EmailTemplates->find('all')
//         ->where([
//             'EmailTemplates.id' => $this->request->getData('email_template_id')
//         ])->first();

        //     $title=$emailTemplate->subject.' - '.date("F d Y");
//     $content=$emailTemplate->template;
//     $slug=strstr($title," ");

        //     $http = new Client(['headers' => [
//         'Authorization' => 'Basic ' . base64_encode("david@geekslife.com:9ruv i4lf bEml JJyr PW2c 0TeE"),
//         'Accept'        => 'application/json',
//     ],
//     ]);
//     $response = $http->post('http://localhost/morningInvestWP/wp-json/wp/v2/newsletter', [
//     'title' => $title,
//     'content' => $content,
//     'status'=>'publish',
//     'slug'=>$slug,
//     'template'=>'elementor_header_footer',
//     ]);
// }


        //        if (!empty($d['send_at'])) {
//            $sendDate = explode("-", $d['send_at']);
//            $d['send_at'] = $sendDate[2] . "-" . $sendDate[0] . "-" . $sendDate[1];
//        }

        if ($d['user_id'] == "All") {
            $listType = $this->request->getData('list_type');

            if (strpos($listType, 'NotSeen') !== false) {
                $explo = explode("_", $listType);


                $listType = "NotSeen";
                $emailCount = empty($explo[1]) ? 5 : $explo[1];
                $days = empty($explo[2]) ? 7 : $explo[2];
            }

            switch ($listType) {
                case "Subscribed": {
                        $conditions['Users.opt_out'] = 0;
                        break;
                    }
                case "UnSubscribed": {
                        $conditions['Users.opt_out'] = 1;
                        break;
                    }
                case "NewsLetter": {
                        $conditions['Users.opt_out'] = 0;
                        $conditions['Users.no_of_affiliates >='] = 3;
                        break;
                    }
                case "NotSeen": {
                        $this->loadModel('ScheduledEmails');
                        $notSeenUsers = $this->ScheduledEmails->find('all')->select([
                            //'ScheduledEmails.id',
                            'ScheduledEmails.user_id',
                            'ScheduledEmails.to_email',
                            'ScheduledEmails__not_seen_emails' => '(SELECT COUNT(*) as not_seen_count FROM scheduled_emails AS SE1 WHERE DATE(SE1.send_at) >= DATE(NOW()) - INTERVAL ' . $days . ' DAY AND SE1.is_seen = 0 AND SE1.to_email = ScheduledEmails.to_email)',
                        ])
                            ->where([
                                '(DATE(ScheduledEmails.send_at) >= DATE(NOW()) - INTERVAL ' . $days . ' DAY)'
                            ])
                            ->group('ScheduledEmails.to_email, ScheduledEmails.user_id HAVING ScheduledEmails__not_seen_emails >= ' . $emailCount)
                            ->order(['ScheduledEmails.to_email' => 'ASC'])
                            ->all();



                        $users = [];
                        foreach ($notSeenUsers as $notSeenUser) {
                            $users[] = [
                                'id' => $notSeenUser->user_id,
                                'email' => $notSeenUser->to_email
                            ];
                        }
                        break;
                    }
                default: {
                        $conditions['Users.opt_out'] = 0;
                    }
            }


            if ($listType != "NotSeen") {
                $users = $this->Users->find('all')
                    ->select(['Users.email', 'Users.id'])
                    ->where($conditions)
                    ->group(['Users.id'])
                    ->order(['Users.first_name' => 'ASC'])
                    ->enableHydration(false)
                    ->all();
            }

        } else {

            $conditions['Users.id'] = $d['user_id'];

            $users = $this->Users->find('all')
                ->select(['Users.email', 'Users.id'])
                ->where($conditions)
                ->group(['Users.id'])
                ->order(['Users.first_name' => 'ASC'])
                ->enableHydration(false)
                ->all();
        }


        foreach ($users as $user) {
            if (!empty($user['email'])) {
                $emails[] = [
                    'user_id' => $user['id'],
                    'email' => $user['email'],
                ];
            }
        }


        if (!empty($emails)) {

            $this->loadModel('ScheduledEmails');

            $emailTemplateId = $this->getRequest()->getData('email_template_id');

            $datetime = date(SQL_DATETIME, strtotime($d['send_at']));
            $given = new \DateTime($datetime, new \DateTimeZone("America/New_York"));
            $given->setTimezone(new \DateTimeZone("America/Denver"));
            $utcDatetime = $given->format(SQL_DATETIME);


            foreach ($emails as $email) {
                $receivers[] = [
                    'scheduled_by' => 'admin',
                    'from_email' => FROM_EMAIL,
                    'to_email' => $email['email'],
                    'user_id' => $email['user_id'],
                    'send_at' => $utcDatetime,
                    'email_template_id' => $emailTemplateId,
                    'status' => 'Pending',
                    'send_after_type' => 'Immediate',
                ];
            }

            $scheduledEmails = $this->ScheduledEmails->newEntities($receivers);
            $this->ScheduledEmails->saveMany($scheduledEmails);

        }

        $this->Flash->success(__('The email has been scheduled.'));
        echo json_encode(['status' => 'scheduled', 'code' => 200]);

        exit;
    }

    public
        function saveScheduledEmail(
    )
    {
        $this->autoRender = false;
        $emails = [];
        $this->loadModel('Users');
        $this->loadModel('ScheduleLists');
        $this->loadComponent('EmailManager');
        $d = $this->request->getData();
        if ($d['user_id'] == "All") {
            $schedule = $this->ScheduleLists->newEntity();
            $schedule->scheduled_by = 'admin';
            $schedule->listType = $d['list_type'];
            $schedule->userlistData = $d['userlistData'];
            $schedule->user_id = $d['user_id'];
            $schedule->email_template_id = $d['email_template_id'];
            $schedule->send_at = $d['send_at'];
           
            if ($d['list_type'] == 'opt_out_campaign_users') {
                $users = $this->Users->find('all')
                    ->select(['Users.email', 'Users.id'])
                    ->where(['Users.lead_from !=' => 'native', 'Users.opt_out' => 1])
                    ->group(['Users.id'])
                    ->order(['Users.first_name' => 'ASC'])
                    ->enableHydration(false)
                    ->all();

                foreach ($users as $user) {
                    if (!empty($user['email'])) {
                        $emails[] = [
                            'user_id' => $user['id'],
                            'email' => $user['email'],
                        ];
                    }
                }
            }else{
                $fff = $this->ScheduleLists->save($schedule);
            }


        } else {
            $conditions['Users.id'] = $d['user_id'];
            $users = $this->Users->find('all')
                ->select(['Users.email', 'Users.id'])
                ->where($conditions)
                ->group(['Users.id'])
                ->order(['Users.first_name' => 'ASC'])
                ->enableHydration(false)
                ->all();

            foreach ($users as $user) {
                if (!empty($user['email'])) {
                    $emails[] = [
                        'user_id' => $user['id'],
                        'email' => $user['email'],
                    ];
                }
            }
        }
        if (!empty($emails)) {
            $this->loadModel('ScheduledEmails');
            $emailTemplateId = $this->getRequest()->getData('email_template_id');

            $datetime = date(SQL_DATETIME, strtotime($d['send_at']));
            $given = new \DateTime($datetime, new \DateTimeZone("America/New_York"));
            $given->setTimezone(new \DateTimeZone("America/Denver"));
            $utcDatetime = $given->format(SQL_DATETIME);
            foreach ($emails as $email) {
                $receivers[] = [
                    'scheduled_by' => 'admin',
                    'from_email' => FROM_EMAIL,
                    'to_email' => $email['email'],
                    'user_id' => $email['user_id'],
                    'send_at' => $utcDatetime,
                    'email_template_id' => $emailTemplateId,
                    'status' => 'Pending',
                    'send_after_type' => 'Immediate',
                ];
            }

            $scheduledEmails = $this->ScheduledEmails->newEntities($receivers);
            $this->ScheduledEmails->saveMany($scheduledEmails);


        }
        $options = [
            'template' => 'daily_schedule_confirm_email',
            'to' => DAILY_SCHEDULE_CONFIRM_EMAIL,
            'subject' => "Today Schedule - " . SITE_TITLE,

        ];
        $this->EmailManager->sendEmail($options);

        $this->Flash->success(__('The email has been scheduled.'));
        echo json_encode(['status' => 'scheduled', 'code' => 200]);

        exit;
    }



    public function markAsSent()
    {
        $this->autoRender = false;
        $this->loadModel('ScheduledEmails');
        $ScheduledEmails = TableRegistry::getTableLocator()->get('ScheduledEmails');
        $query = $ScheduledEmails->query();
        $query->update()->set(['status' => 'Sent'])->where(['status' => 'Pending'])->execute();
        echo "Scheduled emails mark as sent";
        exit;

    }


    public
        function saveScheduledEmailCron(
    )
    {
        // die('ddddd');
        $this->autoRender = false;
        $emails = [];
        $this->loadModel('Users');
        $this->loadModel('ScheduleLists');
        $d = $this->request->getData();
        //pr($id);die();
        $ScheduleLists = TableRegistry::getTableLocator()->get('ScheduleLists');
        $schedule = $this->ScheduleLists->newEntity();
        $schedules = $this->ScheduleLists->find('all')->where(['is_schedule' => 0])->all();
        if (count($schedules) > 0) {

            foreach ($schedules as $schedule) {
                // $d['user_id'] = $schedule->user_id;
                $this->request->data['user_id'] = $schedule->user_id;
                $this->request->data['list_type'] = $schedule->listType;
                $this->request->data['email_template_id'] = $schedule->email_template_id;
                $this->request->data['userlistData'] = $schedule->userlistData;
                $this->request->data['send_at'] = $schedule->send_at;

                $d = $this->request->getData();

                if ($d['user_id'] == "All") {
                    $listType = $this->request->getData('list_type');

                    if (strpos($listType, 'NotSeen') !== false) {
                        $explo = explode("_", $listType);


                        $listType = "NotSeen";
                        $emailCount = empty($explo[1]) ? 5 : $explo[1];
                        $days = empty($explo[2]) ? 7 : $explo[2];
                    }

                    switch ($listType) {
                        case "Subscribed": {
                                $conditions['Users.opt_out'] = 0;
                                break;
                            }
                        case "UnSubscribed": {
                                $conditions['Users.opt_out'] = 1;
                                break;
                            }
                        case "NewsLetter": {
                                $conditions['Users.opt_out'] = 0;
                                $conditions['Users.no_of_affiliates >='] = 3;
                                break;
                            }
                        case "NotSeen": {
                                $this->loadModel('ScheduledEmails');
                                $notSeenUsers = $this->ScheduledEmails->find('all')->select([
                                    //'ScheduledEmails.id',
                                    'ScheduledEmails.user_id',
                                    'ScheduledEmails.to_email',
                                    'ScheduledEmails__not_seen_emails' => '(SELECT COUNT(*) as not_seen_count FROM scheduled_emails AS SE1 WHERE DATE(SE1.send_at) >= DATE(NOW()) - INTERVAL ' . $days . ' DAY AND SE1.is_seen = 0 AND SE1.to_email = ScheduledEmails.to_email)',
                                ])
                                    ->where([
                                        '(DATE(ScheduledEmails.send_at) >= DATE(NOW()) - INTERVAL ' . $days . ' DAY)'
                                    ])
                                    ->group('ScheduledEmails.to_email, ScheduledEmails.user_id HAVING ScheduledEmails__not_seen_emails >= ' . $emailCount)
                                    ->order(['ScheduledEmails.to_email' => 'ASC'])
                                    ->all();



                                $users = [];
                                foreach ($notSeenUsers as $notSeenUser) {
                                    $users[] = [
                                        'id' => $notSeenUser->user_id,
                                        'email' => $notSeenUser->to_email
                                    ];
                                }
                                break;
                            }
                        default: {
                                $conditions['Users.opt_out'] = 0;
                            }
                    }


                    if ($listType != "NotSeen") {
                        $users = $this->Users->find('all')
                            ->select(['Users.email', 'Users.id'])
                            ->where($conditions)
                            ->group(['Users.id'])
                            ->order(['Users.first_name' => 'ASC'])
                            ->enableHydration(false)
                            ->all();
                    }

                } else {

                    $conditions['Users.id'] = $d['user_id'];

                    $users = $this->Users->find('all')
                        ->select(['Users.email', 'Users.id'])
                        ->where($conditions)
                        ->group(['Users.id'])
                        ->order(['Users.first_name' => 'ASC'])
                        ->enableHydration(false)
                        ->all();
                }


                foreach ($users as $user) {
                    if (!empty($user['email'])) {
                        $emails[] = [
                            'user_id' => $user['id'],
                            'email' => $user['email'],
                        ];
                    }
                }


                if (!empty($emails)) {

                    $this->loadModel('ScheduledEmails');

                    $emailTemplateId = $this->getRequest()->getData('email_template_id');

                    $datetime = date(SQL_DATETIME, strtotime($d['send_at']));
                    $given = new \DateTime($datetime, new \DateTimeZone("America/New_York"));
                    $given->setTimezone(new \DateTimeZone("America/Denver"));
                    $utcDatetime = $given->format(SQL_DATETIME);


                    foreach ($emails as $email) {
                        $receivers[] = [
                            'scheduled_by' => 'admin',
                            'from_email' => FROM_EMAIL,
                            'to_email' => $email['email'],
                            'user_id' => $email['user_id'],
                            'send_at' => $utcDatetime,
                            'email_template_id' => $emailTemplateId,
                            'status' => 'Pending',
                            'send_after_type' => 'Immediate',
                        ];
                    }

                    $scheduledEmails = $this->ScheduledEmails->newEntities($receivers);
                    $this->ScheduledEmails->saveMany($scheduledEmails);

                }
            }
        }

        $query = $ScheduleLists->query();
        $query->update()->set(['is_schedule' =>1])->where(['is_schedule' =>0])->execute();

        $this->Flash->success(__('The email has been scheduled.'));
        echo json_encode(['status' => 'scheduled', 'code' => 200]);

        exit;
    }

    // public function deleteScheduledEmail($id = null) {
    //     $this->request->allowMethod(['post', 'delete']);
    //     $this->loadModel('ScheduledEmails');
    //     $user = $this->ScheduledEmails->get($id);
    //     if ($this->ScheduledEmails->delete($user)) {
    //         $this->Flash->success(__('The scheduled email has been deleted.'));
    //     } else {
    //         $this->Flash->error(__('The scheduled email could not be deleted. Please, try again.'));
    //     }

    //     return $this->redirect(['action' => 'scheduledEmails']);
    // }

    public function deleteScheduledEmail($id = null)
    {
        // pr($id);die;
        $this->request->allowMethod(['post', 'delete']);
        $this->loadModel('ScheduledEmails');
        if ($id == 'scheduled') {
            $this->ScheduledEmails->updateAll([
                'status' => 'Sent'
            ], [
                    'status' => 'Pending',
                ]);
            $this->Flash->success(__('The scheduled email has been deleted.'));

        } else {
            $user = $this->ScheduledEmails->get($id);
            if ($this->ScheduledEmails->delete($user)) {
                $this->Flash->success(__('The scheduled email has been deleted.'));
            } else {
                $this->Flash->error(__('The scheduled email could not be deleted. Please, try again.'));
            }
        }


        return $this->redirect(['action' => 'scheduledEmails']);
    }

    public function sendPreviewEmail()
    {
        $status = "Not Sent";
        $this->loadModel('EmailTemplates');
        $this->loadComponent('EmailManager');

        $emailTemplate = $this->EmailTemplates->find('all')
            ->where([
                'EmailTemplates.id' => $this->request->getData('email_template_id')
            ])->first();


        if (!empty($emailTemplate)) {


            $options = [
                'emailFormat' => 'html',
                'layout' => 'designed',
                'template' => 'admin_scheduled_email',
                'to' => $this->request->getData('to'),
                'subject' => $emailTemplate->subject,
                'viewVars' => [
                    'emailContent' => $emailTemplate->template,
                    'eooToken' => "NA",
                    'mailCheckToken' => "NA",
                ]
            ];

            try {
                $this->EmailManager->sendEmail($options);
                $status = "Sent";
            } catch (\Error $e) {
                //Error Message will be here
            }

        }
        echo json_encode(['status' => $status]);
        exit;
    }

    public function notSeenEmails()
    {
        $this->loadModel('ScheduledEmails');

        $week = 1;
        if (!empty($this->request->getQuery('week'))) {
            $week = $this->request->getQuery('week');
        }

        $this->paginate['sortWhitelist'] = [
            "ScheduledEmails__not_seen_emails",
            "ScheduledEmails.not_seen_emails",
        ];

        $query = $this->ScheduledEmails->find('all')->select([
            //'ScheduledEmails.id',
            'ScheduledEmails.to_email',
            'ScheduledEmails__not_seen_emails' => '(SELECT COUNT(*) as not_seen_count FROM `scheduled_emails` AS SE1 WHERE DATE(SE1.send_at) >= DATE(NOW()) - INTERVAL ' . ($week * 7) . ' DAY AND SE1.is_seen = 0 AND SE1.to_email = ScheduledEmails.to_email)',
            'ScheduledEmails__no_of_emails' => '(SELECT COUNT(*) as total_emails FROM `scheduled_emails` AS SE2 WHERE DATE(SE2.send_at) >= DATE(NOW()) - INTERVAL ' . ($week * 7) . ' DAY AND  SE2.to_email = ScheduledEmails.to_email)',
        ])
            ->where(['(DATE(ScheduledEmails.send_at) >= DATE(NOW()) - INTERVAL ' . ($week * 7) . ' DAY)'])
            ->group('ScheduledEmails.to_email HAVING ScheduledEmails__not_seen_emails >=5');

        if (!empty($this->request->getQuery('sort'))) {
            if ($this->request->getQuery('sort') == "ScheduledEmails__not_seen_emails") {
                //$direction = ($this->request->getQuery('direction') == "asc") ? "desc" : "asc";
                $query->order(["ScheduledEmails__not_seen_emails" => $this->request->getQuery('direction')]);
            }
        }


        $this->set('week', $week);
        $this->set('emails', $this->paginate($query));

    }

    public function pauseCampaign()
    {

        $this->loadModel('Settings');

        $pauseCampaign = $this->Settings->find('all')->where(['Settings.setting_name' => 'Play Pause Email Campaign'])->first();

        $this->set('pauseCampaign', $pauseCampaign);

    }

    public function savePauseCampaign($value = null)
    {

        if ($value != null) {

            $this->loadModel('Settings');

            $pauseCampaign = $this->Settings->find('all')->where(['Settings.setting_name' => 'Play Pause Email Campaign'])->first();

            $pauseCampaign->setting_value = $value;

            $this->Settings->save($pauseCampaign);

        }

        echo json_encode(['status' => 'updated']);
        exit;
    }

    public function crons()
    {

    }


    public function sendWordpressPost()
    {

        $this->loadModel('EmailTemplates');
        $this->loadComponent('EmailManager');
        $emailTemplate = $this->EmailTemplates->find('all')
            ->where([
                'EmailTemplates.id' => $this->request->getData('id')
            ])->first();

        $title = $emailTemplate->subject . ' - ' . date("F d Y");
        $content = $emailTemplate->template;
        $slug = strstr($title, " ");

        $newTitle = $emailTemplate->subject . '-' . date("F d Y");
        $slugVal = strstr($newTitle, " ");


        $newSlug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($slugVal));
        $newSlugVal = trim($newSlug, '-');

        $username = WP_USERNAME;
        $password = WP_PASSWORD;
        $process = curl_init(WP_SITE_URL . 'newsletter/?slug=' . $newSlugVal);
        curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt(
            $process,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json'
            )
        );
        $return = curl_exec($process);
        $post_slug_data = json_decode($return, TRUE);
        curl_close($process);

        if (!empty($post_slug_data)) {
            $postID = $post_slug_data[0]['id'];
            echo json_encode(['status' => 'Edit', 'id' => $postID]);
            exit;

        } else {
            $content = str_replace([
                '<!--<![endif]-->',
                '<!--[if !mso ]><!-->',
                '<!--[if !mso]><!-->',
                '<p>Or copy &amp; paste your referral link to others:</p>',
                '<a href="[AFFILIATE_URL]">[AFFILIATE_URL]</a></td>',
                '<p>Hit the button below to learn more and access your Reward Dashboard.&nbsp;</p>',
            ], '', $content);

            $content = str_replace('[REFERRAL_URL]', SITE_URL, $content);
            $content = str_replace(
                [
                    '<p style="line-height:22px;margin-top:15px;margin-bottom:15px;font-weight:bold;color:#000000;">TOGETHER WITH</p>',
                    '<p data-darkreader-inline-color="" style="line-height: 22px; margin-top: 15px; margin-bottom: 15px; font-weight: bold; color: rgb(0, 0, 0); --darkreader-inline-color:#e8e6e3;">TOGETHER WITH</p>',
                ],

                '<h2 style="line-height: 22px; margin-top: 15px; margin-bottom: 15px; font-weight: bold; color: rgb(0, 0, 0); --darkreader-inline-color:#e8e6e3;">TOGETHER WITH</h2>',
                $content
            );
            $content = '<div style="width:670px !important;margin:auto">' . $content . '</div>';
            //echo $content;die();

            $http = new Client([
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($username . ":" . $password),
                    'Accept' => 'application/json',
                ],
            ]);
            $response = $http->post(WP_SITE_URL . 'newsletter/', [
                'title' => $title,
                'content' => $content,
                'status' => 'publish',
                'slug' => $slug,
                // 'template'=>'elementor_header_footer',
                // 'categories'=>'10',

            ]);
            echo json_encode(['status' => 'New']);
            exit;


        }
    }



    public function editWPPost()
    {
        $username = WP_USERNAME;
        $password = WP_PASSWORD;
        $wpPost = $this->request->getData('wpPostid');
        $this->loadModel('EmailTemplates');
        $this->loadComponent('EmailManager');
        $emailTemplate = $this->EmailTemplates->find('all')
            ->where([
                'EmailTemplates.id' => $this->request->getData('id')
            ])->first();

        $title = $emailTemplate->subject . ' - ' . date("F d Y");
        $content = $emailTemplate->template;
        $slug = strstr($title, " ");
        $content = str_replace([
            '<!--<![endif]-->',
            '<!--[if !mso ]><!-->',
            '<!--[if !mso]><!-->',
            '<p>Or copy &amp; paste your referral link to others:</p>',
            '<a href="[AFFILIATE_URL]">[AFFILIATE_URL]</a></td>',
            '<p>Hit the button below to learn more and access your Reward Dashboard.&nbsp;</p>',
        ], '', $content);

        $content = str_replace('[REFERRAL_URL]', SITE_URL, $content);
        $content = str_replace(
            [
                '<p style="line-height:22px;margin-top:15px;margin-bottom:15px;font-weight:bold;color:#000000;">TOGETHER WITH</p>',
                '<p data-darkreader-inline-color="" style="line-height: 22px; margin-top: 15px; margin-bottom: 15px; font-weight: bold; color: rgb(0, 0, 0); --darkreader-inline-color:#e8e6e3;">TOGETHER WITH</p>',
            ],

            '<h2 style="line-height: 22px; margin-top: 15px; margin-bottom: 15px; font-weight: bold; color: rgb(0, 0, 0); --darkreader-inline-color:#e8e6e3;">TOGETHER WITH</h2>',
            $content
        );
        $content = '<div style="width:670px !important;margin:auto">' . $content . '</div>';
        //echo $content;die();

        $http = new Client([
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($username . ":" . $password),
                'Accept' => 'application/json',
            ],
        ]);
        $response = $http->post(WP_SITE_URL . 'newsletter/' . $wpPost, [
            'title' => $title,
            // 'title' => 'abc',
            'content' => $content,
            'status' => 'publish',
            'slug' => $slug,
            'template' => 'elementor_header_footer',
            // 'categories'=>'10',

        ]);
        echo json_encode(['status' => 'editDone']);
        exit;

    }







}
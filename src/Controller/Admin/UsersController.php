<?php

namespace App\Controller\Admin;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $category = "";

        $this->paginate['contain'] = [];

        if (!empty($this->request->getQuery('category'))) {
            switch ($this->request->getQuery('category')) {
                case "subscribers": {
                        $this->paginate['conditions'][] = ['Users.opt_out' => false];
                        break;
                    }
                case "unsubscribers": {
                        $this->paginate['conditions'][] = ['Users.opt_out' => true];
                        break;
                    }
                case "newsletter subscribers":
                case ">3": {
                        $this->paginate['conditions'][] = ['Users.no_of_affiliates >=' => 3];
                        break;
                    }
                case ">5": {
                        $this->paginate['conditions'][] = ['Users.no_of_affiliates >=' => 5];
                        break;
                    }
                case "affiliated": {
                        $this->paginate['conditions'][] = ['Users.affiliated_by !=' => 0];
                        break;
                    }
                case "leads": {
                        $this->paginate['conditions'][] = ['Users.campaign !=' => ''];
                        break;
                    }
                case "leads_optin": {
                        $this->paginate['conditions'][] = ['Users.campaign !=' => ''];
                        $this->paginate['conditions'][] = ['Users.opt_out' => false];
                        break;
                    }
                case "leads_optout": {
                        $this->paginate['conditions'][] = ['Users.campaign !=' => ''];
                        $this->paginate['conditions'][] = ['Users.opt_out' => true];
                        break;
                    }
            }

            $category = $this->request->getQuery('category');

        }

        $users = $this->paginate($this->Users);


        $this->set(compact('users', 'category'));
    }

    public function referrals()
    {

        $this->paginate['contain'] = ['Affiliates'];
        $this->paginate['conditions'][] = ['Users.affiliated_by !=' => 0];

        if (!empty($this->request->getQuery('send_at'))) {
            $sendAt = date('Y-m-d', strtotime($this->request->getQuery('send_at')));
            $this->paginate['conditions'][] = ['DATE(Users.created)' => $sendAt];
        }

        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, []);

        $this->loadModel('Rewards');
        $this->loadModel('UserRewards');

        $rewards = $this->Rewards->find('all')->contain(['Images'])->all();
        $userRewards = $this->UserRewards->find('all')
            ->contain(['Rewards' => ['Images']])
            ->where([
                'UserRewards.user_id' => $id,
                'Rewards.status' => true,
            ])
            ->all();

        $affiliates = $this->Users->find('all')->where(['Users.affiliated_by' => $id])->all();

        $this->set('affiliates', $affiliates);
        $this->set('rewards', $rewards);
        $this->set('userRewards', $userRewards);
        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->password = "Test@123";
            $user->reference_token = $this->encryptToken();
            $user->active = true;
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                $options = [
                    'template' => 'account_by_admin',
                    'to' => EMAIL_TEST_MODE ? ADMIN_EMIAL : $user->email,
                    'subject' => __('Welcome to ' . SITE_TITLE),
                    'viewVars' => [
                        'email' => $user->email,
                    ]
                ];
                try {
                    //$this->loadComponent('EmailManager');
                    //$this->EmailManager->sendEmail($options);
                } catch (\Exception $e) {
                    $this->Flash->success(__('Successfully Registered, but welcome email has not been sent for now.'));
                }

                return $this->redirect(['action' => 'index']);
            } else {
                pr($user->getErrors());
                die;
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user', 'images'));
    }


    private function encryptToken($strlen = 10)
    {
        $str = '0123456789' . uniqid() . 'abcdefghijklmnopqrstuvwxyz' . uniqid();
        return substr(str_shuffle($str), 0, $strlen);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user', 'images'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $user->action = 'delete';
            $this->syndatabase($user);
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function importCsv()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $file = $this->request->getData('file');
            $listType = $this->request->getData('list_type');
            $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
            $imagePath = WWW_ROOT . 'files/' . $file['name'];
            $this->loadComponent('EmailManager');


            $users = [];
            if (in_array(strtolower($fileExt), ['csv'])) {
                if (move_uploaded_file($file["tmp_name"], $imagePath)) {

                    if (($handle = fopen($imagePath, "r")) !== FALSE) {
                        $row = 0;
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

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
                }


                if (!empty($users)) {


                    $newUsers = 0;
                    $index = 0;

                    foreach ($users as $fileUser) {

                        $user = $this->Users->find()->where(['email' => $fileUser['email']])->first();


                        if (empty($user)) {
                            $user = $this->Users->newEntity();
                            $user->email = $fileUser['email'];
                            $user->password = "Test@123";
                            $user->reference_token = empty($fileUser['token']) ? $this->encryptToken() : $fileUser['token'];
                            $user->active = true;
                            if ($listType == "unsubscribed") {
                                $user->opt_out = true;
                            }
                            $this->Users->save($user);

                            //                            $options = [
//                                'template' => 'welcome',
//                                'layout'   => 'undesigned',
//                                'to'       => (EMAIL_TEST_MODE) ? ADMIN_EMAIL : $user->email,
//                                'subject'  => "Danger: Morning Invest is here to help",
//                                'viewVars' => [
//                                    'email' => ADMIN_EMAIL,
//                                ]
//                            ];
//
//                            $this->EmailManager->sendEmail($options);

                            $newUsers++;

                        } else {
                            if ($listType == "unsubscribed") {

                                $user->opt_out = true;
                                $this->Users->save($user);
                                $newUsers++;
                            }
                        }

                        $index++;
                    }
                }
            }

            echo json_encode(['status' => 'done', 'Users' => $newUsers, 'List Type' => $listType]);
            exit;
        }


    }

    public function affiliates($id)
    {
        $users = $this->Users->find('all')->where(['Users.affiliated_by' => $id])->all();
        if (empty($users->toArray())) {
            echo "<h4>No Record Found</h4>";
        } else {
            $lis = [];
            foreach ($users as $user) {
                $lis[] = "<li>" . $user->email . "</li>";
            }
            echo $ul = "<ul class='ul'>" . implode("", $lis) . "</ul>";
        }

        exit;
    }

    public function setUserRewards()
    {

        $this->loadModel('UserRewards');

        $users = $this->Users->find('all')->where(['Users.no_of_affiliates >= ' => 3])->all();


        foreach ($users as $user) {

            if ($user->no_of_affiliates >= 3) {
                $userReward = $this->UserRewards->newEntity();

                $userReward->user_id = $user->id;
                $userReward->reward_id = 1;
                $userReward->delivery_status = "Delivered";
                $userReward->status = 1;
                $userReward->delivered_at = $user->modified;
                $userReward->will_deliver_by = $user->modified;

                $this->UserRewards->save($userReward);
            }

            if ($user->no_of_affiliates >= 5) {
                $userReward = $this->UserRewards->newEntity();

                $userReward->user_id = $user->id;
                $userReward->reward_id = 2;
                $userReward->delivery_status = "Pending";
                $userReward->status = 1;
                $userReward->delivered_at = $user->modified;
                $userReward->will_deliver_by = $user->modified;

                $this->UserRewards->save($userReward);
            }
        }

        echo "Rewards Updated";
        exit;
    }

    public function markDelivered($userRewardId = null)
    {
        $this->loadModel('UserRewards');
        $userReward = $this->UserRewards->find('all')->where(['UserRewards.id' => $userRewardId])->first();
        $userReward->delivery_status = "Delivered";
        $this->UserRewards->save($userReward);

        echo json_encode(['status' => 'Delivered']);
        exit;
    }

    public function markUndelivered($userRewardId = null)
    {
        $this->loadModel('UserRewards');
        $userReward = $this->UserRewards->find('all')->where(['UserRewards.id' => $userRewardId])->first();
        $userReward->delivery_status = "Pending";
        $this->UserRewards->save($userReward);

        echo json_encode(['status' => 'Pending']);
        exit;
    }


    public
        function exportCsv(
    )
    {

        if (!empty($this->request->getQuery('category'))) {
            switch ($this->request->getQuery('category')) {
                case "subscribers": {
                        $this->paginate['conditions'][] = ['Users.opt_out' => false];
                        break;
                    }
                case "unsubscribers": {
                        $this->paginate['conditions'][] = ['Users.opt_out' => true];
                        break;
                    }
                case "newsletter subscribers":
                case ">3": {
                        $this->paginate['conditions'][] = ['Users.no_of_affiliates >=' => 3];
                        break;
                    }
                case ">5": {
                        $this->paginate['conditions'][] = ['Users.no_of_affiliates >=' => 5];
                        break;
                    }
                case "affiliated": {
                        $this->paginate['conditions'][] = ['Users.affiliated_by !=' => 0];
                        break;
                    }
                case "leads": {
                        $this->paginate['conditions'][] = ['Users.campaign !=' => ''];
                        break;
                    }
                case "leads_optin": {
                        $this->paginate['conditions'][] = ['Users.campaign !=' => ''];
                        $this->paginate['conditions'][] = ['Users.opt_out' => false];
                        break;
                    }
                case "leads_optout": {
                        $this->paginate['conditions'][] = ['Users.campaign !=' => ''];
                        $this->paginate['conditions'][] = ['Users.opt_out' => true];
                        break;
                    }
            }
        }


        $users = $this->Users->find('all')->contain(['Affiliates'])->where($this->paginate['conditions'])->all();

        $list[] = [
            "First Name",
            "Last Name",
            "Email",
            "Affiliated By",
            "Address",
            "City",
            "State",
            "Zip",
            "Country",
            "Reference Token",
            "Active",
            "No of Affiliates",
            "IP",
            "OPT OUT",
            "Campaign",
            "Source",
            "Created"
        ];

        foreach ($users as $user) {
            if($user->opt_out == 1){
                $user->opt_out = 'yes';
            }else{
                $user->opt_out = 'no';
            }
            $list[] = [
                $user->first_name,
                $user->last_name,
                $user->email,
                $user->has('affiliate') ? $user->affiliate->email : "",
                $user->address,
                $user->city,
                $user->state,
                $user->zip,
                $user->country,
                $user->reference_token,
                $user->active,
                $user->no_of_affiliates,
                $user->ip,
                $user->opt_out,
                $user->campaign,
                $user->lead_from,
                $user->created
            ];
        }


        $this->arrayToCsvDownload($list);
        exit;

    }

    public
        function arrayToCsvDownload(
        $array,
        $fileName = "export.csv",
        $delimiter = ","
    )
    {
        // open raw memory as file so no temp files needed, you might run out of memory though
        $f = fopen('php://memory', 'w');
        // loop over the input array
        foreach ($array as $line) {
            // generate csv lines from the inner arrays
            fputcsv($f, $line, $delimiter);
        }
        // reset the file pointer to the start of the file
        fseek($f, 0);
        // tell the browser it's going to be a csv file
        header('Content-Type: application/csv');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="' . $fileName . '";');
        // make php send the generated csv lines to the browser
        fpassthru($f);
    }
}
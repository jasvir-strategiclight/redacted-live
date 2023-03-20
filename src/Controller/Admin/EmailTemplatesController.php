<?php

namespace App\Controller\Admin;

use Cake\Network\Http\Client;
use Cake\ORM\TableRegistry;

/**
 * EmailTemplates Controller
 *
 * @property \App\Model\Table\EmailTemplatesTable $EmailTemplates
 *
 * @method \App\Model\Entity\EmailTemplate[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmailTemplatesController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow([
            'synctemplates',

        ]);


    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {

        // $this->paginate = [
        //     'contain' => ['Users']
        // ];
        $emailTemplates = $this->paginate($this->EmailTemplates->find('all')->where(['user_id' => 0, 'category' => 'Admin']));

        $chkTemplates = $this->EmailTemplates->find('all', [
            'order' => 'EmailTemplates.id DESC',
        ]); //->where(['EmailTemplates.id'=>2]);
        $datetime1 = new \DateTime();
        foreach ($chkTemplates as $chkTemplate) {
            $datetime2 = new \DateTime($chkTemplate['modified']);
            $interval = $datetime1->diff($datetime2);
            //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
            //pr($interval->format('%i'));die();
            if ($interval->format('%i') > 15) {
                $this->EmailTemplates->updateAll(['is_open' => 0], ['id' => $chkTemplate['id']]);
            }

        }


        //pr($chkTemplates);die();

        $this->set(compact('emailTemplates'));
    }

    public function sponsorEmails()
    {

        $this->loadModel('ScheduledEmails');
        $emailTemplates = $this->paginate($this->EmailTemplates->find('all')->where(['user_id' => 0, 'category' => 'Admin']));
        $chkTemplates = $this->EmailTemplates->find('all', [
            'order' => 'EmailTemplates.id DESC',
        ]); //->where(['EmailTemplates.id'=>2]);
        $datetime1 = new \DateTime();
        foreach ($chkTemplates as $chkTemplate) {
            $datetime2 = new \DateTime($chkTemplate['modified']);
            $interval = $datetime1->diff($datetime2);

            if ($interval->format('%i') > 15) {
                $this->EmailTemplates->updateAll(['is_open' => 0], ['id' => $chkTemplate['id']]);
            }

        }
        foreach ($emailTemplates as $key => $emailTemplate) {
            if ($this->request->getQuery('category') == 'Monthly') {
                $sentEmails = $this->ScheduledEmails->find('all')->where(['ScheduledEmails.email_template_id' => $emailTemplate->id, 'MONTH(ScheduledEmails.created)' => date('n')])->distinct(['ScheduledEmails.created'])->count();
            } else {
                $sentEmails = $this->ScheduledEmails->find('all')->where(['ScheduledEmails.email_template_id' => $emailTemplate->id])->distinct(['ScheduledEmails.created'])->count();
            }
            $emailTemplate->count = $sentEmails;


        }

        $category = $this->request->getQuery('category');
        $this->set(compact('emailTemplates', 'category'));
    }



    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function realtorTemplates()
    {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $emailTemplates = $this->paginate($this->EmailTemplates->find('all')->where(['user_id' => 0, 'category' => 'Client List Default']));

        $this->set(compact('emailTemplates'));
    }

    /**
     * View method
     *
     * @param string|null $id Email Template id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $emailTemplate = $this->EmailTemplates->get($id, [
            'contain' => ['Users', 'ScheduledEmails']
        ]);

        $this->set('emailTemplate', $emailTemplate);
    }

    public function preview($id)
    {
        $this->viewBuilder()->setLayout(false);
        $emailTemplate = $this->EmailTemplates->get($id, [
            'contain' => ['Users', 'ScheduledEmails']
        ]);

        $this->set('emailTemplate', $emailTemplate);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $emailTemplate = $this->EmailTemplates->newEntity();
        if ($this->request->is('post')) {
            $emailTemplate = $this->EmailTemplates->patchEntity($emailTemplate, $this->request->getData());
            $emailTemplate->category = 'Admin';
            if ($this->EmailTemplates->save($emailTemplate)) {
                $this->Flash->success(__('The email template has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The email template could not be saved. Please, try again.'));
        }
        $users = $this->EmailTemplates->Users->find('list', ['limit' => 200]);
        $this->set(compact('emailTemplate', 'users'));
    }



    public function save()
    {
        $id = 0;

        $emailTemplate = $this->EmailTemplates->find()->where(['id' => $this->request->getData('id')])->first();
        if (empty($emailTemplate)) {
            $emailTemplate = $this->EmailTemplates->newEntity();
        }
        if ($this->request->is('post')) {
            $emailTemplate = $this->EmailTemplates->patchEntity($emailTemplate, $this->request->getData());
            $emailTemplate->category = 'Admin';

            $chk_edit_by = $this->EmailTemplates->get($this->request->getData('id'));
            if ($chk_edit_by['edit_by'] == $this->Auth->user('id')) {
                $this->EmailTemplates->save($emailTemplate);
                $is_save = 1;
            } else {
                $is_save = 2;
            }

            $id = $emailTemplate->id;

        }


        $chk_temp = $this->EmailTemplates->get($id, [
            'contain' => ['Admins']
        ]);


        echo json_encode(['id' => $id, 'is_save' => $is_save, 'admin' => $chk_temp]);
        exit;

    }

    public function is_open($id = null)
    {
        //$id = 0;
        // return $id;
        $emailTemplate = $this->EmailTemplates->get($id, [
            'contain' => []
        ]);
        $this->EmailTemplates->updateAll(['is_open' => 0, 'edit_by' => 0], ['id' => $id]);

        echo json_encode(['id' => $id]);
        exit;

    }

    public function chkEdit($id = null)
    {
        //$id = 0;
        // return $id;
        //$chk_temp = $this->EmailTemplates->find('first')->where(['id'=>$id]);
        //$chk_temp = $this->EmailTemplates->Admins->find()->where(['id'=> $id])->first();
        $chk_temp = $this->EmailTemplates->get($id, [
            'contain' => ['Admins']
        ]);

        echo json_encode(['id' => $id, 'is_open' => $chk_temp['is_open'], 'admin' => $chk_temp]);
        exit;

    }

    /**
     * Edit method
     *
     * @param string|null $id Email Template id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        // $this->loadComponent('Cookie');
        $emailTemplate = $this->EmailTemplates->get($id, [
            'contain' => []
        ]);
        //pr($this->request->query());die();
        $emailTemp = $this->EmailTemplates->find()->where(['id' => $id])->first();
        $alert = 0;
        if ($emailTemp['is_open'] == 1) {
            if (!($this->request->query('overtake'))) {
                return $this->redirect(['action' => 'index', 'overtake' => 1]);
            }


        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->request->getData();
            $emailTemplate = $this->EmailTemplates->patchEntity($emailTemplate, $this->request->getData());
            if ($this->EmailTemplates->save($emailTemplate)) {
                $this->Flash->success(__('The email template has been saved.'));
                $data = $this->getRequest()->getData();
                if (!empty($data['go_to'])) {
                    return $this->redirect(['action' => $data['go_to']]);
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The email template could not be saved. Please, try again.'));
        }
        //pr($id);die();
        $this->EmailTemplates->updateAll(['is_open' => 1, 'edit_by' => $this->Auth->user('id')], ['id' => $id]);
        //  die('ff');

        $users = $this->EmailTemplates->Users->find('list', ['limit' => 200]);
        $this->set(compact('emailTemplate', 'users', 'alert'));
    }



    /**
     * Delete method
     *
     * @param string|null $id Email Template id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $emailTemplate = $this->EmailTemplates->get($id);
        if ($this->EmailTemplates->delete($emailTemplate)) {
            $this->Flash->success(__('The email template has been deleted.'));
        } else {
            $this->Flash->error(__('The email template could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function reset($id = null)
    {
        //$this->request->allowMethod(['post', 'reset']);
        $emailTemplate = $this->EmailTemplates->get($id);
        $this->loadModel('EmailTemplates');

        if ($this->EmailTemplates->updateAll(['template' => $emailTemplate['template_reset']], ['id' => $id])) {
            $this->Flash->success(__('The email template has been reset sucessfully.'));
        } else {
            $this->Flash->error(__('The email template could not be reset. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function createWPPost($id = null)
    {


        $emailTemplate = $this->EmailTemplates->get($id);

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
            $this->Flash->error(__('You already created the same name post'));
            return $this->redirect(['action' => 'index']);

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
                'template' => 'elementor_header_footer',
                // 'categories'=>'10',

            ]);
            $this->Flash->success(__('Template sucessfully converted to Wordpress Post.'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function synctemplates()
    {
        // die('ggggg');
        $this->autoRender = false;
        //pr($user);die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, SITE_URL_MORINVEST . "/admin/email_templates/getAllTemplates");
        // curl_setopt($ch, CURLOPT_POST, 1);
        //  curl_setopt($ch, CURLOPT_POSTFIELDS,"email=".$user->email."&IP=".$user->ip."&password=".$user->password."&reference_token=".$user->reference_token."&opt_out=".$user->opt_out."&affiliated_by=".$user->affiliated_by."&action=".$user->action);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $serverOutput = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $templates = json_decode($serverOutput, true);
        // die('ggggg');
        //  pr($templates);
        // die();
        if (count($templates) > 0) {
            foreach ($templates as $template) {
                $savedata = [];
                // pr($template);
                $savedata['category'] = $template['category'];
                $savedata['user_id'] = $template['user_id'];
                $savedata['label'] = $template['label'];
                $savedata['subject'] = $template['subject'];
                $savedata['preview_line'] = $template['preview_line'];
                $savedata['template'] = $template['template'];
                $savedata['newsletter_url'] = $template['newsletter_url'];
                $savedata['placeholders'] = $template['placeholders'];
                $savedata['note'] = $template['note'];
                $savedata['status'] = $template['status'];
                $savedata['is_open'] = $template['is_open'];
                $savedata['edit_by'] = $template['edit_by'];
                //pr($this->request->getData());
                $emailTemp = $this->EmailTemplates->find('all')->where(['label' => $template['label']])->first();
                if (!empty($emailTemp)) {
                    $this->EmailTemplates->updateAll($savedata, ['label' => $template['label']]);
                } else {
                    $emailTemplate = $this->EmailTemplates->newEntity();
                    $emailTemplate = $this->EmailTemplates->patchEntity($emailTemplate, $savedata);
                    $emailTemplate->category = 'Admin';
                    $this->EmailTemplates->save($emailTemplate);
                }


            }
        }
        curl_close($ch);

        echo 'Template synced successfully';
    }


}
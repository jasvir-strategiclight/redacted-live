<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Auth->allow([
            'home',
            'about',
            'contact',
            'privacyPolicy',
            'termsAndConditions',
            'howItWorks',
            'unsubscribe',
            'mailCheck',
            'faqs'
        ]);
    }

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function display(...$path) {
        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        $this->set(compact('page', 'subpage'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }

    public function home() {
        $this->viewBuilder()->setLayout('home');
        $content = $this->getContent('Home');
        $this->set(compact('exercises', 'content'));
    }


    public function privacyPolicy() {
        $this->viewBuilder()->setLayout('home');
        $this->set('content', $this->getContent('Privacy Policy'));
    }

    public function termsAndConditions() {
        $this->viewBuilder()->setLayout('home');
        $this->set('content', $this->getContent('Terms & Conditions'));
    }

    public function unsubscribe($token = null) {

        $this->loadModel('Users');
        if ($token == null) {
            if ($this->request->getSession()->check('eoo_token')) {
                $token = $this->request->getSession()->read('eoo_token');
            }
        } else {
            $this->request->getSession()->write('eoo_token', $token);
        }

        $email = base64_decode($token);

        $user = $this->Users->find('all')->where(['Users.email' => $email])->first();
        if (!empty($user)) {

            if ($this->request->is('post')) {
                $user->opt_out = true;
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('Email Opt Out Successfully.'));
                    return $this->redirect(['controller' => 'Users', 'action' => 'home']);
                }

            }

            $this->viewBuilder()->setLayout('inner');
            $content['HEADER']['heading'] = "Email Opt Out";
            $content['HEADER']['text'] = "";
            $this->set(compact('content', 'user'));

        } else {
            $this->Flash->error(__('Email Does Not Exits.'));
            $this->redirect(['controller' => 'Users', 'action' => 'home']);
        }
    }

    public function mailCheck($mailCheckToken = null) {
        $this->viewBuilder()->setLayout(false);
        if ($mailCheckToken != null) {
            $this->loadModel('ScheduledEmails');
            //$id = base64_decode($mailCheckToken);
            $id = $mailCheckToken;
            $scheduledEmail = $this->ScheduledEmails->find()->where(['ScheduledEmails.id' => $id])->first();
            if (!empty($scheduledEmail)) {
                $scheduledEmail->is_seen = true;
                $this->ScheduledEmails->save($scheduledEmail);
            }
        }
        
        //Full URI to the image
        $filepath = SITE_URL.'img/1pixel.png';
        
        //Get the filesize of the image for headers
        $filesize = filesize( $filepath );
        
        //Now actually output the image requested (intentionally disregarding if the database was affected)
        	header('Content-Description: File Transfer');
        	header('Content-Type: application/octet-stream');
        	header('Content-Disposition: attachment; filename="blank.png"');
        	header('Expires: 0');
        	header('Cache-Control: must-revalidate');
        	header('Pragma: public');
        	header('Content-Length: ' . filesize($filepath));
        	flush(); // Flush system output buffer
        	readfile($filepath);
        	exit;
    }

}

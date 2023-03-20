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

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    
    
    public $responseCode = SUCCESS_CODE;
    public $responseMessage = "";
    public $responseData = [];
    public $currentPage = 0;
    public $authUserId = 0;
    
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize() {
        parent::initialize();

       // echo date('Y-m-d H:i:s', '1575072000');
        
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie');
        
        $this->loadComponent('Auth', [
                'userModel' => 'Users',
                'authenticate' => [
                    'Form' => [
                        'fields' => [
                            'username' => 'email',
                            'password' => 'password',
                        ]
                    ]
                ],
                'loginAction' => ['controller' => 'Users', 'action' => 'home'],
                'loginRedirect' => ['controller' => 'Users', 'action' => 'referral'],
                'logoutRedirect' => ['controller' => 'Users', 'action' => 'home'],
            ]
        );

        $loggedInUser = $this->Cookie->read('loggedInUser');
        $this->set('Auth', $this->Auth);
        //pr($this->Auth->user()); die;
        if ($this->Auth->user()) {
            $this->set('authUser', $this->Auth->user());
            $this->viewBuilder()->setLayout('home');
            
            $this->searchConditions();
        } else {
            $this->viewBuilder()->setLayout('home');
            
        }
        
        //die('okokokkok');
        
        
        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        
    }
    
    public function beforeFilter(Event $event) {
        /*
         * This is used to redirect the user if admin is login and want to access the user site
         */
        $user = $this->Auth->user();
        if (isset($user['role']) && $user['role'] == 'Admin') {
            return $this->redirect(['controller' => 'Admins', 'action' => 'dashboard', 'prefix' => 'admin']);
        }
    }
    
    
    public function responseFormat() {
        $returnArray = ["code" => $this->responseCode, "message" => $this->responseMessage,];
        if ($this->currentPage > 0) {
            $this->responseData['currentPage'] = $this->currentPage;
        }
        
        if (isset($this->responseData['total'])) {
            $this->responseData['pages'] = ceil($this->responseData['total'] / PAGE_LIMIT);
        }
        
        $returnArray['data'] = !empty($this->responseData) ? $this->responseData : ['message' => 'Data not found'];
        
        return json_encode($returnArray);
    }
    
    public function getErrorMessage($errors, $show = false, $field = []) {
        if (is_array($errors)) {
            foreach ($errors as $key => $error) {
                $field[$key] = "[" . $key . "]";
                $this->getErrorMessage($error, $show, $field);
                break;
            }
        } else {
            $this->responseMessage = ($show) ? implode(" >> ", $field) . " >> " . $errors : $errors;
        }
    }
    
    public function getCurrentPage() {
        $this->currentPage = (!empty($this->request->getQuery('page')) && $this->request->getQuery('page') > 0) ? $this->request->getQuery('page') : 1;
        return $this->currentPage;
    }
    
    public function getOption($name) {
        $this->loadModel('Options');
        
        $option = $this->Options->find('all')->where(['option_name' => $name])->first();
        
        return (empty($option)) ? "Not Found" : ((empty($option->option_value)) ? $option->default_value : $option->option_value);
    }
    
    public function setOptions() {
        $this->loadModel('Options');
        
        $optionsQuery = $this->Options->find('all')->where(['category' => 'General'])->all();
        
        $options = [];
        
        foreach ($optionsQuery as $o) {
            $options[$o->option_name] = (empty($o->option_value)) ? $o->default_value : $o->option_value;
        }
        
        $this->set('options', $options);
    }
    
    
    public function getContent($pageName) {
        $this->loadModel('Pages');
        $page = $this->Pages->find('all')->contain(['Images'])->where(['page' => $pageName])->first();
        $content = [];
        if (!empty($page)) {
            $content = [
                'heading' => $page->heading,
                'content' => $page->content,
                'image' => $page->has('image') ? $page->image : [],
                'plans' => $page->plans,
            ];
        }
        return $content;
    }
    
    public function isIE() {
        return (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) ? true : false;
    }
    
    
    public function searchConditions() {
        if ($this->request->is('post') && !empty($this->request->getData('search_in_listings'))) {
            $keyString = strtolower($this->request->getData('key'));
            $keys = explode(" ", $keyString);
            $matches = explode(",", $this->request->getData('match'));
            $conditions = [];
            foreach ($matches as $match) {
                $conditions["LOWER(" . $match . ") LIKE"] = "%$keyString%";
            }
            $this->paginate = [
                'conditions' => $conditions
            ];
            
            $this->set('search_key', $keyString);
        }
    }

    public function imageTo64($path) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    
    
}

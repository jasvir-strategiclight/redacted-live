<?php

namespace App\Controller\Admin;

use \DrewM\MailChimp\MailChimp;

class MailChimpController extends AppController {

    public function setting($slug = "new-subscription") {

        $this->loadModel('McDetails');
        $mc = $this->McDetails->find('all')->where(['slug' => $slug])->first();

        switch ($slug) {
            case "newsletter":
                {
                    $localFields = [
                        'email'         => 'Email',
                        'affiliate_url' => 'Affiliate URL',
                        'dashboard_url' => 'Dashboard URL',
                    ];
                    break;
                }
            case "5-referral-rewards":
                {
                    $localFields = [
                        'email'         => 'Email',
                        'first_name'    => 'First Name',
                        'last_name'     => 'Last Name',
                        'address'       => 'Address',
                        'city'          => 'City',
                        'state'         => 'State',
                        'zip'           => 'Zip',
                        'country'       => 'Country',
                        'affiliate_url' => 'Affiliate URL',
                        'dashboard_url' => 'Dashboard URL',
                    ];
                    break;
                }
            default :
                {
                    $localFields = [
                        'email'         => 'Email',
                        'affiliate_url' => 'Affiliate URL',
                        'dashboard_url' => 'Dashboard URL',
                    ];
                }
        }


        $this->set('localFields', $localFields);
        $this->set('mc', $mc);
        $this->set('slug', $slug);
    }


    public function getMcLists() {
        $this->loadModel('McDetails');
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;
        $apiKey = $this->getRequest()->getData('api_key');
        $slug = $this->getRequest()->getData('slug');

        //finding MC based on Title
        $mc = $this->McDetails->find()->where(['slug' => $slug])->first();

        if (empty($mc)) {
            $mc = $this->McDetails->find()->first();
            $mc->api_key = $apiKey;
            $this->McDetails->save($mc);
        }
        try {
            $MailChimp = new MailChimp($mc->api_key);

            $lists = $MailChimp->get('lists/');

            if (empty($lists['lists'])) {
                $this->responseMessage = 'No Audience List Found.';
            } else {
                $finalList = [];
                foreach ($lists['lists'] as $list) {

                    $finalList[] = [
                        'value' => $list['id'],
                        'label' => $list['name'],
                    ];
                }
                $this->responseCode = SUCCESS_CODE;
                $this->responseData['lists'] = $finalList;
                $this->responseMessage = $lists['total_items'] . ' Lists Found.';
            }

        } catch (\Exception $e) {
            $this->responseMessage = $e->getMessage();
        }

        echo $this->responseFormat();
        exit;
    }

    public function getMcListFields() {
        $this->loadModel('McDetails');
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;
        $listId = $this->getRequest()->getData('list_id');
        $slug = $this->getRequest()->getData('slug');


        if (empty($listId)) {
            $this->responseMessage = 'Please select a list.';
        } else {
            $mc = $this->McDetails->find()->where(['slug' => $slug])->first();

            $mc->list_id = $listId;
            $this->McDetails->save($mc);

            try {

                $MailChimp = new MailChimp($mc->api_key);

                $fields = $MailChimp->get('lists/' . $listId . '/merge-fields');

                if (empty($fields['merge_fields'])) {
                    $this->responseMessage = 'No Field Found.';
                } else {
                    $finalFields = [];
                    $mergeFields = [];
                    foreach ($fields['merge_fields'] as $field) {

                        $finalFields[] = [
                            'value' => $field['tag'],
                            'label' => $field['name'],
                        ];
                        $mergeFields[] = [
                            'tag'  => $field['tag'],
                            'name' => $field['name'],
                            'type' => $field['type'],
                        ];
                    }

                    $mc->mc_merge_fields = json_encode($mergeFields);
                    $this->McDetails->save($mc);

                    $this->responseCode = SUCCESS_CODE;
                    $this->responseData['fields'] = $finalFields;
                    $this->responseMessage = $fields['total_items'] . ' Fields Found.';
                }

            } catch (\Exception $e) {
                $this->responseMessage = $e->getMessage();
            }
        }

        echo $this->responseFormat();
        exit;

    }

    public function saveMapping($slug = "New Subscription") {
        $this->loadModel('McDetails');
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;
        $data = $this->getRequest()->getData();


        $mc = $this->McDetails->find()->where(['slug' => $slug])->first();

        $mc->merged_fields_json = json_encode($data);
        if ($this->McDetails->save($mc)) {
            $this->responseCode = SUCCESS_CODE;
            $this->responseMessage = "Saved Successfully";
        } else {
            $this->responseMessage = "Something went wrong please try again.";
        }
        echo $this->responseFormat();
        exit;
    }


}
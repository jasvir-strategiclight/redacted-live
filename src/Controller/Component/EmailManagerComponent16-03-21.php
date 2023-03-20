<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Mailer\Email;
use Cake\Network\Exception\NotFoundException;
use App\Mailer\Transport\CustomTransport;

class EmailManagerComponent extends Component {
    
    private $emailResponse;
    
    public function sendEmail($options = []) {
        $this->emailResponse['error'] = true;
        $defaultOptions = [
            'template' => 'default',
            'layout' => 'default',
            'emailFormat' => 'both',
            'to' => null,
            'cc' => null,
            'from' => [FROM_EMAIL => SITE_TITLE],
            'sender' => [FROM_EMAIL => SITE_TITLE],
            'subject' => SITE_TITLE,
            'viewVars' => [
                'logo' => SITE_URL . "/img/logo.png",
                'appName' => SITE_TITLE,
                'appUrl' => SITE_URL
            ],
            'attachments'=>[]
        ];
        
        if (!empty($options['viewVars'])) {
            $options['viewVars'] = array_merge($defaultOptions['viewVars'], $options['viewVars']);
        }
        if (!empty($options['from'])) {
            $options['from'] = array_merge($defaultOptions['from'], $options['from']);
        }
        if (!empty($options['sender'])) {
            $options['sender'] = array_merge($defaultOptions['sender'], $options['sender']);
        }
        $finalOptions = array_merge($defaultOptions, $options);
        
        extract($finalOptions);
        $hasDestination = false;
        try {
            $email = new Email();
            $email->setFrom($from);
            $email->viewBuilder()->setTemplate($template);

            $email->viewBuilder()->setLayout($layout);

            if ($to != null) {
                if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                    $email->setTo($to);
                    $hasDestination = true;
                } else {
                    $hasDestination = false;
                }
            }
            if ($cc != null) {
                if (filter_var($cc, FILTER_VALIDATE_EMAIL)) {
                    $email->setCc($cc);
                    $hasDestination = true;
                } else {
                    if (!$hasDestination)
                        $hasDestination = false;
                }
            }
            
            if ($sender != null) {
                $email->setSender(array_keys($sender)[0], array_Values($sender)[0]);
            }
            
            $email->setEmailFormat($emailFormat);
            $email->setSubject($subject);
            $email->setViewVars($viewVars);
            if (!empty($attachments)) {
                $email->setAttachments($attachments);
            }
            if ($hasDestination) {
                $this->emailResponse['error'] = false;
                $this->emailResponse['status'] = 'Email Sent';
                $email->send();
            } else {
                $this->emailResponse['status'] = 'Email did not send, destination email not found';
            }
        } catch (Exception $e) {
            throw new NotFoundException(__('Destination email not found'));
        }
        return $this->emailResponse;
    }
    
}

?>

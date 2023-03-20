<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SentEmail Entity
 *
 * @property int $id
 * @property int $from
 * @property int $to
 * @property string $subject
 * @property string $body
 * @property string $status
 * @property string $reason_for_not_sent
 * @property string $sent_type
 * @property \Cake\I18n\FrozenTime $created
 */
class SentEmail extends Entity {
    
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'email_from' => true,
        'email_to' => true,
        'subject' => true,
        'body' => true,
        'status' => true,
        'reason_for_not_sent' => true,
        'sent_type' => true,
        'created' => true
    ];
}

<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ScheduledEmail Entity
 *
 * @property int $id
 * @property int $from
 * @property int $to
 * @property int $email_template_id
 * @property int $send_after
 * @property string $send_after_type
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\EmailTemplate $email_template
 * @property \App\Model\Entity\ScheduledEmailApartment[] $scheduled_email_apartments
 */
class ScheduledEmail extends Entity {
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
        'scheduled_by'      => true,
        'from_email'        => true,
        'to_email'          => true,
        'email_content'     => true,
        'from_id'           => true,
        'user_id'           => true,
        'email_template_id' => true,
        'send_after'        => true,
        'send_after_type'   => true,
        'send_at'           => true,
        'is_seen'           => true,
        'status'            => true,
        'created'           => true,
        'modified'          => true,
        'email_template'    => true,
        
    ];
}

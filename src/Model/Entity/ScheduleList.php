<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ScheduleList Entity
 *
 * @property int $id
 * @property string $scheduled_by
 * @property string $email_template_id
 * @property string $listType
 * @property string $userlistData
 * @property string $user_id
 * @property string $send_at
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\EmailTemplate $email_template
 * @property \App\Model\Entity\User $user
 */
class ScheduleList extends Entity
{
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
        'scheduled_by' => true,
        'email_template_id' => true,
        'listType' => true,
        'userlistData' => true,
        'user_id' => true,
        'send_at' => true,
        'created' => true,
        'modified' => true,
        'email_template' => true,
        'user' => true,
    ];
}

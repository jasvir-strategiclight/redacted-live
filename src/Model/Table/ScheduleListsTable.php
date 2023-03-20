<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ScheduleLists Model
 *
 * @property \App\Model\Table\EmailTemplatesTable&\Cake\ORM\Association\BelongsTo $EmailTemplates
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\ScheduleList get($primaryKey, $options = [])
 * @method \App\Model\Entity\ScheduleList newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ScheduleList[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ScheduleList|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ScheduleList saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ScheduleList patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ScheduleList[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ScheduleList findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ScheduleListsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('schedule_lists');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('EmailTemplates', [
            'foreignKey' => 'email_template_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('scheduled_by')
            ->maxLength('scheduled_by', 255)
            ->requirePresence('scheduled_by', 'create')
            ->notEmptyString('scheduled_by');

        $validator
            ->scalar('listType')
            ->maxLength('listType', 255)
            ->requirePresence('listType', 'create')
            ->notEmptyString('listType');

        $validator
            ->scalar('userlistData')
            ->maxLength('userlistData', 255)
            ->notEmptyString('userlistData');

        $validator
            ->scalar('send_at')
            ->maxLength('send_at', 255)
            ->notEmptyString('send_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    // public function buildRules(RulesChecker $rules)
    // {
    //     $rules->add($rules->existsIn(['email_template_id'], 'EmailTemplates'));
    //     $rules->add($rules->existsIn(['user_id'], 'Users'));

    //     return $rules;
    // }
}

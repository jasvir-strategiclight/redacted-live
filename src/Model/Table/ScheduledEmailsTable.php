<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ScheduledEmails Model
 *
 * @property \App\Model\Table\EmailTemplatesTable|\Cake\ORM\Association\BelongsTo $EmailTemplates
 * @property \App\Model\Table\ScheduledEmailApartmentsTable|\Cake\ORM\Association\HasMany $ScheduledEmailApartments
 *
 * @method \App\Model\Entity\ScheduledEmail get($primaryKey, $options = [])
 * @method \App\Model\Entity\ScheduledEmail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ScheduledEmail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ScheduledEmail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ScheduledEmail saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ScheduledEmail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ScheduledEmail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ScheduledEmail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ScheduledEmailsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('scheduled_emails');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('EmailTemplates', [
            'foreignKey' => 'email_template_id',
            'joinType'   => 'LEFT'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType'   => 'LEFT'
        ]);

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->requirePresence('from_email', 'create')
            ->allowEmptyString('from_email', false);

        $validator
            ->requirePresence('to_email', 'create')
            ->allowEmptyString('to_email', false);


        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {

        return $rules;
    }
}

<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Http\Session;
/**
 * Users Model
 *
 * @property \App\Model\Table\ImagesTable|\Cake\ORM\Association\BelongsTo $Images
 * @property \App\Model\Table\ExerciseAttributesTable|\Cake\ORM\Association\HasMany $ExerciseAttributes
 * @property \App\Model\Table\FavoriteExercisesTable|\Cake\ORM\Association\HasMany $FavoriteExercises
 * @property \App\Model\Table\SubscriptionsTable|\Cake\ORM\Association\HasMany $Subscriptions
 * @property \App\Model\Table\UserRoutinesTable|\Cake\ORM\Association\HasMany $UserRoutines
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');


        $this->addBehavior('CounterCache', [
            'Users' => [
                'no_of_affiliates'
            ],
        ]);

        $this->addBehavior('Timestamp');


        $this->belongsTo('Users', [
            'foreignKey' => 'affiliated_by',
            'joinType'   => 'LEFT',
        ]);
        
        $this->belongsTo('Affiliates', [
            'className' => 'Users',
            'foreignKey' => 'affiliated_by',
            'joinType'   => 'LEFT',
        ]);

        


        $this->hasMany('UserStickers', [
            'foreignKey' => 'user_id'
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
            ->email('email')
            ->requirePresence('email', 'create')
            ->allowEmptyString('email', false);


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
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}

<?php

namespace App\Controller\Admin;

/**
 * Rewards Controller
 *
 * @property \App\Model\Table\RewardsTable $Rewards
 *
 * @method \App\Model\Entity\Sticker[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RewardsController extends AppController {
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Images'],
        ];
        $rewards = $this->paginate($this->Rewards);

        $this->set(compact('rewards'));
    }

    /**
     * View method
     *
     * @param string|null $id Sticker id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $reward = $this->Rewards->get($id, [
            'contain' => ['Images', 'UserRewards'],
        ]);

        $this->set('reward', $reward);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $reward = $this->Rewards->newEntity();
        if ($this->request->is('post')) {
            $reward = $this->Rewards->patchEntity($reward, $this->request->getData());
            if ($this->Rewards->save($reward)) {
                $this->Flash->success(__('The reward has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else{
                pr($reward->getErrors()); die;
            }
            $this->Flash->error(__('The reward could not be saved. Please, try again.'));
        }
        $this->set(compact('reward', 'images'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sticker id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $reward = $this->Rewards->get($id, [
            'contain' => ['Images'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reward = $this->Rewards->patchEntity($reward, $this->request->getData());
            if ($this->Rewards->save($reward)) {
                $this->Flash->success(__('The reward has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reward could not be saved. Please, try again.'));
        }
        $this->set(compact('reward', 'images'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sticker id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $reward = $this->Rewards->get($id);
        if ($this->Rewards->delete($reward)) {
            $this->Flash->success(__('The reward has been deleted.'));
        } else {
            $this->Flash->error(__('The reward could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

<?php
/**
 * Copyright (c) Japan Computer Services, Inc.
 *
 * Licensed under The MIT License
 *
 * @copyright Copyright (c) Japan Computer Services, Inc. (http://www.japacom.co.jp)
 * @since     1.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
 * Name: MovesController
 * 
 */
class MovesController extends AppController
{
    /**
     * Initialize callback.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    /**
     * Before filter callback.
     *
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * method: get
     * url   : /
     *
     */
    public function index()
    {
        $query = $this->Moves->find();
        $moves = $query
            ->order(['dt' => 'DESC'])
            ->limit(30);

        $this->set([
            'moves' => $moves,
            '_serialize' => ['moves']
        ]);
    }

    /**
     * method: get
     * url   : /
     *
     */
    public function add()
    {
        //$this->log($this->request->data, 'debug');

        $move = $this->Moves->newEntity();
        $move = $this->Moves->patchEntity($move, [
            'dt'   => $this->request->data['datetime'],
            'val'  => $this->request->data['orientation']
        ]);

        $result = ['code' => 'S', 'message' => 'Success: orientation save is success.'];
        if (!$this->Moves->save($move)) {
            $result = ['code' => 'E', 'message' => 'Error: unexpected error is occurred when save orientation data.'];
        }

        $this->set([
            'result' => $result,
            '_serialize' => ['result']
        ]);
    }
}

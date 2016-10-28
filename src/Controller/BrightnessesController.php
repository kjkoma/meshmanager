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
 * Name: BrightnessesController
 * 
 */
class BrightnessesController extends AppController
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
        // $brightnesses = ['sample' => 'This is sample'];

        $query = TableRegistry::get('VBrightnesses');
        $query = $query->find();
        $brightnesses = $query
            ->order(['dt' => 'DESC'])
            ->limit(30);

        $this->set([
            'brightnesses' => $brightnesses,
            '_serialize' => ['brightnesses']
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

        $brightness = $this->Brightnesses->newEntity();
        $brightness = $this->Brightnesses->patchEntity($brightness, [
            'dt'   => $this->request->data['datetime'],
            'val'  => $this->request->data['brightness']
        ]);

        $result = ['code' => 'S', 'message' => 'Success: brightness save is success.'];
        if (!$this->Brightnesses->save($brightness)) {
            $result = ['code' => 'E', 'message' => 'Error: unexpected error is occurred when save brightness data.'];
        }

        $this->set([
            'result' => $result,
            '_serialize' => ['result']
        ]);
    }
}

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
 * Name: RaspberryTempsController
 * 
 */
class RaspberryTempsController extends AppController
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
        // $temps = ['sample' => 'This is sample'];

        $query = TableRegistry::get('VRaspberryTemps');
        $query = $query->find();
        $temps = $query
            ->order(['dt' => 'DESC'])
            ->limit(30);

        $this->set([
            'temps' => $temps,
            '_serialize' => ['temps']
        ]);
    }

    /**
     * method: get
     * url   : /
     *
     */
    public function add()
    {
        // $this->log($this->request->data, 'debug');

        $temp = $this->RaspberryTemps->newEntity();
        $temp = $this->RaspberryTemps->patchEntity($temp, [
            'dt'   => $this->request->data['datetime'],
            'val'  => $this->request->data['temperature']
        ]);

        $result = ['code' => 'S', 'message' => 'Success: temperature save is success.'];
        if (!$this->RaspberryTemps->save($temp)) {
            $result = ['code' => 'E', 'message' => 'Error: unexpected error is occurred when save temperature data.'];
        }

        $this->set([
            'result' => $result,
            '_serialize' => ['result']
        ]);
    }
}

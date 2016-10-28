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
 * Name: TemperaturesController
 * 
 */
class TemperaturesController extends AppController
{
    /**
     * Initialize callback.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
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
        // $temperatures = ['sample' => 'This is sample'];

        $query = TableRegistry::get('VTemperatures');
        $query = $query->find();
        $temperatures = $query
            ->order(['dt' => 'DESC'])
            ->limit(30);

        $this->set([
            'temperatures' => $temperatures,
            '_serialize' => ['temperatures']
        ]);
    }

    /**
     * method: post
     * url   : /
     *
     */
    public function add()
    {
        //$this->log($this->request->data, 'debug');

        $tempture = $this->Temperatures->newEntity();
        $tempture = $this->Temperatures->patchEntity($tempture, [
            'dt'   => $this->request->data['datetime'],
            'val'  => $this->request->data['temperature']
        ]);

        $result = ['code' => 'S', 'message' => 'Success: temperature save is success.'];
        if (!$this->Temperatures->save($tempture)) {
            $result = ['code' => 'E', 'message' => 'Error: unexpected error is occurred when save temperature data.'];
        }

        $this->set([
            'result' => $result,
            '_serialize' => ['result']
        ]);
    }
}

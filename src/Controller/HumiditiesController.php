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
 * Name: HumiditiesController
 * 
 */
class HumiditiesController extends AppController
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
        // $humidities = ['sample' => 'This is sample'];

        $query = TableRegistry::get('VHumidities');
        $query = $query->find();
        $humidities = $query
            ->order(['dt' => 'DESC'])
            ->limit(30);

        $this->set([
            'humidities' => $humidities,
            '_serialize' => ['humidities']
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

        $humidity = $this->Humidities->newEntity();
        $humidity = $this->Humidities->patchEntity($humidity, [
            'dt'   => $this->request->data['datetime'],
            'val'  => $this->request->data['humidity']
        ]);

        $result = ['code' => 'S', 'message' => 'Success: humidity save is success.'];
        if (!$this->Humidities->save($humidity)) {
            $result = ['code' => 'E', 'message' => 'Error: unexpected error is occurred when save humidity data.'];
        }

        $this->set([
            'result' => $result,
            '_serialize' => ['result']
        ]);
    }
}

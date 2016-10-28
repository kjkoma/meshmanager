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
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Name: MovesTable
 * 
 * 
 */
class MovesTable extends Table
{
    /**
     * Initialize Function
     *
     */
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    /**
     * Validation Function
     *
     */
    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('dt', __('A datetime is required'))
            ->notEmpty('val', __('A temperature value is required'));
    }
}
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Ability extends AppModel {
     public $hasAndBelongsToMany = array(
                    'Ability' => array(
                        'className' => 'Ability',
                        'joinTable' => 'user_abilities',
                        'foreignKey' => 'user_id',
                        'associationForeignKey' => 'ability_id',
                        'unique' => true
));
}
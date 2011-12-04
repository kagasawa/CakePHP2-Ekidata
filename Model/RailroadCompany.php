<?php
/* SVN FILE: $Id$ */
/**
 * [Plugin::Ekidata] 鉄道会社モデル
 *
 * CakePHP 2.0.X
 * PHP versions 5.3 
 *
 * @copyright     COPYRIGHTS (C) 2000-2011 Web-Promotions Limited. All Rights Reserved.
 * @link          http://www.web-prom.net/
 * @package       Plugin::Ekidata
 * @subpackage    CakePHP 2.0.X
 * @headUrl       $HeadURL$
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license
 * 
 * @author        Hideyuki Kagasawa. (kagasawa@web-prom.net)
 */

App::uses('EkidataAppModel', 'Ekidata.Model');

/**
 * RailroadCompany Model
 *
 */
class RailroadCompany extends EkidataAppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'RailroadLine' => array(
            'className' => 'RailroadLine',
            'foreignKey' => 'railroad_company_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

}

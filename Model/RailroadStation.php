<?php
/* SVN FILE: $Id$ */
/**
 * [Plugin::Ekidata] 駅モデル
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
 * RailroadStation Model
 *
 * @property RailroadLine $RailroadLine
 * @property RailroadPref $RailroadPref
 */
class RailroadStation extends EkidataAppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'RailroadLine' => array(
            'className' => 'RailroadLine',
            'foreignKey' => 'railroad_line_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'RailroadPref' => array(
            'className' => 'RailroadPref',
            'foreignKey' => 'railroad_pref_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    /**
     * 指定都道府県の路線IDを取得
     *
     * @param integer $prefId
     * @return (array|bool) 
     */
    public function getPrefLineId($prefId=0) {
        $query = array(
            'conditions' => array(
                $this->alias.'.railroad_pref_id' => $prefId,
            ),
            'group' => array(
                $this->alias.'.railroad_line_id',
            ),
            'fields' => array(
                $this->alias.'.railroad_line_id',
            ),
        );
        $lines = $this->find('all', $query);
        
        if ( !empty($lines) ) {
            return Set::extract('/'.$this->alias.'/railroad_line_id', $lines);
        } else {
            return false;
        }
    }

}

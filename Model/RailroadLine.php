<?php
/* SVN FILE: $Id$ */
/**
 * [Plugin::Ekidata] 路線モデル
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
 * RailroadLine Model
 *
 * @property RailroadCompany $RailroadCompany
 */
class RailroadLine extends EkidataAppModel {

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
        'RailroadCompany' => array(
            'className' => 'RailroadCompany',
            'foreignKey' => 'railroad_company_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'RailroadStation' => array(
            'className' => 'RailroadStation',
            'foreignKey' => 'railroad_line_id',
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

    /**
     * 指定都道府県の鉄道会社IDを取得
     *
     * @param integer $prefId
     * @return (array|bool) 
     */
    public function getPrefCompanyId($prefId=0) {
        
        // RailroadStationインポート
        App::import('Ekidata.Model', 'RailroadStation');
        $RailroadStation = ClassRegistry::init('RailroadStation');

        // 指定都道府県に所属する駅の全路線IDを取得
        $lineId = $RailroadStation->getPrefLineId($prefId);
        
        if ( !empty($lineId) ) {
            $query = array(
                'conditions' => array(
                    $this->alias.'.id' => $lineId,
                ),
                'group' => array(
                    $this->alias.'.railroad_company_id',
                ),
                'fields' => array(
                    $this->alias.'.railroad_company_id',
                ),
            );
            $companies = $this->find('all', $query);
            
            if ( !empty($companies) ) {
                return Set::extract('/'.$this->alias.'/railroad_company_id', $companies);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

<?php
/* SVN FILE: $Id$ */
/**
 * [Plugin::Ekidata] Plugin::Application Level Model
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

App::uses('AppModel', 'Model');

class EkidataAppModel extends AppModel {

    public $actsAs = array('Containable');
    
    /**
     * truncate
     * 
     * @param type $tableName
     * @return bool
     */
    public function truncate($tableName = null) {
        if (is_null($tableName))
            $tableName = $this->table;

        if (!$tableName || is_null($tableName)) {
            return false;
        }

        return $this->getDataSource()->truncate($tableName);
    }

    /**
     * find override
     *
     * @param type $type
     * @param array $query
     * @return type 
     */
    public function find($type = 'first', $query = array()) {

        // [TIPS]
        // CakePHPはdefaultでAssociation指定の全Modelがリレーションされてしまうので
        // ContainableBehaviorを有効にしつつ、containの空配列を強制的に追加することで、
        // defaultでMainモデル以外の配列が戻らなくなるように調整
        if (!isset($query['contain'])) {
            $query['contain'] = false;
        }

        return parent::find($type, $query);
    }
}


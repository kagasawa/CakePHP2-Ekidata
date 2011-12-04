<?php
/* SVN FILE: $Id$ */
/**
 * [Plugin::Ekidata] APIコントローラ
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

App::uses('EkidataAppController', 'Ekidata.Controller');

class ApiController extends EkidataAppController {
    public $name = 'Api';
    public $uses = array('Ekidata.RailroadCompany', 'Ekidata.RailroadLine', 'Ekidata.RailroadStation');
    public $helpers = array('Form', 'Html', 'Session', 'Js');

    /**
     * beforeFilter
     *
     * @access public
     */
    public function beforeFilter() {
        parent::beforeFilter();
//        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->layout = 'ajax';
        
        // 運用モードの場合はAjaxRequestのみ許可する
        if ( Configure::read('debug')==0 && !$this->request->is('ajax') ) {
            throw new MethodNotAllowedException();
        }
    }

    /**
     * JSON::鉄道会社
     *
     * @param integer $prefId 
     */
    public function companies($prefId=0) {
        // 検索条件の初期化
        $conditions = array();

        // 都道府県が指定された場合
        if ( !empty($prefId) ) {
            // 指定した都道府県に所属する駅の鉄道会社IDを全て取得
            $companyId = $this->RailroadLine->getPrefCompanyId($prefId);
            if ( !empty($companyId) ) {
                $conditions = Set::merge($conditions, array(
                    'RailroadCompany.id' => $companyId,
                ));
            }
        }

        // 鉄道会社JSONを出力
        $query = array(
            'conditions' => $conditions,
            'order' => array(
                'RailroadCompany.id' => 'ASC',
            ),
        );
        $_data = $this->RailroadCompany->find('all', $query);
        $this->_jsonRender($_data);
    }
    
    /**
     * JSON::路線
     *
     * @param integer $prefId 
     */
    public function lines($prefId=0) {
        $this->_lines($prefId);
    }

    /**
     * JSON::路線+駅
     *
     * @param integer $prefId 
     */
    public function line_stations($prefId=0) {
        $this->_lines($prefId, array('RailroadStation'));
    }

    /**
     * JSON::路線
     *
     * @param integer $prefId 
     * @param array $contain 
     */
    private function _lines($prefId=0, $contain=array()) {

        if ( empty($prefId) ) {
            $this->_jsonRender();
            exit;
        }

        // 検索条件の初期化
        $conditions = array();

        // 都道府県が指定された場合
        if ( !empty($prefId) ) {
            // 指定した都道府県に所属する駅の路線IDを全て取得
            $lineId = $this->RailroadStation->getPrefLineId($prefId);
            if ( !empty($lineId) ) {
                $conditions = Set::merge($conditions, array(
                    'RailroadLine.id' => $lineId,
                ));
            }
        }
        
        // 鉄道会社JSONを出力
        $query = array(
            'conditions' => $conditions,
            'contain' => $contain,
            'order' => array(
                'RailroadLine.sort' => 'ASC',
            ),
        );
        
        $_data = $this->RailroadLine->find('all', $query);
        $this->_jsonRender($_data);
    }
    
    /**
     * JSON::駅
     *
     * @param integer $lineId 
     */
    public function stations($lineId=0) {
        
        if ( empty($lineId) ) {
            $this->_jsonRender();
            exit;
        }
        
        // 駅JSONを出力
        $query = array(
            'conditions' => array(
                'RailroadStation.railroad_line_id' => $lineId,
            ),
            'order' => array(
                'RailroadStation.sort' => 'ASC',
            ),
        );
        $_data = $this->RailroadStation->find('all', $query);
        $this->_jsonRender($_data);
    }
    
    /**
     * JSONレンダリング
     *
     * @param array $data 
     */
    private function _jsonRender($_data=array()) {
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($_data);
    }
}
<?php
/* SVN FILE: $Id$ */
/**
 * [Plugin::Ekidata] Controller側の処理を自動化するComponent
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
/**
 * [@tips]
 * 
 * 呼び出し元のControllerで以下のように宣言します。
 * 
 * public $components = array(
 *      'Ekidata.Railroader' => array(
 *          'pref' => array(
 *              'id' => 'pref_id',
 *              'list' => 'prefs',
 *          ),
 *          'line' => array(
 *              'id' => 'line_id',
 *              'list' => 'lines',
 *          ),
 *      ),
 * );
 * 
 * [OPTION]
 * 
 * pref/line/station : 都道府県/路線/駅のForm部品の設定識別子
 * id : Form部品のID値
 * list : Form部品のoptionsに渡す変数名
 * 
 * 
 * [APENDIX]
 * 
 * 対応するViewで以下のようにForm部品を配置します
 * 
 * echo $this->Form->input('pref_id', array('type' => 'select', 'id'=>'pref_id', 'options' => $prefs, 'empty' => '選択して下さい▼'));
 * echo $this->Form->input('line_id', array('type' => 'select', 'id'=>'line_id', 'options' => $lines, 'empty' => '選択して下さい▼'));
 * 
 */

App::uses('Component', 'Controller');
App::import('Ekidata.Model', 'RailroadPref');
App::import('Ekidata.Model', 'RailroadLine');
App::import('Ekidata.Model', 'RailroadStation');

class RailroaderComponent extends Component {

    /**
     * Setting
     *
     * @var type 
     */
    public $settings = array(
        'pref' => array(
            'id' => 'railroad_pref_id',
            'list' => 'railroadPrefs',
        ),
        'line' => array(
            'id' => 'railroad_line_id',
            'list' => 'railroadLines',
        ),
        'station' => array(
            'id' => 'railroad_station_id',
            'list' => 'railroadStations',
        ),
    );
    
    /**
     * constructor override
     *
     * @param ComponentCollection $collection
     * @param type $settings 
     */
	public function __construct(ComponentCollection $collection, $settings = array()) {
        
        // Coreでは$this->settingsを上書きしてるのでデフォルトを保管しておく
        $default = $this->settings;
        
        // 親のconstructorを実行
        parent::__construct($collection, $settings);
        
        // Coreでは$this->settingsを上書きしてるのでmergeに書き換える
		$this->settings = Set::merge($default, $settings);
	}
    
    /**
     * initialize
     *
     * @param type $controller 
     */
    public function initialize($controller) {
    }

    /**
     * startup
     *
     * @param type $controller 
     */
    public function startup($controller) {
        $this->RailroadPref = ClassRegistry::init('RailroadPref');
        $this->RailroadLine = ClassRegistry::init('RailroadLine');
        $this->RailroadStation = ClassRegistry::init('RailroadStation');
    }

    /**
     * beforeRender
     *
     * @param type $controller 
     */
    public function beforeRender($controller) {
        
        $railroadPrefs = $this->RailroadPref->find('list');

        $railroadLines = array();
        if ( !empty($controller->request->data[$controller->modelClass][$this->settings['pref']['id']]) ) {
            $lineId = $this->RailroadStation->getPrefLineId($controller->request->data[$controller->modelClass][$this->settings['pref']['id']]);
            if ( !empty($lineId) ) {
                $query = array(
                    'conditions' => array(
                        'RailroadLine.id' => $lineId,
                    ),
                    'order' => array(
                        'RailroadLine.sort' => 'ASC',
                    ),
                );
                $railroadLines = $this->RailroadLine->find('list', $query);
            }
        }

        $railroadStations = array();
        if ( !empty($controller->request->data[$controller->modelClass][$this->settings['line']['id']]) ) {
            $query = array(
                'conditions' => array(
                    'RailroadStation.railroad_line_id' => $controller->request->data[$controller->modelClass][$this->settings['line']['id']],
                ),
                'order' => array(
                    'RailroadStation.sort' => 'ASC',
                ),
            );
            $railroadStations = $this->RailroadStation->find('list', $query);
        }

        $controller->set(array(
            $this->settings['pref']['list'] => $railroadPrefs,
            $this->settings['line']['list'] => $railroadLines,
            $this->settings['station']['list'] => $railroadStations,
        ));
    }
}

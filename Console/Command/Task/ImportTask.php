<?php
/* SVN FILE: $Id$ */
/**
 * [Plugin::Ekidata] Import Task
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
 * CakePHP Model Code Completion
 *
 * ==============================================
 * Add many controller to use your model
 * ==============================================
 * @property RailroadCompany $RailroadCompany
 * @property RailroadLine $RailroadLine
 * @property RailroadStation $RailroadStation
 */
App::uses('Shell', 'Console');

class ImportTask extends Shell {

    public $uses = array('Ekidata.RailroadCompany', 'Ekidata.RailroadLine', 'Ekidata.RailroadStation');

    /**
     * CSVの設置場所
     * ※initializeでdefaultセット
     *
     * @var string 
     */
    private $_dataPath = null;
    
    /**
     * CSVファイル名
     *
     * @var type 
     */
    private $_csv = 'm_station.csv';
    
    /**
     * CSVのカラム名
     *
     * @var type 
     */
    private $_column = array(
        'rr_cd', 'line_cd', 'station_cd', 'line_sort', 'station_sort', 'station_g_cd', 'r_type', 'rr_name', 'line_name', 'station_name', 'pref_cd', 'lon', 'lat', 'f_flag',
    );
    
    /**
     * CSVの文字コード
     *
     * @var type 
     */
    private $_csvEncode = 'euc-jp';
    
    /**
     * 出力時の文字コード(dbにあわせる)
     *
     * @var type 
     */
    private $_exportEncode = 'utf8';

    /**
     * initialize
     *
     * @access public
     */
    public function initialize() {
        parent::initialize();
        
        if ( $this->_dataPath == null ) {
            $this->_dataPath = APP.'Plugin'.DS.'Ekidata'.DS.'Vendor'.DS.'data'.DS;
        }
    }

    /**
     * startup
     *
     * @access public
     */
    public function startup(){
        // overrideでcakeメッセージ除去
    }

    /**
     * execute
     *
     * @access public
     */
    public function execute() {
        
        $csvFile = $this->_dataPath.$this->_csv;
        
        // CSVファイルの存在チェック
        if ( !file_exists($csvFile) ) {
            $this->out($csvFile." がありません。".$this->_csv."を取得して".$this->_dataPath."に設置して下さい。\n");
            return;
        }
        
        // テーブルを破棄
        if ( !$this->RailroadCompany->truncate() ) {
            $this->out($this->RailroadCompany->alias.' テーブルが truncate に失敗しました');
            return;
        }
        if ( !$this->RailroadLine->truncate() ) {
            $this->out($this->RailroadLine->alias.' テーブルが truncate に失敗しました');
            return;
        }
        if ( !$this->RailroadStation->truncate() ) {
            $this->out($this->RailroadStation->alias.' テーブルが truncate に失敗しました');
            return;
        }
        
        // ファイルOPEN
        $fp = fopen($csvFile, "r");
        
        // 1行目がタイトルなので空読み
        $this->_fgetcsv_reg($fp);
        
        // データ作成
        while($line = $this->_fgetcsv_reg($fp)){
            
            // CSVを行単位で整形して取得
            $data = $this->_convertData($line);

            // 鉄道会社の存在チェック
            $query = array(
                'conditions' => array(
                    'RailroadCompany.name' => $data['rr_name'],
                ),
            );
            $railroadCompany = $this->RailroadCompany->find('first', $query);
            if ( empty($railroadCompany) ) {
                $_data = array(
                    'id' => 0,
                    'name' => $data['rr_name'],
                );
                if ( !$this->RailroadCompany->save($_data) ) {
                    $this->out($this->RailroadCompany->alias.' テーブルの save に失敗しました');
                    return;
                }
                $query = array(
                    'conditions' => array(
                        'RailroadCompany.id' => $this->RailroadCompany->getLastInsertId(),
                    ),
                );
                $railroadCompany = $this->RailroadCompany->find('first', $query);
                $this->out('鉄道会社:'.$railroadCompany['RailroadCompany']['name']);
            }
            
            // 路線の存在チェック
            $query = array(
                'conditions' => array(
                    'RailroadLine.name' => $data['line_name'],
                ),
            );
            $railroadLine = $this->RailroadLine->find('first', $query);
            if ( empty($railroadLine) ) {
                $_data = array(
                    'id' => 0,
                    'railroad_company_id' => $railroadCompany['RailroadCompany']['id'],
                    'name' => $data['line_name'],
                    'sort' => $data['line_sort'],
                );
                if ( !$this->RailroadLine->save($_data) ) {
                    $this->out($this->RailroadLine->alias.' テーブルの save に失敗しました');
                    return;
                }
                $query = array(
                    'conditions' => array(
                        'RailroadLine.id' => $this->RailroadLine->getLastInsertId(),
                    ),
                );
                $railroadLine = $this->RailroadLine->find('first', $query);
                $this->out('路線:'.$railroadLine['RailroadLine']['name']);
            }

            // 駅のインポート
            $_data = array(
                'id' => 0,
                'railroad_line_id' => $railroadLine['RailroadLine']['id'],
                'railroad_pref_id' => $data['pref_cd'],
                'name' => $data['station_name'],
                'sort' => $data['station_sort'],
                'lon' => $data['lon'],
                'lat' => $data['lat'],
            );
            if ( !$this->RailroadStation->save($_data) ) {
                $this->out($this->RailroadStation->alias.' テーブルの save に失敗しました');
                return;
            }
            $query = array(
                'conditions' => array(
                    'RailroadStation.id' => $this->RailroadStation->getLastInsertId(),
                ),
            );
            $railroadStation = $this->RailroadStation->find('first', $query);
            $this->out('駅:'.$railroadStation['RailroadStation']['name']);
        }

        $this->out("-- COMPLEET --\n");

        fclose($fp);
    }

    /**
     * 文字コード変換とカラム名付きの連想配列に格納
     *
     * @param type $line
     * @return type 
     */
    private function _convertData($line) {
        mb_convert_variables($this->_exportEncode, $this->_csvEncode, $line);
        $data = array();
        foreach ($line as $key => $val) {
            $data[$this->_column[$key]] = $val;
        }
        return $data;
    }


    /**
     * fgetcsv_reg
     * fgetcsv文字化け対策関数
     *
     * @param mixed $handle
     * @param mixed $length
     * @param string $d
     * @param ' $'
     * @param string $e
     * @access public
     * @return void
     */
    private function _fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = "";
        $eof = false;
        while ($eof != true) {
            $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
            if ($itemcnt % 2 == 0) $eof = true;
        }
        $_csv_line = preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
        $_csv_data = $_csv_matches[1];
        for($_csv_i=0; $_csv_i<count($_csv_data); $_csv_i++) {
            $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
            $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }
        return empty($_line) ? false : $_csv_data;
    }

}

?>
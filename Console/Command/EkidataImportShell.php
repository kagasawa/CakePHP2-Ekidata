<?php
/* SVN FILE: $Id$ */
/**
 * [Plugin::Ekidata] Import Shell
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

App::uses('Shell', 'Console');

class EkidataImportShell extends Shell {

    public $tasks = array('Ekidata.Import');

    public $uses = array();

    public $menus = array(
        1 => array(
            'task' => 'Import',
            'alt' => '駅データをインポート',
        ),
        'q' => array(
            'task' => null,
            'alt' => '終了',
        ),
    );

    /**
     * initialize
     *
     * @access public
     */
    public function initialize() {
        parent::initialize();
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
     * main
     *
     * @access public
     */
    public function main() {

        $menuKeys = array_keys($this->menus);

        //メインメニュー表示
        $value = "";

        $msg = null;
        $msg .= "---------------------------------------------\n";
        $msg .= "> main menu\n";
        foreach($this->menus as $k => $v) {
            $msg .= "[{$k}] {$v['alt']}\n";
        }
        $msg .= "---------------------------------------------\n";
        $msg .= "実行するメニューの番号を選択してください\n";

        while ($value <> "q") {
            $value = $this->in($msg, $menuKeys, "q" );
            if ($value <> 'q') {
                $this->{$this->menus[$value]['task']}->execute();
            }
        }
    }

}
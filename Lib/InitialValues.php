<?php
/* SVN FILE: $Id$ */
/**
 * [Plugin::Ekidata] Schemaの初期データ挿入クラス
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

App::uses('ClassRegistry', 'Utility');

class InitialValues extends Object {

    private $_values = array(
        'RailroadPref' => array(
            array(
               'id' => 1,
               'name' => '北海道',
            ),
            array(
               'id' => 2,
               'name' => '青森県',
            ),
            array(
               'id' => 3,
               'name' => '岩手県',
            ),
            array(
               'id' => 4,
               'name' => '宮城県',
            ),
            array(
               'id' => 5,
               'name' => '秋田県',
            ),
            array(
               'id' => 6,
               'name' => '山形県',
            ),
            array(
               'id' => 7,
               'name' => '福島県',
            ),
            array(
               'id' => 8,
               'name' => '茨城県',
            ),
            array(
               'id' => 9,
               'name' => '栃木県',
            ),
            array(
               'id' => 10,
               'name' => '群馬県',
            ),
            array(
               'id' => 11,
               'name' => '埼玉県',
            ),
            array(
               'id' => 12,
               'name' => '千葉県',
            ),
            array(
               'id' => 13,
               'name' => '東京都',
            ),
            array(
               'id' => 14,
               'name' => '神奈川県',
            ),
            array(
               'id' => 15,
               'name' => '新潟県',
            ),
            array(
               'id' => 16,
               'name' => '富山県',
            ),
            array(
               'id' => 17,
               'name' => '石川県',
            ),
            array(
               'id' => 18,
               'name' => '福井県',
            ),
            array(
               'id' => 19,
               'name' => '山梨県',
            ),
            array(
               'id' => 20,
               'name' => '長野県',
            ),
            array(
               'id' => 21,
               'name' => '岐阜県',
            ),
            array(
               'id' => 22,
               'name' => '静岡県',
            ),
            array(
               'id' => 23,
               'name' => '愛知県',
            ),
            array(
               'id' => 24,
               'name' => '三重県',
            ),
            array(
               'id' => 25,
               'name' => '滋賀県',
            ),
            array(
               'id' => 26,
               'name' => '京都府',
            ),
            array(
               'id' => 27,
               'name' => '大阪府',
            ),
            array(
               'id' => 28,
               'name' => '兵庫県',
            ),
            array(
               'id' => 29,
               'name' => '奈良県',
            ),
            array(
               'id' => 30,
               'name' => '和歌山県',
            ),
            array(
               'id' => 31,
               'name' => '鳥取県',
            ),
            array(
               'id' => 32,
               'name' => '島根県',
            ),
            array(
               'id' => 33,
               'name' => '岡山県',
            ),
            array(
               'id' => 34,
               'name' => '広島県',
            ),
            array(
               'id' => 35,
               'name' => '山口県',
            ),
            array(
               'id' => 36,
               'name' => '徳島県',
            ),
            array(
               'id' => 37,
               'name' => '香川県',
            ),
            array(
               'id' => 38,
               'name' => '愛媛県',
            ),
            array(
               'id' => 39,
               'name' => '高知県',
            ),
            array(
               'id' => 40,
               'name' => '福岡県',
            ),
            array(
               'id' => 41,
               'name' => '佐賀県',
            ),
            array(
               'id' => 42,
               'name' => '長崎県',
            ),
            array(
               'id' => 43,
               'name' => '熊本県',
            ),
            array(
               'id' => 44,
               'name' => '大分県',
            ),
            array(
               'id' => 45,
               'name' => '宮崎県',
            ),
            array(
               'id' => 46,
               'name' => '鹿児島県',
            ),
            array(
               'id' => 47,
               'name' => '沖縄県',
            ),
        ),
    );
    
    public function startup($schema = null) {
        if ($schema === null) {
            return false;
        }
    }

    public function set($modelname) {
        if (!empty($this->_values[$modelname])) {
            $this->{$modelname} = ClassRegistry::init($modelname);
            $this->{$modelname}->saveAll($this->_values[$modelname]);
        }
    }

}


# 駅データ Plugin for CakePHP2.0.4 #

JP StationData Plugin for CakePHP.

駅データ Pluginは駅データを活用するCakePHPプラグインです。

都道府県:[選択して下さい▼]
路線:[選択して下さい▼]
駅:[選択して下さい▼]

このようなFormがあった場合、都道府県の選択内容によって路線SELECTボックスの中身が変わり、
同様に路線SELECTボックスの選択内容によって駅SELECTボックスの中身が変わります。

この機能を実現する為に必要な

* 路線や駅などのデータ
* Ajax用のJavaScriptコード(jQuery仕様)
* AjaxからアクセスされるAPI

といった必要と思われる各機能を備えております。

不動産物件検索サイトを開発するに当たって再利用性を考慮してplug-inとしてまとめました。


## インストール ##

app/Plugin以下に、ダウンロードした圧縮ファイルをEkidataディレクトリとして展開・設置してください。
いつものCakePHPプラグインの設置と同様です。

## セットアップ ##

Ekidata Pluginを利用する前に、1つの手続きを踏む必要があります。
それは「駅データCSVのダウンロード」です。


### 0.準備 ###

駅データ(Station Database)
http://www.ekidata.jp/

から駅CSVをダウンロードして下さい。
※リリース時点でのファイル名は「m_station.csv」で文字コードはeuc-jpでした。

ダウンロードしたら
app/Plugin/Ekidata/Vendor/data

以下に「m_station.csv」を配置して下さい。文字コードは「euc-jp」のままで構いません。


このテキストと同じ階層に

* ekidata_import.sh
* ekidata_schema.sh

の.shファイルがあるのでcakeのルートディレクトリにコピーまたは移動させて下さい。


### 1.駅データ用テーブル作成 ###

駅データ用テーブルを作成します。

schemaコマンドを使用する場合

    $ cake schema create Ekidata --path app/Plugin/Ekidata/Config/Schema

    または ekidata_schema.sh を実行


SQLでテーブルを生成する場合

    付属のtable.sqlを利用して下さい。

    ※上記SQLはMySQLのSQLです。PostgreSQLはschemaから取り込んで下さい。


### 2.駅データをインポート ###

    $ cake Ekidata.ekidata_import

    または ekidata_import.sh を実行

メニューが表示されるので「1.駅データをインポート」を実行して下さい。


## 使い方 ##

Ekidata Pluginは大きく分けて2つの使用方法があります。

### 1.アプリケーションに組み込む ###

app/Config/bootstrap.php

<?php
    CakePlugin::loadAll();
    // または
    CakePlugin::load('Ekidata');



app/Controller/HogesController.php

<?php
class HogesController extends AppController {

    public $components = array(
        'Ekidata.Railroader' => array(
            'pref' => array(
                'id' => 'pref_id',
                'list' => 'prefs',
            ),
            'line' => array(
                'id' => 'line_id',
                'list' => 'lines',
            ),
            'station' => array(
                'id' => 'station_id',
                'list' => 'stations',
            ),
        ),
    );


パラメータの詳細は
app/Controller/Component/RailroaderComponent.php のコメントを参照して下さい。



app/View/Hoges/add|edit.ctp

<?php
    $datas = array(
        'loadJquery' => true,
        'emptyText' => '選択して下さい▼',
        'prefId' => '#pref_id',
        'lineId' => '#line_id',
        'stationId' => '#station_id',
    );
    echo $this->element('interface', $datas, array('plugin'=>'ekidata'));
?>

<?php
    echo $this->Form->input('pref_id', array('type' => 'select', 'id'=>'pref_id', 'options' => $prefs, 'empty' => '選択して下さい▼'));
    echo $this->Form->input('line_id', array('type' => 'select', 'id'=>'line_id', 'options' => $lines, 'empty' => '選択して下さい▼'));
    echo $this->Form->input('station_id', array('type' => 'select', 'id'=>'station_id', 'options' => $stations, 'empty' => '選択して下さい▼'));
?>

パラメータの詳細は
app/View/Elements/interface.ctp のコメントを参照して下さい。


### 2.APIを利用した検索 ###

Ekidata PluginではAjax等で利用するためのHTTPインターフェースを提供しています。

* 鉄道会社取得API (/ekidata/api/companies/都道府県ID) 都道府県ID付与で指定都道府県内の鉄道会社のみ取得。都道府県ID無しで全国の鉄道会社を取得。
* 路線取得API (/ekidata/api/lines/都道府県ID) 都道府県IDは必須。指定都道府県内の路線のみ取得。
* 路線+駅取得API (/ekidata/api/line_stations/都道府県ID) 都道府県IDは必須。指定都道府県内の路線とその路線内の全ての駅を取得。
* 駅取得API (/ekidata/api/stations/路線ID) 路線IDは必須。指定路線内の全ての駅を取得。

出力フォーマットは全てJSON形式です。


# Special Thanks #

駅データ(Station Database)
http://www.ekidata.jp/

素晴らしく有益なデータを無償で公開して頂き、誠にありがとうございます。
本plug-inは駅データ様のCSVフォーマットをベースに構築されております。



# License #

The MIT Lisence

COPYRIGHTS (C) 2000-2011 Web-Promotions Limited. All Rights Reserved. (http://www.web-prom.net/)

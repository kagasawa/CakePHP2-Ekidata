<?php
/* SVN FILE: $Id$ */
/**
 * [Plugin::Ekidata] Interface Element
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
 * アプリ本体のViewに以下のように記述してelementを読み出して下さい
 * 
 * $datas = array(
 *     'loadJquery' => true,
 *     'emptyText' => __('Please select it.', true),
 *     'prefId' => '#pref_id',
 *     'lineId' => '#railroad_line_id',
 *     'stationId' => '#railroad_station_id',
 * );
 * echo $this->element('interface', $datas, array('plugin'=>'ekidata'));
 * 
 * 
 * [OPTION]
 * 
 * loadJquery
 *    true:jQueryを読み込む | false:jQueryを読み込まない
 * 
 * emptyText
 *    SELECTタグの未選択項目のラベル
 * 
 * prefId
 *    都道府県SELECTのID/CLASS要素(IDなら#, CLASSなら.を先頭に付与すること)
 * 
 * lineId
 *    路線SELECTのID/CLASS要素(IDなら#, CLASSなら.を先頭に付与すること)
 * 
 * stationId
 *    駅SELECTのID/CLASS要素(IDなら#, CLASSなら.を先頭に付与すること)
 * 
 * 
 * 例えば都道府県と路線IDのみしか使わない場合はstationIdのOPTIONを未定義にすることで
 * 路線IDの変更イベントが読み込まれないようになってます
 * 
 */

?><?php 
    if ( isset($loadJquery) && $loadJquery == true ) {
        echo $this->Html->script('/ekidata/js/jquery-1.7.1.min');
    }
?>

<script type="text/javascript">
    //<![CDATA[
    $(function(){
        
        var settings = {
            urls : {
                lines : '<?php echo $this->Html->url(array('plugin'=>'ekidata', 'controller'=>'api', 'action'=>'lines')); ?>',
                stations : '<?php echo $this->Html->url(array('plugin'=>'ekidata', 'controller'=>'api', 'action'=>'stations')); ?>'
            },
            emptyText : '<?php echo $emptyText; ?>'
        };
        
        function initSelect(id) {
            $(id)
                .empty()
                .append(
                    $('<option />').val('').html(settings.emptyText)
                );
        }
        
        <?php if ( isset($prefId) && isset($lineId) ) : ?>
        $('<?php echo $prefId; ?>').change(function(){
            <?php if ( isset($lineId) ): ?>initSelect('<?php echo $lineId; ?>');<?php endif; ?>
            <?php if ( isset($stationId) ): ?>initSelect('<?php echo $stationId; ?>');<?php endif; ?>
            
            var url = settings.urls.lines + '/' + $(this).val();
            $.getJSON(url, function(json, status){
                if ( status == 'success' ) {
                    $.each(json, function(){
                        var _this = this;
                        $('<?php echo $lineId; ?>')
                            .append(
                                $('<option />').val(_this.RailroadLine.id).html(_this.RailroadLine.name)
                            );
                    });
                }
            });
        });
        <?php endif; ?>
        
        <?php if ( isset($lineId) && isset($stationId) ) : ?>
        $('<?php echo $lineId; ?>').change(function(){
            initSelect('<?php echo $stationId; ?>');
                    
            var url = settings.urls.stations + '/' + $(this).val();
            $.getJSON(url, function(json, status){
                if ( status == 'success' ) {
                    $.each(json, function(){
                        var _this = this;
                        $('<?php echo $stationId; ?>')
                            .append(
                                $('<option />').val(_this.RailroadStation.id).html(_this.RailroadStation.name)
                            );
                    });
                }
            });
        });
        <?php endif; ?>
    });
    //]]>
</script>

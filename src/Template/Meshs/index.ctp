<?php $this->assign('title', 'ダッシュボード'); ?>
<?= $this->Html->script('https://graphed.japacom.co.jp/graphed.js') ?>

<div class="row">
  <!-- 温度 -->
  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <h4><strong>温度（直近1分毎の温度状況）</strong></h4>
    <div id="graph-temperature" style="height:300px; width:100%;"></div>
  </div>
  <!-- 湿度 -->
  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <h4><strong>湿度（直近1分毎の湿度状況）</strong></h4>
    <div id="graph-humidity" style="height:300px; width:100%;"></div>
  </div>

  <!-- 動きタグの状態 -->
  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <div class="text-center">
      <h4><strong>動きタグの状態</strong></h4>
      <br><br>
      <img id="move-tag-image" src="">
    </div>
  </div>
</div>

<div class="row">
  <!-- 温度 -->
  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
      <table id="temperatures-table" class="table table-striped">
        <thead>
          <tr>
            <td>日時</td>
            <td>平均温度</td>
          <tr>
        </thead>
        <tbody>
        </tbody>
      </table>
  </div>
  <!-- 湿度 -->
  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
      <table id="humidities-table" class="table table-striped">
        <thead>
          <tr>
            <td>日時</td>
            <td>平均湿度</td>
          <tr>
        </thead>
        <tbody>
        </tbody>
      </table>
  </div>

  <!-- 照度 -->
  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <div class="text-center">
      <h4><strong>照度（現在の明るさ）</strong></h4>
      <div id="graph-brightness" style="height:300px; width:100%;"></div>
    </div>
  </div>
</div>

<!-- script start -->
<script type="text/javascript">
$(function() {
    // 変数宣言
    var CNUM   = 19;
    var TYPE   = { "TEMPERATURE":"temperatures", "HUMIDITY":"humidities"
                 , "BRIGHTNESS":"brightnesses", "MOVE":"moves"
                 , "RASPBERRY_TEMPS":"temps"};
    var loader = {};

    //function createLoader() {
    //    loader = {};
        loader[TYPE.TEMPERATURE] = graphed.load({
                containerId: 'graph-temperature',
                key: '3WC0NGRTPL1JZMI6U5B1472086821051'
            });
        loader[TYPE.HUMIDITY] = graphed.load({
                containerId: 'graph-humidity',
                key: 'LZTDKNVHB4CW6XG07581472102296123'
            });
        loader[TYPE.BRIGHTNESS] = graphed.load({
                containerId: 'graph-brightness',
                key: '26SYBARL8JETU9KCPZD1472109640878'
            });
    //}

//console.log($('#graph-temperature > iframe').contents().find('body').css('background-color'));

    // グラフデータ作成
    function makeGraphedData(json, graph_key, key, column_num) {
        var data = [];
        var gkey = (graph_key===null ? key : graph_key);
        for (var i = column_num, j = 0; i >= 0; i--, j++) {
            data[j] = ["T" + i, 0];
            if (json[key].length > i) {
                data[j] = ["T" + i, json[key][i]["val"]];
            }
        }
        loader[gkey].addData(data);
    }

    // グラフリストの作成
    function makeGraphList(json, key, data_suffix) {
        // 表のクリア
        $("#"+key+"-table>tbody").empty();

        for(var i = 0; i < 8; i++) {
            var elem_dt  = "<td>" + json[key][i]["dt"] + "</td>";
            var elem_val = "<td>" + json[key][i]["val"] + data_suffix + "</td>";
            $("#"+key+"-table>tbody").append("<tr>"+elem_dt+elem_val+"</tr>");
        }
    }


    // 温度データのロード
    function loadTemperature() {
        return $.getJSON("/api/v1/temperatures.json", null, function(json){
            makeGraphedData(json, null, TYPE.TEMPERATURE, CNUM);
            makeGraphList(json, TYPE.TEMPERATURE, " 度");
        });
    }

    // ラズパイ温度データのロード
    function loadRaspberryPI() {
        return $.getJSON("/api/v1/raspberry_temps.json", null, function(json){
            makeGraphedData(json, TYPE.TEMPERATURE, TYPE.RASPBERRY_TEMPS, CNUM);
        });
    }

    // 湿度データのロード
    function loadHumidity() {
        return  $.getJSON("/api/v1/humidities.json", null, function(json){
            makeGraphedData(json, null, TYPE.HUMIDITY, CNUM);
            makeGraphList(json, TYPE.HUMIDITY, " %");
        });
    }
    // 照度データのロード
    function loadBrightness() {
        return  $.getJSON("/api/v1/brightnesses.json", null, function(json){
            var dark = 10, bright = 0;
            if (json[TYPE.BRIGHTNESS].length > 0) {
                bright = json[TYPE.BRIGHTNESS][0]["val"];
                dark   = isFinite(bright) ? dark - Number(bright) : 0;
            }
            loader[TYPE.BRIGHTNESS].addData([["brightness", bright], ["dark", dark]]);
        });
    }

    // 動きタグの状態のロード
    function loadMove() {
        return  $.getJSON("/api/v1/moves.json", null, function(json){
            var orientation = json[TYPE.MOVE][0].val;
            if (orientation >= 1 && orientation <= 6) {
                $("#move-tag-image").attr("src", "/img/move_tag_" + String(orientation) + ".png");
            }
        });
    }

    function loadGraph() {
    //    createLoader();
        loadTemperature()
            .then(setTimeout(loadRaspberryPI, 500))
            .then(setTimeout(loader[TYPE.TEMPERATURE].draw, 1000));

        loadHumidity()
            .then(setTimeout(loader[TYPE.HUMIDITY].draw, 500));

        loadBrightness()
            .then(setTimeout(loader[TYPE.BRIGHTNESS].draw, 500));
    }


    // グラフの表示（1分毎）
    setInterval(loadGraph, 1000 * 3);

    // イメージ画像の表示
    setInterval(function() {
        loadMove();
    }, 1000 * 3);

});
</script>


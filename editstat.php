<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/engine.php');
if (!isset(engine::getUserType()[0]) || engine::getUserType()[1] != 1) {
  engine::Redirect();
}
if (isset($_GET['nextId']) && isset($_GET['stage'])){
  engine::nextStage($_GET['nextId'], $_GET['stage']);

}
$row = engine::getOrders(["priority" => $_GET["priority"], "id" => $_GET["id"], "status" => $_GET["status"]]);
if(!isset($row[0])){
  $row = engine::getOrders();
}
engine::get_Header("Список заявок");
?>
<br>
<br>
<div class="container">
  <div class="row">
    <div class="col">
      <div class="jumbotron">
        <form class="form-inline">
          <label class="sr-only" for="idInput">ID</label>
          <input type="text" name="id" class="form-control mb-2 mr-sm-2" id="idInput" placeholder="ID">

          <div class="input-group mb-2 mr-sm-2">
            <label class="sr-only" for="priorityInput">ID</label>
            <select name="status" id="priorityInput" class="custom-select">
              <option value="" selected>Статус</option>
              <option value="register">register</option>
              <option value="clean">clean</option>
              <option value="diagnostic">diagnostic</option>
              <option value="repair">repair</option>
              <option value="quality">quality</option>
              <option value="packing">packing</option>
              <option value="sending">sending</option>
              <option value="sent">sent</option>
              <option value="cancel">cancel</option>
            </select>
          </div>

          <div class="input-group mb-2 mr-sm-2">
            <label class="sr-only" for="priorityInput">ID</label>
            <select name=priority id="priorityInput" class="custom-select">
              <option value="" selected>Приоритет</option>
              <option value="P-1">P-1</option>
              <option value="P-2">P-2</option>
              <option value="P-3">P-3</option>
            </select>
          </div> 
          
          <button type="submit" class="btn btn-primary mb-2"><i class="fa fa-search" aria-hidden="true"></i></button>
        </form>
      </div>
      <?php
      $mas = [
        0 => "register",
        1 => "clean",
        2 => "diagnostic",
        3 => "repair",
        4 => "quality",
        5 => "packing",
        6 => "sending",
        7 => "sent",
        8 => "cancel"
      ];
      for ($i = 0; $i < count($row); $i++) {
        $json = json_decode($row[$i][4], true);
        switch ($row[$i][2]) {
          case 'register':
            $num = 0;
            break;
          case 'clean':
            $num = 1;
            break;
          case 'diagnostic':
            $num = 2;
            break;
          case 'repair':
            $num = 3;
            break;
          case 'quality':
            $num = 4;
            break;
          case 'packing':
            $num = 5;
            break;
          case 'sending':
            $num = 6;
            break;
          case 'sent':
            $num = 7;
            break;
          case 'cancel':
            $num = 8;
            break;
        }
        print_r($json[$num][$mas[$num]]);
      ?>
        <div class="jumbotron">
          <form class="form-inline" action="">
            <p> Id: <?= $row[$i][1] ?> Статус: <?= $row[$i][2] ?> Приоритет: <?= $row[$i][3] ?> Начал работать: <?= $json[$num][$row[$i][2]]['date_start'] ?>
              <?= isset($json[$num - 1][$mas[$num - 1]]['engineer']) ? " " . $json[$num - 1][$mas[$num - 1]]['engineer']['name'] : null  ?> </p>
              <input type="text" name="stage" value="<?= $row[$i][2] ?>" style="display: none;">
              <input type="text" name="nextId" value="<?= $row[$i][1] ?>" style="display: none;">

            <button type="submit" class="btn btn-primary ml-auto">Следующий этап</button>
          </form>
        </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php
engine::get_Footer();
?>
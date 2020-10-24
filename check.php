<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/engine.php');
$url = 'http://oauth.vk.com/authorize';
$redirect_uri = engine::site_url() . 'index.php';
$params = array(
  'client_id'     => engine::vkApp_data['client_id'],
  'redirect_uri'  => $redirect_uri,
  'response_type' => 'code'
);
if (isset($_GET['code'])) {
  engine::vk_getInfo($_GET['code'], $redirect_uri);
  engine::Redirect();
}
if (isset($_GET['id'])) {
  $row = engine::getOrders(["priority" => null, "id" => $_GET['id'], "status" => null])[0];
}
engine::get_Header("Статус заявки");
?>
<br>
<h1 class="topic">Статус заявки</h1>
<br>
<div class="container">
  <div class="row">
    <div class="col-sm">
      <div class="jumbotron">
      <?php if(isset($row)) {?>
        <p>ID: <?= $row[1] ?></p>
        <p>Статус: <?= $row[2] ?> </p>
        <p>Приоритет: <?= $row[3] ?></p>
        <?php
        $json = json_decode($row[4], true);
        for($i = 0; $i < count($json); $i++){ ?>
          <ul>
            Этап: <?= array_keys($json[$i])[0] ?>
            <li>Начало: <?= $json[$i][array_keys($json[$i])[0]]["date_start"] ?></li>
            <li>Конец: <?= $json[$i][array_keys($json[$i])[0]]["date_end"] ?></li>
            <li>Инженер: <?= $json[$i][array_keys($json[$i])[0]]["engineer"]["name"] ?></li>
          </ul>
        <?php } 
      }else{ ?>
      <div class="justify-content-center">

        <h1>Поиск:  </h1>
        <p>Введите выданный вам ID для получения статуса заказа: </p>
        <form class="form-inline">
          <label class="sr-only" for="idInput">ID</label>
          <input type="text" name="id" class="form-control mb-2 mr-sm-2" id="idInput" placeholder="ID">
          <button type="submit" class="btn btn-primary mb-2"><i class="fa fa-search" aria-hidden="true"></i></button>
        </form>
      </div>
      <?php } ?>
      </div>
    </div>
  </div>
</div>
<?php
engine::get_Footer();
?>
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
engine::get_Header();
?>
<br>
<h1 class="topic">Главная</h1>
<br>
<div class="container">
  <div class="row">
    <div class="col-sm">
      <div class="jumbotron">
        <h1>5 причин почему именно мы?</h1>
        <p>1. Доступные цены на ремонт смартфонов!</p>
        <p>2. Качественные комплектующие!</p>
        <p>3. Современные сервисные центры!</p>
        <p>4. Удобное расположение сервисов!</p>
        <p>5. Вы точно останетесь довольны!</p>
        <img src="/img/a.png">
      </div>
    </div>
    <div class="col-sm">
      <div class="jumbotron">
        <h1>Время работы</h1>
        <p>Суббота 12:00–21:00</p>
        <p>Воскресенье 12:00–21:00</p>
        <p>Понедельник 11:00–21:00</p>
        <p>Вторник 11:00–21:00</p>
        <p>Среда 11:00–21:00</p>
        <p>Четверг 11:00–21:00</p>
        <p>Пятница 11:00–21:00</p>
      </div>
    </div>
  </div>
</div>
<?php
engine::get_Footer();
?>
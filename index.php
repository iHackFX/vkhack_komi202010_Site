<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/engine.php');
$url = 'http://oauth.vk.com/authorize';
$redirect_uri = engine::site_url().'index.php';
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
        <p>test</p>
      </div>
    </div>
    <div class="col-sm">
      <div class="jumbotron">
        <p>test</p>
      </div>
    </div>
  </div>
</div>
<?php
engine::get_Footer();
?>
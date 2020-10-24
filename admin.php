<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/engine.php');
if (engine::getUserType()[1] != 1) {
  engine::Redirect();
}
engine::get_Header();
$nums = engine::getNumAtEachState();
?>
<br>
<h1 class="topic">Админ-панель</h1>
<br>
<div class="container">
  <div class="row mx-1">
    <div class="col">
      <div class="jumbotron">
        <p>Сколько заявок всего в системе: <?= engine::getNumOfAllOrders() ?></p>
        <p>Сколько заявок находится на каждом этапе:</p>
        <ul>
          <li>В ожидание: <?= $nums[0] ?></li>
          <li>Чистка: <?= $nums[1] ?></li>
          <li>Диагностика: <?= $nums[2] ?></li>
          <li>Ремонт: <?= $nums[3] ?></li>
          <li>Контроль качества: <?= $nums[4] ?></li>
          <li>Упаковка: <?= $nums[5] ?></li>
          <li>Отправка: <?= $nums[7] ?></li>
          <li>Списано : <?= $nums[8] ?></li>
        </ul>
        <p>Соотношение выполненных заявок и заявок в работе: <?= round($nums[7] / ($nums[0] + $nums[1] + $nums[2] + $nums[3] + $nums[4] + $nums[5]) * 100) ?>%</p>
      </div>
    </div>
    <div class="col-lg">
      <div class="jumbotron">
        <div id="diagram"></div>
      </div>
    </div>
  </div>
</div>
<script src="https://www.google.com/jsapi"></script>
<script>
  google.load("visualization", "1", {
    packages: ["corechart"]
  });
  google.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Заявки', 'кол-во'],
      ['В ожидании', <?= round($nums[0] / engine::getNumOfAllOrders() * 100, 2) ?>],
      ['Чистка', <?= round($nums[1] / engine::getNumOfAllOrders() * 100, 2) ?>],
      ['Диагностика', <?= round($nums[2] / engine::getNumOfAllOrders() * 100, 2) ?>],
      ['Ремонт', <?= round($nums[3] / engine::getNumOfAllOrders() * 100, 2) ?>],
      ['Контроль качества', <?= round($nums[4] / engine::getNumOfAllOrders() * 100, 2) ?>],
      ['Упаковка', <?= round($nums[5] / engine::getNumOfAllOrders() * 100, 2) ?>],
      ['Отправка', <?= round($nums[7] / engine::getNumOfAllOrders() * 100, 2) ?>],
      ['Списано', <?= round($nums[8] / engine::getNumOfAllOrders() * 100, 2) ?>],
    ]);
    var options = {
      title: 'Заявки',
      is3D: true,
      pieResidueSliceLabel: 'Остальное',
      backgroundColor: "#171717",
      legend: {
        textStyle: {
          color: "#ffffff"
        }
      },
      titleTextStyle: {
        color: "#ffffff"
      }
    };
    var chart = new google.visualization.PieChart(document.getElementById('diagram'));
    chart.draw(data, options);
  }
</script>
<?php
engine::get_Footer();
?>
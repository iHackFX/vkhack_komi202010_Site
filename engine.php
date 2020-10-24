<?php
class engine
{
  /** 
   * Данные для подключения к БД
   * 
   * [0] - Хост
   * 
   * [1] - Login
   * 
   * [2] - Password
   * 
   * [3] - Имя базы
   */
  private const sql_data = ["localhost", "mysql", "mysql", "komisoft"];
  public const vkApp_data = ["client_id" => "7638867", "client_secret" => "X7d0bSNOHm9PHGJj9wz3"];
  public static function site_url()
  {
    return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . "/";
  }

  function __construct()
  {
    session_start();
    if (isset($_GET['logout'])) {
      self::logout();
    }
  }

  /** 
   * Перенаправление на определённую ссылку
   * @param string $to куда делать перенаправление
   */
  public static function Redirect(string $to = "/")
  {
    header("Location: $to");
    exit();
  }

  public static function getGUID()
  {
    if (function_exists('com_create_guid')) {
      return com_create_guid();
    } else {
      mt_srand((float)microtime() * 10000); //optional for php 4.2.0 and up.
      $charid = strtoupper(md5(uniqid(rand(), true)));
      $hyphen = chr(45); // "-"
      $uuid = chr(123) // "{"
        . substr($charid, 0, 8) . $hyphen
        . substr($charid, 8, 4) . $hyphen
        . substr($charid, 12, 4) . $hyphen
        . substr($charid, 16, 4) . $hyphen
        . substr($charid, 20, 12)
        . chr(125); // "}"
      return $uuid;
    }
  }


  /** 
   * Получение подключения к БД 
   * @return mysqli $conn Подключение к БД
   */
  public static function get_Connection()
  {
    $conn = mysqli_connect(self::sql_data[0], self::sql_data[1], self::sql_data[2], self::sql_data[3]);
    if (mysqli_connect_errno()) {
      echo "Не удалось подключится к базе данных MySQL: " . mysqli_connect_error();
    }
    return $conn;
  }

  /** 
   * Проверка на админа */
  public static function adm_check() // /admin-panel/Adm_Check.php
  {
    $conn = self::get_Connection();
    $Username = $_SESSION['user'];
    $query = "SELECT * FROM `Users` WHERE User='$Username'";
    $result = mysqli_query($conn, $query);
    $rows = mysqli_num_rows($result);
    if ($rows == 1) {
      while ($row = mysqli_fetch_array($result)) {
        $i = $row['Type'];
        if ($i == '0' || $i == '4') {
          $i = '4'; //something XD
        } else {
          self::Redirect("/");
        }
      }
    }
  }

  /** 
   * Выводит хедер.
   * @param string $title Название страницы
   * @param int $active_page_num Номер активной на данный момент страницы
   */
  public static function get_Header(string $title = "KomiSoft", int $active_page_num = 1)
  {
    $url = 'http://oauth.vk.com/authorize';
    $redirect_uri = engine::site_url() . 'index.php';
    $params = array(
      'client_id'     => engine::vkApp_data['client_id'],
      'response_type' => 'code',
      'redirect_uri'  => $redirect_uri
    );
    $urlDecoded = $url . '?' . urldecode(http_build_query($params));
?>
    <!DOCTYPE html>
    <html lang="ru">

    <head>
      <meta charset="utf-8">
      <title><?php echo ($title) ?></title>
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous" />
      <link href="/css/index.css" rel="stylesheet">
      <link rel="icon" href="/favicon.ico" type="image/x-icon">
    </head>

    <body class="">
      <div class="d-flex" id="wrapper">
        <div class="bg-dark" id="sidebar-wrapper">
          <div class="sidebar-heading"><img src="/img/icon.png" class="nav-brand" width="120" height="35" alt="Komisoft"></div>
          <div class="list-group list-group-flush">
            <a href="/" class="list-group-item list-group-item-action bg-dark"><i class="fas fa-indent"></i> Главная</a>
            <a href="/check.php" class="list-group-item list-group-item-action bg-dark"><i class="fas fa-circle-notch"></i> Статус заявки</a>
            <?= isset(self::getUserType()[0]) || engine::getUserType()[1] != 1 ? '<a href=/editstat.php class="list-group-item list-group-item-action bg-dark"><i class="fas fa-users-cog"></i> Список заявок(editor)</a>' : null ?>
            <?= self::getUserType()[1] == 1 ? '<a href="/admin.php" class="list-group-item list-group-item-action bg-dark"><i class="fas fa-user-cog"></i> Admin</a>' : null ?>
          </div>
        </div>
        <div id="page-content-wrapper">
          <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <button class="btn btn-link" id="menu-toggle"><span class="navbar-toggler-icon"></span></button>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                <li class="nav-item">
                  <?= $_SESSION['userId'] ? 'Привет, ' . $_SESSION['name'] . '. <a href="/?logout">Выйти </a>' : '<a class="nav-link" href="' . $urlDecoded . '">Вход через <i class="fab fa-vk"></i></a>' ?>
                </li>
              </ul>
            </div>
          </nav>
        <?php
      }

      /** 
       * Выводит футер 
       */
      public static function get_Footer()
      {
        ?>
          <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
          <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
          <script>
            $("#menu-toggle").click(function(e) {
              e.preventDefault();
              $("#wrapper").toggleClass("toggled");
            });
          </script>
    </body>

    </html>
<?php
      }

      public static function sanitize($var, $type)
      {
        switch ($type) {
          case 'html':
            $safe = htmlspecialchars($var);
            break;
          case 'sql':
            $safe = mysqli_real_escape_string(self::get_Connection(), $var);
            break;
          case 'file':
            $safe = preg_replace('/(\/|-|_)/', '', $var);
            break;
          case 'shell':
            $safe = escapeshellcmd($var);
            break;
          default:
            $safe = htmlspecialchars($var);
        }
        return $safe;
      }

      public static function getOrders($filter = ["priority" => null, "id" => null, "status" => null])
      {
        $sql = "SELECT * FROM orders";
        $sql_params = [];
        if (isset($filter["id"])) {
          if ($filter["id"] > 0) {
            array_push($sql_params, "serial = '" . self::sanitize($filter["id"], "sql") . "'");
          }
        }
        if (isset($filter["priority"])) {
          if (strlen($filter["priority"]) > 1) {
            array_push($sql_params, "priorty = '" . self::sanitize($filter["priority"], "sql") . "'");
          }
        }
        if (isset($filter["status"])) {
          if (strlen($filter["status"]) > 1) {
            array_push($sql_params, "status = '" . self::sanitize($filter["status"], "sql") . "'");
          }
        }
        if (isset($sql_params[0])) {
          for ($i = 0; $i < count($sql_params); $i++) {
            if ($i == 0) {
              $sql = $sql . " WHERE";
            }
            if ($i > 0) {
              $sql = $sql . " and";
            }
            $sql = $sql . " " . $sql_params[$i];
          }
        }
        $result = mysqli_query(self::get_Connection(), $sql);
        $rows = mysqli_fetch_all($result);
        return $rows;
      }



      public static function nextStage($id, $stage)
      {
        $userid = self::getUserType()[0];
        $name = $_SESSION['name'];
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
        $num = null;
        switch ($stage) {
          case 'register':
            $num = 1;
            break;
          case 'clean':
            $num = 2;
            break;
          case 'diagnostic':
            $num = 3;
            break;
          case 'repair':
            $num = 4;
            break;
          case 'quality':
            $num = 5;
            break;
          case 'packing':
            $num = 7;
            break;
          case 'sending':
            $num = 7;
            break;
          case 'sent':
            $num = 7;
            break;
          case 'cancel':
            $num = 8;
            break;
        }
        if ($result = mysqli_query(self::get_Connection(), "SELECT status_dates FROM orders WHERE serial =" . $id)) {
          $row = json_decode(mysqli_fetch_row($result)[0], true);
          $obj = [
            "engineer" => [
              "id" => $userid,
              "name" => $name
            ]
          ];
          date_default_timezone_set("Europe/Moscow");
          $now = date('Y-m-d H:i:s');
          $row[$num][$mas[$num]]["date_start"] = $now;
          $row[$num - 1][$mas[$num - 1]]["date_end"] = $now;
          $row[$num - 1][$mas[$num - 1]] = array_merge($row[$num - 1][$mas[$num - 1]], $obj);
          $sql = "UPDATE orders SET status_dates = " . "'" . self::sanitize(json_encode($row), "sql") . "'" . ", status = " . "'" . $mas[$num] . "'" . " WHERE serial =" . $id;
          print_r($sql);
          $result = mysqli_query(self::get_Connection(), $sql);
        }
      }

      public static function getUserType()
      {
        if ($result = mysqli_query(self::get_Connection(), "SELECT id, is_admin FROM users WHERE userid =" . $_SESSION['userId'])) {
          $result = mysqli_fetch_row($result);
          return $result;
        } else {
          return [null, 0];
        }
      }

      public static function getNumOfAllOrders()
      {
        $result = mysqli_query(self::get_Connection(), "SELECT status_dates FROM orders");
        return mysqli_num_rows($result);
      }

      public static function getNumAtEachState()
      {
        $register = 0;
        $clean = 0;
        $diagnostic = 0;
        $repair = 0;
        $quality = 0;
        $packing = 0;
        $sending = 0;
        $sent = 0;
        $cancel = 0;
        $result = mysqli_query(self::get_Connection(), "SELECT status FROM orders");
        while ($row = mysqli_fetch_assoc($result)) {
          $row["status"]  == "register"  ? $register++ : null;
          $row["status"] == "clean" ? $clean++ : null;
          $row["status"] == "diagnostic" ? $diagnostic++ : null;
          $row["status"] == "repair" ? $repair++ : null;
          $row["status"] == "quality" ? $quality++ : null;
          $row["status"] == "packing" ? $packing++ : null;
          $row["status"] == "sending" ? $sending++ : null;
          $row["status"] == "sent" ? $sent++ : null;
          $row["status"] == "cancel" ? $cancel++ : null;
        }

        return [$register, $clean, $diagnostic, $repair, $quality, $packing, $sending, $sent, $cancel];
      }

      /**
       * Функция для выхода из аккаунта, и уничтожение сессии
       */
      public static function logout()
      {
        if (session_destroy()) {
          self::Redirect();
        }
      }


      public static function vk_getInfo(string $code, string $redirect_uri)
      {
        $params = array(
          'client_id' => self::vkApp_data['client_id'],
          'client_secret' => self::vkApp_data['client_secret'],
          'code' => $code,
          'redirect_uri' => $redirect_uri
        );

        $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

        $fio = json_decode((file_get_contents('https://api.vk.com/method/users.get?access_token=' . $token['access_token'] . '&user_ids=' . $token['user_id'] . "&v=5.124")));
        $fio = $fio->response[0];
        $sql = "INSERT INTO users (id, userid, name, is_admin) VALUES(null, " . $token['user_id'] . ", '" . $fio->first_name . " " . $fio->last_name . "', 0)";
        mysqli_query(self::get_Connection(), $sql);
        $_SESSION['userId'] = $token['user_id'];
        $_SESSION['name'] = $fio->first_name . " " . $fio->last_name;
        return $token;
      }

      /**
       * Получить ID ВК из БД
       */
      public static function get_UserVkId()
      {
        $login = $_SESSION['user'];
        if ($result = mysqli_query(self::get_Connection(), "INSERT user_id FROM users")) {
          mysqli_data_seek($result, 0);
          $row = mysqli_fetch_row($result);
          mysqli_free_result($result);
          $uid = intval($row[0]);
        } else {
          $uid = null;
        }
        return $uid;
      }

      /**
       * Вход через VK
       * @param string $code код от авторизации через ВК
       * @param string $redirect_uri URL куда нужно сделать переадресацию
       */
      public static function vk_link(string $code, string $redirect_uri)
      {
        $uid = self::vk_getInfo($code, $redirect_uri)['user_id'];
        $user = $_SESSION['user'];
        mysqli_query(self::get_Connection(), "UPDATE users SET userid = '$uid' WHERE `User` = '$user'");
        self::Redirect();
      }

      /** 
       * Авторизация через ВК
       * @param string $code код, возвращаемый авторизацией через приложение
       */
      public static function vk_login(string $code, $redirect_uri)
      {
        $uid = self::vk_getInfo($code, $redirect_uri)['user_id'];
        $result = mysqli_query(self::get_Connection(), "INSERT INTO users (id, userid, name, is_admin) VALUES(null, $uid, )");
        if ($result) {
          mysqli_data_seek($result, 0);
          $row = mysqli_fetch_row($result);
          mysqli_free_result($result);
          $user = $row[0];
        } else {
          $user = null;
        }
        if (isset($user)) {
          $_SESSION['user'] = $user;
        }
        // engine::redirect();
      }
    }

    $engine = new engine;
    $conn = $engine::get_Connection();
    $engine = null;
?>
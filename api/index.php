<?php
error_reporting(1);

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
require_once("./config.php");
$route = $_GET['route'];

$err = [];

$mysqli = new mysqli($db_adr, $db_user, $db_pass, $db_name);

$explode = explode('/', $route);

if ($route == 'save') {

    $uniq_name = '';
    $url = '';
    $file_name = $_FILES['xml']['name'];

    // Создаю случайное имя и проверяю его на уникальность, если оно уже занято, то повторяю попытку
    do {
        $rand_name = randomName(30);
        $uniq_name = $rand_name . '.' . explode('/', $_FILES['xml']['type'])[1];
    } while ($mysqli->query("SELECT `id` FROM `files` WHERE `file_name` = '/loaded_files/$uniq_name'")->fetch_assoc());


    if (isset($_FILES['xml'])) {
        if ($_FILES['xml']['type'] == 'text/xml' || $_FILES['xml']['type'] == 'application/xml') {
            move_uploaded_file($_FILES['xml']['tmp_name'], "../loaded_files/{$uniq_name}");
        } else {
            $err['file'] = 'Incorrect format';
        }
    } else {
        $err['file'] = 'No files!';
    }

    if ($err) {
        header("HTTP/1.1 403 Forbidden");
        die(json_encode($err, JSON_UNESCAPED_UNICODE));
    } else {
        $date = date("d-m-Y H:i:s");
        $uniq_name = explode('.', $uniq_name)[0];
        $file_name = explode('.', $file_name)[0];
        $mysqli->query("INSERT INTO `files` (`uniq_name`, `file_name`, `date`) VALUES ('$uniq_name', '$file_name', '$date')");
        header("HTTP/1.1 200 OK");
        die(json_encode([
            'status' => 'ok',
            'uniq_name' => $uniq_name
        ], JSON_UNESCAPED_UNICODE));
    }
} else if ($explode[0] == 'files') {
    if (count($explode) == 1) {
        // Получение списка сохраненных файлов
        $query = $mysqli->query("SELECT * FROM `files`");

        $data = [];

        while ($item = $query->fetch_assoc()) {
            array_push($data, [
                'id' => $item['id'],
                'uniq_name' => $item['uniq_name'],
                'file_name' => $item['file_name'],
                'date' => $item['date'],
                'file' => $item['file']
            ]);
        }

        header("HTTP/1.1 200 OK");
        die(json_encode($data, JSON_UNESCAPED_UNICODE));
    } else if (count($explode) == 2) {
        // Вывод данных об одном или нескольких файлов разделенных через запятую
        $names = explode(',', $explode[1]);
        $query_body = "SELECT * FROM `files` WHERE ";
        foreach ($names as $ind => $name) {
            $query_body = $query_body . (($ind != 0) ? " OR " : "") . "`uniq_name` = '$name'";
        };
        $query = $mysqli->query($query_body);

        if (!$item = $query->fetch_assoc()) {
            header("HTTP/1.1 403 Forbidden");
            die(json_encode([
                'error' => 'The file does not exist',
            ], JSON_UNESCAPED_UNICODE));
        } else {
            header("HTTP/1.1 200 OK");
            $data = [];
            // Запись в массив данных первого файла
            array_push($data, [
                'id' => $item['id'],
                'uniq_name' => $item['uniq_name'],
                'file_name' => $item['file_name'],
                'date' => $item['date'],
                'file' => $item['file']
            ]);

            // Запись всех остальных файлов
            while ($item = $query->fetch_assoc()) {
                array_push($data, [
                    'id' => $item['id'],
                    'uniq_name' => $item['uniq_name'],
                    'file_name' => $item['file_name'],
                    'date' => $item['date'],
                    'file' => $item['file']
                ]);
            }
            die(json_encode($data, JSON_UNESCAPED_UNICODE));
        }
    }
} else if ($route == 'sort') {
    $filter = $_POST['filter'];
    $ascending = $_POST['ascending'];

    if (!isset($filter)) $filter = 'id';
    if (!isset($ascending)) $ascending = false;

    $query_body;
    $data = [];

    if ($filter == 'id' || $filter == 'uniq_name' || $filter == 'file_name' || $filter == 'date') {
        $query_body = "SELECT * FROM `files` ORDER BY `". $filter. ($ascending ? "`" : "` DESC");
    } else {
        header("HTTP/1.1 403 Forbidden");
        die(json_encode([
            'error' => 'The filter does not exist',
        ], JSON_UNESCAPED_UNICODE));
    }

    $query = $mysqli->query($query_body);

    while ($item = $query->fetch_assoc()) {
        array_push($data, [
            'id' => $item['id'],
            'uniq_name' => $item['uniq_name'],
            'file_name' => $item['file_name'],
            'date' => $item['date'],
            'file' => $item['file']
        ]);
    }

    header("HTTP/1.1 200 OK");
    die(json_encode($data, JSON_UNESCAPED_UNICODE));
}

function randomName(int $num)
{
    $name = '';
    for ($i = 0; $i < $num; $i++) {
        $name = $name . randomChar();
    }
    return $name;
}

function randomChar()
{
    $string = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVNM1234567890";
    $char = substr(str_shuffle($string), 0, 1);
    return $char;
}

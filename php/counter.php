<?php
$counterFile = 'counter.txt';
$ipFile = 'ips.json';

// Получаем IP пользователя
$ip = $_SERVER['REMOTE_ADDR'];

// Получаем сегодняшнюю дату
$today = date('Y-m-d');

// Если нет файла счётчика — создать с 0
if (!file_exists($counterFile)) {
    file_put_contents($counterFile, 0);
}

// Если нет файла IP — создать с пустым массивом
if (!file_exists($ipFile)) {
    file_put_contents($ipFile, json_encode([]));
}

// Загружаем IP-список
$ipData = json_decode(file_get_contents($ipFile), true);

// Если дата отличается от текущей — сбросить IP-список
if (!isset($ipData['date']) || $ipData['date'] !== $today) {
    $ipData = [
        'date' => $today,
        'ips' => []
    ];
}

// Проверяем, был ли уже этот IP сегодня
if (!in_array($ip, $ipData['ips'])) {
    // Увеличиваем счётчик
    $count = (int)file_get_contents($counterFile);
    $count++;
    file_put_contents($counterFile, $count);

    // Добавляем IP
    $ipData['ips'][] = $ip;
    file_put_contents($ipFile, json_encode($ipData));
} else {
    // IP уже был сегодня
    $count = (int)file_get_contents($counterFile);
}

// Выводим результат
echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><style>
    body { font-family: Arial, sans-serif; font-size: 16px; margin: 0; padding: 0.5em; background: #f9f9f9; }
</style></head><body>";
echo "Уникальных посетителей сегодня: <strong>$count</strong>";
echo "</body></html>";
?>

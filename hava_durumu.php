<?php
function getWeatherData() {
    $apiKey = "63bf77c7bfe42303e6a49b98fd363da9";
    $city = "Istanbul";
    $url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric&lang=tr";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

function isEventPossible($temp) {
    return $temp >= 20;
}
?>
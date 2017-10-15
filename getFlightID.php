<?php

$data = json_decode(file_get_contents('php://input'), true);
$airline = $data["airline"];
$flightNumber = $data["flightNumber"];
$date = $data["date"];
getFlightID($date, $airline . $flightNumber);

function getFlightID($flightDate, $flightNumber) {

    $curl = curl_init();
    
    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://api.myskygu.ru/app-portal/flightNoFear/v0.2/getFlightId?Host=api.myskygu.ru&Content-Type=application%2Fjson&Connection=keep-alive&Proxy-Connection=keep-alive&Accept=*%2F*&User-Agent=SkyGuru%20Pro%2F1.2.5%20(iPhone%3B%20iOS%2011.0.2%3B%20Scale%2F3.00)&Accept-Language=en-US%3Bq%3D1%2C%20hu-US%3Bq%3D0.9&Content-Length=75&Accept-Encoding=gzip%2C%20deflate",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n    \"flight_date\": " . $flightDate . " ,\n    \"flight_number\": " . $flightNumber . "\n}",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 315fa219-5d58-6c54-eb94-3316a4472a18"
        ),
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);

    preg_match_all('/^Set-Cookie:\s*([^\r\n]*)/mi', $response, $ms);
    
    $result = array();

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $pos = strpos($response, "{");
        $json = json_decode(substr($response, $pos));
        $result["flight_id"] = $json->result->flight_id;
        $result["format"] = $json->result->format;
    }
    
    $cookies = array();
    
    foreach ($ms[1] as $m) {
        list($name, $value) = explode('=', $m, 2);
        $cookies[$name] = $value;
    }
    $arr = explode(";", $cookies["JSESSIONID"], 2);

    $result["cookie"] = $arr[0];
    
    echo json_encode($result);
}
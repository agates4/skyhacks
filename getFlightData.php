<?php

$data = json_decode(file_get_contents('php://input'), true);
$cookie = $data["cookie"];
$flightID = $data["flightID"];
getFlightData($cookie, $flightID);

function getFlightData($cookie, $flightID) {

    $curl = curl_init();
    
    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://api.myskygu.ru/app-portal/flightNoFear/v0.1.2/getFlightData?Host=api.myskygu.ru&Content-Type=application%2Fjson&Cookie=JSESSIONID%" . $cookie . "&Connection=keep-alive&Proxy-Connection=keep-alive&Accept=*%2F*&User-Agent=SkyGuru%20Pro%2F1.2.5%20(iPhone%3B%20iOS%2011.0.2%3B%20Scale%2F3.00)&Accept-Language=en-US%3Bq%3D1%2C%20hu-US%3Bq%3D0.9&Content-Length=75&Accept-Encoding=gzip%2C%20deflate",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n    \"flight_id\": " . $flightID . ",\n    \"format\": \"IATA\",\n    \"timestamp\": 1508043560\n}",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: b91cf948-156b-41d1-81aa-982f2b42da28"
        ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
}
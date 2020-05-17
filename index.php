<?php

use \aalfiann\ParallelRequest;
require_once ('vendor/autoload.php');
require_once ('config.php');

if(!empty($_GET['url'])) {
    $url = rawurldecode($_GET['url']);

    $temp = explode('.',$url);
    $listmime = [
        'apng','jpg','jpeg','gif','bmp','png','tiff','webp'
    ];
    if(empty($_GET['mime'])) {
        $etemp = end($temp);
        if(!in_array($etemp,$listmime)) {
            header('Content-Type: application/json');
            echo '{"error":"can\'t detect mime type or maybe was not supported! please use parameter mime."}';
            exit;
        }
        $mime = $etemp;
    } else {
        $mime = $_GET['mime'];
    }

    $parse = parse_url($url);
    $offset = $gmt*60*60;
    $timestamp = time() + 60*60;

    $req = new ParallelRequest;
    $req->request = $url;

    $req->options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => array(
            "Accept: */*",
            "Accept-Encoding: gzip, deflate",
            "Cache-Control: no-cache",
            "Connection: keep-alive",
            "Cookie: __cfduid=d491ad3fc1008f5af257f8cff238d09f31589283767",
            "Host: ".$parse['host'],
            "Postman-Token: fc32bbe8-ca22-4a62-8e70-3f134af29ea8,3d1c666c-56f9-48ba-93a3-32957c51ffbe",
            "Referer: ".(empty($_GET['referer'])?'':rawurldecode($_GET['referer'])),
            "User-Agent: PostmanRuntime/7.15.2"
          )
    ];

    $response = $req->setHttpInfo('detail')->send()->getResponse();
    if($response['code'] == 200) {
        header("Content-Type: image/".$mime);
        header("Content-Length: ".$response['info']['headers']['response']['Content-Length']);
        header("Cache-Control: public, max-age=".$maxage);
        header("Expires: ".date('D, d M Y h:i:s',($timestamp-$offset))." GMT");
        header("Sec-Fetch-Dest: image");
        echo $response['response'];
    } else {
        http_response_code($response['code']);
    }
} else {
    header('Content-Type: application/json');
    echo '{"error":"wrong parameter! parameter url is required."}';
}
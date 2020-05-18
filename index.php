<?php

use \aalfiann\ParallelRequest;

require_once ('vendor/autoload.php');
require_once ('config.php');

if (isset($_SERVER["HTTP_REFERER"])) {
    if($firewall_origin) {
        $origin = parse_url($_SERVER["HTTP_REFERER"]);
        if(isset($origin['host'])) {
            if (!in_array($origin["host"],$allow_origin)) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo '{"error":"You don\'t have direct access to this service."}';
                exit;
            }
        }
    }
} else {
    if($firewall_origin) {
        if(!$allow_no_referer) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo '{"error":"You don\'t have access to this service."}';
            exit;
        }
    }
}

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
    
    $referer = (empty($_GET['referer'])?'':rawurldecode($_GET['referer']));
    $etag = 'W/"'.md5($url.$referer.$mime).'"';
    if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) { 
        header("HTTP/1.1 304 Not Modified"); 
        exit;
    }

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
            "Referer: ".$referer,
            "User-Agent: PostmanRuntime/7.15.2"
          )
    ];

    $response = $req->setHttpInfo('detail')->send()->getResponse();
    $lsize = $response['info']['headers']['response']['Content-Length'];
    if($response['code'] == 200) {
        header("HTTP/1.1 200 OK");
        header("Content-Type: image/".$mime);
        header("Content-Length: ".$lsize);
        header("Cache-Control: public, max-age=".$maxage);
        header("Expires: ".gmdate('D, d M Y H:i:s',(time() + $maxage))." GMT");
        header('Etag: '.$etag);
        header("Sec-Fetch-Dest: image");
        header("Pragma: public");
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Etag');
        echo $response['response'];
    } else {
        http_response_code($response['code']);
    }
} else {
    header('Content-Type: application/json');
    echo '{"error":"wrong parameter! parameter url is required."}';
}

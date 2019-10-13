<?php
/*
    LineBot.php
    Line Notifyを使ってメッセージを送信

    require : PHP5

    参考
        LINE notify を使ってみる https://qiita.com/iitenkida7/items/576a8226ba6584864d95

    @T.Nohara 2019
*/


/* 環境設定 */
date_default_timezone_set('Asia/Tokyo');

define('LINE_API_URL'  ,'https://notify-api.line.me/api/notify');
define('LINE_API_TOKEN','FggmK1f9yATEimmaOJFyhVGFxjlATq5NNEIWZv35V89');

function line_post_message($message){

    $data = http_build_query( [ 'message' => $message ], '', '&');

    $options = [
        'http'=> [
            'method'=>'POST',
            'header'=>'Authorization: Bearer ' . LINE_API_TOKEN . "\r\n"
                    . "Content-Type: application/x-www-form-urlencoded\r\n"
                    . 'Content-Length: ' . strlen($data)  . "\r\n" ,
            'content' => $data,
            ]
        ];

    $context = stream_context_create($options);
    $resultJson = @file_get_contents(LINE_API_URL, false, $context);
    $resultArray = json_decode($resultJson, true);
    if($resultArray['status'] != 200)  {
        return false;
    }
    return true;
}

# --- sample ---
// $b_res = line_post_message('<テスト投稿>');
// if(! $b_res){
//     print("faild \n");
// }

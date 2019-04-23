<?php
/*
 * index.php
 *
 * (2019/04/23) Tomoyuki Nohara
 *
 */ 
/*************************************************************************/
/* 環境設定 */
/*************************************************************************/
date_default_timezone_set('Asia/Tokyo');

define('START_DAY', '2019/05/01');


/*************************************************************************/
/* フォーム処理とその他の事前処理 */
/*************************************************************************/
require_once("post_task.php");

// 経過日数を計算 (おまじない)
$from = strtotime(START_DAY." 00:00:00"); // 2016年元旦 6時
$to   = time("Y/m/d H:i:s"); // 2017年元旦 15時
$dif = $to - $from;
$dif_days = (strtotime(date("Y-m-d", $dif)) - strtotime("1970-01-01")) / 86400;

/*************************************************************************/
/* デバッグ */
/*************************************************************************/


/*************************************************************************/
/* PHP終了*/
/*************************************************************************/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>いんべんとかれんだー</title>
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- スペーサー -->
    <div class="spacer20"></div>

    <!-- トップヘッダ＾ -->
    <div id="container">
        <a href="/">
            <img id="top-logo" src="img/50x50_gray.svg">
        </a>
    </div>
    <!-- /トップヘッダ＾ -->

    <!-- スペーサー -->
    <div class="spacer20"></div>


    <!-- PHPでDBの要素を書き出し -->
    <div class="container">

        <!-- DB保存用の用のフォーム要素 -->
        <form method="POST" action=" ">
                <div class="input-group">
                    <!-- 追加するURL -->
                    <input type="text" class="form-control" placeholder="tweet for..." name="tweet_msg" >
                    <!-- 再読み込み防止用のセッションキー -->
                    <input type="hidden" value="<?php echo $session_key ?>" name="session_key">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-default" name="action" value="add">Go!</button>
                    </span>
                </div>
                <!-- /input-group -->
        </form>
    </div><!--/container-->

    <!-- スペーサー -->
    <div class="spacer20"></div>


    <!-- ツイートのカウント数 -->
    <div class="container text-center">
        <!-- カウンター -->
        <span class="counter">
            <strong><?php echo $tweet_number; ?></strong>
            <span class="counter-sub">/1000</span>
        </span>

        <!-- 現在の達成度 -->
        <p id="notifi"> <?php 

            if ($dif_days < 0) {
                $elapsed_comment = "開始日まであと".(-1*$dif_days)."日";
                $yotei = "目標0件";
            }else{
                $elapsed_comment = "経過".$dif_days."日";
                $yotei = "目標".(3*$dif_days)."件";
            }

            printf('( 開始日%1$s - %2$s - %3$s )', START_DAY, $elapsed_comment, $yotei);

        ?></p>

        <!-- 格言 -->
        <p class="speach">

            <em>
                <?php
                // 文章を取り出して表示
                print(get_speach($tweet_number));
                ?>
            </em>

        </p>
    </div>

    <!-- スペーサー -->
    <div class="spacer20"></div>


    <!-- PHPでDBの要素を書き出し -->
    <div class="container idea-area">
        
        <?php
        // for($i=0; $i<count($tweet_list); $i++){
        for($i=count($tweet_list)-1; $i>=0; $i--){

            $tweet = $tweet_list[$i];

            // time_sampをUTCから東京時間へ変更
            $utc_time_stamp = $tweet["time_stamp"];
            $Tokyo_time_stamp =  date("Y-m-d H:i:s", strtotime($utc_time_stamp . "+9 hour"));

            printf(
                $HTML_TEMPLATE,
                $tweet["id"], $tweet["message"], $Tokyo_time_stamp);
        }

        ?>

    </div><!--/container-->


    <!-- スペーサー -->
    <div class="spacer20"></div>
    <div class="spacer20"></div>
    <div class="spacer20"></div>
    <div class="spacer20"></div>

    <!-- footer -->
    <footer class="footer">
      <div class="container text-center">
        <div class="spacer20"></div>
        <p class="text-muted">「いんべんとカレンダー」</p>
        <p class="text-muted">Invent = 発明</p>
        <p class="text-muted">1日5分. 一人1つのアイディアを出して、一年後には1000個のアイディアリストを作る。</p>
        <p class="text-muted">©2019 OrangeBrothers.</p>
      </div>
    </footer>

    <!-- ************************* 以下javaScript *********************************** -->
    <script src="js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

</body>

</html>

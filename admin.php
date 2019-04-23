<?php
/*
 * TweetBox.php
 *
 * (2016/06/01) Tomoyuki Nohara
 *
 */ 

/*************************************************************************/
/* フォーム処理*/
/*************************************************************************/
require_once("post_task.php");

 
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
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>ついーとぼっくす</title>
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
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
        <a href="#">
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
            <span class="counter-sub">/100</span>
        </span>
        <!-- 格言 -->
        <p class="speach">
            <em><?php
                if($tweet_number == 0){
                    print("ようこそ。ここへ。まずは名前を入れてGo!してくれ。");
                }else{
                    @print($speaches[$tweet_number-1] );
                }
                ?></em>
        </p>
    </div>

    <!-- スペーサー -->
    <div class="spacer20"></div>


    <!-- PHPでDBの要素を書き出し -->
    <div class="container">
        
        <?php
        // for($i=0; $i<count($tweet_list); $i++){
        for($i=count($tweet_list)-1; $i>=0; $i--){
            $tweet = $tweet_list[$i];
            printf(
                $HTML_TEMPLATE,
                $tweet["id"], $tweet["message"], $tweet["time_stamp"]);
        }
        ?>

    </div><!--/container-->


    <!-- PHPでDBの要素を書き出し -->
    <div class="container">
    <!-- リセットボタン -->
    <form method="POST" action=" ">
            <span class="input-group-btn">
                <!-- 再読み込み防止用のセッションキー -->
                <input type="hidden" value="<?php echo $session_key ?>" name="session_key">
                <button type="submit" class="btn btn-default"  name="action" value="reset" >reset</button>
            </span>
            <!-- /input-group -->
        <?php  flushrc($flush); ?>
    </form>

    </div><!--/container-->

    <!-- スペーサー -->
    <div class="spacer20"></div>
    <div class="spacer20"></div>
    <div class="spacer20"></div>
    <div class="spacer20"></div>
    <!-- footer -->
    <footer class="footer">
      <div class="container text-right">
        <p class="text-muted">©2016 FiFiFactory.</p>
      </div>
    </footer>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
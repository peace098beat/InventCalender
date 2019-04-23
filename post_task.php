<?php
/*
 * TweetBox.php
 *
 * (2016/06/01) Tomoyuki Nohara
 *
 */ 
// セッション開始
@session_start();

/*************************************************************************/
// Modleクラスのインポート
require_once("php/Model.class.php");
require_once("php/util.php");

// DBクラスの生成
$Model = new Model();

$flush="";

/*************************************************************************/
/*   再読み込み対策  */
/*************************************************************************/
if ( isset($_SESSION['session_key']) && isset($_POST['session_key']) &&
  $_SESSION['session_key'] == $_POST['session_key']) {

    // セッションIDが合致しているので、送信処理を記述
    $msg = "セッションが一致";

    /*********************************
     * データの追加
     ********************************/
    if ($_POST['action'] == 'add'){
        # 今後、保存するデータ増えることを考え、連想配列で格納
        $tweet_msg = $_POST["tweet_msg"];
        if( mb_strlen($tweet_msg) > 1){
            $hash = array('message' => $tweet_msg);
            $res = $Model->add_data($hash );
            if($res){
                htmlComment("Sucess DB Save ::".$tweet_msg);
            }
        }
    } //追加

    /*********************************
     * データの削除
     ********************************/
    if ($_POST['action'] == 'delete'){
        // write code delete..
        $delete_item_id = $_POST["id"];
        $res = $Model->delete_item($delete_item_id);
        if($res){
            htmlComment("Sucess DB Delete Item :: id=".$delete_item_id);
        }
    } //削除

    /*********************************
     * 全データの削除
     ********************************/
    if ($_POST['action'] == 'reset'){
        // write code delete..
        $Model->reset();
    } //reset

} else {
    // なにもしない
    $msg = "セッションキーが一致してません.";
}
// $msgをHTMLにコメントアウトで出力
// htmlComment($msg);

/*************************************************************************/
/*　DBからデータの呼び出し */
/*************************************************************************/
$tweet_list = $Model->get_data(1000);
// echo "res:::::::::::::::::";
// var_dump($tweet_list);
$tweet_number = count($tweet_list);

/*************************************************************************/
/* セッション開始 */
/*************************************************************************/
// タイムスタンプと推測できない文字列にてキーを発行
$session_key = md5(time()."推測できない文字列");
// 発行したキーをセッションに保存
if ( isset($_SESSION['session_key'])){ unset($_SESSION['session_key']);};
// 発行したキーをセッションに保存
$_SESSION['session_key'] = $session_key;


/*************************************************************************/
/* テンプレートの準備 */
/*************************************************************************/
// プレーヤ要素生成用のテンプレート
$HTML_TEMPLATE = '
<!-- Tweet:%1$s, %3$s -->
<form class="tweet-form" method="POST" action="">
%2$s
    <!-- 再読み込み防止用のセッションキー -->
    <input type="hidden" value="'.$session_key.'" name="session_key">
    <input type="hidden" value="%1$s" name="id">
    <button id="submit_button" type="submit" name="action" value="delete">
    <span class="glyphicon glyphicon-remove"></span></button>
</form>
<!-- /Tweet%1$s -->
';

/*************************************************************************/
/* 格言集を取り出す */
/*************************************************************************/
require_once("php/Speache.php");
$speaches = get_speaches();
/*************************************************************************/
/* PHP終了*/
/*************************************************************************/


?>
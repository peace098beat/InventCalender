<?php
/*
	Model.class.php
 */
/**
* Model
*/

define('DB_NAME', 'db_tweetbox');
define('TBL_NAME', 'tbl_tweet');
define('DEBUG_PRINT', false);


function dbprint($s){
	if(DEBUG_PRINT){
		print $s;
	}
}

class Model
{
	var $pdo;
	
	function __construct()
	{
		$this->pdo = new PDO('sqlite:'.DB_NAME.'.db'); 

		if ($this->pdo == null){
		    // dbprint('<!-- DB接続失敗 -->');
		}else{
			// dbprint('<!-- DB接続成功 -->');
		}

		$this->pdo->exec('SET NAMES utf8');
		# SQL実行時にもエラーの代わりに例外を投げるように設定
		# (毎回if文を書く必要がなくなる)
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		# デフォルトのフェッチモードを連想配列形式に設定 
		# (毎回PDO::FETCH_ASSOCを指定する必要が無くなる)
		$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	}
	function __destruct( )
	{
		$this->pdo = null;
	}

	public  function reset(){
		# テーブル削除
		$this->pdo->exec("DROP TABLE IF EXISTS ".TBL_NAME);
		# テーブル作成
		$this->pdo->exec("CREATE TABLE IF NOT EXISTS ".TBL_NAME."(
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			message TEXT,
			hidden INTEGER DEFAULT 0,
			time_stamp DEFAULT CURRENT_TIMESTAMP
			)");
	}

	public function add_data($data){
		// 挿入（プリペアドステートメント）
		$stmt = $this->pdo->prepare("
			INSERT INTO ".TBL_NAME."(message) VALUES (?)
			");
		$ret = $stmt->execute([$data["message"],]);
		if (!$ret) {
		    dbprint( "<!--error Model::add_data-->");
		    return false;
		} 
		return true;
	}

	public function get_data($maxCount=20){
		// 挿入（プリペアドステートメント）
	    $stmt = $this->pdo->prepare("SELECT * FROM ".TBL_NAME." WHERE hidden=0 ORDER BY id DESC ");
	    $ret = $stmt->execute();
		if (!$ret) {
		    dbprint("<!--error Model::get_data-->");
		} 

	    $result = $stmt->fetchAll();

	 //    $result = [];
		// for ($i=0; $i < count($r); $i++) { 
		//     $result[] = $r[$i]['videoid'];
		// }

		if(count($result) > $maxCount){
			$a = array_slice($result, 0, $maxCount);
			return $a;
		}

	    return $result;
	}
	
	/**
	 * [___delete_item description]
	 * @param  [type] $id [description]
	 */
	public function ___delete_item($id){
		$stmt = $this->pdo->prepare("
			DELETE FROM ".TBL_NAME." WHERE id=?
			");
		$ret = $stmt->execute([$id,]);
		if (!$ret) {
		    dbprint("<!--error Model::___delete_item-->");
		    return false;
		} 
		return true;
	}

	/**
	 * [delete_item description]
	 */
	public function delete_item($id){
		// Hidden
		$stmt = $this->pdo->prepare("
			UPDATE ".TBL_NAME." SET hidden = 1 WHERE id=?
			");
		$ret = $stmt->execute([$id,]);
		if (!$ret) {
		    dbprint("<!--error Model::delete_item-->");
		    return false;
		} 
		return true;
	}


	public function addVideoID($videoid, $user='')
	{
		// 挿入（プリペアドステートメント）
		$stmt = $this->pdo->prepare("INSERT INTO tbl_recomen(videoid, user) VALUES (?,?)");
		$ret = $stmt->execute([$videoid, $user]);
		if (!$ret) {
		    dbprint("<!--error Model::addVideoID-->");
		} 
	}
	public function getVideoIDs($maxCount=5)
	{
	    $stmt = $this->pdo->prepare("SELECT videoid FROM tbl_recomen ORDER BY id DESC");
	    $ret = $stmt->execute();
		if (!$ret) {
		    dbprint("<!--error Model::getVideoIDs-->");
		} 

	    $r = $stmt->fetchAll();
	    $result = [];
		
		for ($i=0; $i < count($r); $i++) { 
		    # code...
		    $result[] = $r[$i]['videoid'];
		}

		if(count($result) > $maxCount){
			$a = array_slice($result, 0, $maxCount);
			return $a;
		}

	    return $result;
	}

}

/*
以下サンプル
try {

    // 接続
    $pdo = new PDO('sqlite:my_sqlite_db.db');

    // SQL実行時にもエラーの代わりに例外を投げるように設定
    // (毎回if文を書く必要がなくなる)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // デフォルトのフェッチモードを連想配列形式に設定 
    // (毎回PDO::FETCH_ASSOCを指定する必要が無くなる)
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // テーブル作成
    $pdo->exec("CREATE TABLE IF NOT EXISTS tbl_recomen(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        videoid VARCHAR(15),
        user VARCHAR(10),
        plycnt INTEGER
    )");

    // 挿入（プリペアドステートメント）
    $stmt = $pdo->prepare("INSERT INTO tbl_recomen(videoid, user) VALUES (?,?)");
    foreach ([['OwWT43Eb3IM', 'fifi'], ['OwWT43Eb3IM', 'obata']] as $params) {
        $stmt->execute($params);
    }

    // 選択 (プリペアドステートメント)
    // $stmt = $pdo->prepare("SELECT videoid FROM tbl_recomen ORDER BY id DESC");
    $stmt = $pdo->prepare("SELECT videoid FROM tbl_recomen");
    $stmt->execute();
    $r2 = $stmt->fetchAll();

    // 結果を確認
    var_dump($r2);

    // 接続を閉じる
    $pdo = null;

} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
*/



?>
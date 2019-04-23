<?php
/*
	Model.class.php
 */


/* 環境設定 */
date_default_timezone_set('Asia/Tokyo');

define('DB_NAME', 'db_tweetbox');
define('TBL_NAME', 'tbl_tweet');
define('DEBUG_PRINT', false);
define('DB_BAK_DIR', 'db.bak');



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
		# バックアップを取る
		

		$file = DB_NAME.'.db';
		$newfile = "db.bak/".DB_NAME.date("(Y-m-d_His)").'.db.bak';
		@mkdir(dirname($newfile), 0755, false);

		if (!copy($file, $newfile)) {
		    echo "failed to copy $file...\n";
		}


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
		return $this->_add_data($data["message"]);
	}



	private function _add_data($message){
		// 挿入（プリペアドステートメント）
		$stmt = $this->pdo->prepare("
			INSERT INTO ".TBL_NAME."(message) VALUES (?)
			");
		$ret = $stmt->execute([$message, ]);
		if (!$ret) {
		    dbprint( "<!--error Model::add_data-->");
		    return false;
		} 
		return true;
	}

	# TEST
	public function test_add_data($count){

		for ($i=0; $i < $count; $i++) { 
			$ret = $this->_add_data("dummy-mes-".$i);
			if (!$ret) {
		    	dbprint( "<!--error Model::test_add_data-->");
			} 
		}
		
	}


	public function get_data($maxCount=20){
		// 挿入（プリペアドステートメント）
	    $stmt = $this->pdo->prepare("SELECT * FROM ".TBL_NAME." WHERE hidden=0 ORDER BY id ASC ");
	    $ret = $stmt->execute();
		if (!$ret) {
		    dbprint("<!--error Model::get_data-->");
		} 

	    $result = $stmt->fetchAll();

		if(count($result) > $maxCount){
			$a = array_slice($result, 0, $maxCount);
			return $a;
		}

	    return $result;
	}
	
	/**
	 */
	public function delete_item($id){

		// return $this->real_delete_item($id);
		return $this->hide_item($id);
	}

	/**
	 */
	public function real_delete_item($id){
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
	 */
	public function hide_item($id){
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



}




?>
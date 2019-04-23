<?php
/**
 * util.php
 * ユーザ補助関数
 */


/**
 *  HTMLコメントアウトの書き出し
 */       
function htmlComment($str=''){
	// echo "<!-- Admin:: ".$str." --><br>";
}

/**
 *  Flush!!
 *  HTMLでコメントをかきだし
 */    
function flushrc($s)
{
	if($s != ""){
		echo "<p class='bg-info'>".$s."</p>";
	}
	
	// echo "<p class='bg-success'>".$s."</p>";
	// echo "<p >".$s."</p>";
}


?>
<?php
	/**
	 * 提示操作信息，并且实现页面跳转
	 *@param string $mes
	 *@param string $url
	 *@return 提示信息，实现页面跳转
	 */
	function alertMes($mes,$url){
		echo "<script type='text/javascript'>alert('{$mes}');location.href='{$url}';</script>";
	}

	/**
	 * 截取文件扩展名
	 *@param string $filename
	 *@return string
	 */
	function getExt($filename){
		// pathinfo()函数：返回文件路径的信息
		return strtolower(pathinfo($filename,PATHINFO_EXTENSION));
	}

	/**
	 * 产生唯一名称
	 *@param int $length
	 *@return string
	 */
	function getUniqidName($length=10){
		// microtime()返回当前 Unix 时间戳和微秒数
		// uniqid()获取一个带前缀、基于当前时间微秒数的唯一ID
		return substr(md5(uniqid(microtime(true),true)),0,$length);
	}
?>
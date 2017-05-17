<?php
	//读取指定目录
	/**
	 * 遍历目录函数，只读取目录中的最外层的内容
	 *@param string $path
	 *@return array
	 */
	 function readDirectory($path){
		// 打开目录
		$handle = opendir($path);
		// 定义数组存储数据
		$arr = array();
		// 读取目录：注意不全等的使用
		while(($file = readdir($handle)) !== false){
			// 过滤当前目录以及上一级目录
			if($file != '.' && $file != '..'){
				if(is_file($path.$file)){
					$arr['file'][] = $file;
				}else if(is_dir($path.$file)){
					$arr['dir'][] = $file;
				}
			}
		}
		// 关闭目录
		closedir($handle);
		// 返回数组
		return $arr;
	}
	
	/**
	 * 得到文件夹的大小
	 *@param string $path
	 *@return int
	 */
	function dirSize($path){
		$sum = 0;
		global $sum;
		$handle = opendir($path);
		while(($file = readdir($handle)) !== false){
			if ($file != '.' && $file != '..') {
				if(is_file($path.$file)){
					$sum += filesize($path.$file);
				}
				if (is_dir($path.$file)) {
					// PHP中的魔术常量
					// 递归
					$func = __FUNCTION__;
					$func($path.$file);
				}
			}
		}
		closedir($handle);
		return $sum;
	}
	
	/**
	 * 创建文件夹
	 *@param string $dirname
	 *@return string $mes
	 */
	function createFolder($dirname){
		// 检测文件夹名称的合法性
		if (checkFilename(basename($dirname))) {
			// 当前目录下是否存在同名文件夹名称
			if (!file_exists($dirname)) {
				if (mkdir($dirname,0777,true)) {
					$mes = '文件夹创建成功！';
				}else{
					$mes = '文件夹创建失败！';
				}
			}else{
				$mes = '存在相同文件夹名称！';
			}
		}else{
			$mes = '非法文件夹名称！';
		}
		return $mes;
	}

	/**
	 * 重命名文件夹
	 *@param string $oldname
	 *@param string $newname
	 *@return string $mes
	 */
	function renameFolder($oldname,$newname){
		// 检测文件夹名称的合法性
		if (checkFilename(basename($newname))) {
			// 检测当前目录下是否存在同名文件夹名称
			if(!file_exists($newname)){
				// rename()函数：重命名或者移动文件
				if (rename($oldname,$newname)) {
					$mes = '重命名成功！';
				}else{
					$mes = '重命名失败！';
				}
			}else{
				$mes = '存在相同文件夹名称！';
			}
		}else{
			$mes = '非法文件夹名称！';
		}
		return $mes;
	}

	/**
	 * 复制文件夹
	 *@param string $src 源地址
	 *@param string $dst 目标地址
	 *@return string 
	 */
	function copyFolder($src,$dst){
		if (!file_exists($dst)) {
			mkdir($dst,0777,true);
		}
		$handle = opendir($src);
		while(($file = readdir($handle)) !== false){
			if($file != '.' && $fiel != '..'){
				if(is_file($src.$file)){
					copy($src.$file,$dst.'/'.$file);
				}
				if (is_dir($src.$file)) {
					$func = __FUNCTION__;
					$func($src.$file,$dst.'/'.$file);
				}
			}
		}
		closedir($handle);
		return '复制成功！';
	}

	/**
	 * 剪切文件夹
	 *@param string $src 源地址
	 *@param string $dst 目标地址
	 *@return string $mes
	 */
	function cutFolder($src,$dst){
		if (file_exists($dst)) {
			if(is_dir($dst)){
				if (!file_exists($dst.'/'.basename($src))) {
					// rename()函数：重命名或者移动文件
					if(rename($src, $dst.'/'.basename($src))){
						$mes = '剪切成功！';
					}else{
						$mes = '剪切失败！';
					}
				}else{
					$mes = '存在同名文件夹！';
				}
			}else{
				$mes = '不是一个文件夹！';
			}
		}else{
			$mes = '目标文件夹不存在！';
		}
		return $mes;
	}

	/**
	 * 删除文件夹
	 *@param string $path
	 *@return string
	 */
	function deleteFolder($path){
		$handle = opendir($path);
		while(($file = readdir($handle)) !== false){
			if ($file != '.' && $file != '..') {
				if(is_file($path.$file)){
					unlink($path.$file);
				}
				if(is_dir($path.$file)){
					$func = __FUNCTION__;
					$func($path.$file);
				}
			}
		}
		closedir($path);
		rmdir($path);//删除文件夹，此时里面的文件已经不存在
		return '文件夹删除成功！';

	}
?>
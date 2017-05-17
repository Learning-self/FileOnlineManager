<?php
	header("content-type:text/html;charset=utf-8");

	require_once 'common.func.php';
	require_once 'dir.func.php';
	require_once 'file.func.php';

	error_reporting(0);

	$path = "/uploads/";
	if (isset($_REQUEST['act'])) {
		$act = $_REQUEST['act'];
	}
	if (isset($_REQUEST['filename'])) {
		$filename = $_REQUEST['filename'];
	}
	$filepath = $_REQUEST['filepath']?$_REQUEST['filepath']:$path.$filename;
	if (isset($_REQUEST['dirname'])) {
		$dirname = $_REQUEST['dirname'];
	}
	$dirpath = $_REQUEST['dirpath']?$_REQUEST['dirpath']:$path;
	if($act == '创建文件'){
		$mes = createFile($filepath);
		alertMes($mes,'index.php');
	}elseif ($act == 'showContent') {
		$content = file_get_contents($filepath);
		if (strlen($content)) {
			// 高亮显示字符串中的PHP代码
			$content = highlight_string($content,true);
			// 高亮显示文件中的PHP代码
			// highlight_file($filepath);
			$str =<<<EOF
			<table width='100%' bgcolor='pink' cellpadding='5' cellspacing='0'>
				<tr>
					<td>{$content}</td>
				</tr>
			</table>
EOF;
			echo $str;
		}else{
			alertMes('文件没有内容，请编辑后再查看！','index.php');
		}
	}elseif ($act == 'editContent') {
		$content = file_get_contents($filepath);
		$str = <<<EOF
		<form action="index.php?act=doEdit" method='post'>
			<textarea name="content" cols="190" rows="10">{$content}</textarea><br>
			<input type="hidden" name="filename" value="{$filename}" />
			<input type="hidden" name="path" value="{$filepath}" />
			<input type="submit" value="修改文件内容" />
		</form>
EOF;
		echo $str;
	}elseif($act == 'doEdit'){
		$content = $_REQUEST['content'];
		if (file_put_contents($filepath, $content)) {
			$mes = '文件修改成功！';
		}else{
			$mes = '文件修改失败！';
		}
		alertMes($mes,'index.php');
	}elseif ($act == 'renameFile') {
		$str = <<<EOF
		<form action="index.php?act=doRename" method="post">
			请填写新的文件名：<input type="text" name="newname" placeholder="重命名..."/>
			<input type="hidden" name="filename" value="{$filename}" />
			<input type="hidden" name="path" value="{$filepath}" />
			<input type="submit" value="重命名" />
		</form>
EOF;
		echo $str;
	}elseif ($act == 'doRename') {
		$newname = $_REQUEST['newname'];
		$mes = renameFile($filepath,$newname);
		alertMes($mes,'index.php');
	}elseif($act == 'copyFile'){
		$str = <<<EOF
		<form action="index.php?act=doCopy" method="post">
			请填写目标路径：<input type="text" name="dstname" placeholder="目标路径" />
			<input type="hidden" name="path" value="{$filepath}" />
			<input type="submit" value="复制文件" />
		</form>
EOF;
		echo $str;
	}elseif($act == 'doCopy'){
		$mes = copyFile($filepath,$_REQUEST['dstname']);
		alertMes($mes,'index.php');
	}elseif($act == 'cutFile'){
		$str = <<<EOF
		<form action="index.php?act=doCut" method="post">
			请填写目标路径：<input type="text" name="dstname" placeholder="目标路径" />
			<input type="hidden" name="path" value="{$filepath}" />
			<input type="submit" value="复制文件" />
		</form>
EOF;
		echo $str;
	}elseif($act == 'doCut'){
		$mes = cutFile($filepath,$_REQUEST['dstname']);
		alertMes($mes,'index.php');
	}elseif ($act == 'delFile'){
		$mes = deleteFile($filepath);
		alertMes($mes,'index.php');
	}elseif($act == 'downFile'){
		downFile($filepath);
	}elseif ($act == '创建文件夹') {
		$mes = createFolder($dirpath);
		alertMes($mes,'index.php');
	}elseif($act == 'renameFolder'){
		$str = <<<EOF
			<form action="index.php?act=doRenameFolder" method="post">
				请填写新的文件夹名称：<input type='text' name='newname' placeholder='重命名' />
				<input type='hidden' name='path' value="{$dirpath}" />
				<input type='hidden' name='dirname' value="{$dirname}" />
				<input type='submit' value='重命名' />
			</form>
EOF;
			echo $str;
	}elseif ($act == 'doRenameFolder') {
		$newname = $_REQUEST['newname'];
		$mes = renameFolder($dirname,$path.$newname);
		alertMes($mes,'index.php');
	}elseif($act == 'copyFolder'){
		$str = <<<EOF
			<form action='index.php?act=doCopyFolder'method='POST'>
				将文件夹复制到：<input type='text' name='dstname' placeholder='目标路径'>
				<input type='hidden' name='dirpath' value="{$dirpath}" />
				<input type='hidden' name='dirname' value='{$dirname}' />
				<input type='submit' value='复制文件夹' />
			</form>
EOF;
		echo $str;
	}elseif($act == 'doCopyFolder'){
		$dstname = $_REQUEST['dstname'];
		$mes = copyFolder($dirpath,$path.$dstname.basename($dirpath));
		alertMes($mes,'index.php');
	}
	

	$info = readDirectory($dirpath);

	if (!$info) {
		alertMes('没有文件存在！','index.php');
	}

	// print_r($info);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>WEB在线文件管理器</title>
<link rel="stylesheet" href="css/cikonss.css" />
<link rel="stylesheet" href="css/index.css" />
<script src="jquery-ui/js/jquery-1.10.2.js"></script>
<script src="jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
<script src="jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="js/index.js"></script>
<link rel="stylesheet" href="jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css"  type="text/css"/>
</head>
<body>
	<div id="showDetail"><img src="" id="showImg" alt="" /></div>
	<h1>WEB在线文件管理器</h1>
	<div id="top">
		<ul id="nav">
			<li><a href="index.php" title="主目录"><span class="outspan icon icon-small icon-square"><span class="icon-home"></span></span></a></li>
			<li><a href="#" id="newFile" title="新建文件"><span class="outspan icon icon-small icon-square"><span class="icon-file"></span></span></a></li>
			<li><a href="#" id="newFolder" title="新建文件夹"><span class="outspan icon icon-small icon-square"><span class="icon-folder"></span></span></a></li>
			<li><a href="#" id="upload" title="上传文件"><span class="outspan icon icon-small icon-square"><span class="icon-upload"></span></span></a></li>
			<?php
				$back = ($dirpath == '/uploads/')?'/uploads/':dirname($dirpath);
			?>
			<li><a onclick="location.href='index.php?path=<?php echo $back; ?>';" title="返回上级目录"><span class="outspan icon icon-small icon-square"><span class="icon-arrowLeft"></span></span></a></li>
		</ul>
	</div>
	<form action="index.php" method="post" enctype="multipart/form-data">
		<table id="table">
			<tr id="createFile">
				<td>请输入文件名称</td>
				<td>
					<input type="text" name="filename" />
					<input type="submit" name="act" value="创建文件" />
				</td>
			</tr>
			<tr id="createFolder">
				<td>请输入文件夹名称</td>
				<td>
					<input type="text" name="dirname" />
					<input type="submit" name="act" value="创建文件夹" />
				</td>
			</tr>
			<tr id="uploadFile">
				<td>请选择要上传的文件</td>
				<td>
					<input type="file" name="myFile" />
					<input type="submit" name="act" value="上传文件" />
				</td>
			</tr>
			<tr>
				<td>编号</td>
				<td>名称</td>
				<td>类型</td>
				<td>大小</td>
				<td>可读</td>
				<td>可写</td>
				<td>可执行</td>
				<td>创建时间</td>
				<td>修改时间</td>
				<td>访问时间</td>
				<td>操作</td>
			</tr>
			<?php
				if (!empty($info['file'])) {
					$i = 1;
					foreach ($info['file'] as $filename) {
						$filepath = $path.$filename;
			?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $filename; ?></td>
							<td><?php $src = filetype($filepath)=='file'?'file_ico.png':'folder_ico.png'; ?><img src="<?php echo 'images/'.$src; ?>" alt=""></td>
							<td><?php echo transBytes(filesize($filepath)); ?></td>
							<td><?php $src = is_readable($filepath)?'correct.png':'error.png'; ?><img src="<?php echo 'images/'.$src; ?>" alt=""></td>
							<td><?php $src = is_writable($filepath)?'correct.png':'error.png'; ?><img src="<?php echo 'images/'.$src; ?>" alt=""></td>
							<td><?php $src = is_executable($filepath)?'correct.png':'error.png'; ?><img src="<?php echo 'images/'.$src; ?>" alt=""></td>
							<td><?php echo date('Y-m-d H:i',filectime($filepath)); ?></td>
							<td><?php echo date('Y-m-d H:i',filemtime($filepath)); ?></td>
							<td><?php echo date('Y-m-d H:i',fileatime($filepath)); ?></td>
							<td>
							<?php  
								$ext = strtolower(end(explode('.', $filename)));
								$exts = array('gif','jpeg','jpg','png');
								if (in_array($ext, $exts)) {
							?>
								<input type="hidden" name="imgfilename" class="imgfilename" value="<?php echo $filename; ?>" />
								<input type="hidden" name="imgfilepath" class="imgfilepath" value="<?php echo $filepath; ?>" />
								<a class="showimg" ><img class="small" src="images/show.png"  alt="" title="查看"/></a>|
							<?php
								}else{
							?>
								<a href="index.php?act=showContent&filepath=<?php echo $filepath;?>&filename=<?php echo $filename;?>" ><img class="small" src="images/show.png"  alt="" title="查看"/></a>|
							<?php
								}
							?>	<input type="hidden" name="filename" class="filename" value="<?php echo $filename; ?>" />
								<input type="hidden" name="filepath" class="filepath" value="<?php echo $filepath; ?>" />
								<a href="index.php?act=editContent&filepath=<?php echo $filepath;?>&filename=<?php echo $filename;?>"><img class="small" src="images/edit.png"  alt="" title="修改"/></a>|
								<a href="index.php?act=renameFile&filepath=<?php echo $filepath;?>&filename=<?php echo $filename;?>"><img class="small" src="images/rename.png"  alt="" title="重命名"/></a>|
								<a href="index.php?act=copyFile&filepath=<?php echo $filepath;?>&filename=<?php echo $filename;?>"><img class="small" src="images/copy.png"  alt="" title="复制"/></a>|
								<a href="index.php?act=cutFile&filepath=<?php echo $filepath;?>&filename=<?php echo $filename;?>"><img class="small" src="images/cut.png"  alt="" title="剪切"/></a>|
								<a href="#" class="delFile"><img class="small" src="images/delete.png"  alt="" title="删除"/></a>|
								<a href="index.php?act=downFile&filepath=<?php echo $filepath;?>&filename=<?php echo $filename;?>"><img class="small"  src="images/download.png"  alt="" title="下载"/></a>
							</td>
						</tr>
			<?php
						$i++;
					}
				}
				/*读取目录的操作*/
				if (!empty($info['dir'])) {
					$i = $i==null?1:$i;
					foreach($info['dir'] as $dir){
						$dirname = $dir;
						$dirpath = $path.$dir.'/';
			?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $dirname; ?></td>
					<td><?php $src = filetype($dirpath)=='file'?'file_ico.png':'folder_ico.png'; ?><img src="<?php echo 'images/'.$src; ?>" alt=""></td>
					<td><?php $sum = 0;echo transBytes(dirSize($dirpath)); ?></td>
					<td><?php $src = is_readable($dirpath)?'correct.png':'error.png'; ?><img src="<?php echo 'images/'.$src; ?>" alt=""></td>
					<td><?php $src = is_writable($dirpath)?'correct.png':'error.png'; ?><img src="<?php echo 'images/'.$src; ?>" alt=""></td>
					<td><?php $src = is_executable($dirpath)?'correct.png':'error.png'; ?><img src="<?php echo 'images/'.$src; ?>" alt=""></td>
					<td><?php echo date('Y-m-d H:i',filectime($dirpath)); ?></td>
					<td><?php echo date('Y-m-d H:i',filemtime($dirpath)); ?></td>
					<td><?php echo date('Y-m-d H:i',fileatime($dirpath)); ?></td>
					<td>
						<a href="index.php?dirpath=<?php echo $dirpath; ?>" ><img class="small" src="images/show.png"  alt="" title="查看"/></a>|
						<a href="index.php?act=renameFolder&dirpath=<?php echo $dirpath;?>&dirname=<?php echo $dirname;?>"><img class="small" src="images/rename.png"  alt="" title="重命名"/></a>|
						<a href="index.php?act=copyFolder&dirpath=<?php echo $dirpath;?>&dirname=<?php echo $dirname;?>"><img class="small" src="images/copy.png"  alt="" title="复制"/></a>|
						<a href="index.php?act=cutFolder&dirpath=<?php echo $dirpath;?>&dirname=<?php echo $dirname;?>"><img class="small" src="images/cut.png"  alt="" title="剪切"/></a>|
						<a href="#" class="delFolder"><img class="small" src="images/delete.png"  alt="" title="删除"/></a>|
						<a href="index.php?act=downFolder&dirpath=<?php echo $dirpath;?>&dirname=<?php echo $dirname;?>"><img class="small"  src="images/download.png"  alt="" title="下载"/></a>
					</td>
				</tr>
			<?php
					$i++;
					}
				}
			?>
		</table>
	</form>
</body>
</html>
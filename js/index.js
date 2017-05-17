window.onload = function(){

	function show(dis){
		document.getElementById(dis).style.display = 'block';
	}

	function delFile(filename,path){
		if(window.confirm('您确定要删除吗？删除之后无法恢复哦！！！')){
			location.href = 'index.php?act=delFile&filename='+filename+'&path='+path;
		}
	}

	function delFolder(dirname,path){
		if(window.confirm('您确定要删除吗？删除之后无法恢复哦！！！')){
			location.href = 'index.php?act=delFolder&dirname='+dirname+'&path='+path;
		}
	}
	// 这里应用了jQuery-UI
	function showDetail(t,filename){
		$('#showImg').attr('src',filename);
		$('#showDetail').dialog({
			height:'auto',
			width:'auto',
			position:{my:'center',at:'center',collision:'fit'},
			modal:false,//是否模式对话框
			draggable:true,//是否允许拖拽
			resizable:true,//是否允许拖动
			title:t,//对话框标题
			show:'slide',
			hide:'explode'
		});
	}

	function __goBack(back){
		location.href = 'index.php?path='+back;
	}
	/*nav*/
	document.getElementById('newFile').onclick = function(){
		show('createFile');
	}
	document.getElementById('newFolder').onclick = function(){
		show('createFolder');
	}
	document.getElementById('upload').onclick = function(){
		show('uploadFile');
	}
	shows = document.getElementsByClassName('showimg');
	delFiles = document.getElementsByClassName('delFile');
	filenames = document.getElementsByClassName('filename');
	imgfilenames = document.getElementsByClassName('imgfilename');
	filepaths = document.getElementsByClassName('filepath');
	imgfilepaths = document.getElementsByClassName('imgfilepath');
	for (i = 0; i < shows.length; i++) {
		// 这里是闭包的问题，使用匿名函数解决
			shows[i].onclick = function(num){
				return function(){
					imgfilename = imgfilenames[num].value;
					imgfilepath = imgfilepaths[num].value;
					showDetail(imgfilename,imgfilepath);
				}
		}(i);
	}
	for(i = 0;i < delFiles.length;i++){
		delFiles[i].onclick = function(num){
			return function(){
				filename = filenames[num].value;
				filepath = filepaths[num].value;
				delFile(filename,filepath);
			} 
		}(i);
	}
}
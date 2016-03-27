<?php
/**
 *      本程序由尹兴飞开发
 *      若要二次开发或用于商业用途的，需要经过尹兴飞同意。
 *
 *		http://app.yinxingfei.com			插件技术支持
 *
 *		http://www.cglnn.com			    插件演示站点
 *
 ->		==========================================================================================
 *
 *      2014-11-01 开始由6.1升级到6.2！
 *
 *		愿我的同学、家人、朋友身体安康，天天快乐！
 ->		同时也祝您使用愉快！
 */
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
if(!submitcheck('submit')) {
	$list = '';
	$query = DB::query("SELECT * FROM ".DB::table('yinxingfei_zzza_grade')." ORDER BY id");
	while($value = DB::fetch($query)) {
		$list .= showtablerow('', array('class="td25 lightfont"','class="td24"','class="td28"',''), array(
				'<em class="zzza_hall_top_left_infor_dj_'.$value['id'].'">(LV:'.$value['id'].')</em>',
				'<input type="text" class="txt" name="grouptitle['.$value['id'].']" value="'.$value['grouptitle'].'">',
				'<em id="min_'.$value['id'].'" style="width: 35px;display: inline-block;">'.$value['min'].'</em>&nbsp;~&nbsp;<input type="text" class="max" name="max['.$value['id'].']" dateid="'.$value['id'].'" id="max_'.$value['id'].'" onchange="upnextminb(this,\''.$value['id'].'\');" onkeyup="upnextmina(this,\''.$value['id'].'\');" onclick="changegc(this);" value="'.$value['max'].'" style="width:40px;">',
				''
		), TRUE);
	}
		echo <<<EOT
<script src="source/plugin/yinxingfei_zzza/assets/js/jquery-1.7.2-min.js" type="text/javascript"></script>
<script type="text/JavaScript">
	zzza_jq = jQuery.noConflict();
	function upnextmina(obj,id){
		var max = parseInt(obj.value);
		var nextid = parseInt(id) + 1;
		if(isNaN(max)){
			return false;
		}
		zzza_jq('#min_'+nextid).text(max);
	}
	function changegc(obj){
		obj.style.backgroundColor = '#F9F9F9';
	}
	function upnextminb(obj,id){
		var max = parseInt(obj.value);
		var min = zzza_jq('#min_'+id).text();
		var nextid = parseInt(id) + 1;
		var nextmax = zzza_jq('#max_'+nextid).val();
		if(isNaN(max)){
			alert('不能为空');
			obj.value = parseInt(min) + 1;
			zzza_jq('#min_'+nextid).text(obj.value);
			obj.focus();
			return false;
		}
		if(parseInt(max) <= parseInt(min)){
			alert('上限不能小于或等于下限');
			obj.value = parseInt(min) + 1;
			var nextid = parseInt(id) + 1;
			zzza_jq('#min_'+nextid).text(obj.value);
			return false;
		}
		zzza_jq('#min_'+nextid).text(max);
	}
	function allupnextminb(obj,id){
		var max = parseInt(obj.value);
		var min = zzza_jq('#min_'+id).text();
		var nextid = parseInt(id) + 1;
		var nextmax = zzza_jq('#max_'+nextid).val();
		if(parseInt(nextmax) <= parseInt(max)){
			alert('等级的上限超出下一级的上限');
			return false;
		}else{
			return true;
		}
	}
	function check(){
		var flag = true;
		zzza_jq("#grade input[type='text']").each(function(){
			if(zzza_jq(this).val().length == 0){
				alert('不能为空，请填写完整！');
				zzza_jq(this).focus();
				flag = false;
			}
		});
		zzza_jq("#grade input[class='max']").each(function(){
			var dateid = zzza_jq(this).attr('dateid');
			if(!allupnextminb($('max_'+dateid),dateid)){
				zzza_jq(this).focus();
				zzza_jq(this).css('background-color','red');
				flag = false;
			}			
		});
		return flag;
	}
</script>
<style>
	#grade_set label {
		padding-right: 20px;
	}
	.zzza_grade_icon{
		background: url(source/plugin/yinxingfei_zzza/assets/newimg/admin_cp_grade.jpg) no-repeat;
		height: 18px;
		width: 18px;
		display: inline-block;
		overflow: hidden;
		line-height: 999px;
	}
	.zzza_grade_1 {
		    background-position: -13px -13px;
	}
	.zzza_grade_2 {
		    background-position: -13px -43px;
	}
	.zzza_grade_3 {
		    background-position: -13px -73px;
	}
	.zzza_grade_4 {
		    background-position: -13px -103px;
	}
	.zzza_grade_5 {
		    background-position: -13px -133px;
	}
	.zzza_grade_6 {
		    background-position: -13px -163px;
	}
	.zzza_grade_7 {
		    background-position: -13px -193px;
	}
	.zzza_grade_8 {
		    background-position: -13px -223px;
	}
	.zzza_grade_9 {
		    background-position: -13px -253px;
	}
	.zzza_grade_10 {
		    background-position: -13px -283px;
	}
	.zzza_grade_11 {
		    background-position: -13px -313px;
	}
	.zzza_grade_12 {
		    background-position: -13px -343px;
	}
	.zzza_grade_13 {
		    background-position: -13px -373px;
	}
	.zzza_grade_14 {
		    background-position: -13px -403px;
	}
</style>
EOT;
	showtips('<li>插件内置9个用户等级，您可以自定义等级头衔，让您网站的每日摇摇乐更个性</li><li>根据用户每日摇摇乐累积次数进行等级划分，促进用户每天访问网站进行每日摇摇乐</li><li>请访问：<strong><a href="http://app.yinxingfei.com" target="_blank">http://app.yinxingfei.com</a></strong>，获取更多帮助与最新开发动态，每日摇摇乐站长QQ群：<strong>187204948</strong></li>');
	showformheader("plugins&operation=config&identifier=yinxingfei_zzza&pmod=grade&caozuo=setval",'onsubmit="return check();"');
	showtableheader('等级设置','','id="grade_set"');
	function grade_type($id){
		foreach ( array(1,2,3,4,5,6,7,8,9,10,11,12,13,14) as $v ){
			if($id == $v){
				$list .= '<label><input name="grade_type" type="radio" value="'.$v.'" checked/><div class="zzza_grade_icon zzza_grade_'.$v.'">'.$v.'</div></label>';
			}else{
				$list .= '<label><input name="grade_type" type="radio" value="'.$v.'" /><div class="zzza_grade_icon zzza_grade_'.$v.'">'.$v.'</div></label>';
			}
		}
		return $list;
	}
	function getSetZzza($type){
		return DB::result_first("SELECT val FROM ".DB::table('yinxingfei_zzza_set')." WHERE type = '".$type."'");
	}
	$grade_type = getSetZzza('grade_type');
	$grade_tx = getSetZzza('grade_tx');
	$grade_type_list = grade_type($grade_type);
	showsetting('风格类型', '', '', $grade_type_list);
	showsetting('头衔名称', 'grade_tx', $grade_tx, 'text');
	showsubmit('submit','保存设置','','','',false);
	showtablefooter();
	showformfooter();
	showformheader("plugins&operation=config&identifier=yinxingfei_zzza&pmod=grade&caozuo=saveval",'onsubmit="return check();"');
	showtableheader('等级参数','','id="grade"');
	showsubtitle(array('','等级头衔','累积摇奖天数介于',''));
	echo $list;
	showsubmit('submit','保存设置','td','','',false);
	showtablefooter();
	showformfooter();
} else {
	$caozuo = $_GET['caozuo'];
	if($caozuo == 'setval'){
		DB::insert('yinxingfei_zzza_set',array('type'=>'grade_type','val'=>$_GET['grade_type']),false,true);//更新插入数据
		DB::insert('yinxingfei_zzza_set',array('type'=>'grade_tx','val'=>$_GET['grade_tx']),false,true);//更新插入数据
	}elseif($caozuo == 'saveval'){
		if(is_array($_GET['grouptitle'])) {
			foreach($_GET['grouptitle'] as $id => $val) {
				$minid = $id - 1;
				$min = intval($_GET['max'][$minid]);
				DB::update('yinxingfei_zzza_grade', array(
					'grouptitle' => $_GET['grouptitle'][$id],
					'min' => $min,
					'max' => intval($_GET['max'][$id]),
				), " id = '".$id."'");
			}
		}
	}
	cpmsg('保存成功','action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=grade', 'succeed');
}	
?>
<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

if(!submitcheck('submit')) {
	$list = '';
	$query = DB::query("SELECT * FROM ".DB::table('yinxingfei_zzza_fw')." ORDER BY id");
	while($value = DB::fetch($query)) {
		$list .= showtablerow('', array('class="td25"', 'class="td31"',''), array(
			'<input class="checkbox" type="checkbox" name="delete[]" value="'.$value['id'].'">',
			'<input type="text" class="fwa" name="fwa['.$value['id'].']" value="'.$value['fwa'].'" onblur="javascript:CheckInputInt(this)" onkeyup="javascript:CheckInt(this);" style="width:40px;">&nbsp;~&nbsp;<input type="text" class="fwb" name="fwb['.$value['id'].']" value="'.$value['fwb'].'" onblur="javascript:CheckInputInt(this)" onkeyup="javascript:CheckInt(this);" style="width:40px;">',
			'<input type="text" name="fwbfb['.$value['id'].']" class="gailv" value="'.$value['fwbfb'].'" onblur="javascript:CheckInputInt(this)" onkeyup="javascript:CheckInt(this);" style="width:40px;">&nbsp;%',
			''
		), TRUE);
		
	}
	echo <<<EOT
<script src="source/plugin/yinxingfei_zzza/assets/js/jquery-1.7.2-min.js" type="text/javascript"></script>
<style>
.newtd25{
	text-align: center;
	width: 50px;
}
</style>
<script type="text/JavaScript">
	zzza_jq = jQuery.noConflict();
	zzza_jq(document).ready(function(){
		zzza_jq("#probability input[type='checkbox']").click(function(){
			var nocheckednum = zzza_jq("#probability input[type='checkbox']").length - zzza_jq("#probability input[type='checkbox']:checked").length;
			if(nocheckednum <= 0){
				alert('至少需要一个范围！');
				zzza_jq(this).attr("checked", false); 
				return false;
			}
		});
	});
	var rowtypedata = [
		[
			[1, '<div><a href="javascript:;" class="deleterow" onclick="deleterow(this)" style="color:#666;">撤消</a></div>', 'newtd25'],
			[1, '<input type="text" class="fwa" name="newfwa[]" data="new" onblur="javascript:CheckInputInt(this)" onkeyup="javascript:CheckInt(this);" style="width:40px;">&nbsp;~&nbsp;<input type="text" class="fwb" name="newfwb[]" data="new" onblur="javascript:CheckInputInt(this)" onkeyup="javascript:CheckInt(this);" style="width:40px;">', 'td31'],
			[1, '<input type="text" name="newfwbfb[]" data="new" class="gailv" onblur="javascript:CheckInputInt(this)" onkeyup="javascript:CheckInt(this);" style="width:40px;">&nbsp;%'],
			[1, ''],
		],
	];
	function check(){
		zzza_jq("#probability input[type='number']").each(function(){
			if(zzza_jq(this).val().length == 0){
				alert('不能为空，请填写完整！');
				zzza_jq(this).focus();
				return false;
			}
		});
		var nocheckednum = zzza_jq("#probability input[type='checkbox']").length - zzza_jq("#probability input[type='checkbox']:checked").length;
		if(nocheckednum <= 2){
			alert('至少需要一个范围！');
			zzza_jq("#probability input[type='checkbox']:checked").last().attr("checked", false); 
			return false;
		}
		return true;
	}
	function CheckInputInt(oInput) {
		if(zzza_jq(oInput).attr("data") == 'new'){
			if(oInput.value.length > 0){
				var re = /^[0-9]*[1-9][0-9]*$/;
				if (!re.test(oInput.value.replace(/(^\s*)|(\s*$)/g, "")) || parseInt(oInput.value) < 1){
					alert('请输入大于0的整数');
					oInput.focus();
					return false;
				}
			}
		}else{
			var re = /^[0-9]*[1-9][0-9]*$/;
			if (!re.test(oInput.value.replace(/(^\s*)|(\s*$)/g, "")) || parseInt(oInput.value) < 1){
				alert('请输入大于0的整数');
				oInput.focus();
				return false;
			}
		}
		var sum = 0;
		if(zzza_jq(oInput).hasClass("gailv")){
			zzza_jq("#probability input[class='gailv']").each(function(){
				sum += parseInt(zzza_jq(this).val());
			});
			if(sum > 100){
				alert('所有概率总和不能超过100%');
				oInput.value = 100 - (sum - parseInt(oInput.value));
				return false;
			}
		}
		if(zzza_jq(oInput).hasClass("fwb")){
			var fwaval = zzza_jq(oInput).prev().val();
			if(parseInt(oInput.value) <= parseInt(fwaval)){
				alert('上限不能小于或等于下限！请重新输入');
				oInput.value = '';
				oInput.focus();
				return false;
			}
		}
	}
	function CheckInt(oInput) {
		var re = /^[0-9]+.?[0-9]*$/;
		if (!re.test(oInput.value)){
			oInput.value = '';
		} else if (oInput.value == "0" && oInput.value.length == 1) {
			oInput.value = '';
		}
	}
</script>
EOT;

		showformheader('plugins&operation=config&identifier=yinxingfei_zzza&pmod=probability','onsubmit="return check();"');
		showtips('<li>可以设置不同范围对应不同的概率，每个范围的概率加起来不能超过100%</li><li>为了让每日摇摇乐科学合理的计算用户获得积分，请将每个范围的上下界限与相邻的范围界限对齐，例如：1~10，10~15，15~20</li><li>请访问：<strong><a href="http://app.yinxingfei.com" target="_blank">http://app.yinxingfei.com</a></strong>，获取更多帮助与最新开发动态，每日摇摇乐站长QQ群：<strong>187204948</strong></li>');
		showtableheader('摇奖范围与概率','','id="probability"');
		showsubtitle(array('','范围','概率',''));
		echo $list;
		echo '<tr><td></td><td colspan="3"><div><a href="javascript:;" onclick="addrow(this, 0)" class="addtr">添加</a></div></td></tr>';
		showtablefooter();
		showtableheader();
		showsubmit('submit','保存设置','del','','',false);
		showtablefooter();
		showformfooter();

	} else {
		if($ids = dimplode($_GET['delete'])) {
			DB::query("DELETE FROM ".DB::table('yinxingfei_zzza_fw')." WHERE id IN ($ids)");
		}

		if(is_array($_GET['fwa'])) {
			foreach($_GET['fwa'] as $id => $val) {
				$_GET['fwbfb'][$id] = $_GET['fwbfb'][$id] > 100 ? 99 : $_GET['fwbfb'][$id];
				DB::update('yinxingfei_zzza_fw', array(
					'fwa' => intval($_GET['fwa'][$id]),
					'fwb' => intval($_GET['fwb'][$id]),
					'fwbfb' => intval($_GET['fwbfb'][$id]),
				), " id = '".$id."' ");
			}
		}

		if(is_array($_GET['newfwa'])) {
			foreach($_GET['newfwa'] as $id => $val) {
				$data = array(
					'fwa' => intval($_GET['newfwa'][$id]),
					'fwb' => intval($_GET['newfwb'][$id]),
					'fwbfb' => intval($_GET['newfwbfb'][$id])
				);
				DB::insert('yinxingfei_zzza_fw', $data);
			}
		}
		cpmsg('数据保存成功',"action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=probability", 'succeed');

	}
?>
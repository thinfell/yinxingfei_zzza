<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

if(!submitcheck('submit')) {
	$list = '';
	$query = DB::query("SELECT * FROM ".DB::table('yinxingfei_zzza_range')." ORDER BY id");
	$index = 1;
	while($value = DB::fetch($query)) {
		$list .= showtablerow('', array('class="new40"','class="td23"',''), array(
			'<em id="id_min_'.$index.'"  style="width:30px;display: inline-block;">'.$value['min'].'</em>&nbsp;~',
			'<input type="text" class="max" name="max['.$value['id'].']" value="'.$value['max'].'" id="id_max_'.$index.'" dateid="'.$index.'" onchange="upnextfw1(this,\''.$index.'\')" onclick="changegc(this);" onkeyup="upnextfw2(this,\''.$index.'\');" style="width:40px;">',
			'<input type="text" name="percentage['.$value['id'].']" class="gailv" value="'.$value['percentage'].'" onchange="Checkpercentage(this)" style="width:40px;">&nbsp;%',
			''
		), TRUE);
		$index++;
	}
	echo <<<EOT
<script src="source/plugin/yinxingfei_zzza/assets/js/jquery-1.7.2-min.js" type="text/javascript"></script>
<style>
.new40{
	width: 50px;
	text-align: center;
}
</style>
<script type="text/JavaScript">
	zzza_jq = jQuery.noConflict();
	function allupnextfw1(obj,id){
		var max_v = parseInt(obj.value);
		var min_v = parseInt(zzza_jq('#id_min_'+id).text());
		var fw_last_id = parseInt(id) - 1;
		var fw_next_id = parseInt(id) + 1;
		var max_next_v = zzza_jq('#id_max_'+fw_next_id).val();
		if(max_v >= parseInt(max_next_v)){
			alert('当前范围的上限超出下一个范围的上限');
			return false;
		}else{
			return true;
		}
	}
	function upnextfw1(obj,id){
		var max_v = parseInt(obj.value);
		var min_v = parseInt(zzza_jq('#id_min_'+id).text());
		var fw_last_id = parseInt(id) - 1;
		var fw_next_id = parseInt(id) + 1;
		var max_next_v = zzza_jq('#id_max_'+fw_next_id).val();
		if(isNaN(max_v)){
			alert('不能为空');
			obj.value = parseInt(min_v) + 1;
			zzza_jq('#id_min_'+fw_next_id).text(obj.value);
			obj.focus();
			return false;
		}
		if(max_v <= min_v){
			alert('上限不能小于或等于下限');
			obj.value = parseInt(min_v) + 1;
			zzza_jq('#id_min_'+fw_next_id).text(obj.value);
			obj.focus();
			return false;
		}
		zzza_jq('#id_min_'+fw_next_id).text(max_v);
	}
	function changegc(obj){
		obj.style.backgroundColor = '#F9F9F9';
	}
	function upnextfw2(obj,id){
		var min_v = parseInt(obj.value);
		var fw_last_id = parseInt(id) - 1;
		var fw_next_id = parseInt(id) + 1;
		if(isNaN(min_v)){
			return false;
		}
		zzza_jq('#id_min_'+fw_next_id).text(min_v);
	}
	function Checkpercentage(oInput){
		var sum = 0;
		zzza_jq("#probability input[class='gailv']").each(function(){
			if(!isNaN(parseInt(zzza_jq(this).val()))){
				sum += parseInt(zzza_jq(this).val());
			}
		});
		var nowbfb = parseInt(oInput.value);
		if(isNaN(nowbfb)){
			alert('不能为空');
			oInput.value = 100 - sum;
			oInput.focus();
			return false;
		}
		if(sum > 100){
			alert('所有概率总和不能超过100%');
			oInput.value = 100 - (sum - parseInt(oInput.value));
			return false;
		}
	}
	function check(){
		var flag = true;
		zzza_jq("#probability input[type='text']").each(function(){
			if(zzza_jq(this).val().length == 0){
				alert('不能为空，请填写完整！');
				zzza_jq(this).focus();
				flag = false;
			}
		});
		zzza_jq("#probability input[class='max']").each(function(){
			var dateid = zzza_jq(this).attr('dateid');
			if(!allupnextfw1($('id_max_'+dateid),dateid)){
				zzza_jq(this).focus();
				zzza_jq(this).css('background-color','red');
				flag = false;
			}			
		});
		return flag;
	}
</script>
EOT;
		showformheader('plugins&operation=config&identifier=yinxingfei_zzza&pmod=probability','onsubmit="return check();"');
		showtips('<li>插件内置3个范围，合理设置每个范围，谨防积分泛滥</li><li>每个范围的概率加起来不能超过100%，可以设置不同范围对应不同的概率</li><li>请访问：<strong><a href="http://app.yinxingfei.com" target="_blank">http://app.yinxingfei.com</a></strong>，获取更多帮助与最新开发动态，每日摇摇乐站长QQ群：<strong>187204948</strong></li>');
		showtableheader('摇奖范围与概率','','id="probability"');
		showsubtitle(array('','范围','概率',''));
		echo $list;
		showtablefooter();
		showtableheader();
		showsubmit('submit','保存设置','td','','',false);
		showtablefooter();
		showformfooter();

	} else {
		if(is_array($_GET['max'])) {
			foreach($_GET['max'] as $id => $val) {
				$minid = $id - 1;
				$min = intval($_GET['max'][$minid]);
				DB::update('yinxingfei_zzza_range', array(
					'min' => $min,
					'max' => intval($_GET['max'][$id]),
					'percentage' => intval($_GET['percentage'][$id]),
				), " id = '".$id."' ");
			}
		}
		cpmsg('数据保存成功',"action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=probability", 'succeed');
	}
?>
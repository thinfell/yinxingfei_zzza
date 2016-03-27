<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
showtableheader();
showtagheader('tbody class="psetting"', '', true);
showtitle('未启用的扩展');
$sql = "SELECT * FROM ".DB::table('yinxingfei_zzza_kuozhan')." WHERE available = '0' ORDER BY identifier ASC";
$query = DB::query($sql);
while($value = DB::fetch($query)){
	if($value['name']){
		showtablerow('class="hover hover" style="overflow: hidden;"', array(), array('
					<div style="float:left;width:20%;padding-bottom: 10px;">
						<p><span class="bold">'.$value['name'].'</span></p>
						<p><span class="sml"></span></p>
					</div>
					<div style="float:left;width:80%;padding-bottom: 10px;">
						<p>说明：'.$value['description'].'</p>
						<p style="overflow: hidden;">
							<div style="float:left;width:50%;">版本：'.$value['version'].'<em style="color:#ddd;padding: 0px 5px;">|</em>作者：'.$value['copyright'].'<em style="color:#ddd;padding: 0px 5px;">|</em>类型：'.$value['type'].'<em style="color:#ddd;padding: 0px 5px;">|</em>文件夹：'.$value['identifier'].'</div>
							<div style="text-align: right;float:right;width:50%;">
								<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend&caozuo=kaiqi&kzid='.$value['kzid'].'">开启</a>
								<span>&nbsp;</span>
								<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&identifier=yinxingfei_zzza&pmod=extend&caozuo=xiezai&kzid='.$value['kzid'].'">卸载</a>
							</div>
						</p>
					</div>
					'));
		echo '<style>.yincang_'.$value['identifier'].' {display:none;}</style>';
	}
}
showtagfooter('tbody');
showtablefooter();
?>
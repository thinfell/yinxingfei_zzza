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
var zzza_jq = jQuery.noConflict();
var isBegin = false;
var yinxingfei_zzza_start;
var nuii = 10;
zzza_jq(document).ready(function(){
	juzhong();
	juzhong();
	juzhong();
});
function yjyema(){
	zzza_jq(".yyl-random-box").slideToggle(1000);
}
function juzhong(){
	var a = document.getElementById("yyl-random-box");
	a.style.left = (zzza_jq(window).width()/2 - 400/2)+"px";
	a.style.top = (zzza_jq(window).scrollTop()+zzza_jq(window).height()/2 - 275.5555/2)+"px";
	zzza_jq(".yyl-random-box").css('display','block');
}
function go_yj(){
	if(isBegin) return false;
	isBegin = true;
	var num = document.getElementById('zzza_fw1').value;
	var num_arr = (num+'').split('');
	yinxingfei_zzza_num();
	setTimeout(function(){
		clearTimeout(yinxingfei_zzza_start);
		zzza_jq(".yinxingfei_zzza_num").each(function(index){
			zzza_jq(this).children().text(num_arr[index]);
		});
		//document.getElementById('yinxingfei_zzza_form').submit();
	}, 2000);
}
function yinxingfei_zzza_num(){
	var all_zzzax = Math.round(Math.random()*1000);
	var zzzaggx = all_zzzax%10;
	var zzzassx = ((all_zzzax-zzzaggx)/10)%10;
	var zzzabbx = (all_zzzax-zzzaggx-zzzassx*10)/100;
	zzza_jq('#zzza_txt1').text(zzzabbx);
	zzza_jq('#zzza_txt2').text(zzzassx);
	zzza_jq('#zzza_txt3').text(zzzaggx);
	yinxingfei_zzza_start = setTimeout(yinxingfei_zzza_num,1);
}
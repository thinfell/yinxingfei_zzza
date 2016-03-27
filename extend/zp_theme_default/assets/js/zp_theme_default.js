var zzza_jq = jQuery.noConflict();
var isBegin = false;
function closeRandomBox(){
	zzza_jq(".zzza_random_box").fadeOut(500);
	return false;
}
function zzza_play(){
	if(isBegin) return false;
	isBegin = true;
	zzza_jq.get("plugin.php?id=yinxingfei_zzza:yinxingfei_zzza&mod=today_extcredit",function(data,status){
		if(status != 'success'){
			alert('服务器繁忙，获取数据失败，请刷新重试');
			return false;
		}
		var today_extcredit = data;
		today_extcredit = today_extcredit.replace(/(^\s*)|(\s*$)/g, "");
		var num_arr = (today_extcredit+'').split('');
		zzza_jq(".zzza_num").each(function(index){
			var num = zzza_jq(this).children();
			var gbz = -693 + (77*num_arr[index]);
			setTimeout(function(){
				num.animate({
					'marginTop': gbz
				},{
					duration: 3000+index*1000,
					easing: "easeInOutCirc",
					complete: function(){
						if(index==2){
							isBegin = false;
							document.getElementById('zzza_form').submit();
						}
					}
				});
			}, index * 100);
		});
	});
	return false;
}
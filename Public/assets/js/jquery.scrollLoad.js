/*
 * scrollLoad 1.0 - Extremely Scroll Load Content for jQuery
 * http://code.haohailuo.com/
 *
 * Copyright (c) 2011 Laurence.xu code.haohailuo.com
 * Author Laurence.xu (haohailuo@163.com)
 */
/*
 * use demo
 * $.scrollLoad({'loadurl':'test.php', 'objid':'mypage', 'appendid':'body', 'loadtype':'post'}); ,Ĭ��Ϊget����
*/
(function($) {
	var run_num = 0, requesting = false, request_obj = null, run_interval;
	$.scrollLoad = function(options) {
		var opts = $.extend({}, $.scrollLoad.defaults, options);
		if(opts.loadurl == null || opts.loadurl == '') {
			return false;
		}
		
		if(opts.appendid == null || opts.appendid == '') {
			return false;
		}
		
		if(opts.objid != null && opts.objid != '') {
			$('#'+opts.objid).click(function(){
				//���ü���
				showContent(opts);
			});
		}
		
		run_interval = setInterval(function(){
			cando(opts);
		}, 1000);//ÿ��2���ӵ���һ��cando�������жϵ�ǰ������λ�á�
	};
	
	function showContent(opts) {
		if(requesting && request_obj) {
			/*request_obj.abort();
			requesting = false;
			request_obj = null;*/
			return false;
		}

		requesting = true;

		var old_content = $('#'+opts.objid).html();
		if(opts.loadtype == 'GET' || opts.loadtype == 'get') {
			$('#'+opts.objid).html('');//加载显示
			request_obj = $.get(opts.loadurl, function(data) {
				requesting = false;
				request_obj = null;
				$('#'+opts.appendid).append(data);
				$('#'+opts.objid).html(old_content);
			});
		}else {
			$('#'+opts.objid).html('');//加载显示
			request_obj = $.post(opts.loadurl,{page:run_num}, function(data) {
				requesting = false;
				request_obj = null;
				$('#'+opts.appendid).append(data);
				$('#'+opts.objid).html(old_content);
			});
		}
		
		run_num++;
	}
	
	function cando(opts){
		if(opts.loadnum > 0 && opts.loadnum <= run_num) {
			clearInterval(run_interval);
			return false;
		}
		
		var heights = $(window).height() + document.documentElement.scrollTop;
	
		if(heights > $('#'+opts.objid).offset().top) {
			showContent(opts);//����ǣ�����showContent����������ݡ�
		}
	}
	
	//Ĭ�ϲ���
	$.scrollLoad.defaults = {
		objid: null,			//����������ص�Ŀ��Ԫ��
		appendid: null,			//���ص�������ӵ���Ŀ��Ԫ��
		loadnum: 5,				//���ش���
		loadurl: null,			//���ص�url��ַ
		loadtype: 'GET'			//ajax���ط�ʽ
	};
}(jQuery));

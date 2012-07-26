


$.ajaxSetup({
	cache:false
});


$(document).ready(function(){


/* datepicker setting */

 $.datepicker.regional['ko'] = { // Default regional settings
    closeText: '닫기',
    prevText: '이전달',
    nextText: '다음달',
    currentText: '오늘',
    monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)', '7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
    monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    dayNames: ['일','월','화','수','목','금','토'],
    dayNamesShort: ['일','월','화','수','목','금','토'],
    dayNamesMin: ['일','월','화','수','목','금','토'],
    weekHeader: 'Wk',
    dateFormat: 'yy-mm-dd', // [mm/dd/yy], [yy-mm-dd], [d M, y], [DD, d MM]
//    firstDay: 0,
    isRTL: false,
    showMonthAfterYear: true,
    yearSuffix: ''
 };


$.datepicker.setDefaults({
dateFormat:'yy-mm-dd',
changeYear: true,
changeMonth: true,
showMonthAfterYear: true,
showButtonPanel: true,
showOn: 'both',
buttonImageOnly: true,
buttonImage: "/img/start_point_36.jpg",
buttonText: '달력'
}); 
$.datepicker.setDefaults($.datepicker.regional['ko']);




/* ajax setting */

    loading = $('<img src="/img/icon/ajax-loading.gif" width="16" height="16" alt="Loading" />').css({position:'absolute', left:'10px', top:'10px'}).prependTo(document.body).hide();


    $(document).ajaxStart(function() { loading.css({top:$('html').scrollTop()+10+'px'}).show(); });

    $(document).ajaxStop(function() { loading.hide(); });

    $(document).ajaxError(function(e, xhr, settings, exception) { 
    	alert('일시적 오류입니다. 잠시후 다시 시도해 주세요.');
    	loading.hide();
/*
            $.jGrowl('일시적 오류입니다. 잠시후 다시 시도해 주세요.', { 
            life: 1000
            ,position:'top-right'
            //,theme:'jGerror' 
            });    
*/
    });

    $.ajaxSetup({

    cache: false,
    timeout: 10000
    });

});



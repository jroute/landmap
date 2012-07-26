


$.ajaxSetup({
	cache:false
});


$(document).ready(function(){


/* datepicker setting */

 $.datepicker.regional['ko'] = { // Default regional settings
    closeText: '�ݱ�',
    prevText: '������',
    nextText: '������',
    currentText: '����',
    monthNames: ['1��(JAN)','2��(FEB)','3��(MAR)','4��(APR)','5��(MAY)','6��(JUN)', '7��(JUL)','8��(AUG)','9��(SEP)','10��(OCT)','11��(NOV)','12��(DEC)'],
    monthNamesShort: ['1��','2��','3��','4��','5��','6��','7��','8��','9��','10��','11��','12��'],
    dayNames: ['��','��','ȭ','��','��','��','��'],
    dayNamesShort: ['��','��','ȭ','��','��','��','��'],
    dayNamesMin: ['��','��','ȭ','��','��','��','��'],
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
buttonText: '�޷�'
}); 
$.datepicker.setDefaults($.datepicker.regional['ko']);




/* ajax setting */

    loading = $('<img src="/img/icon/ajax-loading.gif" width="16" height="16" alt="Loading" />').css({position:'absolute', left:'10px', top:'10px'}).prependTo(document.body).hide();


    $(document).ajaxStart(function() { loading.css({top:$('html').scrollTop()+10+'px'}).show(); });

    $(document).ajaxStop(function() { loading.hide(); });

    $(document).ajaxError(function(e, xhr, settings, exception) { 
    	alert('�Ͻ��� �����Դϴ�. ����� �ٽ� �õ��� �ּ���.');
    	loading.hide();
/*
            $.jGrowl('�Ͻ��� �����Դϴ�. ����� �ٽ� �õ��� �ּ���.', { 
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



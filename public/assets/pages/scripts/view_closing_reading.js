$(document).ready(function(){

	var url = $('#ajax_datatable').data('url');
	url = url.replace('?', '');
	var buttons = '<a class="dt-button buttons-html5 btn green btn-outline" href="'+url+'/import"><span>Import Stock Readings</span></a>';
	buttons += '<a class="dt-button buttons-html5 btn green btn-outline" href="'+url+'/market_import"><span>Import Market Readings</span></a>';

	$buttons = $(buttons);
	$('.tools .dt-buttons').prepend(buttons);

});
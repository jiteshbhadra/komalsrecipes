jQuery(document).ready(function($){
	$('.rwf-required strong, .rwf-optional strong, .rwf-layout strong, .rwf-settings strong, .rwf-advance strong').on('click', function(){
		$(this).parent().toggleClass('rwf-collapsed');
		$(this).parent().find('p').toggle();
	});
	$('.wp-list-table.styles ul li').on('click', function(){
		$(this).siblings().removeClass('selected');
		$(this).addClass('selected');
		$('input[name="rfw_style"]').val($(this).data('id'));
	});
});
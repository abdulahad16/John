jQuery(document).ready(function($) {

	$('.list-icons li').each(function() {
		
		$(this).hover(function () {
			$(this).addClass('hovered');
		});
		
		$(this).mouseout(function () {
			$(this).removeClass('hovered');
		});
		
		$(this).click(function () {
			$('.list-icons li').removeClass('activei');
			$(this).addClass('activei');
			
			$('.list-icons li input').each(function() {
				$(this).removeAttr('checked');
			});
			
			$(this).find('input').attr('checked','checked');
			
		});
		
	});

if(jQuery('#upload_image_button').length != 0) {
 
jQuery('#upload_image_button').click(function() {
 formfield = jQuery('#custom_icon').attr('name');
 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
 return false;
});
 
window.send_to_editor = function(html) {
 imgurl = jQuery('img',html).attr('src');
 jQuery('#custom_icon').val(imgurl);
 tb_remove();
}

}

});
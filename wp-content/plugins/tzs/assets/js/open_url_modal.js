function displayUrlInModal(url, title, onLoaded) {
        if ((url == '') || (url == 'undefined') || (title == '') || (title == 'undefined')) {
            return;
        }
	
	var d = jQuery("<div>Загрузка...</div>").dialog({
		title:title,
		modal:true,
		zIndex: 10000,
		autoOpen: true,
		width: 'auto',
		resizable: false,
		closeOnEscape: false,
		open: function(event, ui) {
			jQuery(this).find(".ui-dialog-titlebar-close", ui.dialog || ui).hide();
		}
	});
	
	jQuery("<div></div>").load(url, null, function() {
		if (onLoaded != null)
			onLoaded();
		jQuery(d).remove();
		jQuery(this).find("div[class=entry-content]").dialog({
			title:title,
			modal:true,
			zIndex: 10000,
			autoOpen: true,
			width: 'auto',
                        height: 'auto',
			resizable: true,
			buttons: {
				'Закрыть': function () {
					jQuery(this).dialog("close");
				}
			},
			close: function (event, ui) {
				jQuery(this).remove();
			},
			open: function() {
				if (typeof(redraw) != "undefined") {
					redraw();
				}
			}
		})
	});
}
/* 
 * Отображение сообщения в модальном окне с использованием jQueri-UI
 */

function ksk_show_msg(msg, mode) {
    if ((msg == '') || (msg == undefined)) {
        return;
    }
    
    if ((mode == '') || (mode == undefined)) {
        mode = 'Информация';
    }
    
    jQuery('<div></div>').appendTo('body')
        .html('<div style="margin-top: 20px;">' + msg + '</div>')
        .dialog({
            modal: true,
            title: mode,
            zIndex: 10000,
            autoOpen: true,
            width: 'auto',
            resizable: false,
            buttons: new Object({
                'Закрыть': function () {
                    jQuery(this).dialog("close");
                }
            }),
            close: function (event, ui) {
                jQuery(this).remove();
        }
    });
    
}
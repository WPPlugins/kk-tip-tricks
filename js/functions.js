
function kkttCloseDiv(id){
	jQuery('#'+id).fadeOut("fast");
}

function addTextWindow(){
	jQuery('#kktt-edit').fadeOut("fast",function(){
		jQuery('#kktt-add').fadeIn("fast");
	});
	
}

function editKKTT(id){
	jQuery('#kktt-add').fadeOut("fast",function(){
		jQuery('#kktt-edit').fadeIn("fast",function(){
			jQuery('#kktt-text-edit').val(jQuery('#text-'+id).val());
			jQuery('#kktt-edit-id').val(id);
		});
	});
	
}

function kkttSave(){
	
	var error = 0;
    var text = jQuery('#kktt-text').val();

    if(text == ''){
    	error = 1;
    }

    if(error == 0){
        var wiadomosc = {
            action : 'save_kktt',
            text : text,
            update	:	'0',
            id		:	''
        };

        jQuery('#save-loading').show();
        jQuery.post(ajaxurl,wiadomosc,function(html){

            jQuery('#save-loading').hide();

            var dane = html.split('|||');

            if(dane[0] == 1){
                
                jQuery('#kktt-table tbody').append(dane[3]);

            }

            jQuery('#info').html(dane[1]);
            setTimeout("jQuery('#info').html(' ');",3000);
        });
        
    }else{
    	jQuery('#kktt-error-puste-pola').show();
    	setTimeout("jQuery('#kktt-error-puste-pola').hide();",3000);
    }
	
}

function kkttSaveEdit(){
	
	var error = 0;
    var text = jQuery('#kktt-text-edit').val();
    var id = jQuery('#kktt-edit-id').val();
    
    if(text == ''){
    	error = 1;
    }

    if(error == 0){
        var wiadomosc = {
            action : 'save_kktt',
            text : text,
            update	:	'1',
            id		:	id
        };

        jQuery('#save-loading-edit').show();
        jQuery.post(ajaxurl,wiadomosc,function(html){

            jQuery('#save-loading-edit').hide();

            var dane = html.split('|||');

            if(dane[0] == 1){
                
            	jQuery('#kktt-row-'+id).remove();
                jQuery('#kktt-table tbody').append(dane[3]);

            }

            jQuery('#info').html(dane[1]);
            setTimeout("jQuery('#info').html(' ');",3000);
        });
        
    }else{
    	jQuery('#kktt-error-puste-pola').show();
    	setTimeout("jQuery('#kktt-error-puste-pola').hide();",3000);
    }
	
}

function delKKTT(id){

    if(confirm("Are you sure?")){
        
        jQuery.post(ajaxurl,{
            action : 'del_kktt',
            id : id
        },function(html){

             var dane = html.split('|||');

            if(dane[0] == 1){

                jQuery('#kktt-row-'+id).remove();

            }

            jQuery('#info').html(dane[1]);
            setTimeout("jQuery('#info').html(' ');",3000);
        });
    }
}

function zmienStatusKKTT(id){

    var wiadomosc = {
        action : 'zmiana_statusu_kktt',
        id : id
    };
    jQuery('#loader-status-'+id).show();
    jQuery.post(ajaxurl,wiadomosc,function(html){
        
        jQuery('#loader-status-'+id).hide();
        if(html == 00){
            jQuery('#kktt-status-'+id).attr({
                'src':'../wp-content/plugins/kk-tip-tricks/images/nieaktywny.png'
            });
        }else if(html == 10){
            jQuery('#kktt-status-'+id).attr({
                'src':'../wp-content/plugins/kk-tip-tricks/images/aktywny.png'
            });
        }else{
            jQuery('#info').html('<div style="background: #ffd9d9; margin:20px; padding: 10px; border-top: 1px #bb0000 solid; border-bottom: 1px #bb0000 solid;">Error: 0x2345.</div>');

        }
    });

}

function kkttSaveSettings(){
	
    var anim_bar = jQuery('#kktt-anim-belka').val();
    var head_bar = jQuery('#kktt-bar-head').val();
    var back_color = jQuery('#kktt-back-color').val();
    var font_color = jQuery('#kktt-font-color').val();
    var transp = jQuery('#kktt-transp').val();
    
        var wiadomosc = {
            action : 'save_settings_kktt',
            anim_bar	:	anim_bar,
            head_bar : head_bar,
            back_color	:	back_color,
            font_color	:	font_color,
            transp		:	transp
        };

        jQuery('#save-loading-settings').show();
        jQuery.post(ajaxurl,wiadomosc,function(html){

            jQuery('#save-loading-settings').hide();

            var dane = html.split('|||');


            jQuery('#info').html(dane[1]);
            setTimeout("jQuery('#info').html(' ');",3000);
        });
	
}

function animBarSetKKTT(){
	
	if(jQuery('#kktt-anim-belka').val() == '0'){
		jQuery('#kktt-bar-settings').slideUp("fast");
	}else if(jQuery('#kktt-anim-belka').val() == '1'){
		jQuery('#kktt-bar-settings').slideDown("fast");
	}
}

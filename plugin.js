jQuery(function($){
    $('#btn-save-apikey').on('click', function(e){

        var key = $('#sendgrid-api-key-field').val();
        $formData = $('#sendgrid-form').serialize();
        
        $(e.target).attr("disabled","disabled");


        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data:{
                action: 'saveSendGridApiKey',
                nonce: gfsendgrid.nonce,
                data: $formData
            },
            beforeSend:function(){
                $(e.target).html('Salvando...')
            }
        })
        .done(function(response){
            $('#wpbody-content .wrap').prepend('<div class="updated notice is-dismissible"><p>A chave da api SendGrid foi salva com sucesso!</p> </div>');
            $('.notice').delay(2000).slideUp(300, function(){
                $(e.target).prop("disabled", false);
                $(e.target).html('Salvar');
            });

            $('#wpbody-content .wrap').append(response);
        })
        .fail(function(){
            $('#wpbody-content .wrap').prepend('<div class="error notice is-dismissible"><p>Algo deu errado :(</p></div>');
            $('.notice').delay(2000).slideUp(300, function(){
                $(e.target).prop("disabled", false);
            });
        })
        
    })
});


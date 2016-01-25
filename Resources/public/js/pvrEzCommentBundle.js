YUI().use( "node", "io-base", "io-form", "json-parse", "node-event-simulate", function(Y) {
    var form = Y.one('#commentForm');
    var formError = Y.one('#formError');
    var formSuccess = Y.one('#formSuccess');
    var submitButton = Y.one( '#commentForm [name^=AddComment]');
    var commentSort = Y.one( '#comments-sort' );

    form.on( 'submit', function(evt) {
        formError.setHTML('');
        formSuccess.setHTML('');

        evt.preventDefault();
        Y.io( window.url, {
            method: 'POST',
            form: {
                id: form,
                useDisabled: true
            },
            on: {
                start: function( id, args ) {
                    submitButton.set('disabled', true);
                },
                success: function( id, response, args ) {
                    var data = response.responseText;
                    formSuccess.setHTML( data );
                    form.remove();
                },
                failure: function(id, response, args ) {
                    data = Y.JSON.parse( response.responseText );
                    var error = "";

                    Y.Array.each( Object.keys(data), function( key ) {
                        error += data[key] + "<br />";
                    });
                    formError.setHTML( error );
                    Y.one("a.captcha_reload").simulate("click");
                    submitButton.set('disabled', false);
                }
            }
        });
    });

    if( commentSort && commentSort.length){
        commentSort.on( 'change', function(evt) {
            var sort = this.get('value').split("_")[0];
            var order = this.get('value').split("_")[1];
            var url = window.full_url;

            window.location.href = url + "/(cSort)/" + sort + "/(cOrder)/" + order + "/?#comments";
        });
    }

    //todo: translate from jquery to yui
    /*$('.comment-reply').on('click',function() {
        if($(this).hasClass('active')) {
            $('#form_parent_comment_id').val(0);
            $('#commentForm').detach().insertAfter('#formSuccess');
            $(this).find('i').removeClass().addClass('fa fa-reply');
            $(this).removeClass('active');
        } else {
            var $ancien = $('.comment-reply.active');
            if($ancien.length) {
                $ancien.removeClass('active');
                $ancien.find('i').removeClass().addClass('fa fa-reply');
            }
            $(this).addClass('active');
            $(this).find('i').removeClass().addClass('fa fa-times');
            var $reply_container = $(this).next('.reply');

            $('#form_parent_comment_id').val($reply_container.data('id'));
            $('#commentForm').detach().appendTo($reply_container);
        }
    });*/
});
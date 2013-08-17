YUI().use( "node", "io-base", "io-form", "json-parse", "node-event-simulate", function(Y) {
    var form = Y.one('#commentForm');
    var formError = Y.one('#formError');
    var formSuccess = Y.one('#formSuccess');
    var submitButton = Y.one( '#commentForm input[name^=AddComment]');
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

    commentSort.on( 'change', function(evt) {
        var sort = this.get('value').split("_")[0];
        var order = this.get('value').split("_")[1];
        var url = window.full_url;

        window.location.href = url + "/(cSort)/" + sort + "/(cOrder)/" + order + "/?#comments";
    });

});
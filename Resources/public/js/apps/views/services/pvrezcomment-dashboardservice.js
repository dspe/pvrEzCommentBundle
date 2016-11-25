YUI.add('pvrezcomment-dashboardservice', function (Y) {
    Y.namespace('PvrEzComment');

    Y.PvrEzComment.DashboardViewService = Y.Base.create('pvrezcommentDashboardService', Y.eZ.ServerSideViewService, [], {
        initializer: function () {
            // When a user click on a content, go to view content page
            this.on('*:navigateTo', function (e) {
                this.get('app').navigateTo(
                    e.routeName,
                    e.routeParams
                );
            });

            /**
             * When an user confirm to delete a comment
             */
            this.on('*:deleteComment', function (e) {
                var commentId = e.commentId;
                var uri = this.get('app').get('apiRoot') + 'api/ezp/v2/comment/delete/' + commentId;

                this._notify(
                    'Comment ' + commentId + ' will be delete in a few second !',
                    'comment-delete-' + commentId,
                    'started',
                    10
                );

                // route, commentId, uri, DELETE, successMessage
                this._executeRequest(
                    'deleteComment',
                    commentId,
                    uri,
                    'DELETE',
                    'comment-delete-' + commentId,
                    'Comment ' + commentId + ' successful delete !'
                );
            });

            /**
             * When an user confirm to modify comment status
             */
            this.on('*:statusComment', function (e) {
                var commentId = e.commentId;
                var statusId = e.statusId;
                var uri = this.get('app').get('apiRoot') + 'api/ezp/v2/comment/status/' + commentId + '/' + statusId;

                this._notify(
                    'Comment ' + commentId + ' will change status in a few second !',
                    'comment-status-' + commentId,
                    'started',
                    10
                );

                console.log( 'Comment Id: ' + commentId + ' Status Id: ' + statusId);
                this._executeRequest(
                    'statusComment',
                    commentId,
                    uri,
                    'POST',
                    'comment-status-' + commentId,
                    'Comment ' + commentId + ' successful update !'
                );
            });
        },

        /**
         * Rendering the dashboard from the server side
         **/
        _load: function (callback) {
            var offset = this.get('request').params.offset;
            if (!offset) offset = 0;

            var uri = this.get('app').get('apiRoot') + 'comment/dashboard/' + offset;

            var status = this.get('request').params.status;
            if (status) uri += '/' + status;

            Y.io(uri, { // YUI helper to do AJAX request, see http://yuilibrary.com/yui/docs/io/
                method: 'GET',
                on: {
                    success: function (tId, response) {
                        this._parseResponse(response); // provided by Y.eZ.ServerSideViewService
                        callback(this);
                    },
                    failure: this._handleLoadFailure, // provided by Y.eZ.ServerSideViewService
                },
                context: this,
            });
        },

        /**
         * Notify user with the bottom bar
         **/
        _notify: function (text, identifier, state, timeout) {
            this.fire('notify', {
                notification: {
                    identifier: identifier,
                    text: text,
                    state: state,
                    timeout: timeout
                }
            });
        },

        //  commentId, uri, DELETE, successMessage
        _executeRequest: function (route, commentId, uri, method, key, successMessage) {
            var capi = this.get('capi');
            var that = this;

            capi.getDiscoveryService().getInfoObject(
                route,
                function (error) {
                    if (error) {
                        that._notify( error.message, key, 'error', 60 );
                        return;
                    }
                    capi.getConnectionManager().request(
                        method,
                        uri,
                        "",
                        {"Accept": "application/json"},
                        function (error, response) {
                            if (error && (response.status != 200 || response.status != 204)) {
                                var err = response.xhr.statusText + ': ' + JSON.parse(response.xhr.response).ErrorMessage.errorDescription;
                                that._notify( err, key, 'error', 30 );
                            } else {
                                that._notify( successMessage, key, 'done', 20 );
                                that.fire('refreshView',{});
                            }
                        }
                    );
                }
            );
        }


    });
});

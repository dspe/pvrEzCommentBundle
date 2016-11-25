YUI.add('pvrezcomment-dashboardservice', function (Y) {
    Y.namespace('PvrEzComment');

    Y.PvrEzComment.DashboardViewService = Y.Base.create('pvrezcommentDashboardService', Y.eZ.ServerSideViewService, [], {
        initializer: function () {
            this.on('*:navigateTo', function (e) {
                this.get('app').navigateTo(
                    e.routeName,
                    e.routeParams
                );
            });
        },

        _load: function (callback) {
            var uri = this.get('app').get('apiRoot') + 'comment/dashboard';

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
    });
});
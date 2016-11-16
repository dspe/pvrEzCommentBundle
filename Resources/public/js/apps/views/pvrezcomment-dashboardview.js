YUI.add('pvrezcomment-dashboardview', function (Y) {
    Y.namespace('PvrEzComment');

    Y.PvrEzComment.DashboardView = Y.Base.create('pvrezcommentDashboardView', Y.eZ.ServerSideView, [], {

        events: {
            '.pvrezcomment-location': {
                'tap': '_navigateToLocation'
            }
        },

        initializer: function () {
            this.containerTemplate = '<div class="ez-view-pvrezcommentdashboardview"/>';
        },

        _navigateToLocation: function(e) {
            var link = e.target;
            e.preventDefault();
            this.fire('navigateTo', {
                routeName: link.getData('route-name'),
                routeParams: {
                    id: link.getData('route-id'),
                    languageCode: link.getData('route-languageCode'),
                }
            });
        }
    });
});
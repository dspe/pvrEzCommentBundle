YUI.add('pvrezcomment-listview', function (Y) {
    Y.namespace('PvrEzComment');

    Y.PvrEzComment.ListView = Y.Base.create('pvrezcommentListView', Y.eZ.ServerSideView, [], {

        events: {
            '.pvrezcomment-location': {
                'tap': '_navigateToLocation'
            }
        },

        initializer: function () {
            console.log('listview.js');
            this.containerTemplate = '<div class="ez-view-pvrezcommentlistview"/>';
        },

        _navigateToLocation: function(e) {
            var link = e.target;
            console.log("listview ici?");
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
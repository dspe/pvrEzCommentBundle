YUI.add('pvrezcomment-listplugin', function (Y) {
    Y.namespace('PvrEzComment.Plugin');

    Y.PvrEzComment.Plugin.ListPlugin = Y.Base.create('pvrezcommentListPLugin', Y.Plugin.Base, [], {
        initializer: function () {
            var app = this.get('host');

            console.log("listplugin.js");
            app.views.pvrezcommentListView = {
                type: Y.PvrEzComment.ListView
            };

            app.route({
                name: 'Dashboard',
                path: '/comment/dashboard',
                view: 'pvrezcommentListView',
                service: Y.PvrEzComment.ListViewService,
                sideViews: {'navigationHub': true, 'discoveryBar': false},
                callbacks: ['open', 'checkUser', 'handleSideViews', 'handleMainView'],
            });
        }
    }, {
        NS: 'pvrezcommentTypeApp'
    });

    Y.eZ.PluginRegistry.registerPlugin(
        Y.PvrEzComment.Plugin.ListPlugin, ['platformuiApp']
    );
});
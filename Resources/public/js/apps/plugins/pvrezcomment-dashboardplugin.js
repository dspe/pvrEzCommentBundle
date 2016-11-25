YUI.add('pvrezcomment-dashboardplugin', function (Y) {
    Y.namespace('PvrEzComment.Plugin');

    Y.PvrEzComment.Plugin.DashboardPlugin = Y.Base.create('pvrezcommentDashboardPLugin', Y.Plugin.Base, [], {
        initializer: function () {
            var app = this.get('host');

            app.views.pvrezcommentDashboardView = {
                type: Y.PvrEzComment.DashboardView
            };

            app.route({
                name: 'pvrDashboard',
                path: '/comment/dashboard',
                view: 'pvrezcommentDashboardView',
                service: Y.PvrEzComment.DashboardViewService,
                sideViews: {'navigationHub': true, 'discoveryBar': false},
                callbacks: ['open', 'checkUser', 'handleSideViews', 'handleMainView'],
            });

            app.route({
                name: 'pvrDashboardOffset',
                path: '/comment/dashboard/:offset/:status',
                view: 'pvrezcommentDashboardView',
                service: Y.PvrEzComment.DashboardViewService,
                sideViews: {'navigationHub': true, 'discoveryBar': false},
                callbacks: ['open', 'checkUser', 'handleSideViews', 'handleMainView'],
            })
        }
    }, {
        NS: 'pvrezcommentTypeApp'
    });

    Y.eZ.PluginRegistry.registerPlugin(
        Y.PvrEzComment.Plugin.DashboardPlugin, ['platformuiApp']
    );
});
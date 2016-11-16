YUI.add('pvrezcomment-navigationplugin', function (Y) {
    Y.namespace('PvrEzComment.Plugin');

    Y.PvrEzComment.Plugin.NavigationPlugin = Y.Base.create('pvrezcommentNavigationPlugin', Y.eZ.Plugin.ViewServiceBase, [], {
        initializer: function () {
            var service = this.get('host'); // the plugged object is called host

            service.addNavigationItem({
                Constructor: Y.eZ.NavigationItemView,
                config: {
                    title: "Comments",
                    identifier: "pvrezcomment-dashboard",
                    route: {
                        name: "pvrDashboard"
                    }
                }
            }, 'platform');
        },
    }, {
        NS: 'pvrezcommentNavigation'
    });

    Y.eZ.PluginRegistry.registerPlugin(
        Y.PvrEzComment.Plugin.NavigationPlugin, ['navigationHubViewService']
    );
});
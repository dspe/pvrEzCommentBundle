YUI.add('pvrezcomment-dashboardview', function (Y) {
    Y.namespace('PvrEzComment');

    Y.PvrEzComment.DashboardView = Y.Base.create('pvrezcommentDashboardView', Y.eZ.ServerSideView, [], {
        /**
         * List of customs events
         **/
        events: {
            '.pvrezcomment-location': {
                'tap': '_navigateToLocation'
            },
            '.pvrezcomment-page-link': {
                'tap': '_navigateToOffset'
            },
            '.pvrezcomment-status': {
                'change': '_filterByStatus'
            },
            '.pvrezcomment-delete-link': {
                'tap': '_confirmDeleteComment'
            },
            '.pvrezcomment-status-link': {
                'tap': '_confirmChangeStatusComment'
            }
        },

        /**
         * Server side rendering
         **/
        initializer: function () {
            this.containerTemplate = '<div class="ez-view-pvrezcommentdashboardview"/>';
        },

        /**
         * Navigate To Main Content Location
         **/
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
        },

        /**
         * Navigate to the Dashboard with pagination
         **/
        _navigateToOffset: function(e) {
            var offset = e.target.getData('offset');

            e.preventDefault();
            this.fire('navigateTo', {
                routeName: 'pvrDashboardOffset',
                routeParams: {
                    offset: offset,
                },
            });
        },

        /**
         * Filter comments by Status
         **/
        _filterByStatus: function(e) {
            var select = e.target;

            this.fire('navigateTo', {
                routeName: 'pvrDashboardOffset',
                routeParams: {
                    offset: "0",
                    status: select.get('value')
                }
            });
        },

        /**
         * Confirm box when you click on Delete Comment
         **/
        _confirmDeleteComment: function (e) {
            var deleteElem = e.target;
            var that = this;
            console.log('Fire confirmDeleteComment');
            e.preventDefault();
            this.fire('confirmBoxOpen', {
                config: {
                    title: "Are you sure you want to delete this comment?",
                    confirmHandler: Y.bind(this._deleteComment, this, deleteElem.getData('comment-id')),
                },
            });
        },

        /**
         * Delete has been confirm .. so proceed
         **/
        _deleteComment: function (commentId) {
            this.fire('deleteComment', { commentId: commentId });
        },

        /**
         * Confirm box when you would like to change the status
         **/
        _confirmChangeStatusComment: function (e) {
            var changeElem = e.target;
            var statusId = changeElem.getData('status-id');
            var commentId = changeElem.getData('comment-id');

            var status = statusId == 1 ? 'Accepted' : 'Rejected';
            e.preventDefault();
            this.fire('confirmBoxOpen', {
                config: {
                    title: 'Are you sure you want to change the comment\'s status to ' + status + '?',
                    confirmHandler: Y.bind(this._changeCommentStatus, this, commentId, statusId)
                }
            });
        },

        /**
         * Change the status of a comment
         * @param commentId
         * @param statusId
         * @private
         */
        _changeCommentStatus: function (commentId, statusId) {
            console.log('CommentId: ' + commentId + ' StatusId: ' + statusId);
            this.fire('statusComment', { commentId: commentId, statusId: statusId } );
        },

    });
});

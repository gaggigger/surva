(function() {
    'use strict';

    angular
        .module('app')
        .factory('Tvara', Tvara);

    Tvara.$inject = ['$rootScope', 'Api', 'Xsrf', 'Site', 'User'];

    function Tvara($rootScope, Api, Xsrf, Site, User) {

        var service = {
            init: init,
            toggleEdit: toggleEdit,
            adminMode: adminMode,
            publicMode: publicMode
        };
        return service;

        ////////////////////////////////////////

        function init() {
            if($rootScope.tvara) return;
            console.log('Tvara.init');
            $rootScope.tvara = {
                user: undefined,
                site: undefined,
                current: undefined,
                edit: false,
                admin: false,
                path: {
                    media: 'media/'
                }
            };
            $rootScope.path = {
                prefix: '',
                media: 'media/'
            };
            Xsrf
                .load()
                .then(Site.load)
                .then(User.load);
        }

        function adminMode() {
            $rootScope.tvara.admin = true;
            $rootScope.path.prefix = 'admin/';
        }

        function publicMode() {
            $rootScope.tvara.admin = false;
            $rootScope.path.prefix = '';
        }

        function toggleEdit() {
            $rootScope.tvara.edit = !$rootScope.tvara.edit;
        }

    }
}());
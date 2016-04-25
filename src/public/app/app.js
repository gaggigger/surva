(function() {
    'use strict';

    angular
        .module('app', [
            'app.cache',
            'flow',
            'ngCookies',
            'ngRoute',
            'ngSanitize',
            'ui.tinymce'
        ])
        .constant('Settings', {
            api: {
                base: './api/'
            }
        })
        .config(appConfig)
        .run(appRun);

    appConfig.$inject = [
        '$routeProvider',
        '$locationProvider'
    ];

    function appConfig($routeProvider, $locationProvider) {
        console.log('appConfig');
        $locationProvider.html5Mode(true);
        $routeProvider
        .when('/admin/:nodes*?', {
            template: '',
            requireLogin: true
        })
        .when('/:nodes*', {
            template: '',
            requireLogin: false
        })
        .otherwise({
            redirectTo: '/home'
        });
    }

    appRun.$inject = ['$rootScope', 'Tvara', 'Site'];

    function appRun($rootScope, Tvara, Site) {
        Tvara.init();
        $rootScope.$on('$routeChangeStart', routeChange);

        function routeChange(event, next, current) {
            if(next.$$route.requireLogin) {
                // Admin area
                Tvara.adminMode();
            }
            Site.select(next.params.nodes);
            /*
            if($rootScope.tvara.dirty) {
                var answer = confirm('You have unsaved changes.' +
                    'Are you sure you want to leave this page?');
                if (!answer) {
                    event.preventDefault();
                }
            }*/
        }
    }

}());

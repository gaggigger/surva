(function() {
    'use strict';

    angular
        .module('app')
        .factory('User', User);

    User.$inject = ['$rootScope', 'Api', 'Settings'];

    function User($rootScope, Api, Settings) {
        
        ////////////////////////////////////////
        
        var service = {
            load: load,
            login: login,
            logout: logout
        };

        return service;

        ////////////////////////////////////////

        function load() {
            $rootScope.tvara.user = undefined;
            return Api
                .post('user', {})
                .then(onLoadSuccess, onLoadFail);
        }

        function login(email, password) {
            if($rootScope.tvara.user) return;
            var data = {
                email: email,
                password: password
            };
            return Api
                .post('login', data)
                .then(onLoginSuccess, onLoginFail);
        }

        function logout() {
            return Api
                .post('logout', {})
                .then(onLogoutSuccess, onLogoutFail);
        }

        ////////////////////////////////////////

        function onLoadSuccess(response) {
            if(response.data) {
                $rootScope.tvara.user = response.data;
                console.log('Logged in as ' + $rootScope.tvara.user.username);
            } else {
                $rootScope.tvara.user = undefined;
                console.log('No user logged in');
            }
        }

        function onLoadFail(response) {
            $rootScope.tvara.user = undefined;
            console.error(response.data);
        }


        function onLoginSuccess(response) {
            $rootScope.tvara.user = response.data;
            console.log('Logged in as ' + $rootScope.tvara.user.username);
        }

        function onLoginFail(response) {
            $rootScope.tvara.user = undefined;
            console.error(response.data);
        }

        function onLogoutSuccess(response) {
            $rootScope.tvara.user = undefined;
            console.log('Logged out');
        }

        function onLogoutFail(response) {
            console.error(response.data);
        }
    }

}());

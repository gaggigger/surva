(function() {
    'use strict';

    angular
        .module('app')
        .factory('Api', Api);

    Api.$inject = ['$http', 'Settings'];

    function Api($http, Settings) {
        
        var apiBase = Settings.api.base;
        
        var factory = {
            get: get,
            post: post
        };
        
        return factory;

        function get(action) {
            return $http.get(apiBase + action);
        }

        function post(action, data) {
            return $http.post(apiBase + action, angular.toJson(data));
        }

    }

}());
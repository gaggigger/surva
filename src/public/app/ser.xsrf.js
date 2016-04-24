(function() {
    'use strict';

    angular
        .module('app')
        .factory('Xsrf', Xsrf);

    Xsrf.$inject = ['Api'];

    function Xsrf(Api) {
        
        var service = {
            load: load
        };
        
        return service;

        function load() {
            console.log('Xsrf.load');
            return Api.get('xsrf');
        }

    }

}());
(function() {
    'use strict';

    angular
        .module('app')
        .controller('FlowController', FlowController);

    FlowController.$inject = ['$rootScope', '$cookies', 'Settings'];

    function FlowController($rootScope, $cookies, Settings) {
        var vm = this;
        var defaultMedia = {
            title: '',
            url: '',
            class: ''
        };

        vm.flow = {};
        vm.media = [];
        vm.progBar = 0;

        vm.add = add;
        vm.submit = submit;
        vm.error = error;
        vm.progress = progress;
        vm.success = success;
        vm.cancel = cancel;
        vm.info = info;

        function add(files) {
            if(!$rootScope.tvara.current) return;
            console.log('FlowController add');
            var media = $rootScope.tvara.current.media;
            media = media || [];
            for(var i in files) {
                var file = files[i];
                console.log('Adding %s', file.name);
                var newMedia = angular.copy(defaultMedia);
                newMedia.title = file.name;
                newMedia.url = file.name;
                media.push(newMedia);
            }
        }

        function success() {
            console.log('Upload Success');
            console.log(vm.flow);
            if(!$rootScope.tvara.current) return;
        }

        function cancel() {
            vm.flow.cancel();
        }

        function info() {
            console.log(vm.flow);
        }

        function error() {
            console.log('FlowController Error');
            console.log(vm.flow);
        }

        function progress() {
            for(var i in vm.flow.files) {
                var file = vm.flow.files[i];
                file.progBar = Math.round(file.progress() * 100);
            }
            vm.progBar = Math.round(vm.flow.progress() * 100);
        }

        function submit() {
            console.log('Setting XSRF Header');
            var token = $cookies.get('XSRF-TOKEN');
            vm.flow.opts.headers = {
                'X-XSRF-TOKEN': token
            };
            vm.flow.opts.target = Settings.api.base + 'upload';
            vm.flow.upload();
        }
    }
}());
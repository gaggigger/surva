(function() {
    'use strict';

    angular
        .module('app')
        .factory('Site', Site);

    Site.$inject = ['$rootScope', '$templateCache', '$routeParams', 'Api'];

    function Site($rootScope, $templateCache, $routeParams, Api) {
        
        var nodeMap = [];
        var service = {
            load: load,
            save: save,
            select: select
        };

        return service;

        ////////////////////////////////////////

        function load() {
            return Api
                .get('site')
                .then(onSiteSuccess, onSiteFail);
        }

        function save() {
            var data = angular.toJson($rootScope.tvara.site);
            return Api.post('site/save', {site: data});
        }

        ////////////////////////////////////////

        function onSiteSuccess(response) {
            $rootScope.tvara.site = response.data;
            init();
        }

        function onSiteFail(response) {
            $rootScope.tvara.site = undefined;
            console.error(response.data);
            return false;
        }

        function init() {
            console.time('Site.init');
            initMap();
            select($routeParams.nodes);
            console.timeEnd('Site.init');
            console.log('Site Initialized', $rootScope.tvara.site);
        }

        ////////////////////////////////////////

        function select(path) {
            if(path === undefined) return;
            // var path =  $routeParams.nodes;
            console.log('Selecting Path "%s"', path);
            path = path.replace(/\/$/, '');
            var found = null;
            for(var key in nodeMap) {
                if(key === path) {
                    found = nodeMap[key];
                }
            }
            if(found === null) {
                $rootScope.tvara.current = nodeMap['404'];
            } else {
                $rootScope.tvara.current = found;
            }
        }

        function initMap() {
            nodeMap = [];
            $rootScope.tvara.site.nodes.map(mapper.bind({}, ''));
            // add 404 page
            nodeMap['404'] = {
                url: '404',
                template: '404.html',
                editorTemplate: '404.html'
            };
        }

        function mapper(parentPath, obj, index, array) {
            parentPath = parentPath || '';
            var url = parentPath + obj.uri;
            nodeMap[url] = obj;
            obj.url = url;
            obj.template = getTemplate(url);
            obj.editorTemplate = getTemplate('admin/' + url);
            obj.parentPath = parentPath;
            if(obj.nodes && obj.nodes.length > 0) {
                obj.nodes.map(mapper.bind({}, parentPath + obj.uri + '/'));
            }
        }

        function getTemplateOptions(url) {
            if(!url || url === undefined) return;
            // split url at '/'
            var parts = url.split('/');
            // remove empty parts
            var cleanParts = [];
            var i;
            for(i = 0; i < parts.length; i++) {
                if(parts[i].length > 0) {
                    cleanParts.push(parts[i]);
                }
            }
            // build template options
            var options = ['default.html'];
            var str = '';
            for(i = 0; i < cleanParts.length; i++) {
                str += cleanParts[i];
                options.push(str + '.html');
                if(i < cleanParts.length - 1) {
                    options.push(str + '/default.html');
                }
                str += '/';
            }
            options.reverse();
            return options;
        }

        function getTemplate(url) {
            if(!url || url === undefined) return;
            var options = getTemplateOptions(url);
            for(var i = 0; i < options.length; i++) {
                if($templateCache.get(options[i])) {
                    return options[i];
                }
            }
            return '404.html';
        }

    }
}());
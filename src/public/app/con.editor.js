(function() {
    'use strict';

    angular
        .module('app')
        .controller('Editor', Editor);

    Editor.$inject = ['$rootScope', 'Site'];

    function Editor($rootScope, Site) {
        var vm = this;

        ////////////////////////////////////////

        vm.save           = save;
        vm.addTestimonial = addTestimonial;
        vm.remove         = remove;
        vm.updateUri      = updateUri;
        vm.add            = add;

        vm.tinymceOptions = {
            inline: true,
            menubar: false,
            // plugins: 'link image code',
            toolbar: 'undo redo | bold italic | styleselect',
            style_formats: [
                { title: 'Body', block: 'p' },
                { title: 'Uppercase Header', block: 'h3' }
            ]
        };

        function save(path) {
            Site
                .save()
                .then(function(curr) {
                    path = path || curr.adminUrl;
                    $location.path(path);
                });
        }

        function updateUri(node) {
            console.log(node);
            Site.initMap();
            $location.path(node.adminUrl);
        }

        function addTestimonial(node) {
            if(!node.testimonials) node.testimonials = [];
            node.testimonials.push({message:'', author:''});
        }

        function add(arr, newValue) {
            if(!arr) arr = [];
            arr.push(newValue);
        }

        function remove(arr, obj) {
            arr.splice(arr.indexOf(obj), 1);
        }

    }

}());

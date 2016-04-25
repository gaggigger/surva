(function() {
    'use strict';

    angular
    .module('app')
    .controller('Footer', Footer);

    Footer.$inject = ['Tvara', 'Site'];

    function Footer(Tvara, Site) {
        var vm = this;

        vm.gotoTop = gotoTop;
        vm.toggleAdmin = toggleAdmin;

        function gotoTop() {
            console.log('Go To Top');
        }

        function toggleAdmin() {
            Tvara.toggleAdmin();
        }
    }
}());

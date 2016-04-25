(function() {
    'use strict';

    angular
        .module('app')
        .controller('AdminBar', AdminBar);

    AdminBar.$inject = ['Tvara', 'Site', 'User'];

    function AdminBar(Tvara, Site, User) {
        var vm = this;

        vm.save = save;
        vm.toggleEdit = Tvara.toggleEdit;
        vm.email = 'admin@surva.com';
        vm.password = 'helloworld';
        vm.user = User;

        vm.login = function() {
            User.login(vm.email, vm.password);
        };

        vm.logout = function() {
            User.logout();
            Tvara.publicMode();
        };

        function save() {
            vm.message = 'Saving';
            Site
                .save()
                .then(function(data) {
                    console.log(data);
                    vm.message = data.message;
                });
        }

    }
}());

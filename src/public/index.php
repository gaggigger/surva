<!DOCTYPE html>
<html ng-app="app">
<base href="/" />
<head>
    <meta charset="utf-8">
    <title>
    {{tvara.current ? tvara.current.linkTitle + ' &bullet; ' : ''}}
    {{tvara.site.title}}
    </title>
    <link rel="stylesheet" href="master.css">
    <style media="screen">
        [ng\:cloak],
        [ng-cloak],
        [data-ng-cloak],
        [x-ng-cloak],
        .ng-cloak,
        .x-ng-cloak {
            display: none !important;
        }
    </style>
</head>

<body>

    <ng-include
        ng-if="tvara.admin"
        src="'admin/main.html'">
    </ng-include>

    <ng-include
        ng-if="!tvara.edit"
        src="'main.html'">
    </ng-include>

    <script src="//code.angularjs.org/1.5.3/angular.min.js"></script>
    <script src="//code.angularjs.org/1.5.3/angular-route.min.js"></script>
    <script src="//code.angularjs.org/1.5.3/angular-sanitize.min.js"></script>
    <script src="//code.angularjs.org/1.5.3/angular-cookies.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/ng-flow/2.7.1/ng-flow-standalone.min.js"></script>
    <script src='//cdn.tinymce.com/4/tinymce.min.js'></script>

    <!-- build:js master.js -->
    <script src="vendor/angular-ui-tinymce/dist/tinymce.min.js"></script>
    <script src="app.cache.js"></script>
    <script src="app/app.js"></script>
    <script src="app/ser.api.js"></script>
    <script src="app/ser.xsrf.js"></script>
    <script src="app/ser.tvara.js"></script>
    <script src="app/ser.site.js"></script>
    <script src="app/ser.user.js"></script>
    <script src="app/con.main.js"></script>
    <script src="app/con.adminbar.js"></script>
    <script src="app/con.editor.js"></script>
    <script src="app/con.footer.js"></script>
    <script src="app/con.flow.js"></script>
    <!-- endbuild -->

</body>

</html>

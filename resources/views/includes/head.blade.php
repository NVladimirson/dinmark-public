<meta charset="utf-8" />
{!! SEO::generate(true) !!}
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
<link href="/assets/css/default/app.min.css" rel="stylesheet" />
<link href="/assets/plugins/flag-icon-css/css/flag-icon.min.css" rel="stylesheet" />
<!-- ================== END BASE CSS STYLE ================== -->
<link href="/assets/css/default/theme/blue.min.css" rel="stylesheet" id="theme-css-link">
<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
<style>
    #page-container:not(.page-sidebar-minified) .sidebar .nav-manager{
        display: none;
    }
    .page-sidebar-minified .sidebar .nav>.nav-manager>a{
        padding: 10px 13.5px;
    }

</style>

@stack('css')

<!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="en" ng-app="myApp" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html lang="en" ng-app="myApp" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html lang="en" ng-app="myApp" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" ng-app="myApp" class="no-js"> <!--<![endif]-->

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{config('app.title')}}</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">
  @if(!config('app.debug'))
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/all.css') }}" />
  @else
    <link rel="stylesheet" type="text/css" href="/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css"></link>
    <link rel="stylesheet" type="text/css" href="/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/bower_components/fullcalendar/dist/fullcalendar.min.css"></link>    
    <link rel="stylesheet" type="text/css" href="/css/app.css"></link>
  @endif

  @stack('styles')

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
  <nav class="navbar navbar-default">
    <div class="container">
        <a class="navbar-brand" href="/">{{config('app.title')}}</a>
      </div>
    </div>
  </nav>
  <div class="container">
    @yield('content')
  </div>

  <!-- Scripts -->
  @if(!config('app.debug'))
    <script src="{{ elixir('js/all.js') }}"></script>
  @else
    <script src="/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/bower_components/sirs-skiptrigger/skipTrigger.jquery.js"></script>
    <script src="/bower_components/mutually-exclusive/mutually-exclusive.jquery.js"></script>
    <script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/bower_components/sirs-skipTrigger/skipTrigger.jquery.js"></script>
    <script src="/bower_components/moment/min/moment.min.js"></script>
    <script src="/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
    <script src="/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="/js/app.js"></script>
  @endif
  @stack('scripts')
</body>
</html>

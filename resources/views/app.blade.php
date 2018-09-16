@include('partials.head')
<body>

@include('partials.nav')

<div class="container">
@include('partials.modal')
@yield('scripts')

@yield('content')

</div>

@include('partials.javascript')
</body>
</html>
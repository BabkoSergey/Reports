<script>
    window.Laravel = {!! json_encode(['user_id' => auth()->check() ? auth()->user()->id : null,]) !!};
</script>

<script src="{{ asset('/js/app.js') }}"></script>

<!-- jQuery 3 -->
<script src="{{ asset('/bower_components/jquery/dist/jquery.min.js') }}"></script>

<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<!-- FastClick -->
<script src="{{ asset('/bower_components/fastclick/lib/fastclick.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('/dist/js/adminlte.min.js') }}"></script>

<!-- select2  -->
<script src="{{ asset('/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

<!-- SlimScroll -->
<script src="{{ asset('/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>

<!-- Bootstrap BootBox -->
<script src="{{ asset('/plugins/bootbox.min.js') }}"></script>

<!-- jQuery UI -->
<script src="{{ asset('/plugins/jquery-ui-1.12.1.custom/jquery-ui.js') }}"></script>

<!-- APP -->
<script src="{{ asset('/js/admin.js') }}"></script>

@stack('scripts')
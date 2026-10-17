<script src="{{ asset('js/plugins/noty.js') }}"></script>
@if (Session::has('flash_notification.message'))
@php
    $level = Session::get('flash_notification.level');
    if ($level == 'info') {
        $level = 'information';
    }
@endphp
<script>
    noty({
        type: '{{ $level }}',
        layout: 'bottomRight',
        text: '{!! Session::get('flash_notification.message') !!}',
        timeout: {{ Session::get('flash_notification.timeout') ?: 'false' }}
    });
</script>
@endif

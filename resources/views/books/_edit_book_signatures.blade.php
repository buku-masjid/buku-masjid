<legend>{{ __('report.signatures') }}</legend>
<div class="row">
    <div class="col-md-4">
        <h4 class="text-primary">{{ __('app.left_part') }}</h4>
        {!! FormField::text('acknowledgment_text_left', [
            'value' => Setting::for($book)->get('acknowledgment_text_left'),
            'label' => __('report.acknowledgment_text'),
        ]) !!}
        {!! FormField::text('sign_position_left', [
            'value' => Setting::for($book)->get('sign_position_left'),
            'label' => __('report.sign_position'),
        ]) !!}
        {!! FormField::text('sign_name_left', [
            'value' => Setting::for($book)->get('sign_name_left'),
            'label' => __('report.sign_name'),
        ]) !!}
    </div>
    <div class="col-md-4">
        <h4 class="text-primary">{{ __('app.mid_part') }}</h4>
        {!! FormField::text('acknowledgment_text_mid', [
            'value' => Setting::for($book)->get('acknowledgment_text_mid'),
            'label' => __('report.acknowledgment_text'),
        ]) !!}
        {!! FormField::text('sign_position_mid', [
            'value' => Setting::for($book)->get('sign_position_mid'),
            'label' => __('report.sign_position'),
        ]) !!}
        {!! FormField::text('sign_name_mid', [
            'value' => Setting::for($book)->get('sign_name_mid'),
            'label' => __('report.sign_name'),
        ]) !!}
    </div>
    <div class="col-md-4">
        <h4 class="text-primary">{{ __('app.right_part') }}</h4>
        {!! FormField::text('acknowledgment_text_right', [
            'value' => Setting::for($book)->get('acknowledgment_text_right'),
            'label' => __('report.acknowledgment_text'),
        ]) !!}
        {!! FormField::text('sign_position_right', [
            'value' => Setting::for($book)->get('sign_position_right'),
            'label' => __('report.sign_position'),
        ]) !!}
        {!! FormField::text('sign_name_right', [
            'value' => Setting::for($book)->get('sign_name_right'),
            'label' => __('report.sign_name'),
        ]) !!}
    </div>
</div>

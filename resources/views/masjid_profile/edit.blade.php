@extends('layouts.settings')

@section('title', __('masjid_profile.edit'))

@section('content_settings')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="page-header"><h1 class="page-title">@yield('title')</h1></div>
        <div class="row">
            <div class="col-md-6">
                {{ Form::open(['route' => 'masjid_profile.update', 'method' => 'patch']) }}
                    <div class="card">
                        <div class="card-body">
                            {!! FormField::text('masjid_name', ['required' => true, 'value' => old('masjid_name', Setting::get('masjid_name', config('masjid.name'))), 'label' => __('masjid_profile.name')]) !!}
                            {!! FormField::textarea('masjid_address', ['required' => true, 'value' => old('masjid_address', Setting::get('masjid_address')), 'label' => __('masjid_profile.address')]) !!}
                            {!! FormField::text('masjid_city_name', [
                                'required' => true,
                                'value' => old('masjid_city_name', Setting::get('masjid_city_name')),
                                'label' => __('masjid_profile.city_name'),
                            ]) !!}
                            {!! FormField::text('masjid_google_maps_link', ['value' => old('masjid_google_maps_link', Setting::get('masjid_google_maps_link')), 'label' => __('masjid_profile.google_maps_link')]) !!}
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">{{ __('app.social_media') }}</div>
                        <div class="card-body">
                            {!! FormField::text('masjid_whatsapp_number', ['value' => old('masjid_whatsapp_number', Setting::get('masjid_whatsapp_number')), 'label' => 'Whatsapp', 'addon' => ['before' => 'https://wa.me/']]) !!}
                            {!! FormField::text('masjid_instagram_username', ['value' => old('masjid_instagram_username', Setting::get('masjid_instagram_username')), 'label' => 'Instagram', 'addon' => ['before' => 'https://instagram.com/']]) !!}
                            {!! FormField::text('masjid_youtube_username', ['value' => old('masjid_youtube_username', Setting::get('masjid_youtube_username')), 'label' => 'Youtube', 'addon' => ['before' => 'https://youtube.com/']]) !!}
                            {!! FormField::text('masjid_facebook_username', ['value' => old('masjid_facebook_username', Setting::get('masjid_facebook_username')), 'label' => 'Facebook', 'addon' => ['before' => 'https://facebook.com/']]) !!}
                            {!! FormField::text('masjid_telegram_username', ['value' => old('masjid_telegram_username', Setting::get('masjid_telegram_username')), 'label' => 'Telegram', 'addon' => ['before' => 'https://t.me/']]) !!}
                        </div>
                        <div class="card-footer">
                            {{ Form::submit(__('masjid_profile.update'), ['class' => 'btn btn-success']) }}
                            {{ link_to_route('masjid_profile.show', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <label>{{ __('masjid_profile.masjid_logo') }}</label>
                        <div class="form-group" id="masjid-logo">
                            @if (Setting::get('masjid_logo_path'))
                                <img id="masjid_logo_image_show" class="img-fluid" src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" alt="{{ Setting::get('masjid_name') ?? 'buku masjid'}}">
                            @endif
                        </div>
                        @php
                            $labelText = __('masjid_profile.upload_logo');
                            if (Setting::get('masjid_logo_path')) {
                                $labelText = __('masjid_profile.change_logo');
                            }
                        @endphp
                        <label for="masjid_logo_image" class="btn btn-secondary">{{ $labelText }}</label>
                        {!! FormField::file('masjid_logo_image', [
                            'label' => false,
                            'id' => 'masjid_logo_image',
                            'class' => 'd-none',
                            'info' => ['text' => __('masjid_profile.logo_rule')]
                        ]) !!}
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <label>{{ __('masjid_profile.masjid_photo') }}</label>
                        <div class="form-group" id="masjid-photo">
                            @if (Setting::get('masjid_photo_path'))
                                <img id="masjid_photo_image_show" class="img-fluid" src="{{ Storage::url(Setting::get('masjid_photo_path'))}}" alt="{{ Setting::get('masjid_name') ?? 'buku masjid'}}">
                            @endif
                        </div>
                        @php
                            $labelText = __('masjid_profile.upload_photo');
                            if (Setting::get('masjid_photo_path')) {
                                $labelText = __('masjid_profile.change_photo');
                            }
                        @endphp
                        <label for="masjid_photo_image" class="btn btn-secondary">{{ $labelText }}</label>
                        {!! FormField::file('masjid_photo_image', [
                            'label' => false,
                            'id' => 'masjid_photo_image',
                            'class' => 'd-none',
                            'info' => ['text' => __('masjid_profile.photo_rule')]
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-masjid-logo" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modalMasjidLogo" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalMasjidLogo">{{ __('masjid_profile.masjid_logo') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"></span>
          </button>
        </div>
        <div class="modal-body">
          <div class="img-container">
              <div class="row justify-content-center text-center">
                  <div class="col-md-8 justify-content-center text-center">
                      <img id="logo-image" src="" alt="{{ Setting::get('masjid_name', config('masjid.name')) }}">
                  </div>
                  <div class="col-md-4 justify-content-center text-center">
                      <div class="preview"></div>
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('app.cancel')}}</button>
            <button type="button" class="btn btn-primary" id="crop_logo">{{__('app.crop_and_save')}}</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modal-masjid-photo" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modalMasjidLogo" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalMasjidLogo">{{ __('masjid_profile.masjid_photo') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"></span>
          </button>
        </div>
        <div class="modal-body">
          <div class="img-container">
              <div class="row justify-content-center text-center">
                  <div class="col-md-8 justify-content-center text-center">
                      <img id="photo-image" src="" alt="{{ Setting::get('masjid_name', config('masjid.name')) }}">
                  </div>
                  <div class="col-md-4 justify-content-center text-center">
                      <div class="preview"></div>
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('app.cancel')}}</button>
            <button type="button" class="btn btn-primary" id="crop_photo">{{__('app.crop_and_save')}}</button>
        </div>
      </div>
    </div>
</div>
@endsection

@section('styles')
    {{ Html::style(url('https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.min.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.min.js')) }}
    {{ Html::script(url('js/plugins/noty.js')) }}
    <script>
        var $modalLogo = $('#modal-masjid-logo');
        var imageLogo = document.getElementById('logo-image');
        var cropper;

        $(document).on("change", "#masjid_logo_image", function(e){
            var files = e.target.files;
            var done = function (url) {
                imageLogo.src = url;
                $modalLogo.modal('show');
            };
            var reader;
            var file;
            var url;
            if (files && files.length > 0) {
                file = files[0];
                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
        $modalLogo.on('shown.bs.modal', function () {
            cropper = new Cropper(imageLogo, {
                aspectRatio: 1,
                viewMode: 2,
                preview: '.preview'
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });
        $("#crop_logo").click(function(){
            canvas = cropper.getCroppedCanvas({
                width: 200,
                height: 200,
            });
            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{ route('api.masjid_profile.upload_logo')}}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            '_token': $('meta[name="_token"]').attr('content'),
                            'image': base64data
                        },
                        success: function(data){
                            var status = 'error';
                            if (data.image) {
                                if ($('#masjid_logo_image_show').length) {
                                    $('#masjid_logo_image_show').attr('src', data.image);
                                } else {
                                    $('#masjid-logo').append(`<img id="masjid_logo_image_show" class="img-fluid mt-2" src="${data.image}">`);
                                }
                                status = 'success';
                            }

                            noty({
                                type: status,
                                layout: 'bottomRight',
                                text: data.message,
                                timeout: 3000
                            });

                            $modalLogo.modal('hide');
                        },
                        error : function(data){
                            var status = 'error';
                            var errorMessage = data.responseJSON.message;
                            noty({
                                type: status,
                                layout: 'bottomRight',
                                text: errorMessage,
                                timeout: false
                            });
                        }
                    });
                }
            });
        });
    </script>
    <script>
        var $modalPhoto = $('#modal-masjid-photo');
        var imagePhoto = document.getElementById('photo-image');
        var cropper;

        $(document).on("change", "#masjid_photo_image", function(e){
            var files = e.target.files;
            var done = function (url) {
                imagePhoto.src = url;
                $modalPhoto.modal('show');
            };
            var reader;
            var file;
            var url;
            if (files && files.length > 0) {
                file = files[0];
                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
        $modalPhoto.on('shown.bs.modal', function () {
            cropper = new Cropper(imagePhoto, {
                aspectRatio: 16 / 9,
                viewMode: 2,
                preview: '.preview'
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });
        $("#crop_photo").click(function(){
            canvas = cropper.getCroppedCanvas({
                width: 960,
                height: 640,
            });
            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{ route('api.masjid_profile.upload_photo')}}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            '_token': $('meta[name="_token"]').attr('content'),
                            'image': base64data
                        },
                        success: function(data){
                            var status = 'error';
                            if (data.image) {
                                if ($('#masjid_photo_image_show').length) {
                                    $('#masjid_photo_image_show').attr('src', data.image);
                                } else {
                                    $('#masjid-photo').append(`<img id="masjid_photo_image_show" class="img-fluid mt-2" src="${data.image}">`);
                                }
                                status = 'success';
                            }

                            noty({
                                type: status,
                                layout: 'bottomRight',
                                text: data.message,
                                timeout: 3000
                            });

                            $modalPhoto.modal('hide');
                        },
                        error : function(data){
                            var status = 'error';
                            var errorMessage = data.responseJSON.message;
                            noty({
                                type: status,
                                layout: 'bottomRight',
                                text: errorMessage,
                                timeout: false
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush

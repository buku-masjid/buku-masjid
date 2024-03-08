@extends('layouts.settings')

@section('title', __('masjid_profile.edit'))

@section('content_settings')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="page-header"><h1 class="page-title">@yield('title')</h1></div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    {{ Form::open(['route' => 'masjid_profile.update', 'method' => 'patch']) }}
                        <div class="card-body">
                            {!! FormField::text('masjid_name', ['required' => true, 'value' => old('masjid_name', Setting::get('masjid_name', config('masjid.name'))), 'label' => __('masjid_profile.name')]) !!}
                            {!! FormField::textarea('masjid_address', ['required' => true, 'value' => old('masjid_address', Setting::get('masjid_address')), 'label' => __('masjid_profile.address')]) !!}
                            {!! FormField::text('masjid_google_maps_link', ['value' => old('masjid_google_maps_link', Setting::get('masjid_google_maps_link')), 'label' => __('masjid_profile.google_maps_link')]) !!}
                        </div>
                        <div class="card-footer">
                            {{ Form::submit(__('masjid_profile.update'), ['class' => 'btn btn-success']) }}
                            {{ link_to_route('masjid_profile.show', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
                        </div>
                    {{ Form::close() }}
                </div>
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
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-masjid" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">{{ __('masjid_profile.masjid_logo') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"></span>
          </button>
        </div>
        <div class="modal-body">
          <div class="img-container">
              <div class="row justify-content-center text-center">
                  <div class="col-md-8 justify-content-center text-center">
                      <img id="image" src="" alt="{{ Setting::get('masjid_name', config('masjid.name')) }}">
                  </div>
                  <div class="col-md-4 justify-content-center text-center">
                      <div class="preview"></div>
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('app.cancel')}}</button>
            <button type="button" class="btn btn-primary" id="crop">{{__('app.crop_and_save')}}</button>
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
        var $modal = $('#modal-masjid');
        var image = document.getElementById('image');
        var cropper;

        $(document).on("change", "#masjid_logo_image", function(e){
            var files = e.target.files;
            var done = function (url) {
                image.src = url;
                $modal.modal('show');
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
        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 2,
                preview: '.preview'
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });
        $("#crop").click(function(){
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
                        url: "{{ route('api.masjid_profile.image')}}",
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

                            $modal.modal('hide');
                        }
                    });
                }
            });
        });
    </script>
@endpush

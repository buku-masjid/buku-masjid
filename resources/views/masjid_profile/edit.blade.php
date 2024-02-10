@extends('layouts.settings')

@section('title', __('masjid_profile.edit'))

@section('content_settings')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="page-header"><h1 class="page-title">@yield('title')</h1></div>
        <div class="card">
            {{ Form::open(['route' => 'masjid_profile.update', 'method' => 'patch']) }}
                <div class="card-body">
                    {!! FormField::text('masjid_name', ['required' => true, 'value' => old('masjid_name', Setting::get('masjid_name')), 'label' => __('masjid_profile.name')]) !!}
                    {!! FormField::textarea('masjid_address', ['required' => true, 'value' => old('masjid_address', Setting::get('masjid_address')), 'label' => __('masjid_profile.address')]) !!}
                    {!! FormField::text('masjid_google_maps_link', ['value' => old('masjid_google_maps_link', Setting::get('masjid_google_maps_link')), 'label' => __('masjid_profile.google_maps_link')]) !!}
                    <div class="form-group">
                        <label class="form-label" for="masjid_profile_image">Logo Masjid</label>
                        <div class="custom-file">
                          <input type="file" class="custom-file-input image" id="masjid_profile_image" name="masjid_profile_image">
                          <label class="custom-file-label" for="masjid_profile_image">Choose file</label>
                        </div>
                        @if (Setting::get('masjid_image'))
                            <img id="masjid_profile_image_show" class="img-fluid mt-2" src="{{ asset(Setting::get('masjid_image'))}}" alt="{{ Setting::get('masjid_name') ?? 'buku masjid'}}">
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    {{ Form::submit(__('masjid_profile.update'), ['class' => 'btn btn-success']) }}
                    {{ link_to_route('masjid_profile.show', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<div class="modal fade" id="modal-masjid" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Laravel 10 Image Croper Upload Example - Laravelia </h5>
          <button type="button" class="close" data-dismiss="modal-masjid" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="img-container">
              <div class="row">
                  <div class="col-md-8">
                      <img id="image" src="" alt="{{ Setting::get('masjid_name') ?? 'buku masjid'}}">
                  </div>
                  <div class="col-md-4">
                      <div class="preview"></div>
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="crop">Crop</button>
        </div>
      </div>
    </div>
</div>  
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
<script>
var $modal = $('#modal-masjid');
var image = document.getElementById('image');
var cropper;

$(document).on("change", "#masjid_profile_image", function(e){
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
        viewMode: 3,
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
                url: "{{ route('masjid_profile.image')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {'_token': $('meta[name="_token"]').attr('content'), 'image': base64data},
                success: function(data){
                  $modal.modal('hide');
                  if(!alert(data.message)) {
                    console.log(data.image);
                    if (data.image) {
                        $('#masjid_profile_image_show').attr('src', data.image);
                    }
                  }
                }
            });
        }
    });
});
</script>
@endpush
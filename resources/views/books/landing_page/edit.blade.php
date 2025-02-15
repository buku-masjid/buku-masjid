@extends('layouts.settings')

@section('title', __('book.edit'))

@section('content_settings')
<div class="page-header">
    <h1 class="page-title">{{ $book->name }}</h1>
    <div class="page-subtitle">{{ __('book.landing_page') }}</div>
    <div class="page-options d-flex">
        {{ link_to_route('books.show', __('app.cancel'), [$book], ['class' => 'btn btn-secondary float-right']) }}
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            {{ Form::model($book, ['route' => ['books.landing_page.update', $book], 'method' => 'patch']) }}
            <div class="card-body">
                <h4 class="text-primary">{{ __('book.landing_page') }}</h4>
                {!! FormField::text('due_date', ['label' => __('book.due_date'), 'class' => 'date-select']) !!}
                {!! FormField::textarea('landing_page_content', ['label' => __('book.landing_page_content')]) !!}
            </div>
            <div class="card-footer">
                {{ Form::submit(__('app.save'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('books.show', __('app.cancel'), [$book], ['class' => 'btn btn-link']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <label>{{ __('book.poster_image') }}</label>
                <div class="form-group" id="book-poster">
                    @if (Setting::for($book)->get('poster_image_path'))
                        <img id="book_poster_image_show" class="img-fluid" src="{{ Storage::url(Setting::for($book)->get('poster_image_path'))}}" alt="{{ Setting::get('masjid_name') ?? 'buku masjid'}}">
                    @endif
                </div>
                @php
                    $labelText = __('book.upload_poster');
                    if (Setting::for($book)->get('poster_image_path')) {
                        $labelText = __('book.change_poster');
                    }
                @endphp
                <label for="book_poster_image" class="btn btn-secondary">{{ $labelText }}</label>
                {!! FormField::file('book_poster_image', [
                    'label' => false,
                    'id' => 'book_poster_image',
                    'class' => 'd-none',
                    'info' => ['text' => __('book.poster_rule')]
                ]) !!}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-book-poster" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modalBookPoster" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalBookPoster">{{ __('book.poster_image') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"></span>
          </button>
        </div>
        <div class="modal-body">
          <div class="img-container">
              <div class="row justify-content-center text-center">
                  <div class="col-md-8 justify-content-center text-center">
                      <img id="poster-image" src="" alt="{{ $book->name }}">
                  </div>
                  <div class="col-md-4 justify-content-center text-center">
                      <div class="preview"></div>
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('app.cancel')}}</button>
            <button type="button" class="btn btn-primary" id="crop_poster">{{__('app.crop_and_save')}}</button>
        </div>
      </div>
    </div>
</div>
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
    {{ Html::style(url('https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.min.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
    {{ Html::script(url('https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.min.js')) }}
    {{ Html::script(url('js/plugins/noty.js')) }}
<script>
(function () {
    $('.date-select').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1,
        scrollMonth: false,
    });
})();
</script>
<script>
    var $modalPoster = $('#modal-book-poster');
    var imagePoster = document.getElementById('poster-image');
    var cropper;

    $(document).on("change", "#book_poster_image", function(e){
        var files = e.target.files;
        var done = function (url) {
            imagePoster.src = url;
            $modalPoster.modal('show');
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
    $modalPoster.on('shown.bs.modal', function () {
        cropper = new Cropper(imagePoster, {
            aspectRatio: 2 / 1,
            viewMode: 2,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });
    $("#crop_poster").click(function(){
        canvas = cropper.getCroppedCanvas({
            width: 960,
            height: 480,
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
                    url: "{{ route('api.books.upload_poster_image', $book)}}",
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
                            if ($('#book_poster_image_show').length) {
                                $('#book_poster_image_show').attr('src', data.image);
                            } else {
                                $('#book-poster').append(`<img id="book_poster_image_show" class="img-fluid mt-2" src="${data.image}">`);
                            }
                            status = 'success';
                        }

                        noty({
                            type: status,
                            layout: 'bottomRight',
                            text: data.message,
                            timeout: 3000
                        });

                        $modalPoster.modal('hide');
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

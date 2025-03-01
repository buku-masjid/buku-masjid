@extends('layouts.settings')

@section('title', __('book.edit'))

@section('content_settings')
<div class="row justify-content-center">
    @if (request('action') == 'delete' && $book)
    <div class="col-md-6">
        @can('delete', $book)
            <div class="page-header">
                <h1 class="page-title">{{ $book->name }}</h1>
                <div class="page-subtitle">{{ __('book.delete') }}</div>
                <div class="page-options d-flex"></div>
            </div>
            <div class="card">
                <div class="card-header">{{ __('book.delete') }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label text-primary">{{ __('book.name') }}</label>
                            <p>{{ $book->name }}</p>
                            <label class="control-label text-primary">{{ __('book.description') }}</label>
                            <p>{{ $book->description }}</p>
                            <label class="control-label text-primary">{{ __('bank_account.bank_account') }}</label>
                            <p>{{ optional($book->bankAccount)->name }}</p>
                            <label class="control-label text-primary">{{ __('book.budget') }}</label>
                            <p>{{ $book->budget }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label text-primary">{{ __('book.report_visibility') }}</label>
                            <p>{{ __('book.report_visibility_'.$book->report_visibility_code) }}</p>
                            <label class="control-label text-primary">{{ __('report.periode') }}</label>
                            <p>{{ __('report.'.$book->report_periode_code) }}</p>
                            <label class="control-label text-primary">{{ __('report.start_week_day') }}</label>
                            <p>{{ __('time.days.'.$book->start_week_day_code) }}</p>
                        </div>
                    </div>
                    {!! $errors->first('book_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="card-body bg-warning">
                    <div class="row">
                        <div class="col-1"><i class="fe fe-alert-circle"></i></div>
                        <div class="col-11">{!! __('book.delete_confirm') !!}</div>
                    </div>
                </div>
                <div class="card-footer">
                    {!! FormField::delete(
                        ['route' => ['books.destroy', $book], 'onsubmit' => __('app.delete_confirm')],
                        __('app.delete_confirm_button'),
                        ['class' => 'btn btn-danger'],
                        ['book_id' => $book->id]
                    ) !!}
                    {{ link_to_route('books.edit', __('app.cancel'), [$book], ['class' => 'btn btn-link']) }}
                </div>
            </div>
        @endcan
    </div>
    @else
    <div class="col-md-12">
        <div class="page-header">
            <h1 class="page-title">{{ $book->name }}</h1>
            <div class="page-subtitle">{{ __('book.edit') }}</div>
            <div class="page-options d-flex">
                {{ link_to_route('books.show', __('book.back_to_show'), [$book], ['class' => 'btn btn-secondary']) }}
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">@include('books._edit_nav_tabs')</div>
            <div class="col-md-10">
                <div class="card">
                    {{ Form::model($book, ['route' => ['books.update', $book], 'method' => 'patch']) }}
                    <div class="card-body">
                        @includeWhen(request('tab') == null, 'books._edit_book_settings')
                        @includeWhen(request('tab') == 'signatures', 'books._edit_book_signatures')
                        @includeWhen(request('tab') == 'landing_page', 'books._edit_book_landing_page')
                    </div>
                    <div class="card-footer">
                        {{ Form::submit(__('book.update'), ['class' => 'btn btn-success']) }}
                        {{ link_to_route('books.show', __('app.cancel'), [$book], ['class' => 'btn btn-link']) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
    {{ Html::style(url('https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.min.css')) }}
    {{ Html::style(url('https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
    {{ Html::script(url('https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.min.js')) }}
    {{ Html::script(url('js/plugins/noty.js')) }}
    {{ Html::script(url('https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js')) }}
<script>
(function () {
    $('#landing_page_content').summernote({
        tabsize: 2,
        height: 300
    });
    $('.date-select').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1,
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
<script>
    var $modalThumbnail = $('#modal-book-thumbnail');
    var imageThumbnail = document.getElementById('thumbnail-image');
    var cropper;

    $(document).on("change", "#book_thumbnail_image", function(e){
        var files = e.target.files;
        var done = function (url) {
            imageThumbnail.src = url;
            $modalThumbnail.modal('show');
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
    $modalThumbnail.on('shown.bs.modal', function () {
        cropper = new Cropper(imageThumbnail, {
            aspectRatio: 1,
            viewMode: 2,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });
    $("#crop_thumbnail").click(function(){
        canvas = cropper.getCroppedCanvas({
            width: 320,
            height: 320,
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
                    url: "{{ route('api.books.upload_thumbnail_image', $book)}}",
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
                            if ($('#book_thumbnail_image_show').length) {
                                $('#book_thumbnail_image_show').attr('src', data.image);
                            } else {
                                $('#book-thumbnail').append(`<img id="book_thumbnail_image_show" class="img-fluid mt-2" src="${data.image}">`);
                            }
                            status = 'success';
                        }

                        noty({
                            type: status,
                            layout: 'bottomRight',
                            text: data.message,
                            timeout: 3000
                        });

                        $modalThumbnail.modal('hide');
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

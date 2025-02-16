<h4 class="text-primary">{{ __('book.landing_page') }}</h4>
{!! FormField::text('due_date', ['value' => Setting::for($book)->get('due_date'), 'label' => __('book.due_date'), 'class' => 'date-select']) !!}
{!! FormField::textarea('landing_page_content', ['value' => Purify::clean(Setting::for($book)->get('landing_page_content')), 'label' => __('book.landing_page_content'), 'rows' => 20]) !!}
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="text-center">
            <h4 class="text-primary">{{ __('book.poster_image') }}</h4>
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
    <div class="col-md-4">
        <h4 class="text-primary">{{ __('book.thumbnail_image') }}</h4>
        <div class="form-group" id="book-thumbnail">
            @if (Setting::for($book)->get('thumbnail_image_path'))
                <img id="book_thumbnail_image_show" class="img-fluid" src="{{ Storage::url(Setting::for($book)->get('thumbnail_image_path'))}}" alt="{{ Setting::get('masjid_name') ?? 'buku masjid'}}">
            @endif
        </div>
        @php
            $labelText = __('book.upload_thumbnail');
            if (Setting::for($book)->get('thumbnail_image_path')) {
                $labelText = __('book.change_thumbnail');
            }
        @endphp
        <label for="book_thumbnail_image" class="btn btn-secondary">{{ $labelText }}</label>
        {!! FormField::file('book_thumbnail_image', [
            'label' => false,
            'id' => 'book_thumbnail_image',
            'class' => 'd-none',
            'info' => ['text' => __('book.thumbnail_rule')]
        ]) !!}
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

<div class="modal fade" id="modal-book-thumbnail" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modalBookPoster" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalBookPoster">{{ __('book.thumbnail_image') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"></span>
          </button>
        </div>
        <div class="modal-body">
          <div class="img-container">
              <div class="row justify-content-center text-center">
                  <div class="col-md-8 justify-content-center text-center">
                      <img id="thumbnail-image" src="" alt="{{ $book->name }}">
                  </div>
                  <div class="col-md-4 justify-content-center text-center">
                      <div class="preview"></div>
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('app.cancel')}}</button>
            <button type="button" class="btn btn-primary" id="crop_thumbnail">{{__('app.crop_and_save')}}</button>
        </div>
      </div>
    </div>
</div>

@extends('layouts.settings')

@section('title', __('bank_account.bank_account'))

@section('content_settings')

<div class="page-header">
    <h1 class="page-title">{{ $bankAccount->name }}</h1>
    <div class="page-subtitle">{{ __('bank_account.bank_account') }}</div>
    <div class="page-options d-flex">
        @can('update', $bankAccount)
            {{ link_to_route('bank_accounts.show', __('bank_account_balance.create'), [$bankAccount, 'action' => 'create_bank_account_balance'], ['id' => 'create-bank_account_balance', 'class' => 'btn btn-success mr-2']) }}
        @endcan
        {{ link_to_route('bank_accounts.index', __('bank_account.back_to_index'), [], ['class' => 'btn btn-secondary']) }}
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <tr>
                    <td class="col-xs-2 text-center">{{ __('bank_account.name') }}</td>
                    <td class="col-xs-2 text-center">{{ __('bank_account.number') }}</td>
                    <td class="col-xs-2 text-center">{{ __('bank_account.account_name') }}</td>
                    <td class="col-xs-2 text-center">{{ __('app.status') }}</td>
                </tr>
                <tr>
                    <td class="text-center lead" style="border-top: none;">{{ $bankAccount->name }}</td>
                    <td class="text-center lead" style="border-top: none;">{{ $bankAccount->number }}</td>
                    <td class="text-center lead" style="border-top: none;">{{ $bankAccount->account_name }}</td>
                    <td class="text-center lead" style="border-top: none;">{{ $bankAccount->status }}</td>
                </tr>
            </table>
        </div>

        @if ($bankAccount->description)
            <div class="alert alert-info"><strong>{{ __('app.description') }}:</strong><br>{{ $bankAccount->description }}</div>
        @endif

        <div class="page-header">
            <h2 class="page-title">{{ __('bank_account_balance.bank_account_balance') }}</h2>
        </div>

        <div class="card table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th class="text-nowrap">{{ __('bank_account_balance.date') }}</th>
                        <th class="text-nowrap text-right">{{ __('transaction.amount') }}</th>
                        <th class="">{{ __('app.description') }}</th>
                        <th class="">{{ __('app.created_by') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bankAccountBalances as $key => $bankAccountBalance)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-nowrap">{{ $bankAccountBalance->date }}</td>
                        <td class="text-nowrap text-right">{{ $bankAccountBalance->amount_string }}</td>
                        <td class="">{{ $bankAccountBalance->description }}</td>
                        <td class="">{{ $bankAccountBalance->creator->name }}</td>
                        <td class="text-center text-nowrap">
                            @can('update', $bankAccount)
                                {{ link_to_route(
                                    'bank_accounts.show',
                                    __('app.edit'),
                                    [$bankAccount, 'action' => 'edit_bank_account_balance', 'bank_account_balance_id' => $bankAccountBalance->id],
                                    [
                                        'id' => 'edit-bank_account_balance-'.$bankAccountBalance->id,
                                        'class' => 'btn btn-sm btn-warning',
                                    ]
                                ) }}
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">{{ __('bank_account_balance.empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="form-group" id="bank_account_qris">
                    @if (Setting::for($bankAccount)->get('qris_image_path'))
                        <img id="bank_account_qris_image_show" class="img-fluid" src="{{ Storage::url(Setting::for($bankAccount)->get('qris_image_path'))}}" alt="QRIS">
                    @endif
                </div>
                @can('update', $bankAccount)
                    @php
                        $labelText = __('bank_account.qris_upload_image');
                        if (Setting::for($bankAccount)->get('qris_image_path')) {
                            $labelText = __('bank_account.qris_change_image');
                        }
                    @endphp
                    <label for="bank_account_qris_image" class="btn btn-secondary">{{ $labelText }}</label>
                    {!! FormField::file('bank_account_qris_image', [
                        'label' => false,
                        'id' => 'bank_account_qris_image',
                        'class' => 'd-none',
                    ]) !!}
                @endcan
            </div>
        </div>
    </div>
</div>

@includeWhen(request('action'), 'bank_accounts._bank_account_balance_forms')

<div class="modal fade" id="modal-masjid" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">{{ __('bank_account.qris') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"></span>
          </button>
        </div>
        <div class="modal-body">
          <div class="img-container">
              <div class="row justify-content-center text-center">
                  <div class="col-md-8 justify-content-center text-center">
                      <img id="image" src="" alt="QRIS">
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
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
    {{ Html::style(url('https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.min.css')) }}
@endsection

@push('scripts')
{{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
{{ Html::script(url('https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.min.js')) }}
{{ Html::script(url('js/plugins/noty.js')) }}
{{ Html::script(url('js/plugins/number-format.js')) }}
<script>
(function () {
    var $modal = $('#modal-masjid');
    var image = document.getElementById('image');
    var cropper;

    $(document).on("change", "#bank_account_qris_image", function(e){
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
            viewMode: 2,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });
    $("#crop").click(function(){
        canvas = cropper.getCroppedCanvas();
        canvas.toBlob(function(blob) {
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                var base64data = reader.result;
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ route('api.bank_account.qris_image', $bankAccount) }}",
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
                            if ($('#bank_account_qris_image_show').length) {
                                $('#bank_account_qris_image_show').attr('src', data.image);
                            } else {
                                $('#bank_account_qris').append(`<img id="bank_account_qris_image_show" class="img-fluid mt-2" src="${data.image}">`);
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
    $('#bankAccountBalanceModal').modal({
        show: true,
        backdrop: 'static',
    });
    $('.date-select').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1
    });
    initNumberFormatter('#amount', {
        thousandSeparator: '{{ config('money.thousands_separator') }}',
        decimalSeparator: '{{ config('money.decimal_separator') }}'
    });
})();
</script>
@endpush

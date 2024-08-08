@extends('layouts.guest')

@section('title', __('app.donate'))

@section('content')

<div class="text-center mt-0 mb-2">
    <h1 class="page-title">{{ __('app.donate') }} {{ Setting::get('masjid_name', config('masjid.name')) }}</h1>
</div>

<div class="row justify-content-center">
    @forelse ($bankAccounts as $bankAccount)
        <div class="col-md-6 pb-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $bankAccount->name }}</h3>
                </div>
                <div class="card-body">
                    <p><span class="text-primary">{{ __('bank_account.number') }}</span>:<br><strong>{{ $bankAccount->number }}</strong></p>
                    <p><span class="text-primary">{{ __('bank_account.account_name') }}</span>:<br><strong>{{ $bankAccount->account_name }}</strong></p>
                </div>
                @if ($bankAccount->description)
                    <div class="card-body bg-green-lightest">{{ $bankAccount->description }}</div>
                @endif
            </div>
        </div>
        @if (Setting::for($bankAccount)->get('qris_image_path'))
            <div class="col-md-6 pb-4">
                <a href="{{ Storage::url(Setting::for($bankAccount)->get('qris_image_path'))}}">
                    <img id="bank_account_qris_image_show" class="img-fluid" src="{{ Storage::url(Setting::for($bankAccount)->get('qris_image_path'))}}" alt="QRIS">
                </a>
            </div>
        @endif
    @empty
        {{ __('bank_account.empty') }}
    @endforelse
</div>
@endsection

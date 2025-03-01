@extends('layouts.guest')

@section('title', __('lecturing.list'))

@section('content')
<section class="bg-white">
    <div class="container-md">
        <div class="row section-hero pb-0 d-lg-flex align-items-stretch">
            <div class="col-lg-6">
                @include('layouts.public._masjid_info')
            </div>

            <div class="col-lg-6 d-lg-flex justify-content-end align-items-end">
                @include('public_schedules._shalat_time')
            </div>
        </div>
    </div>
</section>

<section class="bg-white">
    <div class="container-md position-relative">
           <div class="btn-toolbar d-flex justify-content-center row pt-4" style="position: relative; top: 20px; z-index: 10" role="toolbar">
            <div class="btn-group col-auto" role="group">
                <a href="{{ route('public_schedules.this_week', Request::all()) }}" class="btn bm-btn py-2 {{ Route::is('public_schedules.this_week') ? 'bm-btn-primary' : 'btn-light bm-bg-primary-soft' }}">
                    {{ __('time.this_week') }}
                </a>
                <a href="{{ route('public_schedules.next_week', Request::all()) }}" class="btn border bm-btn py-2 {{ Route::is('public_schedules.next_week') ? 'bm-btn-primary' : 'btn-light bm-bg-primary-soft' }}">
                    {{ __('time.next_week') }}
                </a>
            </div>
        </div>
    </div>
</section>

<div class="section-bottom pb-5">
    <div class="container-md p-3 py-lg-0">
        @php
            $fridayAudienceCode = App\Models\Lecturing::AUDIENCE_FRIDAY;
        @endphp
        @if (isset($lecturings[$fridayAudienceCode]))
            <div class="pt-4 pt-lg-5">
                <div class="row ">
                    <div class="col-lg ps-sm-0">
                        @foreach($lecturings[$fridayAudienceCode] as $lecturing)
                            @include('public_schedules._single_'.$fridayAudienceCode)
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="timeline_area pt-4 pt-lg-5">
            <div class="d-lg-flex justify-content-between pb-3">
                <h2 class="fw-bolder mb-3 ">{{ __('lecturing.lecturing') }}</h2>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="apland-timeline-area">
                        @foreach ($audienceCodes as $audienceCode => $audience)
                            @if ($audienceCode != App\Models\Lecturing::AUDIENCE_FRIDAY)
                                @if (isset($lecturings[$audienceCode]))
                                    <div class="single-timeline-area border-bottom py-4">
                                        <div class="d-none d-lg-flex timeline-date wow fadeInLeft" data-wow-delay="0.1s" >
                                            <p>{{ __('lecturing.audience_'.$audienceCode) }}</p>
                                        </div>
                                        <div class="d-block d-lg-none h3">
                                            <p>{{ __('lecturing.audience_'.$audienceCode) }}</p>
                                        </div>
                                        <div class="row">
                                            @foreach($lecturings[$audienceCode] as $lecturing)
                                                @include('public_schedules._single_'.$audienceCode)
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    @if ($audienceCode == App\Models\Lecturing::AUDIENCE_TARAWIH)
                                        @continue
                                    @endif
                                    <div class="container-xl my-4 card bg-light">
                                        <div class="empty">
                                            <p class="empty-title">{{ __('lecturing.audience_'.$audienceCode) }}</p>
                                            <p class="empty-subtitle text-secondary">
                                                {{ __('lecturing.not_found', ['audience' => __('lecturing.audience_'.$audienceCode)]) }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

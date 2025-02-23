@extends('layouts.guest')

@section('title', __('lecturing.list'))

@section('content')
<section class="bg-white">
    <div class="container-md">
        <div class="row section-hero pb-0 d-lg-flex align-items-stretch">
            <div class="col">
                @include('layouts._public_masjid_info')
            </div>

            <div class="d-none d-lg-flex col-7 justify-content-end align-items-end">
                @include('public_schedules._shalat_time')
            </div>
        </div>
    </div>
</section>

<section class="bg-white">
    <div class="container-md position-relative">
        <!-- FILTER -->
           <div class="btn-toolbar d-flex justify-content-center row pt-4" style="position: relative; top: 20px" role="toolbar">
            <div class="btn-group col-auto" role="group" aria-label="Third group">
                <a href="{{ route('public_schedules.this_week', Request::all()) }}">
                    <button type="button" class="btn btn-light border bm-btn py-2">{{ __('time.this_week') }}</button>
                </a>
            </div>
            <div class="btn-group col-auto" role="group" aria-label="Third group">
                <a href="{{ route('public_schedules.next_week', Request::all()) }}">
                    <button type="button" class="btn btn-light border bm-btn py-2">{{ __('time.next_week') }}</button>
                </a>
            </div>
        </div>
    </div>
</section>
<div class="section-bottom pb-5">
    <div class="container-md p-3 py-lg-0">
        <div class="pt-4 pt-lg-5">
            <!-- JUMAT -->
            <div class="row ">
                <div class="col-lg ps-sm-0">
                @foreach ($audienceCodes as $audienceCode => $audience)
                    @if ($audienceCode == App\Models\Lecturing::AUDIENCE_FRIDAY)
                        @if (!isset($lecturings[$audienceCode]))
                            @foreach($lecturings[$audienceCode] as $lecturing)
                                @if ($lecturing->audience_code == App\Models\Lecturing::AUDIENCE_FRIDAY )
                                    @include('public_schedules._single_'.$audienceCode)
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endforeach
                </div>
            </div>
        </div>

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
                                        <div class="row">
                                            @foreach($lecturings[$audienceCode] as $lecturing)
                                                @if ($lecturing->audience_code != App\Models\Lecturing::AUDIENCE_FRIDAY )
                                                    @include('public_schedules._single_'.$audienceCode)
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="container-xl my-4 card bg-light">
                                        <div class="empty">
                                            <p class="empty-title">Kajian {{ __('lecturing.audience_'.$audienceCode) }}</p>
                                            <p class="empty-subtitle text-secondary">
                                                {{ __('lecturing.not_found', ['audience' => __('lecturing.audience_'.$audienceCode)]) }}.
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


@if ($hasGroupedTransactions)
    @if ($isForInternal)
    @else
        @php
            $key = 0;
        @endphp
        @foreach ($transactions as $transaction)
            <div class="row py-2 fs-4 p-2">
                <div class="col-auto d-sm-none d-flex align-items-center">
                    
                    @if ($typeTransactions == 1)
                        <i class="d-sm-none ti icon fe-bold bm-txt-primary">&#xea13;</i>
                    @else
                        <i class="d-sm-none ti icon fe-bold bm-txt-out">&#xea24;</i>
                    @endif
                    <!--<span class="d-none d-sm-block">{{ ++$key }}</span> -->
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col col-cat">
                            <span class="date pe-3 d-block d-sm-inline">{{ $transaction->date }}</span>
                            @if ($isTransactionFilesVisible)
                                <span class="float-end">
                                    @livewire('public-books.files-indicator', ['transaction' => $transaction])
                                </span>
                            @endif
                            <span class="fw-bold">{!! $transaction->date_alert !!} {!! nl2br(htmlentities($transaction->description)) !!}</span>
                        </div>
                        @if ($typeTransactions == 1)
                            <div class="col-lg-2 col-cat col-num bm-txt-primary fw-bold text-start text-sm-end">
                                 {{ format_number($transaction->amount) }}
                            </div>
                            <div class="col-lg-2 col-cat col-num bm-txt-primary fw-bold d-none d-sm-block text-start text-sm-end">
                                -
                            </div>
                        @else
                            <div class="col-lg-2 col-cat col-num bm-txt-out d-none d-sm-block fw-bold text-start text-sm-end">
                                 -
                            </div>
                            <div class="col-lg-2 col-cat col-num bm-txt-out fw-bold text-start text-sm-end">
                                {{ format_number($transaction->amount) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    <!-- <tfoot>
        <tr class="strong">
            <td colspan="3" class="text-end">{{ __('app.total') }} {{ $categoryName }}</td>
            <td class="text-end">
                {{ format_number($transactions->sum('amount')) }}
            </td>
        </tr>
    </tfoot> -->
@endif


<!-- <table class="table table-sm table-hover mb-0">
    <thead>
        <tr >
            <th style="width: 5%" class="text-center ">{{ __('app.table_no') }}</th>
            <th style="width: 15%" class="text-center ">{{ __('time.date') }}</th>
            <th style="width: 60%">{{ __('app.description') }}</th>
            <th style="width: 20%" class="text-nowrap text-end ">{{ __('transaction.amount') }}</th>
        </tr>
    </thead>
    @if ($hasGroupedTransactions)
    @if ($isForInternal)
    @else
        <tbody>
            @php
                $key = 0;
            @endphp
            @foreach ($transactions as $transaction)
            <tr>
                <td class="text-center col-1">{{ ++$key }}</td>
                <td class="text-center col-2">{{ $transaction->date }}</td>
                <td class="col-4">
                    @if ($isTransactionFilesVisible)
                        <span class="float-end">
                            @livewire('public-books.files-indicator', ['transaction' => $transaction])
                        </span>
                    @endif
                    {!! $transaction->date_alert !!} {!! nl2br(htmlentities($transaction->description)) !!}
                </td>
                <td class="text-end col-3">{{ format_number($transaction->amount) }}</td>
            </tr>
            @endforeach
        </tbody>
    @endif
    <tfoot>
        <tr class="strong">
            <td colspan="3" class="text-end">{{ __('app.total') }} {{ $categoryName }}</td>
            <td class="text-end">
                {{ format_number($transactions->sum('amount')) }}
            </td>
        </tr>
    </tfoot>
    @endif
</table> -->

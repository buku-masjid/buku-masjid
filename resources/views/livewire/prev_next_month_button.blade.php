@if ($isDisabled)
    <span class="{{ $buttonClass }} disabled" aria-disabled="true">{{ $buttonText }}</span>
@else
    {{ link_to_route(
        $this->routeName,
        $this->buttonText,
        ['month' => $this->month, 'year' => $this->year] + Request::except(['year', 'month']),
        ['class' => $buttonClass]
    ) }}
@endif

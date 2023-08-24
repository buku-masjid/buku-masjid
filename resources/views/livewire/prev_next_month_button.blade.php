{{ link_to_route(
    $this->routeName,
    $this->buttonText,
    ['month' => $this->month, 'year' => $this->year] + Request::except(['year', 'month']),
    ['class' => $buttonClass]
) }}

{{ link_to_route(
    $this->routeName,
    $this->buttonText,
    ['start_date' => $this->startDate, 'end_date' => $this->endDate] + Request::except(['start_date', 'end_date']),
    ['class' => $buttonClass]
) }}

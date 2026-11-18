<?php

namespace Tests\Fakes;

use Illuminate\Http\Response;

class FakeMpdfWrapper
{
    public $renderedHtml;

    public $capturedFormat;

    public function loadView($view, $data = [], $mergeData = [], $config = [])
    {
        $this->capturedFormat = $config['format'] ?? null;
        $this->renderedHtml = view($view, $data, $mergeData)->render();

        return $this;
    }

    public function stream($filename = null)
    {
        $response = new Response;
        $response->header('Content-Type', 'application/pdf');

        return $response;
    }
}

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                {{ __('report.signatures') }}
                <div class="card-options">
                    @can('update', $book)
                        {{ link_to_route('books.edit', __('app.edit'), [$book, 'tab' => 'signatures'], ['class' => 'btn btn-sm btn-warning text-dark mr-2', 'id' => 'edit_signatures-book-'.$book->id]) }}
                    @endcan
                </div>
            </div>
            <table class="table table-sm card-table">
                <tbody>
                    <tr><th colspan="3">{{ __('app.left_part') }}</th></tr>
                    <tr>
                        <td>{{ __('report.acknowledgment_text') }}</td>
                        <td>{{ Setting::for($book)->get('acknowledgment_text_left') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('report.sign_position') }}</td>
                        <td>{{ Setting::for($book)->get('sign_position_left') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('report.sign_name') }}</td>
                        <td>{{ Setting::for($book)->get('sign_name_left') }}</td>
                    </tr>
                    <tr><th colspan="3">{{ __('app.mid_part') }}</th></tr>
                    <tr>
                        <td>{{ __('report.acknowledgment_text') }}</td>
                        <td>{{ Setting::for($book)->get('acknowledgment_text_mid') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('report.sign_position') }}</td>
                        <td>{{ Setting::for($book)->get('sign_position_mid') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('report.sign_name') }}</td>
                        <td>{{ Setting::for($book)->get('sign_name_mid') }}</td>
                    </tr>
                    <tr><th colspan="3">{{ __('app.right_part') }}</th></tr>
                    <tr>
                        <td>{{ __('report.acknowledgment_text') }}</td>
                        <td>{{ Setting::for($book)->get('acknowledgment_text_right') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('report.sign_position') }}</td>
                        <td>{{ Setting::for($book)->get('sign_position_right') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('report.sign_name') }}</td>
                        <td>{{ Setting::for($book)->get('sign_name_right') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

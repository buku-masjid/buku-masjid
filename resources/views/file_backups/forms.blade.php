@if (request('action') == 'delete' && Request::has('file_name'))
    <div class="card card-danger">
        <div class="card-header">{{ __('file_backup.delete') }}</div>
        <div class="card-body">
            <p>{!! __('file_backup.sure_to_delete_file', ['filename' => request('file_name')]) !!}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('file_backups.index') }}" class="btn btn-secondary">{{ __('file_backup.cancel_delete') }}</a>
            <form action="{{ route('file_backups.destroy', request('file_name')) }}"
                method="post"
                class="float-right"
                onsubmit="return confirm('{{ __('file_backup.delete_confirm') }}')">
                {{ method_field('delete') }}
                {{ csrf_field() }}
                <input type="hidden" name="file_name" value="{{ request('file_name') }}">
                <input type="submit" class="btn btn-danger" value="{{ __('file_backup.confirm_delete') }}">
            </form>
        </div>
    </div>
@endif
@if (request('action') == 'restore' && Request::has('file_name'))
    <div class="card card-warning">
        <div class="card-header">{{ __('file_backup.restore') }}</div>
        <div class="card-body">
            <p>{!! __('file_backup.sure_to_restore', ['filename' => request('file_name')]) !!}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('file_backups.index') }}" class="btn btn-secondary">{{ __('file_backup.cancel_restore') }}</a>
            <form action="{{ route('file_backups.restore', request('file_name')) }}"
                method="post"
                class="float-right"
                onsubmit="return confirm('Click OK to Restore.')">
                {{ csrf_field() }}
                <input type="hidden" name="file_name" value="{{ request('file_name') }}">
                <input type="submit" class="btn btn-warning" value="{{ __('file_backup.confirm_restore') }}">
            </form>
        </div>
    </div>
@endif
@if (request('action') == null)
<div class="card">
    <div class="card-body">
        <form action="{{ route('file_backups.store') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="file_name" class="control-label">{{ __('file_backup.create') }}</label>
                <input type="text" name="file_name" class="form-control" placeholder="{{ date('Y-m-d_Hi') }}">
                {!! $errors->first('file_name', '<div class="text-danger text-right">:message</div>') !!}
            </div>
            <div class="form-group">
                <input type="submit" value="{{ __('file_backup.create') }}" class="btn btn-success">
            </div>
        </form>
    </div>
</div>
@endif

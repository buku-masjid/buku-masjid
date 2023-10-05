@if (request('action') == 'delete' && Request::has('file_name'))
    <div class="card card-danger">
        <div class="card-header">{{ __('database_backup.delete') }}</div>
        <div class="card-body">
            <p>{!! __('database_backup.sure_to_delete_file', ['filename' => request('file_name')]) !!}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('database_backups.index') }}" class="btn btn-secondary">{{ __('database_backup.cancel_delete') }}</a>
            <form action="{{ route('database_backups.destroy', request('file_name')) }}"
                method="post"
                class="float-right"
                onsubmit="return confirm('{{ __('database_backup.delete_confirm') }}')">
                {{ method_field('delete') }}
                {{ csrf_field() }}
                <input type="hidden" name="file_name" value="{{ request('file_name') }}">
                <input type="submit" class="btn btn-danger" value="{{ __('database_backup.confirm_delete') }}">
            </form>
        </div>
    </div>
@endif
@if (request('action') == 'restore' && Request::has('file_name'))
    <div class="card card-warning">
        <div class="card-header">{{ __('database_backup.restore') }}</div>
        <div class="card-body">
            <p>{!! __('database_backup.sure_to_restore', ['filename' => request('file_name')]) !!}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('database_backups.index') }}" class="btn btn-secondary">{{ __('database_backup.cancel_restore') }}</a>
            <form action="{{ route('database_backups.restore', request('file_name')) }}"
                method="post"
                class="float-right"
                onsubmit="return confirm('Click OK to Restore.')">
                {{ csrf_field() }}
                <input type="hidden" name="file_name" value="{{ request('file_name') }}">
                <input type="submit" class="btn btn-warning" value="{{ __('database_backup.confirm_restore') }}">
            </form>
        </div>
    </div>
@endif
@if (request('action') == null)
<div class="card">
    <div class="card-body">
        <form action="{{ route('database_backups.store') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="file_name" class="control-label">{{ __('database_backup.create') }}</label>
                <input type="text" name="file_name" class="form-control" placeholder="{{ date('Y-m-d_Hi') }}">
                {!! $errors->first('file_name', '<div class="text-danger text-right">:message</div>') !!}
            </div>
            <div class="form-group">
                <input type="submit" value="{{ __('database_backup.create') }}" class="btn btn-success">
            </div>
        </form>
        <hr>
        <form action="{{ route('database_backups.upload') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="backup_file" class="control-label">{{ __('database_backup.upload') }}</label>
                <input type="file" name="backup_file" class="form-control">
                {!! $errors->first('backup_file', '<div class="text-danger text-right">:message</div>') !!}
            </div>
            <div class="form-group">
                <input type="submit" value="{{ __('database_backup.upload') }}" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
@endif

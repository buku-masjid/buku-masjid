@extends('layouts.settings')

@section('title', __('database_backup.index_title'))

@section('content_settings')
<div class="page-header">
    <h1 class="page-title">{{ __('database_backup.index_title') }}</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <table class="table table-sm table-responsive-sm">
                <thead>
                    <th class="text-center">{{ __('app.table_no') }}</th>
                    <th>{{ __('database_backup.file_name') }}</th>
                    <th>{{ __('database_backup.file_size') }}</th>
                    <th>{{ __('database_backup.created_at') }}</th>
                    <th class="text-center">{{ __('database_backup.actions') }}</th>
                </thead>
                <tbody>
                    @forelse($backups as $key => $backup)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $backup->getFilename() }}</td>
                        <td>{{ format_size_units($backup->getSize()) }}</td>
                        <td>{{ date('Y-m-d H:i:s', $backup->getMTime()) }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('database_backups.index', ['action' => 'restore', 'file_name' => $backup->getFilename()]) }}"
                                    id="restore_{{ str_replace('.gz', '', $backup->getFilename()) }}"
                                    class="btn btn-warning btn-sm"
                                    title="{{ __('database_backup.restore') }}"><i class="fe fe-refresh-cw"></i></a>
                                <a href="{{ route('database_backups.download', [$backup->getFilename()]) }}"
                                    id="download_{{ str_replace('.gz', '', $backup->getFilename()) }}"
                                    class="btn btn-success btn-sm"
                                    title="{{ __('database_backup.download') }}"><i class="fe fe-download"></i></a>
                                <a href="{{ route('database_backups.index', ['action' => 'delete', 'file_name' => $backup->getFilename()]) }}"
                                    id="del_{{ str_replace('.gz', '', $backup->getFilename()) }}"
                                    class="btn btn-danger btn-sm"
                                    title="{{ __('database_backup.delete') }}"><i class="fe fe-x"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">{{ __('database_backup.empty') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        @include('database_backups.forms')
    </div>
</div>
@endsection

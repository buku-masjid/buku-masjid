@extends('layouts.settings')

@section('title', __('file_backup.index_title'))

@section('content_settings')
<div class="page-header">
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">{{ __('file_backup.index_title') }}</h2>
        </div>
    </div>
</div>

<div class="page-body">
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <table class="table table-sm table-responsive-sm">
                <thead>
                    <th class="text-center">{{ __('app.table_no') }}</th>
                    <th>{{ __('file_backup.file_name') }}</th>
                    <th>{{ __('file_backup.file_size') }}</th>
                    <th>{{ __('file_backup.created_at') }}</th>
                    <th class="text-center">{{ __('file_backup.actions') }}</th>
                </thead>
                <tbody>
                    @forelse($backups as $key => $backup)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $backup['filename'] }}</td>
                        <td>{{ format_size_units($backup['size']) }}</td>
                        <td>{{ date('Y-m-d H:i:s', $backup['modified']) }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('file_backups.index', ['action' => 'restore', 'file_name' => $backup['filename']]) }}"
                                    id="restore_{{ str_replace('.zip', '', $backup['filename']) }}"
                                    class="btn btn-warning btn-sm"
                                    title="{{ __('file_backup.restore') }}"><i class="ti ti-refresh"></i></a>
                                <a href="{{ route('file_backups.download', [$backup['filename']]) }}"
                                    id="download_{{ str_replace('.zip', '', $backup['filename']) }}"
                                    class="btn btn-success btn-sm"
                                    title="{{ __('file_backup.download') }}"><i class="ti ti-download"></i></a>
                                <a href="{{ route('file_backups.index', ['action' => 'delete', 'file_name' => $backup['filename']]) }}"
                                    id="del_{{ str_replace('.zip', '', $backup['filename']) }}"
                                    class="btn btn-danger btn-sm"
                                    title="{{ __('file_backup.delete') }}"><i class="ti ti-x"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">{{ __('file_backup.empty') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        @include('file_backups.forms')
    </div>
</div>
</div>
@endsection

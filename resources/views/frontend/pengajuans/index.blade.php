@extends('layouts.frontend')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @can('pengajuan_create')
                    <div style="margin-bottom: 10px;" class="row">
                        <div class="col-lg-12">
                            <a class="btn btn-success" href="{{ route('frontend.pengajuans.create') }}">
                                {{ trans('global.add') }} {{ trans('cruds.pengajuan.title_singular') }}
                            </a>
                        </div>
                    </div>
                @endcan
                <div class="card">
                    <div class="card-header">
                        {{ trans('cruds.pengajuan.title_singular') }} {{ trans('global.list') }}
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover datatable datatable-Pengajuan">
                                <thead>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.pengajuan.fields.id') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.pengajuan.fields.mahasiswa') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.pengajuan.fields.program') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.pengajuan.fields.semester') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.pengajuan.fields.no_hp') }}
                                        </th>
                                        <th>
                                            Verifikasi
                                        </th>
                                        <th>
                                            &nbsp;
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuans as $key => $pengajuan)
                                        <tr data-entry-id="{{ $pengajuan->id }}">
                                            <td>
                                                {{ $pengajuan->id ?? '' }}
                                            </td>
                                            <td>
                                                {{ $pengajuan->mahasiswa->nama_lengkap ?? '' }}
                                            </td>
                                            <td>
                                                {{ $pengajuan->program->nama_program ?? '' }}
                                            </td>
                                            <td>
                                                {{ $pengajuan->semester ?? '' }}
                                            </td>
                                            <td>
                                                {{ $pengajuan->no_hp ?? '' }}
                                            </td>
                                            <td>
                                                {{ $pengajuan->verif ?? '' }}
                                            </td>
                                            <td>
                                                @can('pengajuan_show')
                                                    <a class="btn btn-xs btn-primary"
                                                        href="{{ route('frontend.pengajuans.show', $pengajuan->id) }}">
                                                        {{ trans('global.view') }}
                                                    </a>
                                                @endcan

                                                @can('pengajuan_edit')
                                                    <a class="btn btn-xs btn-info"
                                                        href="{{ route('frontend.pengajuans.edit', $pengajuan->id) }}">
                                                        {{ trans('global.edit') }}
                                                    </a>
                                                @endcan

                                                @can('pengajuan_delete')
                                                    <form action="{{ route('frontend.pengajuans.destroy', $pengajuan->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                        style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="submit" class="btn btn-xs btn-danger"
                                                            value="{{ trans('global.delete') }}">
                                                    </form>
                                                @endcan

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-Pengajuan:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection

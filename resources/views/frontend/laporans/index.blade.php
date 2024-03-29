@extends('layouts.frontend')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @can('laporan_create')
                    <div style="margin-bottom: 10px;" class="row">
                        <div class="col-lg-12">
                            <a class="btn btn-success" href="{{ route('frontend.laporans.create') }}">
                                {{ trans('global.add') }} {{ trans('cruds.laporan.title_singular') }}
                            </a>
                        </div>
                    </div>
                @endcan
                <div class="card">
                    <div class="card-header">
                        {{ trans('cruds.laporan.title_singular') }} {{ trans('global.list') }}
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover datatable datatable-Laporan">
                                <thead>
                                    <tr>
                                        <th>
                                            NO
                                        </th>
                                        <th>
                                            {{ trans('cruds.laporan.fields.pengajuan') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.laporan.fields.sertifikat') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.laporan.fields.laporan') }}
                                        </th>
                                        <th>
                                            &nbsp;
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($laporans as $key => $laporan)
                                        @foreach ($laporan->laporans as $laporan)
                                            <tr data-entry-id="{{ $laporan->id }}">
                                                <td>
                                                    {{ $no++ }}
                                                </td>
                                                <td>
                                                    {{ $laporan->pengajuan->program->nama_program ?? '' }}
                                                </td>
                                                <td>
                                                    @if ($laporan->sertifikat)
                                                        <a href="{{ $laporan->sertifikat->getUrl() }}" target="_blank"
                                                            onclick="event.preventDefault(); window.open('{{ $laporan->sertifikat->getUrl() }}', '_blank');">
                                                            {{ trans('global.view_file') }}
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($laporan->laporan)
                                                        <a href="{{ $laporan->laporan->getUrl() }}" target="_blank"
                                                            onclick="event.preventDefault(); window.open('{{ $laporan->laporan->getUrl() }}', '_blank');">
                                                            {{ trans('global.view_file') }}
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @can('laporan_show')
                                                        <a class="btn btn-xs btn-primary"
                                                            href="{{ route('frontend.laporans.show', $laporan->id) }}">
                                                            {{ trans('global.view') }}
                                                        </a>
                                                    @endcan

                                                    @can('laporan_edit')
                                                        <a class="btn btn-xs btn-info"
                                                            href="{{ route('frontend.laporans.edit', $laporan->id) }}">
                                                            {{ trans('global.edit') }}
                                                        </a>
                                                    @endcan

                                                    @can('laporan_delete')
                                                        <form id="delete-form-{{ $laporan->id }}"
                                                            action="{{ route('admin.laporans.destroy', $laporan->id) }}"
                                                            method="POST" style="display: inline-block;">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <button type="button" class="btn btn-xs btn-danger"
                                                                onclick="deleteLaporan({{ $laporan->id }})">
                                                                {{ trans('global.delete') }}
                                                            </button>
                                                        </form>
                                                    @endcan

                                                </td>

                                            </tr>
                                        @endforeach
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
        function deleteLaporan(laporanId) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "data tidak akan bisa di kembalikan!",
                icon: 'warning',
                confirmButtonText: 'Iya, hapus!',
                showDenyButton: true,
                denyButtonText: `Tidak, batal!`,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // jika pengguna menekan tombol "Yes, delete it!", submit form
                    document.getElementById('delete-form-' + laporanId).submit();
                    Swal.fire('Tersimpan!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Perubahan tidak di simpan', '', 'info')
                }
            });
        }
    </script>
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
            let table = $('.datatable-Laporan:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection

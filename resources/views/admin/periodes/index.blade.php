@extends('layouts.admin')
@section('content')
    @can('periode_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.periodes.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.periode.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.periode.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Periode">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.periode.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.periode.fields.tahun_periode') }}
                            </th>
                            <th>
                                Status
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
                        @foreach ($periodes as $key => $periode)
                            <tr data-entry-id="{{ $periode->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $no++ }}
                                </td>
                                <td>
                                    {{ $periode->tahun_periode ?? '' }}
                                </td>
                                <td>
                                    {{ $periode->status ?? '' }}
                                </td>
                                <td>
                                    @can('periode_show')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.periodes.show', $periode->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('periode_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.periodes.edit', $periode->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('periode_delete')
                                        <form id="delete-form-{{ $periode->id }}"
                                            action="{{ route('admin.periodes.destroy', $periode->id) }}" method="POST"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="button" class="btn btn-xs btn-danger"
                                                onclick="deletePeriode({{ $periode->id }})">
                                                {{ trans('global.delete') }}
                                            </button>
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
@endsection
@section('scripts')
    @parent
    <script>
        function deletePeriode(periodeId) {
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
                    document.getElementById('delete-form-' + periodeId).submit();
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
            @can('periode_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.periodes.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id')
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-Periode:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection

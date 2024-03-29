<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyLaporanRequest;
use App\Http\Requests\StoreLaporanRequest;
use App\Http\Requests\UpdateLaporanRequest;
use App\Models\Laporan;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class LaporanController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('laporan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $laporans = Laporan::with(['pengajuan', 'media'])->get();

        return view('admin.laporans.index', compact('laporans'));
    }

    public function create()
    {
        abort_if(Gate::denies('laporan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pengajuans = Pengajuan::pluck('semester', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.laporans.create', compact('pengajuans'));
    }

    public function store(StoreLaporanRequest $request)
    {
        $laporan = Laporan::create($request->all());

        if ($request->input('laporan', false)) {
            $laporan->addMedia(storage_path('tmp/uploads/' . basename($request->input('laporan'))))->toMediaCollection('laporan');
        }

        if ($request->input('sertifikat', false)) {
            $laporan->addMedia(storage_path('tmp/uploads/' . basename($request->input('sertifikat'))))->toMediaCollection('sertifikat');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $laporan->id]);
        }

        return redirect()->route('admin.laporans.index')->with('message', 'Berhasil membuat laporan mbkm mahasiswa!');
    }

    public function edit(Laporan $laporan)
    {
        abort_if(Gate::denies('laporan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pengajuans = Pengajuan::pluck('semester', 'id')->prepend(trans('global.pleaseSelect'), '');

        $laporan->load('pengajuan');

        return view('admin.laporans.edit', compact('laporan', 'pengajuans'));
    }

    public function update(UpdateLaporanRequest $request, Laporan $laporan)
    {
        $laporan->update($request->all());

        if ($request->input('laporan', false)) {
            if (!$laporan->laporan || $request->input('laporan') !== $laporan->laporan->file_name) {
                if ($laporan->laporan) {
                    $laporan->laporan->delete();
                }
                $laporan->addMedia(storage_path('tmp/uploads/' . basename($request->input('laporan'))))->toMediaCollection('laporan');
            }
        } elseif ($laporan->laporan) {
            $laporan->laporan->delete();
        }

        if ($request->input('sertifikat', false)) {
            if (!$laporan->sertifikat || $request->input('sertifikat') !== $laporan->sertifikat->file_name) {
                if ($laporan->sertifikat) {
                    $laporan->sertifikat->delete();
                }
                $laporan->addMedia(storage_path('tmp/uploads/' . basename($request->input('sertifikat'))))->toMediaCollection('sertifikat');
            }
        } elseif ($laporan->sertifikat) {
            $laporan->sertifikat->delete();
        }

        return redirect()->route('admin.laporans.index')->with('message', 'Berhasil mengubah laporan mbkm mahasiswa!');
    }

    public function show(Laporan $laporan)
    {
        abort_if(Gate::denies('laporan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $laporan->load('pengajuan');

        return view('admin.laporans.show', compact('laporan'));
    }

    public function destroy(Laporan $laporan)
    {
        abort_if(Gate::denies('laporan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $laporan->delete();

        return back()->with('message', 'Berhasil menghapus laporan mbkm mahasiswa!');
    }

    public function massDestroy(MassDestroyLaporanRequest $request)
    {
        $laporans = Laporan::find(request('ids'));

        foreach ($laporans as $laporan) {
            $laporan->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('laporan_create') && Gate::denies('laporan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Laporan();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        $url = $media->getUrl();
        $headers = [
            'Content-Type' => 'application/pdf',
        ];

        return response()->json(['id' => $media->id, 'url' => $url], Response::HTTP_CREATED, $headers);
    }
}

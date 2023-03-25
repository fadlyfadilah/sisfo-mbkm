<?php

namespace App\Http\Requests;

use App\Models\Laporan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class UpdateLaporanRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('laporan_edit');
    }

    public function rules()
    {
        return [
            'pengajuan_id' => [
                'required',
                'integer',
            ],
            'laporan' => [
                'required',
            ],
            'sertifikat' => [
                'required',
            ],
        ];
    }
}
<?php

namespace App\Http\Requests;

use App\Models\Mahasiswa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class UpdateMahasiswaRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mahasiswa_edit');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'nama_lengkap' => [
                'string',
                'required',
            ],
            'nim' => [
                'string',
                'required',
            ],
            'prodi_id' => [
                'required',
                'integer',
            ],
            'jenis_kelamin' => [
                'required',
            ],
            'tanggal_lahir' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'periode_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
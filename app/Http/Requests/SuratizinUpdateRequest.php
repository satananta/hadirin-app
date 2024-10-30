<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratizinUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'keterangan_admin' => ['nullable', 'string'],
            'status' => ['required', 'in:Pending,Terima,Tolak'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}

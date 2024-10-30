<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
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
            'jam_masuk' => ['required'],
            'max_jam_masuk' => ['required'],
            'jam_keluar' => ['required'],
            'lat' => ['required', 'numeric', 'between:-99.999999,99.999999'],
            'long' => ['required', 'numeric', 'between:-999.999999,999.999999'],
            'radius' => ['required', 'integer'],
        ];
    }
}

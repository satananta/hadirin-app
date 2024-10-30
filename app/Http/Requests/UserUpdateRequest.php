<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $rules = [
            'nip' => ['required', 'string', 'max:6', 'unique:users,nip,' . $this->user->id],
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email,' . $this->user->id],
            'tanggal_lahir' => ['required', 'date'],
            'photo' => ['string'],
            'jabatan' => ['required', 'in:Manager,CEO,IT Support'],
            'level' => ['required', 'in:admin,karyawan'],
        ];

        if($this->password !== null) {
            return array_merge($rules, ['password' => ['required', 'max:150']]);
        }

        return $rules;
    }
}

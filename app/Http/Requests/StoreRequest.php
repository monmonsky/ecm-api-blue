<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'about' => 'nullable|string',
            'phone' => 'required|string|max:15',
            'address_id' => 'required',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'User',
            'name' => 'Store Name',
            'logo' => 'Store Logo',
            'about' => 'About Store',
            'phone' => 'Phone Number',
            'address_id' => 'Address ID',
            'city' => 'City',
            'address' => 'Full Address',
            'postal_code' => 'Postal Code',
        ];
    }
}

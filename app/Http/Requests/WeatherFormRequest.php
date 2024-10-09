<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WeatherFormRequest extends FormRequest
{
    /**
     * Determiner si l'utilisateur est autorisé à faire la request
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Les Régles pour appliquer la reuqest 
     *
     * @return array
     */
    public function rules()
    {
        return [
            'city' => 'required|string|max:255',
        ];
    }
}

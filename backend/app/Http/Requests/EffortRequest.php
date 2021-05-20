<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EffortRequest extends FormRequest
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
            //
            'title' => 'required|max:50',
            'content' => 'required|max:500',
            'reflection' => 'max:250',
            'enthusiasm' => 'max:250',
            'effort_time' => 'nullable|integer|max:20'
        ];
    }

    public function attributes()
    {
        return [
            //
            'title' => 'タイトル',
            'content' => '本文',
            'reflection' => '反省点',
            'enthusiasm' => '今後の意気込み',            
            'effort_time' => '取組時間'
        ];
    }
}

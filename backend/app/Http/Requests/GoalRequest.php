<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoalRequest extends FormRequest
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
            'goal_time' => 'integer|min:10',
            'deadline' => 'required|date|after:today',
        ];
    }

    public function attributes()
    {
        return [
            //
            'title' => 'タイトル',
            'content' => '本文',
            'goal_time' => '目標継続時間',
            'deadline' => '達成期限',
            'today' => '今日'
        ];
    }    
}

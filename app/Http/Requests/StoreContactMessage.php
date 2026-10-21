<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessage extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'regex:/^09\d{9}$/'],
            'email' => ['nullable', 'string', 'email', 'max:190'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    /**
     * Get the custom validation messages in Persian.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'نام و نام خانوادگی را وارد کنید.',
            'name.string' => 'نام و نام خانوادگی باید متن باشد.',
            'name.max' => 'نام و نام خانوادگی نباید بیشتر از ۱۲۰ نویسه باشد.',
            'phone.required' => 'شماره موبایل را وارد کنید.',
            'phone.string' => 'شماره موبایل باید متن باشد.',
            'phone.regex' => 'شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود.',
            'email.email' => 'نشانی ایمیل معتبر نیست.',
            'email.max' => 'نشانی ایمیل نباید بیشتر از ۱۹۰ نویسه باشد.',
            'subject.max' => 'موضوع پیام نباید بیشتر از ۱۸۰ نویسه باشد.',
            'message.required' => 'متن پیام را وارد کنید.',
            'message.string' => 'متن پیام باید متن باشد.',
            'message.min' => 'متن پیام باید دست‌کم ۱۰ نویسه باشد.',
            'message.max' => 'متن پیام نباید بیشتر از ۲۰۰۰ نویسه باشد.',
        ];
    }

    /**
     * Get the custom attribute names in Persian.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'نام و نام خانوادگی',
            'phone' => 'شماره موبایل',
            'email' => 'نشانی ایمیل',
            'subject' => 'موضوع',
            'message' => 'متن پیام',
        ];
    }
}

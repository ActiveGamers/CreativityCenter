<?php

namespace App\Http\Requests;

use App\Enums\GradeLevel;
use App\Support\PersianDigits;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalise the mobile number before the validation.
     *
     * The visitor may type the number with the Persian or the Arabic-Indic
     * digits; the stored number is always written with Latin digits.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->phone)) {
            $this->merge([
                'phone' => PersianDigits::toLatin($this->phone),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'student_name' => ['required', 'string', 'max:120'],
            'parent_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'regex:/^09\d{9}$/'],
            'email' => ['nullable', 'string', 'email', 'max:190'],
            'grade_level' => ['required', 'string', Rule::enum(GradeLevel::class)],
            'major_id' => ['nullable', 'integer', Rule::exists('majors', 'id')->where('is_active', true)],
            'note' => ['nullable', 'string', 'max:1000'],
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
            'student_name.required' => 'نام و نام خانوادگی دانش‌آموز را وارد کنید.',
            'student_name.string' => 'نام دانش‌آموز باید متن باشد.',
            'student_name.max' => 'نام دانش‌آموز نباید بیشتر از ۱۲۰ نویسه باشد.',
            'parent_name.required' => 'نام و نام خانوادگی والدین را وارد کنید.',
            'parent_name.string' => 'نام والدین باید متن باشد.',
            'parent_name.max' => 'نام والدین نباید بیشتر از ۱۲۰ نویسه باشد.',
            'phone.required' => 'شماره موبایل را وارد کنید.',
            'phone.string' => 'شماره موبایل باید متن باشد.',
            'phone.regex' => 'شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود.',
            'email.email' => 'نشانی ایمیل معتبر نیست.',
            'email.max' => 'نشانی ایمیل نباید بیشتر از ۱۹۰ نویسه باشد.',
            'grade_level.required' => 'پایه تحصیلی مورد نظر را انتخاب کنید.',
            'grade_level.string' => 'پایه تحصیلی باید متن باشد.',
            'grade_level.enum' => 'پایه تحصیلی انتخاب‌شده معتبر نیست.',
            'major_id.integer' => 'رشته انتخاب‌شده معتبر نیست.',
            'major_id.exists' => 'رشته انتخاب‌شده معتبر نیست.',
            'note.max' => 'توضیحات نباید بیشتر از ۱۰۰۰ نویسه باشد.',
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
            'student_name' => 'نام و نام خانوادگی دانش‌آموز',
            'parent_name' => 'نام و نام خانوادگی والدین',
            'phone' => 'شماره موبایل',
            'email' => 'نشانی ایمیل',
            'grade_level' => 'پایه تحصیلی',
            'major_id' => 'رشته تحصیلی',
            'note' => 'توضیحات',
        ];
    }
}

<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('web') !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'student_full_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today'],
            'school_name' => ['required', 'string', 'max:255'],
            'education_level' => ['required', 'in:SD,SMP,SMA,UTBK,UMUM'],
            'grade' => ['required', 'string', 'max:50'],
            'whatsapp' => ['required', 'string', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/'],
            'agree_terms' => ['accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'whatsapp.regex' => 'Format nomor WhatsApp tidak valid (contoh: 081234567890).',
            'agree_terms.accepted' => 'Anda wajib menyetujui syarat, ketentuan, dan kebijakan refund sebelum melanjutkan pembayaran.',
        ];
    }
}

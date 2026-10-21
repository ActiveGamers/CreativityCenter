<?php

namespace App\Actions;

use App\Enums\AdmissionStatus;
use App\Models\AdmissionRequest as AdmissionModel;

class SubmitAdmissionRequest
{
    /**
     * Store an admission request submitted through the admission form.
     *
     * @param  array<string, mixed>  $data  The validated form data.
     */
    public function handle(array $data): AdmissionModel
    {
        return AdmissionModel::query()->create([
            'student_name' => $data['student_name'],
            'parent_name' => $data['parent_name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'grade_level' => $data['grade_level'],
            'major_id' => $data['major_id'] ?? null,
            'note' => $data['note'] ?? null,
            'status' => AdmissionStatus::New,
        ]);
    }
}

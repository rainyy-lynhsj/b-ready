<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreImplementationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'teacher';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'workshop_id' => ['required', 'integer', 'exists:workshops,id'],
            'classroom_package_id' => ['required', 'integer', 'exists:classroom_packages,id'],
            'implementation_date' => ['required', 'date', 'before_or_equal:today'],
            'teacher_reflection' => ['nullable', 'string', 'max:5000'],
            'remarks' => ['nullable', 'string', 'max:2000'],
            'supporting_record' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx,xlsx', 'max:10240'],
            'students' => ['required', 'array', 'min:1'],
            'students.*.student_identifier' => ['required', 'string', 'max:255'],
            'students.*.score' => ['required', 'numeric', 'min:0', 'max:1000'],
            'students.*.total_questions' => ['nullable', 'numeric', 'min:1', 'max:1000'],
            'students.*.max_score' => ['nullable', 'numeric', 'min:1', 'max:1000'],
        ];
    }

    /**
     * Custom message definitions.
     */
    public function messages(): array
    {
        return [
            'workshop_id.required' => 'Please select the associated workshop.',
            'classroom_package_id.required' => 'A valid classroom package is required.',
            'implementation_date.required' => 'The implementation date is required.',
            'implementation_date.before_or_equal' => 'The implementation date cannot be in the future.',
            'students.required' => 'You must record at least one student result.',
            'students.min' => 'You must record at least one student result.',
            'students.*.student_identifier.required' => 'Every student must have an identifier (Name, LRN, or ID).',
            'students.*.score.required' => 'A score is required for each student.',
            'students.*.score.numeric' => 'Scores must be valid numbers.',
            'students.*.score.min' => 'Scores cannot be negative.',
            'students.*.max_score.required' => 'A total possible score is required.',
            'students.*.max_score.min' => 'Total possible score must be greater than zero.',
        ];
    }
}

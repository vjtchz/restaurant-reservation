<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return $this->user() !== null;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'date' => ['required', 'date', 'after_or_equal:today'],

      'time_from' => [
        'required',
        'date_format:H:i',
      ],

      'time_to' => [
        'required',
        'date_format:H:i',
        'after:time_from',
      ],

      'guests' => [
        'required',
        'integer',
        'min:1',
        'max:' . config('restaurant.max_guests', 10),
      ],
    ];
  }

  /**
   * Get custom validation error messages.
   *
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'time_to.after' => __('reservations.validation.time_to_after'),
    ];
  }

  /**
   * Normalize inputs before validation.
   */
  protected function prepareForValidation(): void
  {
  }
}

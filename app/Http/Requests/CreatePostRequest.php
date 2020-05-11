<?php

namespace App\Http\Requests;

use App\Exceptions\ThrottleException;
use App\Reply;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/** We will use this class as a gatekeeper for our post requests.
 * We will use it to join 2 lesser gates, of Authorization and Validation.
 */
class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', new Reply());
    }

    // Were overriding the FormRequest's failedAuthorization to throw our custom exception instead.
    // This exception is handled within Exceptions\Handler, together with ValidationException.
    protected function failedAuthorization()
    {
        throw new ThrottleException('You are posting too many replies, please take a break.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|spamfree'
        ];
    }
}

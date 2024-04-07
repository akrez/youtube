<?php

namespace App\Rules;

use App\Services\YoutubeUrlService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VideoIdRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!YoutubeUrlService::parse($value)) {
            $fail(__('validation.regex', [
                'attribute' => __('validation.attributes.v'),
            ]));
        }
    }
}

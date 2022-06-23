<?php

namespace App\Rules;

use App\Helpers\ReCaptcha3Helper;
use Illuminate\Contracts\Validation\Rule;

class Recaptcha3 implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $reCaptcha = new ReCaptcha3Helper();
        $result = $reCaptcha->recaptchaVerify($value);
        $validation = false;

        if ($result['success'] && $result['score'] > env('RECAPTCHA3_SCORE')) {
            $validation = true;
            \Log::info("[RECAPTCHA_3] => : ", [
                'score' => $result
            ]);
        }

        return $validation;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No es posible continuar con la solicitud';
    }
}

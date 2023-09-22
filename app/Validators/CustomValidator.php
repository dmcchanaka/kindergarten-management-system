<?php
namespace App\Validators;

use Illuminate\Validation\Validator;

class CustomValidator
{
    public static function validate(array $data, array $rules, array $attributes = [])
    {
        $validator = app('validator')->make($data, $rules);

        if (!empty($attributes)) {
            $validator->setAttributeNames($attributes);
        }

        return $validator;
    }
}
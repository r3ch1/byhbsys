<?php

namespace App\Actions;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseAction
{
    protected array $validationRules=[];

    protected function validateData(array $data) :void
    {
        $validator = Validator::make($data, $this->validationRules);
        if ($validator->fails()) {
            throw new ValidationException($validator, null, $validator->errors());
        }
    }
}

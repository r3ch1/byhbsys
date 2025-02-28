<?php

namespace App\Actions;

use App\Dtos\BaseDto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseAction
{
    protected array $validationRules=[];

    protected function validateData(array|BaseDto $data) :void
    {
        if ($data instanceof BaseDto) $data = $data->toArray();
        $validator = Validator::make($data, $this->validationRules);
        if ($validator->fails()) {
            throw new ValidationException($validator, null, $validator->errors());
        }
    }
}

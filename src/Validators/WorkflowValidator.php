<?php

namespace VamikaDigital\WorkflowManager\Validators;

use Illuminate\Support\Facades\Validator;
use VamikaDigital\WorkflowManager\Contracts\WorkflowValidatorContract;

class WorkflowValidator implements WorkflowValidatorContract
{
    /**
     * Validate the attributes with the given rules.
     *
     * @param array $attributes
     * @param array $rules
     * @return mixed
     */
    public function validate(array $attributes, array $rules)
    {
        $result = Validator::make($attributes, $rules);
        if ($result->fails()) {
            return $result->errors()->getMessages();
        }
        return true;
    }
}

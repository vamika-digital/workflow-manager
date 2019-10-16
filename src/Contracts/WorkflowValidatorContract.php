<?php
namespace VamikaDigital\WorkflowManager\Contracts;

interface WorkflowValidatorContract
{
    /**
     * Validate the attributes with the given rules.
     *
     * @param array $attributes
     * @param array $rules
     * @return mixed
     */
    public function validate(array $attributes, array $rules);
}

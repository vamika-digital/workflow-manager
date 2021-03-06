<?php

namespace VamikaDigital\WorkflowManager\Facades;

use Illuminate\Support\Facades\Facade;

class WorkflowManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'workflowmanager';
    }
}

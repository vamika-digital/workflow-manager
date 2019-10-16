<?php
namespace VamikaDigital\WorkflowManager\Contracts;

use VamikaDigital\WorkflowManager\Events\WorkflowTransitionEvents;

interface WorkflowCallbackContract
{
  public function handle(WorkflowTransitionEvents $event);
}

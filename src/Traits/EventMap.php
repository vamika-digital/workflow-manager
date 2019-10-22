<?php
namespace VamikaDigital\WorkflowManager\Traits;

use VamikaDigital\WorkflowManager\Events\WorkflowEvents;
use VamikaDigital\WorkflowManager\Listeners\WorkflowApproverManager;
use VamikaDigital\WorkflowManager\Listeners\WorkflowHistoryManager;
use VamikaDigital\WorkflowManager\Listeners\WorkflowNotificationManager;

trait EventMap
{
    /**
     * All of the Workflow event / listener mappings.
     *
     * @var array
     */
    protected $events = [
        WorkflowEvents::POST_TRANSITION => [
            WorkflowApproverManager::class,
            WorkflowHistoryManager::class,
        ],
        WorkflowEvents::PRE_TRANSITION => [
        ],
        WorkflowEvents::CAN_TRANSITION => [
        ]
    ];
}

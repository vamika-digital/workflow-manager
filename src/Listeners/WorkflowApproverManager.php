<?php
namespace VamikaDigital\WorkflowManager\Listeners;

use VamikaDigital\WorkflowManager\Events\WorkflowTransitionEvents;

class WorkflowApproverManager
{
    /**
     * Handle the event.
     *
     * @param WorkflowTransitionEvents $event
     * @return void
     */
    public function handle(WorkflowTransitionEvents $event)
    {
        $roles = [];
        if (! isset($event->getConfig()['approver_roles']) || count($event->getConfig()['approver_roles']) <= 0) {
          $roles = [];
        } else {
            $roles = $event->getConfig()['approver_roles'];
        }
        $sm = $event->getStateMachine();
        $model = $sm->getObject();
        $model->attachApprovers([
            'transition' => $event->getTransition(),
            'role_name' => $event->getRolename(),
            'from' => $event->getStage(),
            'from_text' => $event->getStageName(),
            'approver_roles' => $roles,
        ]);
    }
}

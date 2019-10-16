<?php
namespace VamikaDigital\WorkflowManager\Listeners;

use VamikaDigital\WorkflowManager\Events\WorkflowTransitionEvents;

class WorkflowHistoryManager
{
    /**
     * Handle the event.
     *
     * @param WorkflowTransitionEvents $event
     * @return void
     */
    public function handle(WorkflowTransitionEvents $event)
    {
        $sm = $event->getStateMachine();
        $model = $sm->getObject();
        $model->addHistoryLine([
            'transition' => $event->getTransition(),
            'role_name' => $event->getRolename(),
            'from' => $event->getStage(),
            'from_text' => $event->getStageName()
        ]);
    }
}

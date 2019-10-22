<?php

namespace VamikaDigital\WorkflowManager\Traits;

use Illuminate\Support\Facades\Log;
use VamikaDigital\WorkflowManager\Models\WorkflowHistory;
use VamikaDigital\WorkflowManager\WorkflowManager;

trait Workflowable
{
    /**
     * StateMachine.
     */
    protected $workflowInstance;
    /**
     * Transition Other Data.
     */
    protected $otherData;

    /**
     * Create a singleton StateMachine instance form the specified config.
     *
     * @return WorkflowManager
     * @throws \Exception
     */
    public function workflowInstance()
    {
        if (! $this->workflowInstance) {
            $this->workflowInstance = new WorkflowManager($this, $this->getWorkflowStages());
        }
        return $this->workflowInstance;
    }

    public function otherData() {
        return $this->otherData;
    }

    /**
     * Return the actual state of
     * the object.
     *
     * @return mixed
     * @throws \Exception
     */
    public function currentStageIs()
    {
        return $this->workflowInstance()->getCurrentStage();
    }

    /**
     * Return the name of the state.
     *
     * @param $state
     * @return mixed
     * @throws \Exception
     */
    public function getStageName($state)
    {
        if (! isset($this->workflowInstance()->getConfiguration()['stages'][$state]['text'])) {
            return $state;
        }
        return $this->workflowInstance()->getConfiguration()['stages'][$state]['text'];
    }

    /**
     * Return the actual stage name.
     *
     * @return mixed
     */
    public function getCurrentStageName()
    {
        return $this->workflowInstance()->getConfiguration()['stages'][$this->workflowInstance()->getCurrentStage()]['text'];
    }

    /**
     * Check the transition is possible or not.
     *
     * @return array
     * @throws \Exception
     */
    public function nextTransitions()
    {
        return $this->workflowInstance()->getPossibleTransitions();
    }

    /**
     * Check the transition is possible or not.
     *
     * @param $transition
     * @param $rolename
     * @return mixed
     * @throws \Exception
     */
    public function transitionAllowed($transition, $rolename)
    {
        return $this->workflowInstance()->can($transition, $rolename);
    }

    /**
     * Apply the specified transition.
     *
     * @param $transition
     * @param $rolename
     * @return mixed
     * @throws \Exception
     */
    public function transition($transition, $rolename, $otherData = null)
    {
        $this->otherData = $otherData;
        return $this->workflowInstance()->apply($transition, $rolename);
    }

    /**
     * Return the transition history of the model.
     *
     * @return mixed
     */
    public function histories()
    {
        return $this->hasMany(WorkflowHistory::class, 'model_id', 'id');
    }

    /**
     * Return the approvers of the model.
     *
     * @return mixed
     */
    public function approvers()
    {
        return $this->morphToMany('App\User', 'approvable', 'workflow_approvers', 'approvable_id', 'approver_id');
    }

    /**
     * Add a history line to the table with the model name and record id.
     *
     * @param array $transitionData
     * @return mixed
     */
    public function addHistoryLine(array $transitionData)
    {
        $transitionData['user_id'] = auth()->id();
        $transitionData['model_name'] = get_class();
        return $this->histories()->create($transitionData);
    }

    /**
     * Add approvers for workflow actions.
     *
     * @param array $approverData
     * @return mixed
     */
    public function attachApprovers(array $approverData)
    {
        $approverData['user_id'] = auth()->id();
        $approverData['model_name'] = get_class();
    }
}
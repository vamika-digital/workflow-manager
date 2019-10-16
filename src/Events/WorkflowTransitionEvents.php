<?php

namespace VamikaDigital\WorkflowManager\Events;

use VamikaDigital\WorkflowManager\Contracts\WorkflowContract;

class WorkflowTransitionEvents
{
    /**
     * @var string
     */
    protected $transition;
    
    /**
     * @var string
     */
    protected $fromStage;

    /**
     * @var string
     */
    protected $rolename;
    
    /**
     * @var array
     */
    protected $configuration;
    
    /**
     * @var
     */
    protected $workflow;
    
    /**
     * @var bool
     */
    protected $rejected = false;
    
    /**
     * @param string $transition Name of the transition being applied
     * @param string $fromStage Stage from which the transition is applied
     * @param array $configuration Configuration of the transition
     * @param WorkflowContract $workflow
     */
    public function __construct($transition, $fromStage, array $configuration, WorkflowContract $workflow)
    {
        $this->transition = $transition;
        $this->fromStage = $fromStage;
        $this->rolename = $configuration['rolename'];
        $this->configuration = $configuration;
        $this->workflow = $workflow;
        return $this;
    }
    /**
     * @return string
     */
    public function getTransition()
    {
        return $this->transition;
    }
    /**
     * @return string
     */
    public function getRolename()
    {
        return $this->rolename;
    }
    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->configuration;
    }
    /**
     * @return WorkflowContract
     */
    public function getStateMachine()
    {
        return $this->workflow;
    }
    /**
     * @return string
     */
    public function getStage()
    {
        return $this->fromStage;
    }
    /**
     * @return string
     */
    public function getStageName()
    {
        return $this->getStateMachine()->getConfiguration()['stages'][$this->getStage()]['text'];
    }
    /**
     * @param bool $reject
     */
    public function setRejected($reject = true)
    {
        $this->rejected = (bool) $reject;
    }
    /**
     * @return bool
     */
    public function isRejected()
    {
        return $this->rejected;
    }
    /**
     * @return array
     */
    public function convertToArray()
    {
        return [
            $this->transition,
            $this->getStage(),
            $this->getStageName(),
            $this->configuration,
            $this->workflow
        ];
    }
}
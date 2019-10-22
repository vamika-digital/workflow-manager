<?php

namespace VamikaDigital\WorkflowManager;

use Illuminate\Support\Facades\Log;
use VamikaDigital\WorkflowManager\Contracts\WorkflowCallbackContract;
use VamikaDigital\WorkflowManager\Contracts\WorkflowContract;
use VamikaDigital\WorkflowManager\Contracts\WorkflowValidatorContract;
use VamikaDigital\WorkflowManager\Events\WorkflowEvents;
use VamikaDigital\WorkflowManager\Events\WorkflowTransitionEvents;
use VamikaDigital\WorkflowManager\Exceptions\WorkflowException;
use VamikaDigital\WorkflowManager\Exceptions\WorkflowValidatorException;
use VamikaDigital\WorkflowManager\Validators\WorkflowValidator;

class WorkflowManager implements WorkflowContract
{
    /**
     * @var
     */
    protected $object;
    
    /**
     * Configuration array.
     */
    protected $configuration;
    
    /**
     * @var
     */
    protected $callbackFactory;
    
    /**
     * @var array
     */
    protected $validatorErrors = [];
    
    /**
     * WorkflowManager constructor.
     * @param $object
     * @param $configuration
     */
    public function __construct($object, $configuration)
    {
        $this->object = $object;
        isset($configuration['property_path']) ?: $configuration['property_path'] = 'stage';
        isset($configuration['property_path_name']) ?: $configuration['property_path_name'] = 'stage_name';
        $this->configuration = $configuration;
        $this->getCurrentStage();
    }

    /**
     * Can the transition be applied on the underlying object.
     *
     * @param string $transition
     * @param string $rolename
     *
     * @return bool
     * @throws WorkflowException
     */
    public function can($transition, $rolename)
    {
        if (! isset($this->configuration['transitions'][$this->getCurrentStage()])) {
            throw new WorkflowException(__('workflow::exception.missing_transition', ['transition' => $transition, 'stage' => $this->getCurrentStage()]));
        }
        Log::info($this->getCurrentStage() . " $transition");
        if (!isset($this->configuration['transitions'][$this->getCurrentStage()][$transition]) && empty($rolename)) {
            return false;
        }
        $nextStages = collect($this->configuration['transitions'][$this->getCurrentStage()][$transition]);
        if ($nextStages->whereIn('rolename', [$rolename, '*'])->count() === 0) {
            return false;
        }
        event(WorkflowEvents::CAN_TRANSITION, $this);
        return true;
    }

    /**
     * Applies the transition on the underlying object.
     *
     * @param string $transition Transition to apply
     * @param string $rolename Role by applied
     *
     * @return void If the transition has been applied or not (in case of soft apply or rejected pre transition event)
     * @throws WorkflowException
     */
    public function apply($transition, $rolename)
    {
        if ($this->can($transition, $rolename)) {
            $nextStages = collect($this->configuration['transitions'][$this->getCurrentStage()][$transition]);
            $nextStageConfigs = $nextStages->whereIn('rolename', [$rolename, '*'])
                ->filter(function ($configs, $key) {
                    if (!isset($configs['validations']) || count($configs['validations']) <= 0) {
                        return true;
                    } else {
                        $validations = $configs['validations'];
                        return $this->object->isValidWorkflowValidation($validations);
                    }
                })
                ->map(function ($value, $key) use ($rolename) {
                    $value['stage_key'] = $key;
                    $value['rolename'] = $rolename;
                    return $value;
                })->first();
            tap($this->setWorkflowEvent($transition, $nextStageConfigs), function ($event) use ($nextStageConfigs) {
                $this->firePreEvents($event)
                    ->updateCurrentStage($nextStageConfigs)
                    ->firePostEvents($event);
            });
        }
    }

    /**
     * Returns the current stage.
     *
     * @return string
     */
    public function getCurrentStage()
    {
        return $this->object->getAttribute($this->configuration['property_path']);
    }

    /**
     * Returns the underlying object.
     *
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }
    /**
     * Return the current configuration object.
     *
     * @return mixed
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Return the possible transactions which are available from the
     * current stage.
     *
     * @return array
     */
    public function getPossibleTransitions()
    {
        $stage = $this->getCurrentStage();
        return collect($this->configuration['transitions'][$stage])->keys()->toArray();
    }

    /**
     * Set a new stage to the underlying object.
     *
     * @param string $stage
     *
     * @return WorkflowManager
     * @throws SMException
     * @throws WorkflowException
     */
    protected function updateCurrentStage($nextStageConfigs)
    {
        $stage = $nextStageConfigs['stage_key'];
        if (! array_key_exists($stage, $this->configuration['stages'])) {
            throw new WorkflowException(__('workflow::exception.missing_stage', ['stage' => $stage]));
        }
        $stageName = $this->configuration['stages'][$stage]['text'];
        $this->object->setAttribute($this->configuration['property_path'], $stage);
        $this->object->setAttribute($this->configuration['property_path_name'], $stageName);
        return $this;
    }

    /**
     * Fire all of the pre events before the status has been changed.
     *
     * @param $event
     * @return WorkflowManager
     */
    protected function firePreEvents($event)
    {
        event(WorkflowEvents::PRE_TRANSITION, $event);
        if (! $this->callValidators($event)) {
            throw WorkflowValidatorException::withMessages($this->validatorErrors);
        }
        $this->callCallbacks($event, 'pre');
        return $this;
    }

    /**
     * @param $event
     * @return WorkflowManager
     */
    protected function firePostEvents($event)
    {
        event(WorkflowEvents::POST_TRANSITION, $event);
        $this->callCallbacks($event, 'post');
        return $this;
    }
    /**
     * @param $event
     * @param $position
     * @return bool
     */
    protected function callCallbacks($event, $position)
    {
        if (! isset($event->getConfig()['callbacks'][$position]) || count($event->getConfig()['callbacks'][$position]) <= 0) {
            return true;
        }
        foreach ($event->getConfig()['callbacks'][$position] as $key => &$callback) {
            if ((! class_exists($callback)) && (! $callback instanceof WorkflowCallbackContract)) {
                report(new WorkflowException(__('workflow::exception.missing_callback', ['callback' => $callback])));
                continue;
            }
            $app = new $callback();
            $app->handle($event);
        }
    }
    /**
     * @param $event
     * @return bool
     */
    protected function callValidators($event)
    {
        if (! isset($event->getConfig()['validators']) || count($event->getConfig()['validators']) <= 0) {
            return true;
        }
        foreach ($event->getConfig()['validators'] as $key => $rules) {
            $class = is_numeric($key) ? WorkflowValidator::class : $key;
            if ((! class_exists($class)) && (! $class instanceof WorkflowValidatorContract)) {
                array_push($this->validatorErrors, [[__('workflow::validation.missing_validator_class', ['class' => $class])]]);
                continue;
            }
            $app = new $class();
            $result = $app->validate($event->getStateMachine()->getObject()->getAttributes(), $rules);
            if ($result !== true) {
                array_push($this->validatorErrors, $result);
            }
        }
        return empty($this->validatorErrors);
    }

    /**
     * @param $transition
     * @return WorkflowTransitionEvents
     */
    protected function setWorkflowEvent($transition, $nextStageConfigs)
    {
        return new WorkflowTransitionEvents($transition, $this->getCurrentStage(), $nextStageConfigs, $this);
    }
}
<?php
namespace VamikaDigital\WorkflowManager\Contracts;

interface WorkflowContract
{
  /**
   * Can the transition be applied on the underlying object.
   *
   * @param string $transition
   * @param string $rolename
   *
   * @return bool
   */
  public function can($transition, $rolename);
  
  /**
   * Applies the transition on the underlying object.
   *
   * @param string $transition Transition to apply
   * @param string $rolename
   *
   * @return bool If the transition has been applied or not (in case of soft apply or rejected pre transition event)
   */
  public function apply($transition, $rolename);
  
  /**
   * Returns the current stage.
   *
   * @return string
   */
  public function getCurrentStage();
  
  /**
   * Returns the underlying object.
   *
   * @return object
   */
  public function getObject();
  
  /**
   * Return the current configuration object.
   *
   * @return mixed
   */
  public function getConfiguration();
  
  /**
   * Returns the possible transitions.
   *
   * @return array
   */
  public function getPossibleTransitions();
}
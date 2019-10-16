<?php

namespace VamikaDigital\WorkflowManager\Events;

abstract class WorkflowEvents
{
    const PRE_TRANSITION = 'vamikadigital.workflow.pre_transition';

    const POST_TRANSITION = 'vamikadigital.workflow.post_transition';

    const CAN_TRANSITION = 'vamikadigital.workflow.can_transition';
}

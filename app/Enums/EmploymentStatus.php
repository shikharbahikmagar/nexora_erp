<?php

namespace App\Enums;

enum EmploymentStatus: string
{
    case ACTIVE = 'active';
    case PROBATION = 'probation';
    case ON_LEAVE = 'on_leave';
    case SUSPENDED = 'suspended';
    case NOTICE_PERIOD = 'notice_period';
    case TERMINATED = 'terminated';
    case RESIGNED = 'resigned';
    case RETIRED = 'retired';
}

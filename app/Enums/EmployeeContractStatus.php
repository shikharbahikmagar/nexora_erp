<?php

namespace App\Enums;

enum EmployeeContractStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case TERMINATED = 'terminated';
}

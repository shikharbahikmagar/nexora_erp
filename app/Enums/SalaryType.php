<?php

namespace App\Enums;

enum SalaryType: string
{
    case HOURLY = 'hourly';
    case DAILY = 'daily';
    case MONTHLY = 'monthly';
    case ANNUAL = 'annual';
}

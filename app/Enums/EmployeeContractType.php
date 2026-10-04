<?php

namespace App\Enums;

enum EmployeeContractType: string
{
    case PERMANENT = 'permanent';
    case TEMPORARY = 'temporary';
    case INTERNSHIP = 'internship';
    case FREELANCE = 'freelance';
    case PART_TIME = 'part_time';
    case CONTRACT = 'contract';
}

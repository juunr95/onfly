<?php

namespace App\Enums;

enum OrderStatuses: string
{
    case REQUESTED = 'requested';
    case APPROVED = 'approved';
    case CANCELLED = 'cancelled';
}

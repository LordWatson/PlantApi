<?php

namespace App\Enums;

enum EventEnum: string
{
        case Created = 'Created';
        case Updated = 'Updated';
        case Deleted = 'Deleted';
}

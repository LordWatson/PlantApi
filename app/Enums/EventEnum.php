<?php

namespace App\Enums;

enum EventEnum: string
{
        case Created = 'Created';
        case Read = 'Read';
        case Updated = 'Updated';
        case Deleted = 'Deleted';
}

<?php

namespace App\Enums;

enum ResourceType: string
{
    case ROOM = 'room';
    case EQUIPMENT = 'equipment';
    case CAR = 'car';
    case HOUSE = 'house';
}

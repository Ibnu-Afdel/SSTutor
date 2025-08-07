<?php

namespace App\Enums\Course;

enum DiscountType: string
{
    case PERCENT = 'percent';
    case AMOUNT = 'amount';
}

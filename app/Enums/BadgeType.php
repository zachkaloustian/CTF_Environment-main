<?php

namespace App\Enums;

enum BadgeType: string
{
    case FIRST_SOLVE = 'first_solve';
    case SOLVE_COUNT = 'solve_count';
    case CATEGORY_COMPLETE = 'category_complete';
    case DIFFICULTY_COMPLETE = 'difficulty_complete';
    case STREAK = 'streak';
}
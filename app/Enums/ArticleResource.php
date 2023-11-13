<?php

namespace App\Enums;

enum ArticleResource: string
{
    case NEWSAPI = 'newsapi';
    case GUARDIAN = 'guardian';
    case NEW_YORK_TIMES = 'new_york_times';
}

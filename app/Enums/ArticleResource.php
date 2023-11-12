<?php

namespace App\Enums;

enum ArticleResource: string
{
    case NEWSAPI = 'newsapi';
    case GUARDIAN = 'guardian';
}

<?php

namespace App\Enums;

enum State: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            State::Draft => __('Draft'),
            State::Published => __('Published'),
            State::Archived => __('Archived'),
        };
    }

    public function actionLabel(): string
    {
        return match ($this) {
            State::Draft => __('Revert to Draft'),
            State::Published => __('Publish'),
            State::Archived => __('Archive'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            State::Draft => 'gray',
            State::Published => 'green',
            State::Archived => 'red',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            State::Draft => 'eye',
            State::Published => 'check-circle',
            State::Archived => 'archive-box',
        };
    }
}

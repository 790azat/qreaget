<?php

namespace App\Support;

/** Russian labels for the admin panel. */
class Labels
{
    public static function all(): array
    {
        return [
            'statusLabels' => ['new' => 'Новая', 'review' => 'На рассмотрении', 'agreed' => 'Согласована', 'in_progress' => 'В работе', 'done' => 'Завершена', 'rejected' => 'Отклонена'],
            'priorityLabels' => ['low' => 'Низкий', 'normal' => 'Обычный', 'high' => 'Высокий'],
            'typeLabels' => ['individual' => 'Частное лицо', 'lawyer' => 'Адвокат', 'company' => 'Компания', 'importer' => 'Импортёр/брокер', 'court' => 'Суд/следствие', 'other' => 'Другое'],
        ];
    }
}

<?php
return [
        'translations' => [
            'app*' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/language',
                'sourceLanguage' => 'zh-EN',
                'fileMap' => [
                    'app' => 'app.php',
                    'app/error' => 'error.php',
                ],
            	'on missingTranslation' => ['app\msg\events\TranslationEventHandler', 'handleMissingTranslation'],
            ],
        ],
    ];
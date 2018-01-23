<?php

namespace app\language\events;

use yii\i18n\MissingTranslationEvent;

class TranslationEventHandler
{
	public static function handleMissingTranslation(MissingTranslationEvent $event)
	{
		$event->translatedMessage = "@MISSING: {$event->category}.{$event->message} FOR LANGUAGE {$event->language},{$event->data} @";
	}
}
<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class BrowserLocaleSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $preferredLanguage = $request->getPreferredLanguage();

        if ($preferredLanguage) {
            $request->setLocale($preferredLanguage);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            // The custom listener must be called before LocaleListener
            'kernel.request' => ['onKernelRequest', 200],
        ];
    }
}

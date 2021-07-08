<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class SessionLocaleSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->hasPreviousSession()) {
            return;
        }
        $sessionLocale = $request->getSession()->get('locale');

        if ($sessionLocale) {
            $request->setLocale($sessionLocale);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            // The custom listener must be called before LocaleListener
            'kernel.request' => ['onKernelRequest', 110],
        ];
    }
}

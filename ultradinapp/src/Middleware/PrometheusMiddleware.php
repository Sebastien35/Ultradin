<?php

namespace App\Middleware;

use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

class PrometheusMiddleware implements EventSubscriberInterface
{
    private $registry;
    private $histogram;

    public function __construct()
    {
        $this->registry = new CollectorRegistry(new InMemory());

        $this->histogram = $this->registry->getOrRegisterHistogram(
            'http_requests',
            'duration_seconds',
            'Request duration in seconds',
            ['method', 'route']
        );
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $event->getRequest()->attributes->set('start_time', microtime(true));
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $request = $event->getRequest();
        $start = $request->attributes->get('start_time');

        if ($start) {
            $duration = microtime(true) - $start;
            $this->histogram->observe($duration, [$request->getMethod(), $request->getPathInfo()]);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onKernelRequest',
            ResponseEvent::class => 'onKernelResponse',
        ];
    }
}

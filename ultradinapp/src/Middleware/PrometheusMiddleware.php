<?php

namespace App\Middleware;

use Prometheus\CollectorRegistry;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PrometheusMiddleware implements EventSubscriberInterface
{
    private $registry;
    private $requestCounter;
    private $responseDuration;

    public function __construct(CollectorRegistry $registry)
    {
        $this->registry = $registry;

        // Using getOrRegister methods to prevent issues with multiple registrations
        $this->requestCounter = $this->registry->getOrRegisterCounter(
            'http',  // Namespace (ensure it's correct)
            'http_requests_total',
            'Total number of HTTP requests by method',
            ['method']  // Labels (GET, POST, etc.)
        );

        $this->responseDuration = $this->registry->getOrRegisterHistogram(
            'http',  // Namespace
            'http_response_duration_seconds',
            'Histogram of HTTP request durations in seconds',
            ['method'],  // Labels
            [0.1, 0.2, 0.5, 1, 2, 5]  // Buckets
        );
    }

    public function onKernelRequest(RequestEvent $event)
    {
        // Save start time to calculate response duration
        $event->getRequest()->attributes->set('start_time', microtime(true));

        // Register or get the 'http_requests_total' counter, with the 'method' label
        $counter = $this->registry->getOrRegisterCounter(
            'http', // Namespace
            'http_requests_total', // Metric name
            'Counts HTTP requests by method', // Help text
            ['method'] // Labels (you can add more labels if necessary)
        );

        // Increment the counter with the HTTP method as the label value (GET, POST, etc.)
        $counter->inc(['method' => $event->getRequest()->getMethod()]);
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $request = $event->getRequest();
        $startTime = $request->attributes->get('start_time');

        if ($startTime) {
            // Calculate response duration in seconds
            $duration = microtime(true) - $startTime;
            // Log response duration for debugging
            error_log("Response duration: $duration seconds");

            // Observe the duration in the histogram
            $this->responseDuration->observe($duration, [$request->getMethod()]);
        }

        // Log when request counter is incremented
        error_log("Incrementing request counter for method: " . $request->getMethod());
        // Increment the request counter by method (GET, POST, etc.)
        $this->requestCounter->inc([$request->getMethod()]);
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onKernelRequest',
            ResponseEvent::class => 'onKernelResponse',
        ];
    }
}

<?php

namespace App\Controller;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MetricsController
{
    #[Route('/metrics', name: 'metrics')]
    public function metrics()
    {
        $registry = new CollectorRegistry(new \Prometheus\Storage\InMemory());
        $renderer = new RenderTextFormat();

        return new Response($renderer->render($registry->getMetricFamilySamples()), Response::HTTP_OK, ['Content-Type' => RenderTextFormat::MIME_TYPE]);
    }
}

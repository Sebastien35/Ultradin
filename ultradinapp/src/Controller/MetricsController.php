<?php

namespace App\Controller;

use Prometheus\RenderTextFormat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Prometheus\CollectorRegistry;
use Symfony\Component\Routing\Attribute\Route;

class MetricsController extends AbstractController
{
    private $registry;

    public function __construct(CollectorRegistry $registry)
    {
        $this->registry = $registry;
    }

    #[Route('/metrics', name: 'metrics')]
    public function metrics(): Response
    {
        $renderer = new RenderTextFormat();
        
        // Get the registered metrics
        $metrics = $this->registry->getMetricFamilySamples();
    

        // Render the metrics to response
        $response = $renderer->render($metrics);

        return new Response($response, 200, ['Content-Type' => RenderTextFormat::MIME_TYPE]);
    }
}

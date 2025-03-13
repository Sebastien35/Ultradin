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
        
        // Log the metric family samples to ensure that they are there
        foreach ($metrics as $metric) {
            error_log("Metric Name: " . $metric->getName());
            foreach ($metric->getSamples() as $sample) {
                error_log("Sample Name: " . $sample->getName() . " Value: " . $sample->getValue());
            }
        }

        // Render the metrics to response
        $response = $renderer->render($metrics);
        error_log("Rendered metrics: " . $response);

        return new Response($response, 200, ['Content-Type' => RenderTextFormat::MIME_TYPE]);
    }
}

<?php

namespace App\Command;

use App\Entity\CurrencyExchangeRate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:fetch-exchange-rates')]
class FetchExchangeRatesCommand extends Command
{
    protected static $defaultName = 'app:fetch-exchange-rates';
    private HttpClientInterface $httpClient;
    private string $fixerApiKey;
    private EntityManagerInterface $entityManager;

    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->httpClient = $httpClient;
        $this->fixerApiKey = $_ENV['FIXER_API_KEY'];
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Fetch exchange rates for a set of currencies');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $currencies = ['USD', 'GBP', 'JPY', 'DKK']; // Add more currencies as needed

        $endpoint = sprintf(
            'http://data.fixer.io/api/latest?access_key=%s&symbols=%s',
            $this->fixerApiKey,
            implode(',', $currencies)
        );

        try {
            $response = $this->httpClient->request('GET', $endpoint);
            $responseData = $response->toArray();

            if (!$responseData['success']) {
                $output->writeln('<error>' . $responseData['error']['info'] . '</error>');
                return Command::FAILURE;
            }

            $rates = $responseData['rates'];
            
            foreach ($rates as $currency => $rate) {
                $output->writeln("$currency: $rate");
                $currencyExchangeRate = new CurrencyExchangeRate();
                $currencyExchangeRate->setCurrencyCode($currency);
                $currencyExchangeRate->setRateToEur($rate);
                $currencyExchangeRate->setIat(new \DateTime());
                $this->entityManager->persist($currencyExchangeRate);

            }
            $this->entityManager->flush();

            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }

}

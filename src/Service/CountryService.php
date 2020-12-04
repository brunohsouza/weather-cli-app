<?php

declare(strict_types=1);

namespace App\Service;


use App\Entity\Country;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CountryService
{

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $apifirst, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->client = $apifirst;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function getCountryList(): ?string
    {
        $startTime = microtime(true);
        try {
            $response = $this->client->request(
                'GET',
                '/data/v1/countries',
                [
                    'query' => [
                        'access' => 'full',
                        'limit' => 251
                    ]
                ]
            );

            $endtime = microtime(true);
            echo 'process time:', $endtime - $startTime . PHP_EOL;

            return $response->getContent();
        } catch (ClientExceptionInterface $e) {
            throw $e;
        } catch (RedirectionExceptionInterface $e) {
            throw $e;
        } catch (ServerExceptionInterface $e) {
            throw $e;
        } catch (TransportExceptionInterface $e) {
            throw $e;
        }
    }

    public function findByAcronyms(string $acronym):? object
    {
        return $this->entityManager->getRepository(Country::class)->findOneBy(['acronyms' => $acronym]);
    }

    public function getCountryByAcronym(string $countryAbbreviation)
    {
        try {
            $response = $this->client->request(
                'GET',
                '/data/v1/countries',
                [
                    'query' => [
                        'abbreviation' => $countryAbbreviation
                    ]
                ]
            );

            return $response->toArray();
        } catch (ClientExceptionInterface $e) {
            throw $e;
        } catch (RedirectionExceptionInterface $e) {
            throw $e;
        } catch (ServerExceptionInterface $e) {
            throw $e;
        } catch (TransportExceptionInterface $e) {
            throw $e;
        }
    }

}
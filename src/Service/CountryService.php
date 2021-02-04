<?php

declare(strict_types=1);

namespace App\Service;


use App\Entity\Country;
use Doctrine\ORM\EntityManagerInterface;
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

    public function __construct(
        HttpClientInterface $apifirst,
        EntityManagerInterface $entityManager
    )
    {
        $this->client = $apifirst;
        $this->entityManager = $entityManager;
    }

    public function getCountryList(): ?string
    {
        try {
            $response = $this->client->request(
                'GET',
                '/data/v1/countries',
                [
                    'query' => [
                        'pretty' => true,
                        'limit' => 251,
                    ]
                ]
            );

            return $response->getContent();
        } catch (ClientExceptionInterface $clientException) {
            throw $clientException;
        } catch (RedirectionExceptionInterface $redirectionException) {
            throw $redirectionException;
        } catch (ServerExceptionInterface $serverException) {
            throw $serverException;
        } catch (TransportExceptionInterface $transportException) {
            throw $transportException;
        }
    }

    public function findByAcronyms(string $acronym):? object
    {
        return $this->entityManager->getRepository(Country::class)->findOneBy(['acronyms' => $acronym]);
    }

    public function getCountryByUserInfo(string $countryInfo): string
    {
        try {
            $response = $this->client->request(
                'GET',
                '/data/v1/countries',
                [
                    'query' => [
                        'q' => $countryInfo,
                        'pretty' => true
                    ]
                ]
            );

            return $response->getContent();
        } catch (ClientExceptionInterface $clientException) {
            throw $clientException;
        } catch (RedirectionExceptionInterface $redirectionException) {
            throw $redirectionException;
        } catch (ServerExceptionInterface $serverException) {
            throw $serverException;
        } catch (TransportExceptionInterface $transportException) {
            throw $transportException;
        }
    }

}
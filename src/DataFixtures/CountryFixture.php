<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Service\CountryService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CountryFixture extends Fixture
{

    private CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function load(ObjectManager $manager)
    {
//        $countries = $this->countryService->getCountryList();
        $countries = json_decode(file_get_contents('data/country.list.json'), true);
        foreach ($countries['data'] as $key => $item) {
            $country = new Country();
            $country->setName($item['country']);
            $country->setAcronyms($key);
            $country->setRegion($item['region']);

            $manager->persist($country);
        }
        $manager->flush();
    }
}

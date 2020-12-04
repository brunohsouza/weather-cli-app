<?php

namespace App\DataFixtures;

use App\Entity\Coordinates;
use App\Entity\Country;
use App\Service\CountryService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use JsonMachine\JsonMachine;
use App\Entity\City;

class CityFixture extends Fixture implements DependentFixtureInterface
{
    /**
     * @var CountryService
     */
    private CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function load(ObjectManager $manager)
    {
        $jsonFile = 'data/city.list.json';
        if (file_exists($jsonFile)) {
            $jsonStream = JsonMachine::fromFile($jsonFile);
            foreach ($jsonStream as $item) {
                $city = new City();
                $city->setId($item['id']);
                $city->setName($item['name']);
                $city->setCoordinates(json_encode($item['coord']));

                $country = $this->countryService->findByAcronyms($item['country']);
                $city->setCountry($country);

                $manager->persist($city);
            }

            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [CountryFixture::class];
    }
}

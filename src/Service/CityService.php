<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;

class CityService
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByName(string $name):? City
    {
        return $this->entityManager->getRepository(City::class)->findOneBy(['name' => $name]);
    }
}
<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="city")
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $name;

    /**
     * @ORM\Column(type="json")
     */
    private string $coordinates;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="cities", fetch="EAGER")
     */
    private $country;

    /**
     * @ORM\OneToOne(targetEntity=Weather::class, mappedBy="city", cascade={"persist", "remove"})
     */
    private $weather;

    public function __construct()
    {
        $this->country = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return City
     */
    public function setId(int $id): City
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return City
     */
    public function setName(string $name): City
    {
        $this->name = $name;
        return $this;
    }

    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(string $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * @return Collection|Country[]
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getContext()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => [
                'id ' => $this->getCountry()->getId(),
                'name' => $this->getCountry()->getName(),
                'acronyms' => $this->getCountry()->getAcronyms(),
                'region' => $this->getCountry()->getRegion()
            ],
            'coordinates' => json_decode($this->getCoordinates()),
        ];
    }

    public function getWeather(): ?Weather
    {
        return $this->weather;
    }

    public function setWeather(Weather $weather): self
    {
        $this->weather = $weather;

        // set the owning side of the relation if necessary
        if ($weather->getCity() !== $this) {
            $weather->setCity($this);
        }

        return $this;
    }
}
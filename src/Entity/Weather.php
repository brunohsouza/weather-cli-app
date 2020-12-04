<?php

namespace App\Entity;

use App\Repository\WeatherRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WeatherRepository::class)
 */
class Weather
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $main;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $windSpeed;

    /**
     * @ORM\OneToOne(targetEntity=City::class, inversedBy="weather", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\OneToOne(targetEntity=Temperature::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $temperature;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMain(): ?string
    {
        return $this->main;
    }

    public function setMain(string $main): self
    {
        $this->main = $main;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getWindSpeed(): ?float
    {
        return $this->windSpeed;
    }

    public function setWindSpeed(float $windSpeed): self
    {
        $this->windSpeed = $windSpeed;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getTemperature(): ?Temperature
    {
        return $this->temperature;
    }

    public function setTemperature(Temperature $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedat(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\TemperatureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TemperatureRepository::class)
 */
class Temperature
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="float")
     */
    private float $realtime;

    /**
     * @ORM\Column(type="float")
     */
    private float $min;

    /**
     * @ORM\Column(type="float")
     */
    private float $max;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private float $feelsLike;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $humidity;

    /**
     * @ORM\Column(type="string", nullable=false, options={"default": "Celsius"})
     */
    private string $measure;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRealtime(): ?float
    {
        return $this->realtime;
    }

    public function setRealtime(float $realtime): self
    {
        $this->realtime = $realtime;

        return $this;
    }

    public function getMin(): ?float
    {
        return $this->min;
    }

    public function setMin(float $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function getMax(): ?float
    {
        return $this->max;
    }

    public function setMax(float $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function getFeelsLike(): ?float
    {
        return $this->feelsLike;
    }

    public function setFeelsLike(?float $feelsLike): self
    {
        $this->feelsLike = $feelsLike;

        return $this;
    }

    public function getHumidity(): ?int
    {
        return $this->humidity;
    }

    public function setHumidity(?int $humidity): self
    {
        $this->humidity = $humidity;

        return $this;
    }

    /**
     * @return string
     */
    public function getMeasure(): string
    {
        return $this->measure;
    }

    /**
     * @param string $measure
     * @return Temperature
     */
    public function setMeasure(string $measure): Temperature
    {
        $this->measure = $measure;
        return $this;
    }
}

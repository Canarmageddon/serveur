<?php

namespace App\Dto;

class CostInput
{
    private ?int $creator;

    private ?string $beneficiaries;

    private ?int $trip;

    private ?string $label;

    private ?float $value;

    /**
     * @return int|null
     */
    public function getCreator(): ?int
    {
        return $this->creator;
    }

    /**
     * @param int|null $creator
     */
    public function setCreator(?int $creator): void
    {
        $this->creator = $creator;
    }

    private ?string $category;

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     */
    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string|null
     */
    public function getBeneficiaries(): ?string
    {
        return $this->beneficiaries;
    }

    /**
     * @param string|null $beneficiaries
     */
    public function setBeneficiaries(?string $beneficiaries): void
    {
        $this->beneficiaries = $beneficiaries;
    }

    /**
     * @return int|null
     */
    public function getTrip(): ?int
    {
        return $this->trip;
    }

    /**
     * @param int|null $trip
     */
    public function setTrip(?int $trip): void
    {
        $this->trip = $trip;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     */
    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * @param float|null $value
     */
    public function setValue(?float $value): void
    {
        $this->value = $value;
    }
}
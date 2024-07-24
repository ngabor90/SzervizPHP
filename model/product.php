<?php

class Product
{
    private ?int $id;
    private string $serialNumber;
    private string $manufacturer;
    private string $type;
    private string $status;
    private string $submissionDate;
    private string $lastStatusChange;

    public function __construct(?int $id, string $serialNumber, string $manufacturer, string $type, string $status, string $submissionDate, string $lastStatusChange)
    {
        $this->id = $id;
        $this->serialNumber = $serialNumber;
        $this->manufacturer = $manufacturer;
        $this->type = $type;
        $this->status = $status;
        $this->submissionDate = $submissionDate;
        $this->lastStatusChange = $lastStatusChange;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Getter methods
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerialNumber(): string
    {
        return $this->serialNumber;
    }

    public function getManufacturer(): string
    {
        return $this->manufacturer;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getSubmissionDate(): string
    {
        return $this->submissionDate;
    }

    public function getLastStatusChange(): string
    {
        return $this->lastStatusChange;
    }
}

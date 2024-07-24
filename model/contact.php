<?php

class Contact
{
    private int $id;
    private int $productId;
    private string $firstName;
    private ?string $middleName;
    private string $lastName;
    private string $phone;
    private string $email;

    public function __construct(
        ?int $id,
        int $productId, 
        string $firstName,
        ?string $middleName,
        string $lastName,
        string $phone,
        string $email
    ) {
        $this->id = $id ?? 0;
        $this->productId = $productId;
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->email = $email;
    }

    // Getter methods
    public function getId(): int {
        return $this->id;
    }

    public function getProductId(): int {
        return $this->productId;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function getMiddleName(): ?string {
        return $this->middleName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function getPhone(): string {
        return $this->phone;
    }

    public function getEmail(): string {
        return $this->email;
    }
}

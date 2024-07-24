<?php

class DatabaseManager
{
    public static function productRead(): array
    {
        try {
            $con = new mysqli("127.0.0.1", "root", "", "webfejlesztoVizsga");
            $stmt = $con->prepare("SELECT id, serial_number, manufacturer, type, status, submission_date, last_status_change
                    FROM `products`
                    WHERE status != 'Kész' OR (status = 'Kész' AND DATE(last_status_change) = CURDATE())
                    ORDER BY FIELD(status, 'Beérkezett', 'Hibafeltárás', 'Alkatrész beszerzés alatt', 'Javítás', 'Kész')");
            $stmt->execute();
            $result = $stmt->get_result();

            $array = [];
            while ($row = $result->fetch_assoc()) {
                $array[] = new Product(
                    $row['id'],
                    $row['serial_number'],
                    $row['manufacturer'],
                    $row['type'],
                    $row['status'],
                    $row['submission_date'],
                    $row['last_status_change']
                );
            }

            $stmt->close();
            $con->close();

            return $array;
        } catch (mysqli_sql_exception) {
            return [null];
        }
    }

    public static function contactRead(): array
    {
        try {
            $con = new mysqli("127.0.0.1", "root", "", "webfejlesztoVizsga");
            $stmt = $con->prepare("SELECT id, product_id, first_name, middle_name, last_name, phone, email FROM `contacts`");
            $stmt->execute();
            $stmt->bind_result($id, $productId, $firstName, $middleName, $lastName, $phone, $email);

            $array = [];
            while ($stmt->fetch()) {
                $array[] = new Contact(
                    $id,
                    $productId,
                    $firstName,
                    $middleName,
                    $lastName,
                    $phone,
                    $email
                );
            }

            $stmt->close();
            $con->close();

            return $array;
        } catch (mysqli_sql_exception) {
            return [null];
        }
    }

    public static function productandContactUpload(?Product $product, ?Contact $contact): void
    {
        try {
            $con = new mysqli("127.0.0.1", "root", "", "webfejlesztoVizsga");

            if ($product !== null) {
                // Insert product
                $serial_number = $product->getSerialNumber();
                $manufacturer = $product->getManufacturer();
                $type = $product->getType();
                $status = $product->getStatus();
                $submission_date = $product->getSubmissionDate();
                $last_status_change = $product->getLastStatusChange();

                $stmt = $con->prepare("INSERT INTO `products` (serial_number, manufacturer, type, status, submission_date, last_status_change) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $serial_number, $manufacturer, $type, $status, $submission_date, $last_status_change);
                if ($stmt->execute()) {
                    $product_id = $con->insert_id;
                    $product->setId($product_id);
                } else {
                    echo "Hiba a termék rögzítésekor: " . $stmt->error;
                    return;
                }
                $stmt->close();
            }

            if ($contact !== null) {
                // Insert contact
                $product_id = $contact->getProductId();
                $first_name = $contact->getFirstName();
                $middle_name = $contact->getMiddleName();
                $last_name = $contact->getLastName();
                $phone = $contact->getPhone();
                $email = $contact->getEmail();

                $stmt = $con->prepare("INSERT INTO `contacts` (product_id, first_name, middle_name, last_name, phone, email) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssss", $product_id, $first_name, $middle_name, $last_name, $phone, $email);
                if ($stmt->execute()) {
                    echo "Termék és kapcsolattartó sikeresen rögzítve.";
                } else {
                    echo "Hiba a kapcsolattartó rögzítésekor: " . $stmt->error;
                }
                $stmt->close();
            }

            $con->close();
        } catch (mysqli_sql_exception $e) {
            echo "Hiba történt: " . $e->getMessage();
        }
    }

    public static function productandContactDelete(int $id): void
    {
        try {
            $con = new mysqli("127.0.0.1", "root", "", "webfejlesztoVizsga");

            $stmt = $con->prepare("DELETE FROM `products` WHERE `id` = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            $stmt = $con->prepare("DELETE FROM `contacts` WHERE `id` = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            $con->close();
        } catch (mysqli_sql_exception $e) {
            echo "Hiba történt: " . $e->getMessage();
        }
    }

    public static function fetchProductAndContact(int $id): ?array
{
    try {
        $con = new mysqli("127.0.0.1", "root", "", "webfejlesztoVizsga");

        // Fetch product
        $stmt = $con->prepare("SELECT * FROM `products` WHERE `id` = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($product_id, $serial_number, $manufacturer, $type, $status, $submission_date, $last_status_change);
        if ($stmt->fetch()) {
            $product = new Product($product_id, $serial_number, $manufacturer, $type, $status, $submission_date, $last_status_change);
            //echo "Product found: ID = $product_id<br>";
        } else {
            echo "No product found with the given ID.";
            return null; // No product found with the given ID
        }
        $stmt->close();

        // Fetch contact
        $stmt = $con->prepare("SELECT * FROM `contacts` WHERE `product_id` = ?");
        $stmt->bind_param("i", $product_id);  // Use $product_id instead of $id
        $stmt->execute();
        $stmt->bind_result($contact_id, $contact_product_id, $first_name, $middle_name, $last_name, $phone, $email);
        if ($stmt->fetch()) {
            $contact = new Contact($contact_id, $contact_product_id, $first_name, $middle_name, $last_name, $phone, $email);
            //echo "Contact found: ID = $contact_id, Phone = $phone<br>";
        } else {
            echo "No contact found for the given product ID.";
            return null; // No contact found for the given product ID
        }
        $stmt->close();

        $con->close();

        return ['product' => $product, 'contact' => $contact];
        
    } catch (mysqli_sql_exception $e) {
        echo "Database error: " . $e->getMessage();
        return null;
    }
}

    public static function updateProduct(int $product_id, string $serial_number, string $manufacturer, string $type, string $status): bool
    {
        try {
            $con = new mysqli("127.0.0.1", "root", "", "webfejlesztoVizsga");

            $stmt = $con->prepare("UPDATE `products` SET `serial_number` = ?, `manufacturer` = ?, `type` = ?, `status` = ?, `last_status_change` = NOW() WHERE `id` = ?");
            $stmt->bind_param("ssssi", $serial_number, $manufacturer, $type, $status, $product_id);
            $stmt->execute();

            $stmt->close();
            $con->close();

            return true;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    public static function updateContact(int $product_id, string $first_name, string $middle_name, string $last_name, string $phone, string $email): bool
    {
        try {
            $con = new mysqli("127.0.0.1", "root", "", "webfejlesztoVizsga");

            $stmt = $con->prepare("UPDATE `contacts` SET `first_name` = ?, `middle_name` = ?, `last_name` = ?, `phone` = ?, `email` = ? WHERE `product_id` = ?");
            $stmt->bind_param("sssssi", $first_name, $middle_name, $last_name, $phone, $email, $product_id);
            $stmt->execute();

            $stmt->close();
            $con->close();

            return true;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }
}


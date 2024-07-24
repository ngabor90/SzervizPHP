<?php

function load($class)
{
    $path = "../model/";
    include $path . $class . ".php";
}

spl_autoload_register("load");

function indexView(){
    include "../view/index.php";
}

function productSummaryView()
{
    $products = DatabaseManager::productRead();
    include "../view/productSummaryView.php";
}

function contactSummaryView()
{
    $contacts = DatabaseManager::contactRead();
    include "../view/contactSummaryView.php";
}

function newProductView()
{
    return include "../view/newProductView.php";
}

function productUpdateView()
{
    $id = $_GET["update"];
    //echo "Fetching product and contact for ID: $id<br>";  // Debug message
    $updateProduct = DatabaseManager::fetchProductAndContact($id);
    if ($updateProduct === null) {
        echo "No product or contact found with the given ID.";
    } else {
        $product = $updateProduct['product'];
        $contact = $updateProduct['contact'];
        include "../view/productUpdateView.php";
    }
}



function newProduct()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $serial_number = $_POST['serial_number'];
        $manufacturer = $_POST['manufacturer'];
        $type = $_POST['type'];
        $submission_date = date("Y-m-d");
        $status = "Beérkezett";
        $last_status_change = date("Y-m-d H:i:s");
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        // Create Product object
        $product = new Product(null, $serial_number, $manufacturer, $type, $status, $submission_date, $last_status_change);

        // Insert the product and get its ID
        DatabaseManager::productandContactUpload($product, null);

        // Get the generated product ID
        $product_id = $product->getId();

        // Create Contact object with the product ID
        $contact = new Contact(null, $product_id, $first_name, $middle_name, $last_name, $phone, $email);

        // Insert the contact
        DatabaseManager::productandContactUpload(null, $contact);

        // Show the new product view
        newProductView();
    }
}

function productUpdate()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product_id = $_POST['product_id'];
        $serial_number = $_POST['serial_number'];
        $manufacturer = $_POST['manufacturer'];
        $type = $_POST['type'];
        $status = $_POST['status'];
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        // Update product
        DatabaseManager::updateProduct($product_id, $serial_number, $manufacturer, $type, $status);

        // Update contact
        DatabaseManager::updateContact($product_id, $first_name, $middle_name, $last_name, $phone, $email);

        // Redirect to the product summary view
        echo("Sikeres módosítás");
        header("Location: controller.php?page=productSummaryView");
        exit();
    } else {
        productUpdateView();
    }
}

function productDelete()
{
    $id = $_GET["delete"];
    DatabaseManager::productandContactDelete($id);
    productSummaryView();
}

function errorView()
{
    include "../view/errorView.php";
}


function main()
{
    if (array_key_exists("page", $_GET)) {
        $page = $_GET["page"];
    } else {
        $page = "index";
    }
    if (array_key_exists("serial_number", $_POST)) {
        $page = "newProduct";
    }

    if(array_key_exists("delete", $_GET)){
        $page = "productDelete";
    }

    if(array_key_exists("update", $_GET)){
        $page = "productUpdate";
    }

    switch ($page) {
        case "productSummaryView":
            productSummaryView();
            break;

        case "contactSummaryView":
            contactSummaryView();
            break;

        case "newProductView":
            newProductView();
            break;

        case "newProduct":
            newProduct();
            break;

        case "productUpdate":
            productUpdate();
            break;

        case "productDelete":
            productDelete();
            break;

        case "productUpdateView":
            productUpdateView();
            break;

        case "index":
            indexView();
            break;

        default:
            errorView();
            break;
    }
}

main();
<?php
$base_url = '/proyecto2'; 

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../models/Product.php';

$database = new Database();
$db = $database->connect();

$product = new Product($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if(isset($_GET['id'])) {
            $product->id = $_GET['id'];
            $product->read_single();
            
            $product_arr = array(
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'category_id' => $product->category_id,
                'category_name' => $product->category_name
            );

            echo json_encode($product_arr);
        } else {
            $result = $product->read();
            $num = $result->rowCount();

            if($num > 0) {
                $products_arr = array();
                $products_arr['data'] = array();

                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);

                    $product_item = array(
                        'id' => $id,
                        'name' => $name,
                        'description' => html_entity_decode($description),
                        'price' => $price,
                        'category_id' => $category_id,
                        'category_name' => $category_name
                    );

                    array_push($products_arr['data'], $product_item);
                }

                echo json_encode($products_arr);
            } else {
                echo json_encode(
                    array('message' => 'No Products Found')
                );
            }
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        $product->name = $data->name;
        $product->description = $data->description;
        $product->price = $data->price;
        $product->category_id = $data->category_id;

        if($product->create()) {
            echo json_encode(
                array('message' => 'Product Created')
            );
        } else {
            echo json_encode(
                array('message' => 'Product Not Created')
            );
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        $product->id = $data->id;
        $product->name = $data->name;
        $product->description = $data->description;
        $product->price = $data->price;
        $product->category_id = $data->category_id;

        if($product->update()) {
            echo json_encode(
                array('message' => 'Product Updated')
            );
        } else {
            echo json_encode(
                array('message' => 'Product Not Updated')
            );
        }
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        $product->id = $data->id;

        if($product->delete()) {
            echo json_encode(
                array('message' => 'Product Deleted')
            );
        } else {
            echo json_encode(
                array('message' => 'Product Not Deleted')
            );
        }
        break;
}
?>
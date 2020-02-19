<?php
namespace App\Repositories\Product;

interface ProductRepositoryInterface
{
    function getAllProductByCategory($id);
    function getAllPaginate();
    function addProduct($req);
    function disableProduct($id);
    function updateProduct($req, $id);
    function findProductByName($request);
    function find_1($id);
}
?>

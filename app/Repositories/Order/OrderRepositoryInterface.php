<?php
namespace App\Repositories\Order;

interface OrderRepositoryInterface
{
//web
    function getOrderById($orderID);
    function getOrderItemById($orderItemID);
    function getProductById($productID);
    function getAllOrderItemByOrderId($orderID);

    function getDetailOrder(&$orderItems);
    function printOrder($order, &$orderItems);
    function revenue();
   
    function send($to = "", $data = array());
    function vieworder($id);
    function indexRechage();
//success order
    function checkOrder($orderID);
    function changeStatusAndCheckOutOrder($order, $user);
    function createdNotificationSuccessOrder($order, $user);

// error order
    function changeStatusAndCancelOrder($order, $user);
    function createdNotificationCancelOrder($order, $user);

// next order
    function changeStatusNextOrder($orderID);
}
?>

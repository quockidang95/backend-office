<?php
namespace App\Repositories\Order;

interface OrderRepositoryInterface
{
    //web
    public function getOrderById($orderID);
    public function getOrderItemById($orderItemID);
    public function getProductById($productID);
    public function getAllOrderItemByOrderId($orderID);

    public function getDetailOrder(&$orderItems);
    public function printOrder($order, &$orderItems);
    public function revenue();
   
    public function send($to = "", $data = array());
    public function vieworder($id);
    public function indexRechage();
    //success order
    public function checkOrder($orderID);
    public function changeStatusAndCheckOutOrder($order, $user);
    public function createdNotificationSuccessOrder($order, $user);

    // error order
    public function changeStatusAndCancelOrder($order, $user);
    public function createdNotificationCancelOrder($order, $user);

    // next order
    public function changeStatusNextOrder($orderID);
}

<?php
namespace App\Repositories\User;

interface UserRepositoryInterface
{
//api
    function validator($request);
    function createUser($request);
    function userLogin($request);
    function userUpdate($request);
//web
    function rechangeUserAndCreateNotification($id, $request);
    function searchUser($request);
    function infoUser($id);
    
}
?>

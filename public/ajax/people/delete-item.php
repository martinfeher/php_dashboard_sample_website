<?php

    /**
     * Route: /ajax/work_position/add-item.php
     * Description: Delete item in db table php_sample_dashboard_website.work_position
     * Return: string
     *
     */

    session_start();
    if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        require_once '../../../config/app.php';
        if(@isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']===$app['domain'] . '/people.php') {
            $headers = apache_request_headers();
            if($headers['Csrf-Token'] == $_SESSION['Csrf-Token']) {

                try {
                    require_once '../../../config/database.php';
                    require_once '../../../config/helpers.php';

                    $uuid = $_POST['id'];

                    $db = new PDO($connection_db_server_s1['dns'], $connection_db_server_s1['user'], $connection_db_server_s1['password']);
                    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = "DELETE FROM php_sample_dashboard_website.people WHERE uuid = :uuid;";

                    $stmt = $db->prepare($sql);
                    $stmt->bindValue(':uuid', $uuid);
                    $stmt->execute();
                    $db = null;

                } catch(PDOException $e) {
                    echo $sql . "<br>" . $e->getMessage();
                    $db = null;
                    exit('connection error');
                }
                $db = null;

                echo 'ok';

            } else {
                header('Error: CSRF token mismatch');
            }
        } else {
            header('Error: No valid referer');
        }
    } else {
        header('Error: No valid Ajax request');
    }
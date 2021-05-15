<?php

    /**
     * Route: /ajax/work_position/tabulka-data.php
     * Description: Retrieve data from db table php_sample_dashboard_website.work_position
     * Return: string
     *
     */

    session_start();
   if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
       require_once '../../../config/app.php';
       if(@isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] === $app['domain'] . '/work_position.php') {
           $headers = apache_request_headers();
           if($headers['Csrf-Token'] == $_SESSION['Csrf-Token']) {

                try {
                    require_once '../../../config/database.php';
                    require_once '../../../config/helpers.php';

                    $db = new PDO($connection_db_server_s1['dns'], $connection_db_server_s1['user'], $connection_db_server_s1['password']);

                    $sql = " SELECT uuid as id, title, description FROM php_sample_dashboard_website.work_position;";

                    $stmt = $db->prepare($sql);
                    $stmt->execute([]);
                    $work_position_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $db = null;

                } catch(PDOException $e) {
                    echo $sql . "<br>" . $e->getMessage();
                    $db = null;
                    exit('connection error');
                }
                $db = null;

                $output['table_data'] = $work_position_data;
                echo json_encode($output);

           } else {
               header('Error: CSRF token mismatch');
           }
       } else {
           header('Error: No valid referer');
       }
   } else {
       header('Error: No valid Ajax request');
   }
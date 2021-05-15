<?php

    /**
     * Route: /ajax/work_position/tabulka-data.php
     * Description: Retrieve data from db table php_sample_dashboard_website.people
     * Return: string
     *
     */

    session_start();
    if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        require_once '../../../config/app.php';
        if(@isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] === $app['domain'] . '/people.php') {
            $headers = apache_request_headers();
            if($headers['Csrf-Token'] == $_SESSION['Csrf-Token']) {

                try {
                    require_once '../../../config/database.php';
                    require_once '../../../config/helpers.php';

                    $db = new PDO($connection_db_server_s1['dns'], $connection_db_server_s1['user'], $connection_db_server_s1['password']);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql  = " SELECT people.uuid as id, people.first_name, people.surname, people.title, people.email, people.phone, work_position.title as work_position, work_position.uuid as work_position_uuid ";
                    $sql .= " FROM php_sample_dashboard_website.people ";
                    $sql .= " LEFT JOIN php_sample_dashboard_website.work_position ";
                    $sql .= " ON people.work_position_uuid = work_position.uuid; ";

                    $stmt = $db->prepare($sql);
                    $stmt->execute([]);
                    $people = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $db = null;

                } catch(PDOException $e) {
                    echo $sql . "<br>" . $e->getMessage();
                    $db = null;
                    exit('connection error');
                }
                $db = null;

                $output['table_data'] = $people;
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
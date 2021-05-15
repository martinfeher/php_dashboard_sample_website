<?php

    /**
     * Route: /ajax/work_position/add-update-item.php
     * Description: Add new item or update existing item in db table php_sample_dashboard_website.work_position
     * Return: string
     *
     */

    session_start();
    if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        require_once '../../../config/app.php';
        if(@isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']===$app['domain'] . '/work_position.php') {
            $headers = apache_request_headers();
            if($headers['Csrf-Token'] == $_SESSION['Csrf-Token']) {

                require_once '../../../config/helpers.php'; 

                $id = $_POST['id'];
                $title = $_POST['title'];
                $description = $_POST['description'];

                // Validation

                $validation_error = 0;
                $validation_error_msg['title'] = '';
                $validation_error_msg['description'] = '';


                if (strlen($title) > 250) {
                    $validation_error_msg['title'] = 'The maximum number of characters allowed is 250';
                    $validation_error = 1;
                }
                if ($title === '') {
                    $validation_error_msg['title'] = 'Title is required';
                    $validation_error = 1;
                }

                if ($validation_error === 1) {
                    $output['status'] = 'validation error';
                    $output['$validation_error'] = $validation_error;
                    $output['validation_error_msg'] = $validation_error_msg;
                    echo json_encode($output);
                    return;
                }


                // End Validation

                try {
                    require_once '../../../config/database.php';

                    $db = new PDO($connection_db_server_s1['dns'], $connection_db_server_s1['user'], $connection_db_server_s1['password']);
                    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    if ($id === '') {
                        $sql  = " INSERT INTO php_sample_dashboard_website.work_position (uuid, title, description) VALUES (:uuid, :title, :description) ";
                        $stmt = $db->prepare($sql);
                        $stmt->bindValue(':uuid', uuidv4());
                        $stmt->bindValue(':title', $title);
                        $stmt->bindValue(':description', $description);
                        $stmt->execute();

                    } else {
                        $sql  = " UPDATE php_sample_dashboard_website.work_position SET title=:title, description=:description WHERE uuid=:uuid ";
                        $stmt = $db->prepare($sql);
                        $stmt->bindValue(':title', $title);
                        $stmt->bindValue(':description', $description);
                        $stmt->bindValue(':uuid', $id);
                        $stmt->execute();
                    }

                    $db = null;

                } catch(PDOException $e) {
                    echo $sql . "<br>" . $e->getMessage();
                    $db = null;
                    exit('connection error');
                }
                $db = null;

                $output['status'] = 'ok';
                echo json_encode($output);
                return;

            } else {
                header('Error: CSRF token mismatch');
            }
        } else {
            header('Error: No valid referer');
        }
    } else {
        header('Error: No valid Ajax request');
    }
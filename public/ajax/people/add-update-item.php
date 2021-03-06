<?php

    /**
     * Route: /ajax/work_position/add-update-item.php
     * Description: Add new item, edit existing item to db table php_sample_dashboard_website.people
     * Return: string
     *
     */

    session_start();
    if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        require_once '../../../config/app.php';
        if(@isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']===$app['domain'] . '/people.php') {
            $headers = apache_request_headers();
            if($headers['Csrf-Token'] == $_SESSION['Csrf-Token']) {

                require_once '../../../config/helpers.php';

                $id = $_POST['id'];
                $first_name = $_POST['first_name'];
                $surname= $_POST['surname'];
                $title = $_POST['title'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                $work_position_uuid = $_POST['work_position'];

                // Validation

                $validation_error = 0;
                $validation_error_msg['first_name'] = '';
                $validation_error_msg['surname'] = '';
                $validation_error_msg['title'] = '';
                $validation_error_msg['email'] = '';
                $validation_error_msg['phone'] = '';

                if (strlen($first_name) > 100) {
                    $validation_error_msg['first_name'] = 'The maximum number of characters allowed is 100';
                    $validation_error = 1;
                }
                if ($first_name === '') {
                    $validation_error_msg['first_name'] = 'First_name is required';
                    $validation_error = 1;
                }

                if (strlen($surname) > 150) {
                    $validation_error_msg['surname'] = 'The maximum number of characters allowed is 150';
                    $validation_error = 1;
                }
                if ($surname === '') {
                    $validation_error_msg['surname'] = 'Surname is required';
                    $validation_error = 1;
                }

                if (strlen($title) > 150) {
                    $validation_error_msg['title'] = 'The maximum number of characters allowed is 150';
                    $validation_error = 1;
                }

                if (strlen($email) > 150) {
                    $validation_error_msg['email'] = 'The maximum number of characters allowed is 150';
                    $validation_error = 1;
                }
                if ($email === '') {
                    $validation_error_msg['email'] = 'Email is required';
                    $validation_error = 1;
                }

                if (strlen($phone) > 35) {
                    $validation_error_msg['phone'] = 'Phone mus?? ma?? maxim??lne 35 symbolov';
                    $validation_error = 1;
                }
                if ($phone === '') {
                    $validation_error_msg['phone'] = 'Phone is required';
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
                        $sql  = " INSERT INTO php_sample_dashboard_website.people (uuid, first_name, surname, title, email, phone, work_position_uuid) VALUES (:uuid, :first_name, :surname, :title, :email, :phone, :work_position_uuid) ";
                        $stmt = $db->prepare($sql);
                        $stmt->bindValue(':uuid', uuidv4());
                        $stmt->bindValue(':first_name', $first_name);
                        $stmt->bindValue(':surname', $surname);
                        $stmt->bindValue(':title', $title);
                        $stmt->bindValue(':email', $email);
                        $stmt->bindValue(':phone', $phone);
                        $stmt->bindValue(':work_position_uuid', $work_position_uuid);
                        $stmt->execute();

                    } else {
                        $sql  = " UPDATE php_sample_dashboard_website.people SET first_name=:first_name, surname=:surname, title=:title, email=:email, phone=:phone, work_position_uuid=:work_position_uuid WHERE uuid=:uuid ";
                        $stmt = $db->prepare($sql);
                        $stmt->bindValue(':first_name', $first_name);
                        $stmt->bindValue(':surname', $surname);
                        $stmt->bindValue(':title', $title);
                        $stmt->bindValue(':email', $email);
                        $stmt->bindValue(':phone', $phone);
                        $stmt->bindValue(':work_position_uuid', $work_position_uuid);
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
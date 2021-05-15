<?php
    /**
     * Route: /people.php
     *
     * Description:
     *  On the page the user can
     *      1. Display the list of people and their parametres first_name, surname, title, email, phone, work position title and description
     *      2. Add new person
     *      3. Edit an existing person by clicking on the edit button
     *      4. Delete the person entry by clicking on the delete button
     *
     *      The user can search and order the data in the table
     * 
     */

    session_start();
    $_SESSION['Csrf-Token'] = bin2hex(random_bytes(32));

    // Retrieve data from php_sample_dashboard_website.people table
    require_once '../config/database.php';

    $db = new PDO($connection_db_server_s1['dns'], $connection_db_server_s1['user'], $connection_db_server_s1['password']);

    $sql = " SELECT * FROM php_sample_dashboard_website.work_position; ";
    $stmt = $db->prepare($sql);
    $stmt->execute([]);
    $work_position = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $db= null;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>People | php dashboard sample project</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/assets/libs/bootstrap-4.6.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
</head>
<body>
<div id="person" class="container">
    <?php include("../views_layout_partials/top_menu.php"); ?>
    <?php include("../views_layout_partials/side_menu.php"); ?>
    <div class="content">
        <h5 class="page_title">People</h5>
        <br>
        <br>
        <div>
            <div>
                <button type="button" id="add_person_btn" class="btn btn-primary mr-1">add</button>
            </div>
            <table id="table_person" class="table cell-border">
                <thead>
                <tr>
                    <th>first name</th>
                    <th>surname</th>
                    <th>title</th>
                    <th>email</th>
                    <th>phone</th>
                    <th>work position</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Add Edit WOrk Position Modal -->
    <div class="modal fade" id="person_modal" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_person_modal_titulok">Create person</h5>
                    <h5 class="modal-title" id="edit_person_modal_titulok" style="display:none;">Edit Person</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-4 px-4 pb-2">
                    <form>
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <div class="input-group">
                                <label for="first_name" class="col-form-label col-md-3">First name:</label>
                                <input value="" type="text" name="first_name" maxlength="100" id="first_name" class="form-control modal-input col-md-8">
                            </div>
                            <div id="validation-info-first_name" class="offset-md-3 col-md-8 pt-1 pl-0 validation-info">required</div>
                            <div id="error-message-first_name" class="alert alert-danger error-message offset-md-3 col-md-8 p-1" style="font-size: 0.9em; display:none;"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <label for="surname" class="col-form-label col-md-3">Surname:</label>
                                <input value="" type="text" name="surname" maxlength="150" id="surname" class="form-control modal-input col-md-8">
                            </div>
                            <div id="validation-info-surname" class="offset-md-3 col-md-8 pt-1 pl-0 validation-info">required</div>
                            <div id="error-message-surname" class="alert alert-danger error-message offset-md-3 col-md-8 p-1" style="font-size: 0.9em; display:none;"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <label for="title" class="col-form-label col-md-3">Title:</label>
                                <input value="" type="text" name="title" maxlength="150" id="title" class="form-control modal-input col-md-8">
                            </div>
                            <div id="validation-info-title" class="offset-md-3 col-md-8 pt-1 pl-0 validation-info">please add text, maximum of 150 charackters</div>
                            <div id="error-message-title" class="alert alert-danger error-message offset-md-3 col-md-8 p-1" style="font-size: 0.9em; display:none;"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <label for="email" class="col-form-label col-md-3">Email:</label>
                                <input value="" type="text" name="email" maxlength="150" id="email" class="form-control modal-input col-md-8">
                            </div>
                            <div id="validation-info-email" class="offset-md-3 col-md-8 pt-1 pl-0 validation-info">required, please add text, maximum of 150 charackters</div>
                            <div id="error-message-email" class="alert alert-danger error-message offset-md-3 col-md-8 p-1" style="font-size: 0.9em; display:none;"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <label for="phone" class="col-form-label col-md-3">Phone:</label>
                                <input value="" type="text" name="phone" maxlength="35" id="phone" class="form-control modal-input col-md-8">
                            </div>
                            <div id="validation-info-phone" class="offset-md-3 col-md-8 pt-1 pl-0 validation-info">please add phone number</div>
                            <div id="error-message-phone" class="alert alert-danger error-message offset-md-3 col-md-8 p-1" style="font-size: 0.9em; display:none;"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <label for="work_position" class="col-form-label col-md-3">Work position:</label>
                                <select name="work_position" id="work_position" class="form-control col-md-7">
                                    <?php
                                    foreach($work_position as $item) { ?>
                                        <option value="<?php echo $item['uuid'] ?>"><?php echo $item['title'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div id="validation-info-work-position" class="offset-md-3 col-md-7 pt-1 pl-0 validation-info" style="margin-top: -17px;"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-sm btn-secondary"data-dismiss="modal">Cancel</button>
                            <button type="button" id="confirm_update_person_btn" class="btn-sm btn-primary">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal-->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Yes, delete it!</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span class="font-weight-light" aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="confirm_delete_message" style="text-align: center;">Are you sure, you want to delete this work position with name: <br/> <span id="delete_first_name"></span> ?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancel</button>
                    <button id="confirmation-modal-delete-button" class="btn btn-danger btn-sm" type="button">Delete</button>
                </div>
            </div>
        </div>
    </div>

</div>

<link href="/assets/libs/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="/assets/libs/jquery-3.6.0/js/jquery-3.6.0.min.js"></script>
<script src="/assets/libs/bootstrap-4.6.0/js/bootstrap.min.js"></script>
<script src="/assets/libs/datatables/js/jquery.dataTables.min.js"></script>
<script src="/assets/libs/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.js"></script>
<script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.js"></script>

<script type="text/javascript">

    $(document).ready(function(){
        let table = createTableEntitaPerson();
        tableEntitaPersonData();
    });

    $.ajaxSetup({
        headers: {'Csrf-Token': '<?php echo $_SESSION['Csrf-Token']; ?>'}
    });

    $(document).delegate("#add_person_btn", "click", function(e){
        $('#add_person_modal_titulok').show();
        $('#edit_person_modal_titulok').hide();

        let id = $('#id').val('');
        let first_name = $('#first_name').val('');
        let surname = $('#surname').val('');
        let title = $('#title').val('');
        let email = $('#email').val('');
        let phone = $('#phone').val('');
        let work_position = $('#work_position').val('');

        $('#person_modal').modal('show');
    });

    $(document).delegate(".edit_row_btn", "click", function(e){
        $('#id').val($(this).data('id'));
        $('#first_name').val($(this).data('first_name'));
        $('#surname').val($(this).data('surname'));
        $('#title').val($(this).data('title'));
        $('#email').val($(this).data('email'));
        $('#phone').val($(this).data('phone'));
        let work_position_uuid =  $(this).data('work_position_uuid');
        work_position_uuid === null ? $('#work_position').val('') : $('[name=work_position]').val(work_position_uuid);
        $('#add_person_modal_titulok').hide();
        $('#edit_person_modal_titulok').show();
        $('.error-message').html('').hide();
        $('#person_modal').modal('show');
    });

    $(document).delegate("#confirm_update_person_btn", "click", function(e){
        e.preventDefault();
        console.log('ok');
        let id = $('#id').val();
        let first_name = $('#first_name').val();
        let surname = $('#surname').val();
        let title = $('#title').val();
        let email = $('#email').val();
        let phone = $('#phone').val();
        let work_position = $('#work_position').val();
        $.ajax({
            url: "/ajax/people/add-update-item.php",
            type: 'POST',
            dataType: 'json',
            data: {
                id: id,
                first_name: first_name,
                surname: surname,
                title: title,
                email: email,
                phone: phone,
                work_position: work_position,
                is_ajax: 1
            },
            success: function(data) {
                if (data.status === 'validation error') {
                    data['validation_error_msg']['first_name'] !== '' ? $('#error-message-first_name').html(data['validation_error_msg']['first_name']).fadeIn(200) : $('#error-message-first_name').html('').hide();
                    data['validation_error_msg']['surname'] !== '' ? $('#error-message-surname').html(data['validation_error_msg']['surname']).fadeIn(200) : $('#error-message-surname').html('').hide();
                    data['validation_error_msg']['title'] !== '' ? $('#error-message-title').html(data['validation_error_msg']['title']).fadeIn(200) : $('#error-message-title').html('').hide();
                    data['validation_error_msg']['email'] !== '' ? $('#error-message-email').html(data['validation_error_msg']['email']).fadeIn(200) : $('#error-message-email').html('').hide();
                    data['validation_error_msg']['phone'] !== '' ? $('#error-message-phone').html(data['validation_error_msg']['phone']).fadeIn(200) : $('#error-message-phone').html('').hide();
                    data['validation_error_msg']['work_position'] !== '' ? $('#error-message-work_position').html(data['validation_error_msg']['work_position']).fadeIn(200) : $('#error-message-work_position').html('').hide();
                } else if (data.status === 'ok') {
                    tableEntitaPersonData();
                    $('#person_modal').modal('hide');
                    $('.error-message').html('').hide();
                    $('#first_name').val('');
                    $('#surname').val('');
                    $('#title').val('');
                    $('#email').val('');
                    $('#phone').val('');
                    $('#work_position').val('');
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $(document).delegate(".delete_row_btn", "click", function(e){
        let id = $(this).data("id");
        $('#confirmationModal').modal('show');
        $('#delete_first_name').html($(this).data("first_namesurname"));
        $(document).delegate("#confirmation-modal-delete-button", "click", function(e){
            $('#confirmationModal').modal('hide');
            $.ajax({
                url:' /ajax/people/delete-item.php',
                method: 'POST',
                data: {
                    id: id,
                    is_ajax: 1
                },
                success: function (data) {
                    if (data === 'ok') {
                        $('#table_person').DataTable().row("#pp_" + id).remove().draw();
                        $('#person_modal').modal('hide');
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
    });


    function tableEntitaPersonData() {
        $.ajax({
            type: 'POST',
            dataType:' json',
            url: "/ajax/people/table-data.php",
            data: {
                is_ajax: 1
            },
            success:function(data){
                let table = createTableEntitaPerson(data.table_data);
            },
            error: function (error) {
                console.log(error);
            }
        })
    }

    function createTableEntitaPerson(data) {

        $('#table_person').DataTable().clear().destroy();
        let table = $('#table_person').DataTable({
            "searching": true,
            "lengthChange": false,
            "bInfo" : false,
            "order": [[1, "asc"]],
            "pageLength": 12,
            "language": {
                "searchPlaceholder": "#first-name #surname #title #email #phone #work-position",
            },
            "createdRow": function(row, data, dataIndex ) {
                $(row).attr('id', 'pp_' + data.id);
            },
            "data": data,

            "columns": [
                {"data": "first_name", "title": "first name", "orderable": true, "searchable": true, "className": "text-left text-wrap", "width": "15%"},
                {"data": "surname", "title": "surname", "orderable": true, "searchable": true, "className": "text-left text-wrap", "width": "10%"},
                {"data": "title", "title": "title", "orderable": true, "searchable": true, "className": "text-left text-wrap", "width": "6%"},
                {"data": "email", "title": "email", "orderable": true, "searchable": true, "className": "text-left text-wrap", "width": "10%"},
                {"data": "phone", "title": "phone", "orderable": true, "searchable": true, "className": "text-center text-wrap", "width": "10%"},
                {"data": "work_position", "title": "work position", "orderable": true, "searchable": true, "className": "text-center text-wrap", "width": "20%"},
                {"data": "edit", "title": "", "orderable": false, "searchable": false, "className": "text-center text-wrap", "width": "10%",
                    "render": function (data, type, row) {
                        return `<button type="button" id=edit_"${row.id}"  data-id="${row.id}" data-first_name="${row.first_name}" data-surname="${row.surname}" data-title="${row.title}" data-email="${row.email}" data-phone="${row.phone}" data-work_position_uuid="${row.work_position_uuid}" class="btn-sm btn-success edit_row_btn">edit</button>`;
                    }
                },
                {"data": "delete", "title": "", "orderable": false, "searchable": false, "className": "text-center text-wrap", "width": "15%",
                    "render": function (data, type, row) {
                        return `<button type="button" data-id="${row.id}" data-first_namesurname="${row.first_name} ${row.surname}" class="btn-sm btn-danger delete_row_btn">delete</button>`;
                    }
                },
            ],
        });
        return table;
    }

</script>
</body>
</html>

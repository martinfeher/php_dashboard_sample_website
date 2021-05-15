<?php
    /**
     * Route: /work_position.php
     *
     * Description:
     *  On the page user can
     *  1. Display the list of work position
     *  2. Add new work position
     *  3. Edit existing work position, button edit
     *  4. Delete work position, button delete
     *
     *  The work position has 2 parametres - title and description.
     *  The user can serach accross the data and order the data by their parametres
     */

    session_start();
    $_SESSION['Csrf-Token'] = bin2hex(random_bytes(32));

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Work position | php dashboard sample project</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="/assets/libs/bootstrap-4.6.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="/assets/css/app.css" rel="stylesheet">
    </head>
    <body>
    <div id="work_position" class="container">
        <?php include("../views_layout_partials/top_menu.php"); ?>
        <?php include("../views_layout_partials/side_menu.php"); ?>
        <div class="content">
            <h5 class="page_title">Work position</h5>
            <br>
            <br>
            <div>
                <div>
                    <button type="button" id="add_work_position_btn" class="btn btn-primary mr-1">add</button>
                </div>
                <table id="table_work_position" class="table cell-border">
                    <thead>
                        <tr>
                            <th>title</th>
                            <th>description</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- Add Edit Work position Modal -->
        <div class="modal fade" id="work_position_modal" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="add_work_position_modal_titulok">Create work position</h5>
                        <h5 class="modal-title" id="edit_work_position_modal_titulok" style="display:none;">Edit work position</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-4 px-4 pb-2">
                        <form>
                            <input type="hidden" name="id" id="id">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="title" class="col-form-label col-md-3">Title:</label>
                                    <input value="" type="text" name="title" maxlength="250" id="title" class="form-control modal-input col-md-8">
                                </div>
                                <div id="validation-info-title" class="offset-md-3 col-md-8 pt-1 pl-0 validation-info">required, please add text, maximum of 250 charackters</div>
                                <div id="error-message-title" class="alert alert-danger error-message offset-md-3 col-md-8 p-1" style="font-size: 0.9em; display:none;"></div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="description" class="col-form-label col-md-3">Description:</label>
                                    <input value="" type="text" name="description" maxlength="1000" id="description" class="form-control modal-input col-md-8">
                                </div>
                                <div id="validation-info-description" class="offset-md-3 col-md-8 pt-1 pl-0 validation-info">please add text, maximum of 1000 charackters</div>
                                <div id="error-message-description" class="alert alert-danger error-message offset-md-3 col-md-8 p-1" style="font-size: 0.9em; display:none;"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-sm btn-secondary"data-dismiss="modal">Close</button>
                                <button type="button" id="confirm_update_work_position_btn" class="btn-sm btn-primary">Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Potvrdit Vymazat Modal-->
        <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Confirm delete</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span class="font-weight-light" aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p class="confirm_delete_message" style="text-align: center;">Are you sure, you want to delete this work position with name: <br/> <span id="delete_title"></span> ?</p>
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
            let table = createTablePracovnaPozicia();
            tablePracovnaPoziciaData();
        });

        $.ajaxSetup({
            headers: {'Csrf-Token': '<?php echo $_SESSION['Csrf-Token']; ?>'}
        });

        $(document).delegate("#add_work_position_btn", "click", function(e){
            $('#add_work_position_modal_titulok').show();
            $('#edit_work_position_modal_titulok').hide();
            let id = $('#id').val('');
            let title = $('#title').val('');
            let description = $('#description').val('');
            $('#work_position_modal').modal('show');
        });

        $(document).delegate(".edit_row_btn", "click", function(e){
            $('#id').val($(this).data('id'));
            $('#title').val($(this).data('title'));
            $('#description').val($(this).data('description'));
            $('#add_work_position_modal_titulok').hide();
            $('#edit_work_position_modal_titulok').show();
            $('.error-message').html('').hide();
            $('#work_position_modal').modal('show');
        });

        $(document).delegate("#confirm_update_work_position_btn", "click", function(e){
            e.preventDefault();
            let id = $('#id').val();
            let title = $('#title').val();
            let description = $('#description').val();
            $.ajax({
                url: "/ajax/work_position/add-update-item.php",
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    title: title,
                    description: description,
                    is_ajax: 1
                },
                success: function(data) {
                    if (data.status === 'validation error') {
                        data['validation_error_msg']['title'] !== '' ? $('#error-message-title').html(data['validation_error_msg']['title']).fadeIn(200) : $('#error-message-title').html('').hide();
                        data['validation_error_msg']['description'] !== '' ? $('#error-message-description').html(data['validation_error_msg']['description']).fadeIn(200) : $('#error-message-description').html('').hide();
                    } else if (data.status === 'ok') {
                        tablePracovnaPoziciaData();
                        $('#work_position_modal').modal('hide');
                        $('.error-message').html('').hide();
                        $('#title').val('');
                        $('#description').val('');
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
            $('#delete_title').html($(this).data("title"));
            $(document).delegate("#confirmation-modal-delete-button", "click", function(e){
                $('#confirmationModal').modal('hide');
                $.ajax({
                    url:' /ajax/work_position/delete-item.php',
                    method: 'POST',
                    data: {
                        id: id,
                        is_ajax: 1
                    },
                    success: function (data) {
                        if (data === 'ok') {
                            $('#table_work_position').DataTable().row("#pp_" + id).remove().draw();
                            $('#work_position_modal').modal('hide');
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });
        });

        function tablePracovnaPoziciaData() {
            $.ajax({
                type: 'POST',
                dataType:' json',
                url: "/ajax/work_position/table-data.php",
                data: {
                    is_ajax: 1
                },
                success:function(data){
                    let table = createTablePracovnaPozicia(data.table_data);
                },
                error: function (error) {
                    console.log(error);
                }
            })
        }

        function createTablePracovnaPozicia(data) {

            $('#table_work_position').DataTable().clear().destroy();
            let table = $('#table_work_position').DataTable({
                "searching": true,
                "lengthChange": false,
                "bInfo" : false,
                "order": [[0, "asc"]],
                "pageLength": 12,
                "language": {
                    "searchPlaceholder": "#title #description",
                },
                "createdRow": function(row, data, dataIndex ) {
                    $(row).attr('id', 'pp_' + data.id);
                },
                "data": data,
                "columns": [
                    {"data": "title", "title": "title", "orderable": true, "searchable": true, "className": "text-center text-wrap", "width": "35%"},
                    {"data": "description", "title": "description", "orderable": true, "searchable": true, "className": "text-center text-wrap", "width": "35%"},
                    {"data": "edit", "title": "", "orderable": false, "searchable": false, "className": "text-center text-wrap", "width": "15%",
                        "render": function (data, type, row) {
                            return `<button type="button" data-id="${row.id}" data-title="${row.title}" data-description="${row.description}" class="btn-sm btn-success edit_row_btn">edit</button>`;
                        }
                    },
                    {"data": "delete", "title": "", "orderable": false, "searchable": false, "className": "text-center text-wrap", "width": "15%",
                        "render": function (data, type, row) {
                            return `<button type="button" data-id="${row.id}" data-title="${row.title}" class="btn-sm btn-danger delete_row_btn">delete</button>`;
                        }
                    },
                ],
            });
            return table;
        }
    
    </script>
    </body>
</html>

<?php 
require_once('includes/session.php');
require_once('includes/dbconnect.php');
require_once('includes/utils.php');
require_once('includes/functions.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web Development Workshop Demo</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="assets/vendor/bootstrap/bootstrap-4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/DataTables/datatables.min.css">
</head>
<body>
    <div class="container">
        <div class="d-flex">
            <h1 class="flex-grow-1">Welcome to the Dashboard</h1>
            <div class="p-2 d-flex justify-content-end">
                <a href="logout.php" class="btn btn-primary">Log Out</a>
            </div>
        </div>
        <div id="alert" class="mb-3"></div>
        <div class="mb-3">
            <button id="addUserButton" class="btn btn-success">Add User</button>
        </div>
        <div class="mb-3">
            <table id="usersTable" class="table table-striped table-sm" width="100%"></table>
        </div>
    </div>

    <!-- add user modal -->
    <form id="addUserForm" action="api/users.php">
        <div id="addUserModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Add User</h5>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="add_username" name="username" class="form-control" placeholder="Enter username">
                        </div>
                        <div class="form-group">
                            <label for="username">Password</label>
                            <input type="text" id="add_password" name="password" class="form-control" placeholder="Enter password">
                        </div>
                        <div class="form-group">
                            <label for="office">Office</label>
                            <input type="text" id="add_office" name="office" class="form-control" placeholder="Enter Office">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- edit form -->
    <form id="editUserForm" action="api/users.php">
        <input type="hidden" id="user_id" name="user_id">
        <div id="editUserModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Edit User</h5>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="edit_username" name="username" class="form-control" placeholder="Enter username">
                        </div>
                        <div class="form-group">
                            <label for="username">Password</label>
                            <input type="text" id="edit_password" name="password" class="form-control" placeholder="Enter password">
                        </div>
                        <div class="form-group">
                            <label for="office">Office</label>
                            <input type="text" id="edit_office" name="office" class="form-control" placeholder="Enter Office">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <script src="assets/vendor/jquery/jquery-3.4.1.min.js"></script>
    <script src="assets/vendor/bootstrap/bootstrap-4.3.1/js/bootstrap.min.js"></script>
    <script src="assets/vendor/DataTables/datatables.min.js"></script>
    <script type="text/javascript">

        // wait for the page to be ready
        $(function() {

            // helper functions for simplifying execution tasks that are often repeated.
            function displayMessage(response) {
                $('#alert').empty();
                // create alert
                $('<div></div>')
                    .addClass(`alert ${ response.success ? `alert-success` : `alert-danger` } alert-dismissable fade show`)
                    .append($(document.createTextNode(response.message)))
                    .append(
                        // create close button for alert
                        $('<button></button>')
                            .addClass('close')
                            .html('&times')
                            .attr('data-dismiss', 'alert')
                    )
                    .appendTo('#alert');
            }

            function reloadTable(table) {
                table.ajax.reload(null, false);
            }

            // ------------------------------------------------------------
            // DATA TABLE

            // creates the data table
            // must store into a variable for later reference
            // to be able to reload the table when data changes
            const userTable = $('#usersTable').DataTable({
                ajax: {
                    url: 'api/users.php',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'user_id', title: 'Id' },
                    { data: 'username', title: 'Username' },
                    { data: 'password', title: 'Password' },
                    { data: 'office', title: 'Office' },
                    { data: 'user_id', render: function(data, type, row, meta) {
                        return `
                            <button class="btn btn-primary" data-action="edit">Edit</button>
                            <button id="delete" class="btn btn-danger" data-action="delete">Delete</button>
                        `
                    }}
                ]
            })

            //--------------------------------------------------------------------
            // EVENT HANDLERS - User Interface events we will handle

            // handles click event on add button
            $('#addUserButton').on('click', function() {
                $('#addUserModal').modal('show');
            })

            // handles click event on delete button
            $('#usersTable').on('click', '[data-action="delete"]', function() {
                const parentRow = $(this).parents('tr').get(0);
                console.log(userTable.row(parentRow).data());
                const data = userTable.row(parentRow).data();

                if (confirm(`Are you sure you want to delete ${ data.username }?`)) {
                    $.ajax({
                        url: 'api/users.php',
                        method: 'DELETE',
                        data: {
                            user_id: data.user_id
                        }
                    })
                    .then(function(response) {
                        reloadTable(userTable);
                        displayMessage(response);
                    })
                }
            })

            // handles click event on edit button
            $('#usersTable').on('click', '[data-action="edit"]', function() {
                const parentRow = $(this).parents('tr').get(0);
                const data = userTable.row(parentRow).data();
                $('#editUserForm #user_id').val(data.user_id);
                $('#editUserForm #edit_username').val(data.username);
                $('#editUserForm #edit_password').val(data.password);
                $('#editUserForm #edit_office').val(data.office);
                $('#editUserModal').modal('show');

            })

            //---------------------------------------------------
            // FORM SUBMISSION EVENTS

            // handles submit event of add user form
            $('#addUserForm').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                if (confirm('Are you sure you want to add this user?')) {
                    $.ajax({
                        url: e.target.action,
                        method: 'post',
                        data: $(this).serialize()
                    })
                    .then(function(response) {
                        reloadTable(userTable);
                        displayMessage(response);
                        $('.modal.show').modal('hide');
                        form.reset();
                    })
                }
            })

            // handles submit event of edit user form
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                if (confirm(`Are you sure you want to update this record?`)) {
                    $.ajax({
                        url: 'api/users.php',
                        method: 'PUT',
                        data: $(this).serialize()
                    })
                    .then(function(response) {
                        reloadTable(userTable);
                        displayMessage(response);
                        $('.modal.show').modal('hide');
                        form.reset();
                    })
                }
            })
        })
    </script>
</body>
</html>
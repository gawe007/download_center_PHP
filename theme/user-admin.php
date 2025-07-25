<div class="container-fluid">
    <?php include("offcanvas-adminmenu.php"); ?>
    <section class="row">
        <div class="col-12 col-md-10 p-3" style="min-width: 0; flex: 1 1 auto;">
            <?php include("header-admin.php")?>
            <div class="d-block">
                <p class="display-3">User Management</p>
                <hr noshade/>
                <div class="d-flex flex-wrap p-2 mb-3">
                    <button id="btnAddUser" class="btn btn-lg btn-primary fw-bold text-light">+ New User</button>
                </div>
                <div class="p-2 pe-5 table-responsive">
                <table id="userTable" class="table m-2 w-100 mb-2">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Name</td>
                            <td>E-mail</td>
                            <td>Level</td>
                            <td>Actions</td>
                        </tr>
                    </thead>
                    <?php
                    require_once("bin/entity/user.php");
                    $user = new user(null);
                    $data = $user->getUsers();
                    if(count($data) < 1){
                        echo "<tbody><tr><td colspan='4' class='text-center'>No Data Loaded</td></tr></tbody>";
                        exit();
                    }
                
                    echo "<tbody>";
                    foreach($data as $row){
                        $translated = $user::translateLevel($row['level']);
                        echo "<tr id='row_{$row['id']}'><td>{$row['id']}</td><td class='username-{$row['id']}'>{$row['name']}</td><td class='useremail-{$row['id']}'>{$row['email']}</td><td class='userlevel-{$row['id']}'>{$translated}</td><td><div class='d-flex justify-content-around'><button class='btn btn-sm btn-warning text-dark btn-edit' data-id='".$row['id']."' data-level='{$row['level']}'>Edit</button><button class='btn btn-sm btn-primary text-white btn-pass' data-id='".$row['id']."' >Change Password</button></div></td></tr>";
                    }
                    echo "</tbody>";
                    ?>
                </table>
                <small>
                    User Deletion can only be done directly on the DB.
                </small>

                <div id="modalAdd" class="modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm" action="#" method="post">
                        <div class="mb-3">
                            <label for="userEmailNew" class="form-label">Email address</label>
                            <input type="email" class="form-control" name="email" id="userEmailNew" placeholder="contoh@email.com" maxlength="50" required>
                        </div>
                        <div class="mb-3">
                            <label for="userPasswordNew" class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" id="userPasswordNew" placeholder="********" maxlength="12" required>
                        </div>
                        <div class="mb-3">
                            <label for="userNameNew" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="userNameNew" placeholder="John Smith" maxlength="30" required>
                        </div>
                        <div class="mb-3">
                            <label for="userLevelNew" class="form-label">Level</label>
                            <select name="userLevelNew" id="userLevelNew" class="form-select" aria-label="Select user level" required>
                                <option value="1">Normal</option>
                                <option value="2">Admin</option>
                                <option value="3">System</option>
                            </select>
                        </div>
                    </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submitAddBtn">Save</button>
                    </div>
                    </div>
                </div>
                </div>

                <div id="modalEdit" class="modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" action="#" method="post">
                        <input type="hidden" name="id" id="userId" value="null"/>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" name="email" id="userEmail" placeholder="contoh@email.com" maxlength="50" required>
                        </div>
                        <div class="mb-3">
                            <label for="userName" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="userName" placeholder="John Smith" maxlength="30" required>
                        </div>
                        <div class="mb-3">
                            <label for="userLevel" class="form-label">Level</label>
                            <select name="userLevel" id="userLevel" class="form-select" aria-label="Select user level" required>
                                <option value="1">Normal</option>
                                <option value="2">Admin</option>
                                <option value="3">System</option>
                            </select>
                        </div>
                    </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submitEditBtn">Save changes</button>
                    </div>
                    </div>
                </div>
                </div>
                <div id="modalPassword" class="modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="passwordForm" action="#" method="post">
                        <input type="hidden" name="id" id="passId"/>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" id="userPassword" placeholder="********" maxlength="12" required>
                        </div>
                    </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submitPassBtn">Save changes</button>
                    </div>
                    </div>
                </div>
                </div>

                <div id="modalDelete" class="modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="p-3 text-center fs-3">Carefull!! This action is permanent!</div>
                        <form id="deleteForm" action="#" method="post">
                        <input type="hidden" name="id" id="deleteId"/>
                        <input type="hidden" name="deleteUserLevel" id="deleteUserLevel"/>
                        <div class="mb-3">
                            <label for="userPasswordDelete" class="form-label">Insert Your Password</label>
                            <input type="password" name="password" class="form-control" id="userPasswordDelete" placeholder="********" maxlength="12" required>
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" id="submitDeleteBtn">Delete</button>
                    </div>
                    </div>
                </div>
                </div>

                <script type="text/javascript">
                const token = '<?= htmlspecialchars(base64_encode($global['auth_token']))?>';
                let translatedLevel = {
                    '1' : 'Normal',
                    '2' : 'Admin',
                    '3' : 'System'
                }
                $(document).ready(function() {
                    // Initialize DataTable
                    const table = new DataTable('#userTable', {
                        columnDefs: [{
                        searchable: false,
                        orderable: false,
                        targets: 0
                        }]
                    });

                    // Auto-index first column
                    table.on('order.dt search.dt', () => {
                        table.column(0, { order:'applied', search:'applied' })
                        .nodes()
                        .each((cell, i) => cell.innerText = i + 1);
                    }).draw();

                    // Cache modals
                    const editUserModal = new bootstrap.Modal($('#modalEdit'));
                    const editPassModal = new bootstrap.Modal($('#modalPassword'));
                    const deleteModal = new bootstrap.Modal($('#modalDelete'));
                    const addModal = new bootstrap.Modal($('#modalAdd'));

                    $('#btnAddUser').on('click', function(){
                        addModal.show();
                    });

                    $('#submitAddBtn').on('click', function(){
                        $(this).prop('disabled', true);
                        let name = $('#userNameNew').val();
                        let email = $('#userEmailNew').val();
                        let password = $('#userPasswordNew').val();
                        let level = $('#userLevelNew').val();
                        $.ajax({
                            type: "POST",
                            url: '<?= $global['full_url']?>/bin/api_login.php',
                            contentType: "application/json",
                            headers: {
                                "Authorization": "Bearer " + token
                            },
                            credentials: 'include',
                            data: JSON.stringify({action: "add_user", data: {level: level, name: name, email: email, password: password}}),
                            success : function (response){
                                if(response.status){
                                    window.location.reload();
                                }
                                $(this).prop('disabled', false);
                            },
                            error : function(response){
                                Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: "Save failed" + response.message,
                                        });
                                $(this).prop('disabled', false);
                                }
                            });
                        $('#addForm')[0].reset();
                        if (document.activeElement) {
                            document.activeElement.blur();
                        }
                        $('#modalAdd').modal('hide');
                    });

                    // Delegate Edit button click
                    $('#userTable').on('click', '.btn-edit', function() {
                        const id = $(this).data('id');
                        const level = $(this).data('level');
                        $('#userId').val(id);
                        $('#userEmail').val($('.useremail-' + id).text());
                        $('#userName').val($('.username-' + id).text());
                        $('#userLevel').val(level);
                        editUserModal.show();
                    });

                    // Delegate Change Password button click
                    $('#userTable').on('click', '.btn-pass', function() {
                        const id = $(this).data('id');
                        $('#passId').val(id);
                        $('#userPassword').val('');
                        editPassModal.show();
                    });

                    $('#userTable').off('click', '.btn-delete').on('click', '.btn-delete', function() {
                        const id = $(this).data('id');
                        const rawLevel = $(this).data('level');
                        const level = Number(rawLevel);
                        if(level == 3){
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Not Authorized",
                            });
                        }else{
                            deleteModal.show();
                        }
                    });


                    $('#submitEditBtn').on('click', function(){
                        $(this).prop('disabled', true);
                        let id = $('#userId').val();
                        let name = $('#userName').val();
                        let email = $('#userEmail').val();
                        let level = $('#userLevel').val();
                        console.log("level: " + level);
                        $.ajax({
                            type: "POST",
                            url: '<?= $global['full_url']?>/bin/api_login.php',
                            contentType: "application/json",
                            headers: {
                                "Authorization": "Bearer " + token
                            },
                            credentials: 'include',
                            data: JSON.stringify({action: "update_user", data: {level: level, id: id, name: name, email: email}}),
                            success : function (response){
                                if(response.status){
                                    Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: "Saved!",
                                    });
                                    table.cell('#row_' + id, 1).data(name).draw();
                                    table.cell('#row_' + id, 2).data(email).draw();
                                    table.cell('#row_' + id, 3).data(translatedLevel[level.toString()]).draw();
                                }
                                $(this).prop('disabled', false);
                            },
                            error : function(response){
                                Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: "Save failed" + response.message,
                                        });
                                $(this).prop('disabled', false);
                                }
                            });
                        $('#editForm')[0].reset();
                        if (document.activeElement) {
                            document.activeElement.blur();
                        }
                        $('#modalEdit').modal('hide');
                    });

                    $('#submitPassBtn').on('click', function(){
                        let id = $('#passId').val();
                        let password = $('#userPassword').val();
                        $.ajax({
                            type: "POST",
                            url: '<?= $global['full_url']?>/bin/api_login.php',
                            contentType: "application/json",
                            headers: {
                                "Authorization": "Bearer " + token
                            },
                            credentials: 'include',
                            data: JSON.stringify({action: "update_user_password", data: {id: id, password: password}}),
                            success : function (response){
                                if(response.status){
                                    Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: "Saved!",
                                    });
                                }
                                $(this).prop('disabled', false);
                            },
                            error : function(error){
                                Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: "Save failed " + error,
                                        });
                                $(this).prop('disabled', false);
                                }
                            });
                        $('#passwordForm')[0].reset();
                        if (document.activeElement) {
                            document.activeElement.blur();
                        }
                        $('#modalPassword').modal('hide');
                    });

                    $('#submitDeleteBtn').on('click', function(){
                        let id = $('#deleteId').val();
                        let password = $('#userPasswordDelete').val();
                        $.ajax({
                            type: "POST",
                            url: '<?= $global['full_url']?>/bin/api_login.php',
                            contentType: "application/json",
                            headers: {
                                "Authorization": "Bearer " + token
                            },
                            credentials: 'include',
                            data: JSON.stringify({action: "delete_user", data: {id: id, password: password}}),
                            success : function (response){
                                if(response.status){
                                    Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: "Saved!",
                                    });
                                }
                                $(this).prop('disabled', false);
                            },
                            error : function(error){
                                console.log(error);
                                Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: "Save failed ",
                                        });
                                $(this).prop('disabled', false);
                                }
                            });
                        $('#deleteForm')[0].reset();
                        if (document.activeElement) {
                            document.activeElement.blur();
                        }
                        $('#modalDelete').modal('hide');
                    });

                });

                </script>
                </div>
            </div>
        </div>
    </section>
</div>
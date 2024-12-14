<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .error {
            color: red;
            font-size: 0.875em;
            margin-top: 5px;
        }
        .is-invalid {
            border-color: red;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>User Management</h1>

    <form id="userForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="mb-3 col col-6">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" class="form-control" name="name" required placeholder="Name">
            </div>
            <div class="mb-3 col col-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" class="form-control" name="email" required placeholder="Email">
            </div>
            <div class="mb-3 col col-6">
                <label for="phone" class="form-label">Phone No.</label>
                <input type="text" id="phone" class="form-control" name="phone" required placeholder="Phone No. +91">
            </div>
            <div class="mb-3 col col-6">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" class="form-control" name="description" placeholder="Description"></textarea>
            </div>
            <div class="mb-3 col col-6">
                <label for="role_id" class="form-label">Role</label>
                <select id="role_id" class="form-select" name="role_id">
                    <option value="">Select Role</option>
                    @foreach($roles as $roleName)
                        <option value="{{$roleName->id}}">{{$roleName->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col col-6">
                <label for="profile_image" class="form-label">Profile Image</label>
                <input type="file" id="profile_image" accept="image/png, image/jpg, image/jpeg" class="form-control" name="profile_image">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <hr>

    <h3>All Users</h3>
    <table class="table table-bordered" id="usersTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Description</th>
                <th>Role</th>
                <th>Profile Image</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->description }}</td>
                    <td>{{$user->getRole->name ?? ''}}</td>
                    <td><img src="{{ asset('images/'.$user->profile_image) }}" width="50"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        
        $("#userForm").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255
                },
                email: {
                    required: true,
                    email: true,
                },
                phone: {
                    required: true,
                },
                description: {
                    required: true,
                    maxlength: 500
                },
                role_id: {
                    required: true
                },
                profile_image: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: "Please enter a name.",
                    maxlength: "Name should not exceed 255 characters."
                },
                email: {
                    required: "Please enter an email address.",
                    email: "Please enter a valid email address.",
                },
                phone: {
                    required: "Please enter a phone number.",
                    pattern: "Please enter a valid Indian phone number starting with 7, 8, or 9."
                },
                description: {
                    maxlength: "Description should not exceed 500 characters."
                },
                role_id: {
                    required: "Please select a role."
                },
                profile_image: {
                    required: "Please upload a profile image.",
                    extension: "Only image files are allowed (jpeg, png, jpg, gif).",
                    filesize: "Image size should not exceed 2MB."
                }
            },
            submitHandler: function(form) {
                event.preventDefault();

                let formData = new FormData(form);
                $.ajax({
                    url: 'api/v1/user/create',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.error) {
                            // console.log(response.error);
                            Swal.fire('Error', response.message);
                        } else {
                            Swal.fire('Success', response.success, 'success');
                            
                            // console.log(response.user);
                            $('#usersTable tbody').append(`
                                <tr>
                                    <td>${response.user.name}</td>
                                    <td>${response.user.email}</td>
                                    <td>${response.user.phone}</td>
                                    <td>${response.user.description}</td>
                                    <td>${response.user.role_id}</td>
                                    <td><img src="/images/${response.user.profile_image}" width="50"></td>
                                </tr>
                            `);
                            
                            form.reset();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Something went wrong!', 'error');
                    }
                });

                return false; 
            }
        });
    });
</script>

</body>
</html>

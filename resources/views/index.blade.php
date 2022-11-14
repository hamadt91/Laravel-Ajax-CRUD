<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- toaster -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <title>Laravel Ajax</title>
</head>
<body>

<!-- Add Book -->
<div class="container mt-5">
    <h1>Laravel Crud using Ajax</h1>
    <form action="{{route('book.store')}}" method="post" id="reset_form">
        @csrf
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Title</label>
            <input type="text" class="form-control" name="title">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Author</label>
            <input type="text" class="form-control" name="author">
        </div>
        <button type="button" class="btn btn-primary" id="submit_button">Submit</button>
    </form>
</div>

<!-- list -->
<div class="container">
    <table class="table table-dark table-striped mt-5">
        <thead>
        <tr>
            <th scope="col">Title</th>
            <th scope="col">Author</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
        </tr>
        </thead>
        <tbody id="test">
        @foreach($books as $book)
            <tr id="{{$book->id}}">
                <td>{{$book->title}}</td>
                <td>{{$book->author}}</td>
                <td>
                    <button type="button" class="btn btn-primary edit" data-bs-toggle="modal"
                            data-bs-target="#exampleModal" data-id="{{$book->id}}">
                        Edit
                    </button>
                </td>
                <td>
                    <button type="button" class="btn btn-danger delete" data-id="{{$book->id}}">
                        Delete
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Title </label>
                    <input type="text" class="form-control" name="model_title" value="">
                </div>
                <div class="mb-3">
                    <label class="form-label">Author</label>
                    <input type="text" class="form-control" name="model_author" value="">
                </div>
                <button type="button" class="btn btn-primary" id="update_button">Update</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<!-- toaster -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    @if(\Illuminate\Support\Facades\Session::has('success'))
    toastr.success("{{\Illuminate\Support\Facades\Session::get('success')}}", 'success', {timeOut: 3000});
    @endif
    @if(\Illuminate\Support\Facades\Session::has('error'))
    toastr.error('{{\Illuminate\Support\Facades\Session::get('error')}}', 'error', {timeOut: 3000});
    @endif
</script>

<script>

    $(document).ready(function () {
        let id = '';
        let c = '';
        //Create
        $(document).on('click', '#submit_button', function () {
            let title = $("input[name=title]").val();
            let author = $("input[name=author]").val();
            let url = "{{route('book.store')}}";
            if (title != '' && author != '') {
                let data = {
                    "_token": "{{ csrf_token() }}",
                    'title': title,
                    'author': author
                }
                $.post(url, data, function (response) {
                    if (response.status === 'success') {
                        document.getElementById("reset_form").reset();
                        c = '<tr id="' + response.data.id + '">' +
                            '<td>' + response.data.title + '</td>' +
                            '<td>' + response.data.author + '</td>' +
                            '<td>' +
                            '<button type="submit" class="btn btn-primary edit" data-id=' + response.data.id + ' data-bs-toggle="modal" data-bs-target="#exampleModal">' +
                            'Edit' +
                            '</button>' +
                            '</td>' +
                            '<td>' + '<button type="submit" class="btn btn-danger delete" data-id=' + response.data.id + ' >' + 'delete' + '</button>' + '</td>' +
                            +'</tr>';
                        $('#test').prepend(c);
                    }
                });
            } else {
                toastr.error('Please fill all the required fields', 'error');
            }
        });

        //Edit
        $(document).on('click', '.edit', function () {
            id = $(this).data("id");
            let url = "{{route('book.edit', 'id')}}";
            url = url.replace('id', id);
            $.get(url, function (response) {
                if (response.status === 'success') {
                    let title = $("input[name=model_title]").val(response.data.title);
                    let author = $("input[name=model_author]").val(response.data.author);
                }
            });
        });

        //Update
        $(document).on('click', '#update_button', function () {
            let title = $("input[name=model_title]").val();
            let author = $("input[name=model_author]").val();
            let url = "{{route('book.update','id')}}";
            url = url.replace('id', id);
            if (title != '' && author != '') {
                let data = {
                    "_token": "{{ csrf_token() }}",
                    'model_title': title,
                    'model_author': author
                }
                $.post(url, data, function (response) {
                    if (response.status === 'success') {
                        $(function () {
                            $('#exampleModal').modal('toggle');
                        });
                        c = '<td>' + response.data.title + '</td>' +
                            '<td>' + response.data.author + '</td>' +
                            '<td>' +
                            '<button type="submit" class="btn btn-primary edit" data-id=' + response.data.id + ' data-bs-toggle="modal" data-bs-target="#exampleModal">' +
                            'Edit' +
                            '</button>' +
                            '</td>' +
                            '<td>' + '<button type="submit" class="btn btn-danger delete" data-id=' + response.data.id + '>' + 'delete' + '</button>' + '</td>';
                        $("#" + response.data.id).empty().prepend(c);
                    }
                });
            } else {
                toastr.error('Please fill all the required fields ', 'error');
            }
        });

        //Delete
        $(document).on('click', '.delete', function () {
            let delete_id = $(this).data("id");
            let url = "{{route('book.destroy','id')}}";
            url = url.replace('id', delete_id);
            let data = {
                "_token": "{{ csrf_token() }}",
            }
            $.post(url, data, function (response) {
                if (response.status === 'success') {
                    $("#" + response.data).empty();
                }
            });
        });
    });
</script>
</body>
</html>

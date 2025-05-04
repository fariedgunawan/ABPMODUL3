@extends('layouts.app') {{-- pastikan kamu punya layout ini --}}
@section('content')
<div class="container mt-4">
    <h2>Book Management</h2>
    <button type="button" class="btn btn-success mb-3" id="createNewBook">Add Book</button>
    <table class="table table-bordered" id="bookTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Author</th>
                <th>Title</th>
                <th>Year</th>
                <th width="150px">Action</th>
            </tr>
        </thead>
    </table>
</div>

{{-- Modal --}}
<div class="modal fade" id="bookModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="bookForm" name="bookForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="bookModalLabel">Add Book</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="book_id" id="book_id">
          <div class="mb-3">
              <label>Author</label>
              <select class="form-select" name="author_id" id="author_id" required>
                  <option value="">-- Select Author --</option>
                  @foreach ($authors as $author)
                      <option value="{{ $author->id }}">{{ $author->name }}</option>
                  @endforeach
              </select>
          </div>
          <div class="mb-3">
              <label>Title</label>
              <input type="text" class="form-control" id="title" name="title" required>
          </div>
          <div class="mb-3">
              <label>Year</label>
              <input type="number" class="form-control" id="year" name="year" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    var table = $('#bookTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('books.getBooks') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'author.name', name: 'author.name'},
            {data: 'title', name: 'title'},
            {data: 'year', name: 'year'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#createNewBook').click(function () {
        $('#bookForm').trigger("reset");
        $('#bookModalLabel').html("Add Book");
        $('#book_id').val('');
        $('#bookModal').modal('show');
    });

    $('body').on('click', '.editBook', function () {
        var id = $(this).data('id');
        $.get("{{ url('books') }}" + '/' + id, function (data) {
            $('#bookModalLabel').html("Edit Book");
            $('#bookModal').modal('show');
            $('#book_id').val(data.id);
            $('#title').val(data.title);
            $('#year').val(data.year);
            $('#author_id').val(data.author_id);
        })
    });

    $('#bookForm').on('submit', function (e) {
        e.preventDefault();
        $('#saveBtn').html('Saving...');
        $.ajax({
            data: $('#bookForm').serialize(),
            url: "{{ route('books.store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                $('#bookForm').trigger("reset");
                $('#bookModal').modal('hide');
                table.draw();
                $('#saveBtn').html('Save');
            },
            error: function (data) {
                alert('Validation failed. Please check the fields.');
                $('#saveBtn').html('Save');
            }
        });
    });

    $('body').on('click', '.deleteBook', function () {
        var id = $(this).data("id");
        if (confirm("Are you sure want to delete this book?")) {
            $.ajax({
                type: "DELETE",
                url: "{{ url('books') }}" + '/' + id,
                success: function (data) {
                    table.draw();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });
});
</script>
@endsection

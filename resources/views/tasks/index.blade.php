<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">PHP - Simple To Do List App</h4>
            </div>
            <div class="card-body">
                <form id="task-form" class="d-flex mb-3">
                    <input type="text" id="task-input" class="form-control" placeholder="Enter task" required>
                    <button type="submit" class="btn btn-primary ms-2">Add Task</button>
                </form>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Task</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="task-list">
                        @foreach($tasks as $index => $task)
                        <tr data-id="{{ $task->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $task->description }}</td>
                            <td>
                                <span class="badge {{ $task->is_completed ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $task->is_completed ? 'Done' : 'Pending' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm complete-task" {{ $task->is_completed ? 'disabled' : '' }}>
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-danger btn-sm delete-task">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $('#task-form').on('submit', function(e) {
            e.preventDefault();
            let taskDescription = $('#task-input').val();
            
            $.ajax({
                url: "{{ route('tasks.store') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    description: taskDescription
                },
                success: function(response) {
                    let taskHtml = `
                        <tr data-id="${response.task.id}">
                            <td>${$('#task-list tr').length + 1}</td>
                            <td>${response.task.description}</td>
                            <td>
                                <span class="badge bg-secondary">Pending</span>
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm complete-task">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-danger btn-sm delete-task">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    $('#task-list').append(taskHtml);
                    $('#task-input').val('');
                }
            });
        });

        $(document).on('click', '.complete-task', function() {
            let taskId = $(this).closest('tr').data('id');
            
            $.ajax({
                url: `/tasks/${taskId}/complete`,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function() {
                    let row = $(`tr[data-id="${taskId}"]`);
                    row.find('td:eq(2) .badge').removeClass('bg-secondary').addClass('bg-success').text('Done');
                    row.find('.complete-task').prop('disabled', true);
                }
            });
        });

        // Delete task with confirmation
        $(document).on('click', '.delete-task', function() {
            if (!confirm('Are you sure to delete this task?')) return;

            let taskId = $(this).closest('tr').data('id');

            $.ajax({
                url: `/tasks/${taskId}`,
                method: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function() {
                    $(`tr[data-id="${taskId}"]`).remove();
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

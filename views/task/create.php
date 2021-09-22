<div class="row m-3">
    <div class="col-md-2"></div>
    <div class="col-md-8 bg-light rounded-lg">
        <form method="POST">
            <h1 class="text-center">Add Task</h1>
            <div class="form-group">
                <label for="example1">Name</label>
                <input type="text" class="form-control" id="example1" name="name" value="<?= $task->name ?>">
            </div>
            <div class="form-group">
                <label for="example2">Position</label>
                <input type="number" class="form-control" id="example2" name="position" min="0" max="100" value="<?= $task->position ?>">
            </div>
            <button type="submit" class="btn btn-primary mb-1">Save</button>
        </form>
    </div>
</div>
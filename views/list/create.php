<div class="row m-3">
    <div class="col-md-2"></div>
    <div class="col-md-8 bg-light rounded-lg">
        <form method="POST">
            <h1 class="text-center">Insert / Update TODO LIST</h1>
            <div class="form-group">
                <label for="exampleInputEmail1">Name</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="name" value="<?= $list->name ?>">
            </div>
            <button type="submit" class="btn btn-primary mb-1">Save</button>
        </form>
    </div>
</div>
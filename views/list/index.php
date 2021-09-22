<div class="row m-1">
    <div class="col-md-1"></div>
    <div class="col-md-8">
        <h1 class="text-center">TODO LISTs</h1>
        <a href="/list/create" class="btn btn-warning mb-1">Create</a>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col" class="w-50">Name</th>
                    <th scope="col" class="w-25">Date started</th>
                    <th scope="col" class="w-25">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $index = 1;
                foreach ($lists as $list) {
                ?>
                    <tr>
                        <th scope="row"><?= $index ?></th>
                        <td>
                            <a href="/task/index/?list_id=<?= $list->id ?>">
                                <?= $list->name ?>
                            </a>
                        </td>
                        <td><?= $list->created_at ?></td>
                        <td>
                            <a href="/list/delete/?list_id=<?= $list->id ?>" class="btn btn-danger">Delete</a>
                            <a href="/list/update/?list_id=<?= $list->id ?>" class="btn btn-primary">Edit</a>
                        </td>
                    </tr>
                <?php
                    $index++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
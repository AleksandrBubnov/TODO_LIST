<?php
$url = $_SERVER['REQUEST_URI'];
$url = explode('/', $url);
$url[2] = 'completed';
$url = implode('/', $url);
$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $url;

$order = null;
if (!empty($_GET['order'])) {
    $order = $_GET['order'];
    $order = "order=$order&";
}
?>
<div class="row m-1">
    <div class="col-md-1"></div>
    <div class="col-md-8">
        <h1 class="text-center"><?= $list->name ?></h1>
        <a href="/task/create/?<?= $order ?>list_id=<?= $list->id ?>" class="btn btn-warning mb-1">Create</a>
        <table class="table table-striped table-hover table-bordered table-dark">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col" class="w-25"><a href="/task/index/?order=name&list_id=<?= $list->id ?>">Name</a></th>
                    <th scope="col" class="w-25 text-center"><a href="/task/index/?order=position&list_id=<?= $list->id ?>">Position</a></th>
                    <th scope="col" class="w-25">Date started</th>
                    <th scope="col" class="w-15 text-center">Completed</th>
                    <th scope="col" class="w-25">Date completed</th>
                    <th scope="col" class="w-25">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $index = 1;
                if ($tasks) {
                    foreach ($tasks as $task) {
                ?>
                        <tr>
                            <th scope="row"><?= $index ?></th>
                            <td> <?= $task->name ?> </td>
                            <td name="position" class="text-center">
                                <a href="/task/position/?<?= $order ?>list_id=<?= $list->id ?>&task_id=<?= $task->id ?>&direction=lArr">
                                    &lArr;
                                </a>
                                <?= $task->position ?>
                                <a href="/task/position/?<?= $order ?>list_id=<?= $list->id ?>&task_id=<?= $task->id ?>&direction=rArr">
                                    &rArr;
                                </a>
                            </td>
                            <td> <?= $task->created_at ?> </td>
                            <td>
                                <div class="form-group form-check text-center">
                                    <?php
                                    if ($task->completed) {
                                        $check = 'checked="checked"';
                                    } else {
                                        $check = null;
                                    }
                                    ?>
                                    <input type="checkbox" <?= $check ?> value="<?= $task->id ?>" onchange="changeCompleted(event,this)" class="form-check-input check_completed" name="completed">
                                </div>
                            </td>
                            <td name="completed_at"> <?= $task->completed_at ?> </td>
                            <td>
                                <a href="/task/delete/?<?= $order ?>list_id=<?= $list->id ?>&task_id=<?= $task->id ?>" class="btn btn-danger">Delete</a>
                                <a href="/task/update/?<?= $order ?>list_id=<?= $list->id ?>&task_id=<?= $task->id ?>" class="btn btn-primary">Edit</a>
                            </td>
                        </tr>
                <?php
                        $index++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function changeCompleted(event, element) {
        event.preventDefault();
        let isChecked = 0;
        if (!element) return event.preventDefault();
        if (element.hasAttribute("checked")) {
            element.removeAttribute("checked");
            isChecked = 0;
        } else {
            element.setAttribute("checked", "checked");
            isChecked = 1;
        }
        newData(element, isChecked);
    }

    function newData(element, completed) {
        let xmlhttp = new XMLHttpRequest();
        let url = <?php echo json_encode($url); ?> + "&task_id=" + element.value + "&completed=" + completed;
        var text = '';

        let nodes = element.parentElement.parentElement.parentElement.childNodes;
        let node_completed_at;
        if (nodes || nodes.length > 0) {
            let length = nodes.length;
            for (let i = 0; i < length; i++) {
                if (nodes[i].nodeName == 'TD' &&
                    nodes[i].hasAttribute("name") &&
                    nodes[i].getAttribute("name") == "completed_at") {
                    node_completed_at = nodes[i];
                }
            }
        }

        xmlhttp.open('GET', url, true);
        xmlhttp.onreadystatechange = function() {
            if (this.status == 200 && this.readyState == 4) {
                if (this.responseText) {
                    let text = JSON.parse(this.responseText);
                    node_completed_at.innerText = text.completed_at;
                }
            }
        }
        xmlhttp.send();
    }
</script>
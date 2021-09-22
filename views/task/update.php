<div class="row m-3">
    <div class="col-md-2"></div>
    <div class="col-md-8 bg-light rounded-lg">
        <form id="edit_task" method="POST">
            <h1 class="text-center">Edit Task</h1>
            <div class="form-group">
                <label for="example1">Name</label>
                <input type="text" class="form-control" id="example1" name="name" value="<?= $task->name ?>">
            </div>
            <div class="form-group">
                <label for="example2">Position</label>
                <input type="number" class="form-control" id="example2" name="position" min="0" max="100" value="<?= $task->position ?>">
            </div>
            <div class="form-group form-check">
                <?php
                if ($task->completed) {
                    $check = 'checked="checked"';
                } else {
                    $check = null;
                }
                ?>
                <!-- <input type="checkbox" <?= $check ?> value="<?= $task->completed ?>" class="form-check-input check_completed" id="example3" name="vis_example3" /> -->
                <input type="checkbox" <?= $check ?> value="<?= $task->id ?>" onchange="changeCompleted(event,this)" class="form-check-input check_completed" id="example3" name="vis_example3" />
                <label class="form-check-label" for="example3">Completed</label>
                <!-- <input type="text" hidden class="form-control" name="completed" id="unvis_example3" value="<?= $task->completed ?>"> -->
            </div>
            <button type="submit" class="btn btn-outline-success mb-1 btn-sm btn-block">Save</button>
        </form>
    </div>
</div>
<script type="text/javascript">
</script>

<?php
$url = $_SERVER['REQUEST_URI'];
$url = explode('/', $url);
$url[2] = 'completed';
$url = implode('/', $url);
$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $url;
?>

<script type="text/javascript">
    // window.onload = function() {
    //     let input = document.getElementById("example3");
    //     let unvis_input = document.getElementById("unvis_example3");

    //     input.onchange = function(event) {
    //         if (!input) return event.preventDefault();
    //         if (input.hasAttribute("checked")) {
    //             input.removeAttribute("checked");
    //             input.setAttribute("value", "0");
    //             unvis_input.setAttribute("value", "0");
    //         } else {
    //             input.setAttribute("checked", "checked");
    //             input.setAttribute("value", "1");
    //             unvis_input.setAttribute("value", "1");
    //         }
    //     }
    // }

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

        xmlhttp.open('GET', url, true);
        xmlhttp.onreadystatechange = function() {
            if (this.status == 200 && this.readyState == 4) {
                if (this.responseText) {
                    //
                }
            }
        }
        xmlhttp.send();
    }
</script>
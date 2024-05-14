<?php
    session_start();

    $todoList = isset($_SESSION["todoList"]) ? $_SESSION["todoList"] : [];
    $finishedList = isset($_SESSION["finishedList"]) ? $_SESSION["finishedList"] : [];

    function appendData($data, $list) {
        if (!in_array($data, $list)) {
            array_push($list, $data);
        } else {
            echo '<script>alert("Error: Task already exists")</script>';
        }
        return $list;
    }

    function deleteData($toDelete, $list) {
        if (($key = array_search($toDelete, $list)) !== false) {
            unset($list[$key]);
        }
        return $list;
    }

    function displayTasks($todoList) {
        foreach ($todoList as $task) {
            echo '<div class="d-flex p-2 bd-highlight w-100 justify-content-between">';
            echo '<li class="list-group-item w-100">';
            echo '<input type="checkbox" onclick="finishTask(this, \'' . $task . '\')"> ' . $task;
            echo '</li>';
            echo '<a href="index.php?delete=true&task=' . urlencode($task) . '" class="btn btn-danger">Delete</a>';
            echo '</div>';
        }
    }

    function displayFinishedTasks($finishedList) {
        foreach ($finishedList as $task) {
            echo '<div class="d-flex p-2 bd-highlight w-100 justify-content-between">';
            echo '<li class="list-group-item w-100 completed">';
            echo '<input type="checkbox" checked disabled> ' . $task;
            echo '</li>';
            echo '<a href="index.php?delete=true&task=' . urlencode($task) . '&finished=true" class="btn btn-danger">Delete</a>';
            echo '</div>';
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["task"])) {
        $todoList = appendData($_POST["task"], $todoList);
        $_SESSION["todoList"] = $todoList;
    }

    if (isset($_GET['task'])) {
        $task = $_GET['task'];
        if (isset($_GET['finished']) && $_GET['finished'] == 'true') {
            $finishedList = deleteData($task, $finishedList);
            $_SESSION["finishedList"] = $finishedList;
        } else {
            $todoList = deleteData($task, $todoList);
            $_SESSION["todoList"] = $todoList;
        }
    }

    if (isset($_GET['finish']) && $_GET['finish'] == 'true' && isset($_GET['task'])) {
        $task = $_GET['task'];
        $todoList = deleteData($task, $todoList);
        $finishedList = appendData($task, $finishedList);
        $_SESSION["todoList"] = $todoList;
        $_SESSION["finishedList"] = $finishedList;
        header('Location: index.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple To-Do List</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .completed {
            text-decoration: line-through;
            color: grey;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">To-Do List</h1>
        <div class="card">
            <div class="card-header">Add a new task</div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <input type="text" class="form-control" name="task" placeholder="Enter your task here">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Tasks</div>
            <ul class="list-group list-group-flush" id="task-list">
            <?php
                displayTasks($todoList);
            ?>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">Tasks Finished</div>
            <ul class="list-group list-group-flush" id="finished-list">
            <?php
                displayFinishedTasks($finishedList);
            ?>
            </ul>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function finishTask(checkbox, task) {
            if (checkbox.checked) {
                window.location.href = 'index.php?finish=true&task=' + encodeURIComponent(task);
            }
        }
    </script>
</body>
</html>

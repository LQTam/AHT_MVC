<?php

namespace MVC\Controllers;

use MVC\Models\Task;
use MVC\Core\Controller;
use MVC\Models\TaskRepository;

class TasksController extends Controller
{
    private $taskRepo;
    public function __construct()
    {
        $this->taskRepo = new TaskRepository('tasks', null, new Task);
    }
    function index()
    {
        $tasks = new Task();
        $d['tasks'] = $this->taskRepo->getAll($tasks);
        $this->set($d);
        $this->render("index");
    }

    function create()
    {
        extract($_POST);

        if (isset($title) && !empty($title) && isset($description) && !empty($description)) {

            $task = new Task();
            $task->setTitle($title);
            $task->setDescription($description);

            if ($this->taskRepo->add($task)) {
                header("Location: " . WEBROOT . "tasks/index");
            }
        }

        $this->render("create");
    }

    function edit($id)
    {
        $task = new Task();
        extract($_POST);

        $d['task'] = $this->taskRepo->get($id);

        if (isset($title)) {

            $task->setId($id);
            $task->setTitle($title);
            $task->setDescription($description);
            if ($this->taskRepo->update($task)) {
                header("Location: " . WEBROOT . "tasks/index");
            }
        }

        $this->set($d);
        $this->render("edit");
    }

    function delete($id)
    {
        $task = new Task();
        $task->setId($id);

        if ($this->taskRepo->delete($task)) {
            header("Location: " . WEBROOT . "tasks/index");
        }
    }
}

<?php

namespace MVC\Models;

class TaskRepository extends TaskResource
{
    public function add($model)
    {
        return $this->save($model);
    }

    public function update($model)
    {
        return $this->save($model);
    }

    public function get($id)
    {
        return $this->find($id);
    }

    public function getAll($model)
    {
        return $this->all($model);
    }

    public function delete($model)
    {
        return parent::delete($model);
    }
}

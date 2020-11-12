<?php


namespace MVC\Core;


use MVC\Config\Database;

class Resource implements ResourceInterface
{
    private $table;
    private $id;
    private $model;

    public function _init($table, $id, $model)
    {
        $this->table = $table;
        $this->id = $id;
        $this->model = $model;
    }

    public function save($model)
    {
        $placeNames = [];
        $properties = $model->getProperties();

        if ($model->getId() === null) {
            unset($properties['id']);
        }

        foreach ($properties as $key => $value) {
            array_push($placeNames, ':' . $key);
        }

        $columnsString = implode(',', array_keys($properties));
        $placeNamesString = implode(',', $placeNames);

        if (empty($model->getId())) {

            $sql = "INSERT INTO {$this->table} ({$columnsString}, created_at, updated_at) VALUES ({$placeNamesString}, :created_at, :updated_at)";
            $req = Database::getBdd()->prepare($sql);
            $date = array("created_at" => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'));

            return $req->execute(array_merge($properties, $date));
        } else {

            $sql = "UPDATE {$this->table} SET title = :title, description = :description , updated_at = :updated_at WHERE id = :id";
            $req = Database::getBdd()->prepare($sql);
            $date = array("id" => $model->getId(), 'updated_at' => date('Y-m-d H:i:s'));

            return $req->execute(array_merge($properties, $date));
        }
    }

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} where id = :id";
        $req = Database::getBdd()->prepare($sql);
        $req->execute(['id' => $id]);
        return $req->fetch();
    }

    public function all($model)
    {
        $properties = implode(',', array_keys($model->getProperties()));
        $sql = "SELECT {$properties} FROM {$this->table}";
        $req = Database::getBdd()->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }

    public function delete($model)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $req = Database::getBdd()->prepare($sql);
        return $req->execute([':id' => $model->getId()]);
    }
}

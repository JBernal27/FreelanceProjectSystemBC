<?php

class Project extends DB
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM projects WHERE id = :id";
        $query = $this->prepare($sql);
        $query->execute([':id' => $id]);
        return $query->fetch();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM projects";
        $query = $this->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO projects (title, description, start_date, delivery_date, status, user_id, created_at) 
                VALUES (:title, :description, :start_date, :delivery_date, :status, :user_id, :created_at)";
        $query = $this->prepare($sql);
        $success = $query->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':start_date' => $data['start_date'],
            ':delivery_date' => $data['delivery_date'],
            ':status' => $data['status'],
            ':user_id' => $data['user_id'],
            ':created_at' => $data['created_at'] ?? date('Y-m-d H:i:s')
        ]);

        // Si la inserción fue exitosa, retorna el ID del registro recién creado
        return $success ? $this->pdo->lastInsertId() : false;
    }

    public function update($id, $data)
    {
        $sql = "UPDATE projects 
                SET title = :title, description = :description, start_date = :start_date, 
                    delivery_date = :delivery_date, status = :status, user_id = :user_id 
                WHERE id = :id";
        $query = $this->prepare($sql);
        return $query->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':start_date' => $data['start_date'],
            ':delivery_date' => $data['delivery_date'],
            ':status' => $data['status'],
            ':user_id' => $data['user_id']
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM projects WHERE id = :id";
        $query = $this->prepare($sql);
        return $query->execute([':id' => $id]);
    }

    public function getByUserId($user_id)
    {
        $sql = "SELECT * FROM projects WHERE user_id = :user_id";
        $query = $this->prepare($sql);
        $query->execute([':user_id' => $user_id]);
        return $query->fetchAll();
    }

    public function addFile($project_id, $file_path)
    {
        $sql = "INSERT INTO project_files (project_id, file_path) VALUES (:project_id, :file_path)";
        $query = $this->prepare($sql);
        return $query->execute([
            ':project_id' => $project_id,
            ':file_path' => $file_path
        ]);
    }

    public function getFiles($project_id)
    {
        $sql = "SELECT * FROM project_files WHERE project_id = :project_id";
        $query = $this->prepare($sql);
        $query->execute([':project_id' => $project_id]);
        return $query->fetchAll();
    }
}

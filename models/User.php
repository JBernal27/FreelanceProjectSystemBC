<?php

class User extends DB
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $query = $this->prepare($sql);
        $query->execute([':id' => $id]);
        return $query->fetch();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM users";
        $query = $this->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $query = $this->prepare($sql);
        return $query->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
        $query = $this->prepare($sql);
        return $query->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $query = $this->prepare($sql);
        return $query->execute([':id' => $id]);
    }

    public function getByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $query = $this->prepare($sql);
        $query->execute([':email' => $email]);
        return $query->fetch();
    }
}
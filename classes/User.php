<?php

require_once __DIR__ . '/Database.php';

class User {
    public $id;
    public $name;
    public $email;
    public $role_id;

    public function __construct($data) {
        $this->id = $data['user_id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->role_id = $data['role_id'] ?? null;
    }

    public static function register($name, $email, $password, $role_id = 1): bool {
        $pdo = Database::getInstance();

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO Users (name, email, password_hash, role_id)
            VALUES (:name, :email, :pass, :role)
        ");

        try {
            return $stmt->execute([
                'name' => $name,
                'email' => $email,
                'pass' => $hashed,
                'role' => $role_id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function login($email, $password): ?User {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare("
            SELECT * FROM Users WHERE email = :email
        ");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        if ($row && password_verify($password, $row['password_hash'])) {
            return new User($row);
        }

        return null;
    }

    public static function getById($id): ?User {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? new User($row) : null;
    }

    public function update($newName, $newPassword = null): bool {
        $pdo = Database::getInstance();

        $sql = "UPDATE Users SET name = :name";
        $params = ['name' => $newName, 'id' => $this->id];

        if ($newPassword) {
            $sql .= ", password_hash = :pass";
            $params['pass'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE user_id = :id";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }
}

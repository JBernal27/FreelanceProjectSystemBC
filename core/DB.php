<?php

class DB
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = self::conect();
    }

    public static function conect()
    {
        $url = $_ENV['DATABASE_URL'];

        if (!$url) {
            http_response_code(500);
            die("❌ DATABASE_URL no está definida");
        }

        $db = parse_url($url);

        $host = $db['host'];
        $port = $db['port'];
        $user = $db['user'];
        $pass = $db['pass'];
        $dbname = ltrim($db['path'], '/');

        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            $pdo = new PDO($dsn, $user, $pass);

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;
        } catch (PDOException $e) {
            http_response_code(500);
            die("❌ Error al conectar: " . $e->getMessage());
        }
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }
}

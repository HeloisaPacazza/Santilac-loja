<?php

class db
{
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $port = '3306';
    private $dbname = 'loja_santilac';
    private $table_name;

    public function __construct($table_name)
    {
        $this->table_name = $table_name;
    }

    function conn()
    {
        try {
            $conn = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;port=$this->port",
                $this->user,
                $this->password,
                [
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => ' SET NAMES utf8',
                ]
            );

            return $conn;
        } catch (PDOException $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    }

    public function store($dados)
    {
        $conn = $this->conn();
        $flag = 0;
        $arrayDados = [];

        $sql = "INSERT INTO $this->table_name (";

        foreach ($dados as $campo => $valor) {
            if ($flag == 0) {
                $sql .= "$campo ";
            } else {
                $sql .= ", $campo ";
            }
            $flag = 1;
        }

        $sql .= ') VALUES (';

        $flag = 0;
        foreach ($dados as $campo => $valor) {
            if ($flag == 0) {
                $sql .= '?';
            } else {
                $sql .= ', ?';
            }
            $flag = 1;
            $arrayDados[] = $valor;
        }

        $sql .= ');';

      //  var_dump($sql, $dados);
      //  exit;

        $st = $conn->prepare($sql);
        $st->execute($arrayDados);
    }

    public function update($dados)
    {
        $id = $dados['id'];
        $conn = $this->conn();
        $flag = 0;
        $arrayDados = [];

        $sql = "UPDATE $this->table_name SET ";

        foreach ($dados as $campo => $valor) {
            if ($flag == 0) {
                $sql .= "$campo = ? ";
            } else {
                $sql .= ", $campo = ? ";
            }
            $flag = 1;
            $arrayDados[] = $valor;
        }

        $sql .= " WHERE id = $id";

        // var_dump($sql, $arrayDados);
        // var_dump($sql, $arrayDados);

        //exit();

        $st = $conn->prepare($sql);
        $st->execute($arrayDados);
    }

    public function find($id)
    {
        //select * from usuario WHERE id = 5
        $conn = $this->conn();

        $sql = "SELECT * FROM $this->table_name WHERE id = ?";

        $st = $conn->prepare($sql);
        $st->execute([$id]);

        return $st->fetchObject();
    }

    public function all()
    {
        $conn = $this->conn();

        $sql = "SELECT * FROM $this->table_name";

        $st = $conn->prepare($sql);
        $st->execute();

        return $st->fetchAll(PDO::FETCH_CLASS);
    }

   public function destroy($id)
{
    $conn = $this->conn();

    try {
        
        $sql = "DELETE FROM $this->table_name WHERE id = ?";
        $st = $conn->prepare($sql);
        $st->execute([$id]);

    } catch (PDOException $e) {
       
        echo "Erro ao deletar funcionário: " . $e->getMessage();
    }
}


    public function search($dados)
{
    $conn = $this->conn();
    $campo = $dados['tipo'] ?? null;
    $valor = $dados['valor'] ?? null;
   if (!$campo || !$valor) {
        return [];
    }
   
    $sql = "SELECT * FROM $this->table_name WHERE $campo LIKE ?";
    $st = $conn->prepare($sql);
    $st->execute(["%$valor%"]);

    return $st->fetchAll(PDO::FETCH_CLASS);
}


    public function login($dados)
{
    $conn = $this->conn();

    $sql = "SELECT * FROM $this->table_name WHERE login = ?";
    $st = $conn->prepare($sql);
    $st->execute([$dados['login']]);

    $result = $st->fetchObject();

    
    if (!$result) {
        return 'error';
    }

    if (!password_verify($dados['senha'], $result->senha)) {
        return 'error';
    }

    return $result;
}


    function checkLogin()
    {
        session_start();

        if (empty($_SESSION['login'])) {
            session_destroy();
            header('Location: ../login.php?error=Sessao Expirada!');
        }
    }
}

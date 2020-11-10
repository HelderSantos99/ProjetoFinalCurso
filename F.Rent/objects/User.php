<?php

/**
 * Classe User permite gerir e manipular utilizadores da aplicação
 *
 * @author mjp
 */
class User {

    // database connection and table name
    private $conn;
    private $table_name = "Utilizador";
    // object properties
    
    public $nome;
    public $email;

    public $password;

    // constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Método para a inserção de um novo User na DB
     * @return boolean
     */
    public function create() {

        // insert query
        $query = "INSERT INTO " . $this->table_name . "
            SET
                email = :email,
                nome = :nome,
                password = :password";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // bind the values
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':nome', $this->nome);
        

        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
        $stmt->bindValue(':password', $password_hash);

        // execute the query, also check if query was successful
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Atualizar um utilizador na base de dados
     * @return boolean
     */
    public function update() {

        // if password needs to be updated
        $password_set = !empty($this->password) ? ", password = :password" : "";

        // if no posted password, do not update the password
        $query = "UPDATE " . $this->table_name . "
            SET
                nome = :nome
              
                {$password_set}
            WHERE email = :email";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->email = filter_var($this->email,FILTER_SANITIZE_EMAIL);
        $this->nome = filter_var($this->nome,FILTER_SANITIZE_STRING);
        $this->password = filter_var($this->password,FILTER_SANITIZE_STRING);

        // bind the values from the form
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':nome', $this->nome);
        

        // hash the password before saving to database
        if (!empty($this->password)) {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
            $stmt->bindValue(':password', $password_hash);
        }

     

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false; 
    }

    /**
     * Verifica se um email existe na tabela de utilizadores
     * @return boolean
     */
    public function emailExists() {

        // query to check if email exists
        $query = "SELECT nome, password
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->email = filter_var($this->email,FILTER_SANITIZE_EMAIL);

        // bind given email value
        $stmt->bindValue(1, $this->email);

        // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        // if email exists, assign values to object properties for easy access and use for php sessions
        if ($num > 0) {

            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // assign values to object properties
            
            $this->nome = $row['nome'];
            $this->password = $row['password'];

            // return true because email exists in the database
            return true;
        }

        // return false if email does not exist in the database
        return false;
    }

}

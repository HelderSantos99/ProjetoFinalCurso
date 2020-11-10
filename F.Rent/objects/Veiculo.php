<?php

class Veiculo {

    // Ligação à Base de Dados e nome da tabela
    private $conn;
    private $table_name = "Veiculo";
    // Propriedades
    public $ID;
    public $id_Categoria;
    public $Marca;
    public $Modelo;
    public $Lugares;
    public $Preco_dia;
    public $Combustivel;
    public $Portas;
    public $Caixa;
    public $Empresas_ID;
    

    /**
     * Método construtor que instancia a ligação à Base de Dados
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    
    /**
     * Método para ler todos os elementos da tabela
     * @return PDOStatement Devolve PDOStatement com todos os elementos da tabela
     */
    function read() {

        // Query SQL
        $query = "SELECT
               ID, id_Categoria, Marca, Modelo, Lugares, Preco_dia, Combustivel, Portas, Caixa, Empresas_ID
            FROM
                " . $this->table_name . "
            ORDER BY
                ID ASC";

        // Preparar query statement
        $stmt = $this->conn->prepare($query);

        // Executar query
        $stmt->execute();

        // Devolver PDOStatement
        return $stmt;
    }
    
    
     /**
     * Método para pesquisa
     * @param string $keywords Palavras para procura
     * @return PDOStatement com resultado da procura
     */
    function search($keywords) {
        // Query SQL
        $query = "SELECT
                ID, id_Categoria, Marca, Modelo, Lugares, Preco_dia, Combustivel, Portas, Caixa, Empresas_ID
            FROM
                " . $this->table_name . " 
            WHERE
                id_Categoria LIKE ? OR Marca LIKE ? OR Modelo LIKE ? OR Lugares LIKE ? OR Combustivel LIKE ? OR Caixa LIKE ?  OR ID LIKE ?  
            ORDER BY
                ID ASC";

        // Preparar query
        $stmt = $this->conn->prepare($query);

        // Filtrar palavras de pesquisa
        $search = '%'.filter_var($keywords,FILTER_SANITIZE_STRING).'%';

        // Atribuir valores 
        $stmt->bindValue(1, $search);
        $stmt->bindValue(2, $search);
        $stmt->bindValue(3, $search);
        $stmt->bindValue(4, $search);
        $stmt->bindValue(5, $search);
        $stmt->bindValue(6, $search);
        $stmt->bindValue(7, $search);

        // Executar query
        $stmt->execute();

        return $stmt;
    }


    /**
     * Método para criar registo na Base de Dados
     * @return Boolean Devolve true quando insere na Base de Dados
     */
//    function create() {
//        // Query de inserção
//        $query = "INSERT INTO
//                " . $this->table_name . "
//            SET
//                name=:name, price=:price, description=:description, 
//                category_id=:category_id, created=:created";
//
//        // Preparar query
//        $stmt = $this->conn->prepare($query);
//
//        // Filtrar valores
//        $this->name = filter_var($this->name, FILTER_SANITIZE_STRING);
//        $this->price = (float) filter_var($this->price, 
//                FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
//        $this->description = filter_var($this->description, FILTER_SANITIZE_STRING);
//        $this->category_id = filter_var($this->category_id, FILTER_SANITIZE_NUMBER_INT);
//        $this->created = filter_var($this->created, FILTER_SANITIZE_STRING);
//
//        // Associar valores
//        $stmt->bindValue(":name", $this->name);
//        $stmt->bindValue(":price", $this->price);
//        $stmt->bindValue(":description", $this->description);
//        $stmt->bindValue(":category_id", $this->category_id);
//        $stmt->bindValue(":created", $this->created);
//
//        // Executar query
//        if ($stmt->execute()) {
//            return true;
//        }
//
//        return false;
//    }

    /**
     * Método para obter um registo da Base de Dados
     * @return None
     */
    function readOne() {
        // Query SQL para ler apenas um registo
        $query = "SELECT
                ID, id_Categoria, Marca, Modelo, Lugares, Preco_dia, Combustivel, Portas, Caixa, Empresas_ID
            FROM
                " . $this->table_name . " 
            WHERE
                ID = :ID
            LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        // Filtrar e associar valor do ID
        $this->ID = filter_var($this->ID, FILTER_SANITIZE_NUMBER_INT);
        $stmt->bindValue(':ID', $this->ID);

        // Executar query
        $stmt->execute();

        // Obter dados do registo e instanciar o objeto
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->ID = $row['ID'];
        $this->id_Categoria = $row['id_Categoria'];
        $this->Marca = $row['Marca'];
        $this->Modelo = $row['Modelo'];
        $this->Lugares = $row['Lugares'];
        $this->Preco_dia = $row['Preco_dia'];
        $this->Combustivel = $row['Combustivel'];
        $this->Portas = $row['Portas'];
        $this->Caixa = $row['Caixa'];
        $this->Empresas_ID = $row['Empresas_ID'];
        
    }

    /**
     * Método para atualizar um registo na Base de Dados
     * @return Boolean Devolve true quando atualiza na Base de Dados
     */
//    function update() {
//
//        // update query
//        $query = "UPDATE
//                " . $this->table_name . "
//            SET
//                name = :name,
//                price = :price,
//                description = :description,
//                category_id = :category_id,
//                created = :created,
//                modified = :modified
//            WHERE
//                id = :id";
//
//        // Preparar query
//        $stmt = $this->conn->prepare($query);
//
//        // Filtrar valores
//        $this->name = filter_var($this->name, FILTER_SANITIZE_STRING);
//        $this->price = (float) filter_var($this->price, 
//                FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
//        $this->description = filter_var($this->description, FILTER_SANITIZE_STRING);
//        $this->category_id = filter_var($this->category_id, FILTER_SANITIZE_NUMBER_INT);
//        $this->created = filter_var($this->created, FILTER_SANITIZE_STRING);
//        $this->modified = filter_var($this->modified, FILTER_SANITIZE_STRING);
//        $this->id = filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);
//
//        // Associar valores
//        $stmt->bindValue(":name", $this->name);
//        $stmt->bindValue(":price", $this->price);
//        $stmt->bindValue(":description", $this->description);
//        $stmt->bindValue(":category_id", $this->category_id);
//        $stmt->bindValue(":created", $this->created);
//        $stmt->bindValue(":modified", $this->modified);
//        $stmt->bindValue(":id", $this->id);
//
//        // Executar query
////        var_dump($stmt);
//        if ($stmt->execute()) {
//            return true;
//        }
//
//        return false;
//    }

    /**
     * Método para apagar um registo da Base de Dados
     * @return Boolean Devolve true quando remove da Base de Dados
     */
    function delete() {
        // Query SQL
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = :ID";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // Filtrar e associar valor do ID
        $this->ID = filter_var($this->ID, FILTER_SANITIZE_NUMBER_INT);
        $stmt->bindValue(':ID', $this->ID);

        // Executar query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    
    /**
     * Método para obter os registos de uma página
     * @param int $from_record_num - número do primeiro registo
     * @param int $records_per_page - número de registos por página
     * @return PDOStatement com registos da página
     */
    public function readPaging($from_record_num = 1, $records_per_page = 10) {

        // Query SQL
        $query = "SELECT
                ID, id_Categoria, Marca, Modelo, Lugares, Preco_dia, Combustivel, Portas, Caixa, Empresas_ID
            FROM
                " . $this->table_name . " 
            ORDER BY ID DESC
            LIMIT :FIRST_REC, :NUM_REC";

        // Preparar Query
        $stmt = $this->conn->prepare($query);

        // Atribuir valores
        $stmt->bindValue(':FIRST_REC', $from_record_num, PDO::PARAM_INT);
        $stmt->bindValue(':NUM_REC', $records_per_page, PDO::PARAM_INT);

        // Executar query
        $stmt->execute();

        // Devolver resultados
        return $stmt;
    }

    /**
     * Método para devolver o número total de registos
     * @return int
     */
    public function count() {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

}


<?php

class Reserva {

    // Ligação à Base de Dados e nome da tabela
    private $conn;
    private $table_name = "Reserva";
    // Propriedades
    public $ID;
    public $data_levantamento;
    public $data_entrega;
    public $Utilizador_email;
    public $Veiculo_ID;
    

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
               ID, data_levantamento, data_entrega, Utilizador_email, Veiculo_ID
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
     * Método para criar registo na Base de Dados
     * @return Boolean Devolve true quando insere na Base de Dados
     */
    
    function create() {
        // Query de inserção
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
               data_levantamento=:data_levantamento, data_entrega=:data_entrega, 
                Utilizador_email=:Utilizador_email, Veiculo_ID=:Veiculo_ID";

        // Preparar query
        $stmt = $this->conn->prepare($query);

        // Filtrar valores
        $this->data_levantamento = filter_var($this->data_levantamento, FILTER_SANITIZE_STRING);
        $this->data_entrega = filter_var($this->data_entrega, FILTER_SANITIZE_STRING);
        $this->Utilizador_email = filter_var($this->Utilizador_email, FILTER_SANITIZE_EMAIL);
        $this->Veiculo_ID = filter_var($this->Veiculo_ID, FILTER_SANITIZE_NUMBER_INT);

        // Associar valores
        $stmt->bindValue(":data_levantamento", $this->data_levantamento);
        $stmt->bindValue(":data_entrega", $this->data_entrega);
        $stmt->bindValue(":Utilizador_email", $this->Utilizador_email);
        $stmt->bindValue(":Veiculo_ID", $this->Veiculo_ID);

        // Executar query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Método para obter um registo da Base de Dados
     * @return None
     */
    function readOne() {
        // Query SQL para ler apenas um registo
        $query = "SELECT
                ID, data_levantamento, data_entrega, Utilizador_email, Veiculo_ID
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
        $this->data_levantamento = $row['data_levantamento'];
        $this->data_entrega = $row['data_entrega'];
        $this->Utilizador_email = $row['Utilizador_email'];
        $this->Veiculo_ID = $row['Veiculo_ID'];
        $this->ID = $row['ID'];
    }
    
   
    
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
     * Método para pesquisa
     * @param string $keywords Palavras para procura
     * @return PDOStatement com resultado da procura
     */
//    function search($keywords) {
//        // Query SQL
//        $query = "SELECT
//                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created, p.modified
//            FROM
//                " . $this->table_name . " p
//                LEFT JOIN
//                    categories c
//                        ON p.category_id = c.id
//            WHERE
//                p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
//            ORDER BY
//                p.created DESC";
//
//        // Preparar query
//        $stmt = $this->conn->prepare($query);
//
//        // Filtrar palavras de pesquisa
//        $search = '%'.filter_var($keywords,FILTER_SANITIZE_STRING).'%';
//
//        // Atribuir valores 
//        $stmt->bindValue(1, $search);
//        $stmt->bindValue(2, $search);
//        $stmt->bindValue(3, $search);
//
//        // Executar query
//        $stmt->execute();
//
//        return $stmt;
//    }

    /**
     * Método para obter os registos de uma página
     * @param int $from_record_num - número do primeiro registo
     * @param int $records_per_page - número de registos por página
     * @return PDOStatement com registos da página
     */
//    public function readPaging($from_record_num = 1, $records_per_page = 10) {
//
//        // Query SQL
//        $query = "SELECT
//                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created, p.modified
//            FROM
//                " . $this->table_name . " p
//                LEFT JOIN
//                    categories c
//                        ON p.category_id = c.id
//            ORDER BY p.created DESC
//            LIMIT :FIRST_REC, :NUM_REC";
//
//        // Preparar Query
//        $stmt = $this->conn->prepare($query);
//
//        // Atribuir valores
//        $stmt->bindValue(':FIRST_REC', $from_record_num, PDO::PARAM_INT);
//        $stmt->bindValue(':NUM_REC', $records_per_page, PDO::PARAM_INT);
//
//        // Executar query
//        $stmt->execute();
//
//        // Devolver resultados
//        return $stmt;
//    }

    /**
     * Método para devolver o número total de registos
     * @return int
     */
//    public function count() {
//        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
//
//        $stmt = $this->conn->prepare($query);
//        $stmt->execute();
//        $row = $stmt->fetch(PDO::FETCH_ASSOC);
//
//        return $row['total_rows'];
//    }

}

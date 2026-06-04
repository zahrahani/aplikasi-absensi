<?php

/**
 * =====================================================
 * MODEL: Parent
 * Menyimpan turunan dari struktur model
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Models;

class ParentModels {
    protected $db;
    protected $table;
    protected $query;
    protected $hasWhere = false;
    protected $useUpdate = false;
    protected $useDelete = false;
    protected $dataUpdate = [];

    /**
    * Constructor
    */
    public function __construct($table_) {
        $this->db = getDB();
        $this->table = $table_;
        $this->query = '';
    }

    /**
    * Query Select
    */
    public function select($columns = []) {
        $stringColumn = implode(', ', $columns);

        $this->query = "SELECT {$stringColumn} FROM {$this->table} ";

        return $this;
    }

    /**
    * Query Select raw
    */
    public function selectRaw($string) {
        $this->query = "SELECT {$string} FROM {$this->table} ";

        return $this;
    }

    /**
    * Query Where
    */
    public function where($column, $operator, $value = null) {

        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        if (!$this->hasWhere) {
            $this->query .= "WHERE {$column} {$operator} '{$value}' ";
            $this->hasWhere = true;
        } else {
            $this->query .= "AND {$column} {$operator} '{$value}' ";
        }

        return $this;
    }

    /**
    * Query orWhere
    */
    public function orWhere($column, $operator, $value = null) {

        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        if (!$this->hasWhere) {
            $this->query .= "WHERE {$column} {$operator} '{$value}' ";
            $this->hasWhere = true;
        } else {
            $this->query .= "OR {$column} {$operator} '{$value}' ";
        }

        return $this;
    }

    /**
    * Query whereRaw
    */
    public function whereRaw($string) {

        if (!$this->hasWhere) {
            $this->query .= "WHERE {$string} ";
            $this->hasWhere = true;
        } else {
            $this->query .= "AND {$string} ";
        }

        return $this;
    }

    /**
    * Query JOIN
    */
    public function join($table, $columnA, $columnB, $type = 'INNER') {

        $this->query .= "{$type} JOIN {$table} ON {$columnA} = {$columnB} ";

        return $this;
    }

    /**
    * Query GroupBy
    */
    public function groupBy($kolom,$type="ASC") {

        $this->query .= " GROUP BY {$kolom} {$type} ";

        return $this;
    }


    /**
    * Get Query
    */
    public function get() {
        $sql = $this->query;
        $stmt = $this->db->query($sql);
        $this->query = '';
        $this->hasWhere = false;
        return $stmt->fetchAll();     
    }

    /**
     * Membuat data baru
     */
    public function create($data = []) {

        $columns = implode(', ', array_keys($data));

        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $values = array_values($data);

        $sql = "INSERT INTO {$this->table} ({$columns})
        VALUES ({$placeholders})";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($values);

        return $result;
    }

    /**
     * Membuat data baru
     */
    public function count() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn();
    }

    /**
     * Membuat update data baru
     */
    public function update($data = []) {

        if (empty($data)) return false;

        // SET
        $set = implode(', ', array_map(fn($col) => "{$col} = ? ", array_keys($data)));

        $this->query = "UPDATE {$this->table} SET {$set}";
        $this->dataUpdate = $data;
        $this->useUpdate = true;

        return $this;
    }

    /**
     * Membuat update data baru
     */
    public function delete() {

        $this->query = "DELETE FROM {$this->table} ";
        $this->useDelete = true;
        return $this;
    }

    /**
     * Execute Query
     */
    public function execute() {
        if ( !empty($this->dataUpdate) && $this->useUpdate ) {
            $stmt = $this->db->prepare($this->query);

            // values: data dulu, lalu where
            $values = array_merge(array_values($this->dataUpdate));

            $this->query = "";
            $this->dataUpdate = null;
            $this->useUpdate = false;

            return $stmt->execute($values);
        } elseif ($this->useDelete) {
            $stmt = $this->db->prepare($this->query);
            
            $this->query = "";
            $this->useDelete = false;
            return $stmt->execute();

        }
    }


    /**
     * Mengembailkan id terakhir
     */
    public function lastInsertId() {
        return $this->db->lastInsertId();
    }

    /**
     * Mengembailkan data terbaru
     */
    public function latest() {
        $this->query .= " ORDER BY {$this->table}.created_at DESC ";
        return $this;
    }

    /**
     * Order BY
     */
    public function orderBy($kolom, $urutan = "ASC") {
        $this->query .= " ORDER BY {$kolom} {$urutan} ";
        return $this;
    }
}


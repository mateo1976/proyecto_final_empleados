<?php
// app/models/Empleado.php
require_once __DIR__ . '/../config/Connection.php';

class Empleado {
    private $db;
    public function __construct() {
        $this->db = Connection::getInstance();
    }

    public function all() {
        $stmt = $this->db->query("SELECT * FROM empleado ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM empleado WHERE id = :id");
        $stmt->execute(['id'=>$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = 'INSERT INTO empleado (documento,nombre,sexo,domicilio,telefono,correo,fechaIngreso,fechaNacimiento,sueldoBasico)
                VALUES (:documento,:nombre,:sexo,:domicilio,:telefono,:correo,:fechaIngreso,:fechaNacimiento,:sueldoBasico)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = 'UPDATE empleado SET documento=:documento,nombre=:nombre,sexo=:sexo,domicilio=:domicilio,telefono=:telefono,correo=:correo,fechaIngreso=:fechaIngreso,fechaNacimiento=:fechaNacimiento,sueldoBasico=:sueldoBasico WHERE id=:id';
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM empleado WHERE id = :id");
        return $stmt->execute(['id'=>$id]);
    }

    // consultas útiles
    public function cumpleanosPorMes($mes) {
        $stmt = $this->db->prepare("SELECT * FROM empleado WHERE MONTH(fechaNacimiento)=:mes ORDER BY DAY(fechaNacimiento)");
        $stmt->execute(['mes'=>$mes]);
        return $stmt->fetchAll();
    }

    public function totalNomina() {
        $stmt = $this->db->query("SELECT SUM(sueldoBasico) as total FROM empleado");
        return $stmt->fetch();
    }

    public function totalPorSexo() {
        $stmt = $this->db->query("SELECT sexo, COUNT(*) as cantidad, SUM(sueldoBasico) as totalSueldo FROM empleado GROUP BY sexo");
        return $stmt->fetchAll();
    }

    public function countMayorSalario($salario_minimo) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as cantidad FROM empleado WHERE sueldoBasico > :sm");
        $stmt->execute(['sm'=>$salario_minimo]);
        return $stmt->fetch();
    }
}

<?php
// app/controllers/EmpleadoController.php
require_once __DIR__ . '/../models/Empleado.php';

class EmpleadoController {
    private $model;
    public function __construct() {
        $this->model = new Empleado();
    }

    public function index() {
        $empleados = $this->model->all();
        require __DIR__ . '/../views/empleados/index.php';
    }

    public function create() {
        require __DIR__ . '/../views/empleados/create.php';
    }

    public function store($data) {
        $this->model->create($data);
        header('Location: /proyecto_final_empleados/public/?route=empleados');
    }

    public function edit($id) {
        $empleado = $this->model->find($id);
        require __DIR__ . '/../views/empleados/edit.php';
    }

    public function update($id, $data) {
        $this->model->update($id, $data);
        header('Location: /proyecto_final_empleados/public/?route=empleados');
    }

    public function delete($id) {
        $this->model->delete($id);
        header('Location: /proyecto_final_empleados/public/?route=empleados');
    }
}

<?php namespace App\Controllers;

use App\Models\CantanteModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class RestCantanteController extends ResourceController
{
    protected $modelName = 'App\Models\CantanteModel';
    protected $format = 'json';

    public function index()
    {
        
        return $this->genericResponse($this->model->findAll(),"",200);
    }

    public function show($id = null)
    {
        
        if($id == null){
            return $this->genericResponse(null,"ID no encontrado",204);
        }

        $cantante = $this->model->find($id);

        if(!$cantante){
            return $this->genericResponse(null,"Cantante no existe",204);
        }   

        return $this->genericResponse($cantante,"",200);
        
    }

    public function create()
    {
        
        $cantante = new CantanteModel();

        if($this->validate('cantantes')){

            $id = $cantante->insert([
                
                'nombre_natural' => $this->request->getPost('nombre_natural'),
                'nombre_artistico' => $this->request->getPost('nombre_artistico'),
                'genero_musical' => $this->request->getPost('genero_musical'),
                'edad' => $this->request->getPost('edad'),
                'pais_nacimiento' => $this->request->getPost('pais_nacimiento')
            ]);

            return $this->genericResponse($this->model->find($id), null, 200);

        }

        $validation = \Config\Services::validation();

        return $this->genericResponse(null,$validation->getErrors(),500);
    }

    
    public function update($id = null)
    {
        $cantante = new CantanteModel();

        $cantante = $this->model->find($id);

        if(!$cantante){
            return $this->genericResponse(null,"Cantante no existe",204);
        }   

        $data = $this->request->getRawInput();

        if($this->validate('cantantes')){

            $cantante = new CantanteModel();
            $cantante->update($id, [
                'nombre_natural' =>  $data['nombre_natural'],
                'nombre_artistico' => $data['nombre_artistico'],
                'genero_musical' => $data['genero_musical'],
                'edad' => $data['edad'],
                'pais_nacimiento' => $data['pais_nacimiento']
            ]);

            return $this->genericResponse($this->model->find($id), null, 200);
            
        }

        $validation = \Config\Services::validation();

        return $this->genericResponse(null,$validation->getErrors(),500);
        
        
    }

    public function delete($id = null)
    {
        
        if($id == null){
            return $this->genericResponse(null,"ID no encontrado",204);
        }

        $cantante = $this->model->find($id);

        if(!$cantante){
            return $this->genericResponse(null,"Cantante no existe",204);
        }   

        $this->model->delete($id);
        return $this->genericResponse("Cantante $id eliminado con éxito","",200);
        
    }

    // mensaje personalizado
    public function genericResponse($data, $msj, $code)
    {
        if($code == 200) {
            return $this->respond(array(
                "data" => $data,
                "code" => $code
            ));
        }else{
            return $this->respond(array(
                "msj" => $msj,
                "code" => $code
            ));
        }
    }
}
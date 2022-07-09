<?php namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\MovieModel;
use CodeIgniter\RESTful\ResourceController;

class RestMovie extends ResourceController
{
    protected $modelName = 'App\Models\MovieModel';
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

        $movie = $this->model->find($id);

        if(!$movie){
            return $this->genericResponse(null,"Movie no existe",204);
        }   

        return $this->genericResponse($movie,"",200);
        
    }

    public function create()
    {
        $movie = new MovieModel();
        $category = new CategoryModel();

        if($this->validate('movies')){
            if(!$this->request->getPost('category_id'))
                return $this->genericResponse(null, array("category_id" => "Categoría no existe"), 500);

            if(!$category->get($this->request->getPost('category_id'))){
                return $this->genericResponse(null, array("category_id" => "Categoría no existe"),500);
            }

            $id = $movie->insert([
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'category_id' => $this->request->getPost('category_id')
            ]);

            return $this->genericResponse($this->model->find($id), null, 200);

            
            if(!$this->request->getPost('category_id'))
                return $this->genericResponse(null, array("category_id" => "Categoría no existe"), 500);
            
        }

        $validation = \Config\Services::validation();

        return $this->genericResponse(null,$validation->getErrors(),500);
    }

    public function update($id = null)
    {
        $movie = new MovieModel();
        $category = new CategoryModel();

        $movie = $this->model->find($id);

        if(!$movie){
            return $this->genericResponse(null,"Movie no existe",204);
        }   

        $data = $this->request->getRawInput();

        if($this->validate('movies')){
            if(!$data['category_id'])
                return $this->genericResponse(null, array("category_id" => "Categoría no existe"), 500);

            if(!$category->get($data['category_id'])){
                return $this->genericResponse(null, array("category_id" => "Categoría no existe DB"),500);
            }

            
            $movie->update($id, [
                'title' => $data['title'],
                'description' => $data['description'],
                'category_id' => $data['category_id']
            ]);

            return $this->genericResponse($this->model->find($id), null, 200);

            
            if(!$data['category_id'])
                return $this->genericResponse(null, array("category_id" => "Categoría no existe"), 500);
            
        }

        $validation = \Config\Services::validation();

        return $this->genericResponse(null,$validation->getErrors(),500);
    }

    public function delete($id = null)
    {
        
        if($id == null){
            return $this->genericResponse(null,"ID no encontrado",204);
        }

        $movie = $this->model->find($id);

        if(!$movie){
            return $this->genericResponse(null,"Movie no existe",204);
        }   

        $this->model->delete($id);
        return $this->genericResponse("Movie $id eliminado con éxito","",200);
        
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
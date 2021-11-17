<?php

namespace Devsbuddy\LiquidLite\Http\Controllers;

use App\Http\Controllers\Controller;
use Devsbuddy\LiquidLite\Models\LiquidCrud;
use Devsbuddy\LiquidLite\Services\CreateCrudRecordService;
use Devsbuddy\LiquidLite\Traits\HasResponse;
use Devsbuddy\LiquidLite\Traits\HasStubs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class CrudController extends Controller
{
    use HasStubs, HasResponse;

    public $request;
    public $modelName;
    public $controllerName;
    public $modelPluralName;
    public $modelEntities;
    public $modelEntity;

    public function index()
    {
        $cruds = LiquidCrud::paginate(10);

        return view('liquid-lite::cruds.index', compact('cruds'));
    }

    public function store(Request $request)
    {

        $this->request = $request;
        $this->modelName = Str::studly(Str::singular($this->request->get('model')));
        $this->modelPluralName = Str::plural($this->modelName);
        $this->controllerName = $this->modelName . 'Controller';
        $this->modelEntities = Str::snake($this->modelPluralName);
        $this->modelEntity = Str::snake($this->modelName);

        LiquidCrud::create([
            'name' => $this->modelPluralName,
            'model' => $this->modelName,
            'controllers' => [
                'api' => $this->controllerName . '.php',
                'admin' => $this->controllerName . '.php'
            ],
            'menu' => [
                'label' => ucfirst($this->modelEntities),
                'url' => 'adminr.' . $this->modelEntities . 'index'
            ],

        ]);
    }

    public function configure($id)
    {
        try{
            $id = decrypt($id);
            $crud = LiquidCrud::findOrFail($id);
            $routes = json_decode(File::get(base_path() . '/routes/liquid/api/' . $crud->payload->routes->api));
            return view('liquid-lite::cruds.configure', compact('crud', 'routes'));
        } catch (\Exception $e){
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    public function getResource(LiquidCrud $resource)
    {
        try{

            return $this->success($resource, 200);
        } catch (\Exception $e){
            return $this->error($e->getMessage(), 500);
        } catch (\Error $e){
            return $this->error($e->getMessage(), 500);
        }
    }

    public function updateApiMiddlewares(LiquidCrud $resource, Request $request)
    {
        if($this->updateRouteFile($resource, $request)){
            return $this->successMessage("API public routes permission updated!", 200);
        } else {
            return $this->error("Something went wrong!", 500);
        }
    }

    private function updateRouteFile(LiquidCrud $resource, Request $request)
    {
        $routeFile = (array) json_decode(File::get(base_path() . '/routes/liquid/api/'.Str::lower($resource->name) . '/' . Str::lower($resource->name) .'.json'));

        foreach ($request->all() as $key => $method){
            if($method){
                if(!in_array("auth:api", $routeFile[$key]->middleware)){
                    array_push($routeFile[$key]->middleware, "auth:api");
                }
            } else {
                if (($apiKey = array_search("auth:api", $routeFile[$key]->middleware)) !== false) {
                    unset($routeFile[$key]->middleware[$apiKey]);
                }
            }
        }


        File::put(base_path() . '/routes/liquid/api/' . Str::lower($resource->name) . '/' . Str::lower($resource->name) . '.json',  json_encode((object) $routeFile));

        return true;
    }

    public function destroy(LiquidCrud $crud)
    {
        $crudService = new CreateCrudRecordService();
        $crudService->rollback($crud->id);
        $crud->delete();
        return back()->with('success', 'Crud deleted successfully!');
    }
}


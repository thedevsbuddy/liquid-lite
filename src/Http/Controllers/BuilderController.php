<?php

namespace Devsbuddy\LiquidLite\Http\Controllers;

use App\Http\Controllers\Controller;
use Devsbuddy\LiquidLite\Database;
use Devsbuddy\LiquidLite\Models\LiquidCrud;
use Devsbuddy\LiquidLite\Services\BuildControllersService;
use Devsbuddy\LiquidLite\Services\BuildMigrationService;
use Devsbuddy\LiquidLite\Services\BuildModelService;
use Devsbuddy\LiquidLite\Services\BuildRoutesService;
use Devsbuddy\LiquidLite\Services\BuildViewsService;
use Devsbuddy\LiquidLite\Services\CreateCrudRecordService;
use Devsbuddy\LiquidLite\Traits\HasStubs;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BuilderController extends Controller
{
    use HasStubs;

    public $createCrudRecordService;
    public $buildControllersService;
    public $buildModelService;
    public $buildMigrationService;
    public $buildRouteService;
    public $buildViewsService;

    public function __construct()
    {
        $this->createCrudRecordService = new CreateCrudRecordService();
        $this->buildControllersService = new BuildControllersService();
        $this->buildModelService = new BuildModelService();
        $this->buildMigrationService = new BuildMigrationService();
        $this->buildRouteService = new BuildRoutesService();
        $this->buildViewsService = new BuildViewsService();
    }

    public function index()
    {
        try{
            return view('liquid-lite::index');
        } catch (\Exception $e){
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $dataTypes = Database::dataTypes();
            return view('liquid-lite::builder.create', compact('dataTypes'));
        } catch (\Exception $e){
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    public function build(Request $request)
    {
//        if($this->resourceExists($request)){
//            return response()->json([
//                'status' => 'error',
//                'message' => 'CRUD already exist!'
//            ], 200);
//        }

        try{

            $this->createCrudRecordService->store($request);

            $this->buildControllersService
                ->prepare($request)
                ->buildApiController()
                ->buildController()
                ->cleanUp();


            $this->createCrudRecordService->update([
                'name' => Str::title($this->buildControllersService->modelEntities),
                'controllers' => [
                    'api' => $this->buildControllersService->controllerName,
                    'admin' => $this->buildControllersService->controllerName,
                ],
                'menu' => [
                    'label' => Str::studly($this->buildControllersService->modelEntities),
                    'route' => 'adminr.' . $this->buildControllersService->modelEntities . '.index'
                ],
                'table' => $this->buildControllersService->tableName,
                'payload' => [
                    'model' => $this->buildControllersService->modelName . '.php',
                    'views' => [
                        'index' => 'admin/' . $this->buildControllersService->modelEntities . '/index.blade.php',
                        'create' => 'admin/' . $this->buildControllersService->modelEntities . '/create.blade.php',
                        'edit' => 'admin/' . $this->buildControllersService->modelEntities . '/edit.blade.php',
                    ],
                    'migration' => config('liquid.api.version'),
                    'controllers' => [
                        'api' => $this->buildControllersService->controllerName . '.php',
                        'admin' => $this->buildControllersService->controllerName . '.php',
                    ],
                    'routes' => [
                        'api' => $this->buildControllersService->modelEntities . '/' . $this->buildControllersService->modelEntities . '.json',
                        'admin' => $this->buildControllersService->modelEntities . '/' . $this->buildControllersService->modelEntities . '.json',
                    ],
                ],
            ]);

            $this->buildModelService
                ->prepare($request)
                ->buildModel()
                ->cleanUp();

            $this->buildMigrationService
                ->prepare($request)
                ->buildMigration()
                ->cleanUp();

            $this->createCrudRecordService->update([
                'migration' => $this->buildMigrationService->migrationFileName,
                'payload->migration' => $this->buildMigrationService->migrationFileName . '.php'
            ]);

            $this->buildRouteService
                ->prepare($request)
                ->buildApiRoute()
                ->buildAdminRoute()
                ->cleanUp();

            $this->buildViewsService
                ->prepare($request)
                ->buildIndexView()
                ->buildCreateView()
                ->buildEditView()
                ->cleanUp();

            Artisan::call('migrate');

            return response()->json(['status' => 'success', 'message' => 'CRUD generated Successfully!'], 200);
        } catch (\Exception $e){
            $this->rollbackAll();
            return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()], 200);
        } catch (\Error $e){
            $this->rollbackAll();
            return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Checks if Resource already exists
     *
     * @param Request $request
     * @return bool
     */
    private function resourceExists(Request $request)
    {
        $crud = LiquidCrud::where('name', Str::snake($request->get('model')))->where('model', $request->get('model'))->first();
        $model = File::exists(base_path() . "/app/Models/".Str::title($request->get('model')).".php");
        if(!is_null($crud)){
           return true;
        }
        if($model){
            return true;
        }
        return false;
    }


    /**
     * Rollbacks everything
     * if anything goes wrong
     */
    private function rollbackAll(){
        $this->buildControllersService->rollback()->cleanUp();
        $this->buildModelService->rollback()->cleanUp();
        $this->buildMigrationService->rollback()->cleanUp();
        $this->buildRouteService->rollback()->cleanUp();
        $this->buildViewsService->rollback()->cleanUp();
        $this->createCrudRecordService->rollback();
    }
}


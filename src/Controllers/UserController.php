<?php

namespace CrCms\Passport\Controllers;

use CrCms\Passport\DataProviders\User\DeleteDataProvider;
use CrCms\Passport\DataProviders\User\IndexDataProvider;
use CrCms\Passport\DataProviders\User\StoreDataProvider;
use CrCms\Passport\DataProviders\User\UpdateDataProvider;
use CrCms\Passport\Handlers\User\DeleteHandler;
use CrCms\Passport\Handlers\User\ListHandler;
use CrCms\Passport\Handlers\User\StoreHandler;
use CrCms\Passport\Handlers\User\UpdateHandler;
use CrCms\Microservice\Routing\Controller;
use CrCms\Passport\Resources\UserResource;

/**
 * Class UserController
 * @package CrCms\Passport\Controllers
 */
class UserController extends Controller
{
    /**
     * @param IndexDataProvider $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexDataProvider $provider)
    {
        $paginate = $this->app->make(ListHandler::class)->handle($provider);

        return $this->response->paginator($paginate, UserResource::class);
    }

    /**
     * @param StoreDataProvider $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreDataProvider $provider)
    {
        $model = $this->app->make(StoreHandler::class)->handle($provider);

        return $this->response->resource($model, UserResource::class);
    }

    /**
     * @param UpdateDataProvider $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateDataProvider $provider)
    {
        $model = $this->app->make(UpdateHandler::class)->handle($provider);

        return $this->response->resource($model, UserResource::class);
    }

    /**
     * @param DeleteDataProvider $provider
     * @return \CrCms\Microservice\Server\Http\Response
     */
    public function destroy(DeleteDataProvider $provider)
    {
        $row = $this->app->make(DeleteHandler::class)->handle($provider);

        return $this->response->noContent();
    }
}
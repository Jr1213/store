<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateCategoryRequest;
use App\Http\Requests\Products\UpdateCategoryRequest;
use App\Http\Resources\Products\CategoriesResource;
use App\Models\Products\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            return $this->successResponse(CategoriesResource::collection(Category::all()));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request): JsonResponse
    {
        try {
            $file = $request->file('path')->store('categories', 'public');
            $request['path'] = $file;
            Category::create($request->all());
            return $this->successResponse('created', Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        try {
            return $this->successResponse(new CategoriesResource($category));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        try {
            $data = $request->all();
            if ($request->hasFile('path')) {
                $image = $request->file('path')->store('categories', 'public');
                $data['path']   = $image;
                Storage::drive('public')->delete($category->path);
            }
            $category->update($data);
            return $this->successResponse('updated');
        } catch (Exception $th) {
            return $this->exceptionResponse($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        try {
            $category->delete();
            return  $this->successResponse([],Response::HTTP_NO_CONTENT);
        } catch (Exception $th) {
            return $this->exceptionResponse($th);
        }
    }
}

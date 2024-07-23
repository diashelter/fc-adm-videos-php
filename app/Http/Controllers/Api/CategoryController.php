<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use Core\Application\UseCase\Category\CategoryInput;
use Core\Application\UseCase\Category\Create\CreateCategoryInput;
use Core\Application\UseCase\Category\Create\CreateCategoryUseCase;
use Core\Application\UseCase\Category\Delete\DeleteCategoryUseCase;
use Core\Application\UseCase\Category\List\ListCategoryUseCase;
use Core\Application\UseCase\Category\ListAll\ListCategoriesInput;
use Core\Application\UseCase\Category\ListAll\ListCategoriesUseCase;
use Core\Application\UseCase\Category\Update\CategoryUpdateInput;
use Core\Application\UseCase\Category\Update\UpdateCategoryUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoriesUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListCategoriesInput(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('totalPage', 15),
            )
        );

        return CategoryResource::collection(collect($response->items))
            ->additional([
                'meta' => [
                    'total' => $response->total,
                    'current_page' => $response->current_page,
                    'last_page' => $response->last_page,
                    'first_page' => $response->first_page,
                    'per_page' => $response->per_page,
                    'to' => $response->to,
                    'from' => $response->from,
                ],
            ]);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryUseCase $useCase)
    {
        $response = $useCase->execute(input: new CreateCategoryInput(
            name: $request->name,
            description: $request->description ?? '',
            isActive: (bool) $request->is_active ?? true,
        ));

        return (new CategoryResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ListCategoryUseCase $useCase, string $id)
    {
        $response = $useCase->execute(new CategoryInput($id));

        return (new CategoryResource($response))->response();
    }

    public function update(UpdateCategoryRequest $request, UpdateCategoryUseCase $useCase, string $id)
    {
        $response = $useCase->execute(
            input: new CategoryUpdateInput(
                id: $id,
                name: $request->name,
                description: $request->description,
            )
        );

        return (new CategoryResource($response))->response();
    }

    public function destroy(DeleteCategoryUseCase $useCase, string $id)
    {
        $response = $useCase->execute(
            input: new CategoryInput($id)
        );

        return response()->noContent();
    }
}

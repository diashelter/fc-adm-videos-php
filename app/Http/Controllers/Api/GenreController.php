<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use Core\Application\UseCase\Genre\Create\CreateGenreUseCase;
use Core\Application\UseCase\Genre\Create\InputCreateGenreDto;
use Core\Application\UseCase\Genre\Delete\DeleteGenreUseCase;
use Core\Application\UseCase\Genre\Delete\InputDeleteGenreDto;
use Core\Application\UseCase\Genre\List\InputListGenreDto;
use Core\Application\UseCase\Genre\List\ListGenreUseCase;
use Core\Application\UseCase\Genre\ListAll\InputListGenresDto;
use Core\Application\UseCase\Genre\ListAll\ListGenresUseCase;
use Core\Application\UseCase\Genre\Update\InputUpdateGenreDto;
use Core\Application\UseCase\Genre\Update\UpdateGenreUseCase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GenreController extends Controller
{
    public function index(Request $request, ListGenresUseCase $useCase)
    {
        $response = $useCase->execute(new InputListGenresDto(
            filter: $request->get('filter', ''),
            order: $request->get('order', 'DESC'),
            page: (int) $request->get('page', 1),
            totalPage: (int) $request->get('totalPage', 15),
        ));

        return GenreResource::collection(collect($response->items))
            ->additional([
                'meta' => [
                    'total' => $response->total,
                    'current_page' => $response->currentPage,
                    'last_page' => $response->lastPage,
                    'first_page' => $response->firstPage,
                    'per_page' => $response->perPage,
                    'to' => $response->to,
                    'from' => $response->from,
                ],
            ]);
    }

    public function store(StoreGenreRequest $request, CreateGenreUseCase $useCase)
    {
        $response = $useCase->execute(input: new InputCreateGenreDto(
            name: $request->name,
            categoriesId: $request->categories_ids,
            isActive: (bool) $request->is_active ?? true,
        ));

        return (new GenreResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ListGenreUseCase $useCase, string $id)
    {
        $response = $useCase->execute(new InputListGenreDto($id));

        return new GenreResource($response);
    }

    public function update(UpdateGenreRequest $request, UpdateGenreUseCase $useCase, string $id)
    {
        $response = $useCase->execute(new InputUpdateGenreDto(
            id: $id,
            name: $request->name,
            categoriesId: $request->categories_ids
        ));

        return new GenreResource($response);
    }

    public function destroy(DeleteGenreUseCase $useCase, string $id)
    {
        $response = $useCase->execute(input: new InputDeleteGenreDto($id));

        return response()->noContent();
    }
}

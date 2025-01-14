<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $category;

    public function __construct(CategoryService $category)
    {
        $this->category = $category;
    }

    /**
     * @OA\Get(
     *     tags={"Categories"},
     *     path="/categories",
     *     summary="Obter todos as recursos",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *          name="paginate", in="query", description="Quantidade de dados por página",
     *          @OA\Schema(type="int")
     *     ),
     *     @OA\Parameter(
     *          name="name", in="query", description="Filtrar recurso pelo nome",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          name="active", in="query", description="Filtrar recursos ativos ou inativos",
     *          @OA\Schema(type="bool")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function index(Request $request)
    {
        return CategoryResource::collection(
            $this->category->getAllCategories(
                (array) $request->all()
            )
        );
    }


    /**
     * @OA\Post(
     *     tags={"Categories"},
     *     path="/categories",
     *     summary="Armazenar novo recurso no banco de dados",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="active", type="bool"),
     *        )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created"
     *     )
     * )
     */
    public function store(CategoryRequest $request)
    {
        return new CategoryResource(
            $this->category->createCategory(
                (array) $request->validated()
            )
        );
    }

    /**
     * @OA\Get(
     *     tags={"Categories"},
     *     path="/categories/{id}",
     *     summary="Ver um recurso",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string", format="char32"),
     *         @OA\Examples(example="uuid", value="0006faf6-7a61-426c-9034-579f2cfcfa83", summary="UUID value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function show(string $id)
    {
        return new CategoryResource(
            $this->category->findCategoryById($id)
        );
    }

    /**
     * @OA\Put(
     *     tags={"Categories"},
     *     path="/categories/{id}",
     *     summary="Atualizar um recurso no banco de dados",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string", format="char32"),
     *         @OA\Examples(example="uuid", value="0006faf6-7a61-426c-9034-579f2cfcfa83", summary="UUID value."),
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="active", type="bool"),
     *        )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No Content"
     *     )
     * )
     */
    public function update(CategoryRequest $request, string $id)
    {
        return $this->category->updateCategory(
            (array) $request->validated(),
            $id
        );
    }

    /**
     * @OA\Delete(
     *     tags={"Categories"},
     *     path="/categories/{id}",
     *     summary="Deletar recurso",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string", format="char32"),
     *         @OA\Examples(example="uuid", value="0006faf6-7a61-426c-9034-579f2cfcfa83", summary="UUID value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        return $this->category->deleteCategory($id);
    }
}

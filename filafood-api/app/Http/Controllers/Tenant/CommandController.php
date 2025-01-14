<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommandRequest;
use App\Http\Resources\CommandResource;
use App\Services\CommandService;
use Illuminate\Http\Request;

class CommandController extends Controller
{
    protected $command;

    public function __construct(CommandService $command)
    {
        $this->command = $command;
    }

    /**
     * @OA\Get(
     *     tags={"Commands"},
     *     path="/commands",
     *     summary="Obter todos os recursos",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *          name="paginate", in="query", description="Quantidade de dados por página",
     *          @OA\Schema(type="int")
     *     ),
     *     @OA\Parameter(
     *          name="identify", in="query", description="Filtrar recurso pelo número identificador",
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *          name="number", in="query", description="Filtrar recurso pelo número da comanda",
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function index(Request $request)
    {
        return CommandResource::collection(
            $this->command->getAllCommands(
                (array) $request->all()
            )
        );
    }

    /**
     * @OA\Post(
     *     tags={"Commands"},
     *     path="/commands",
     *     summary="Armazenar novo recurso no banco de dados",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="number", type="integer"),
     *              @OA\Property(
     *                  property="products",
     *                  type="array",
     *                  @OA\Items(
     *                       @OA\Property(
     *                           property="product_id",
     *                           type="string",
     *                           format="uuid",
     *                           description="ID do produto",
     *                           example="d290f1ee-6c54-4b01-90e6-d701748f0851"
     *                       ),
     *                       @OA\Property(
     *                           property="quantity",
     *                           type="integer",
     *                           description="Quantidade do produto",
     *                           example=5,
     *                           minimum=1
     *                       )
     *                  )
     *              ),
     *        )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created"
     *     )
     * )
     */
    public function store(CommandRequest $request)
    {
        return new CommandResource(
            $this->command->createCommand(
                (array) $request->validated()
            )
        );
    }

    /**
     * @OA\Get(
     *     tags={"Commands"},
     *     path="/commands/{id}",
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
        return new CommandResource(
            $this->command->findCommandById($id)
        );
    }
}

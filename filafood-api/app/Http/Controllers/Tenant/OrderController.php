<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $order;

    public function __construct(OrderService $order)
    {
        $this->order = $order;
    }

    /**
     * @OA\Get(
     *     tags={"Orders"},
     *     path="/orders",
     *     summary="Obter todos os recursos",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *          name="paginate", in="query", description="Quantidade de dados por página",
     *          @OA\Schema(type="int")
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
        return OrderResource::collection(
            $this->order->getAllOrders(
                (array) $request->all()
            )
        );
    }

    /**
     * @OA\Get(
     *     tags={"Orders"},
     *     path="/orders/{id}",
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
        return new OrderResource(
            $this->order->findOrderById($id)
        );
    }
}

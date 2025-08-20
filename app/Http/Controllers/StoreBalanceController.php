<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelpers;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\StoreBalanceResource;
use App\Models\Store;
use App\Models\StoreBalance;
use Illuminate\Support\Str;
use App\Repositories\StoreBalanceRepository;
use Illuminate\Http\Request;

class StoreBalanceController extends Controller
{
    private StoreBalanceRepository $storeBalanceRepository;

    public function __construct(StoreBalanceRepository $storeBalanceRepository)
    {
        $this->storeBalanceRepository = $storeBalanceRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $users = $this->storeBalanceRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelpers::jsonResponse(true, 'Store Balance retrieved successfully.', StoreBalanceResource::collection($users), 200);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $users = $this->storeBalanceRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelpers::jsonResponse(true, 'Store Balance retrieved successfully.', PaginateResource::make($users, StoreBalanceResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            if (! Str::isUuid($id)) {
                return ResponseHelpers::jsonResponse(false, 'Invalid UUID.', null, 422);
            }

            $storeBalance = $this->storeBalanceRepository->getById($id);

            if (!$storeBalance) {
                return ResponseHelpers::jsonResponse(true, 'Store Balance not found.', null, 404);
            }

            return ResponseHelpers::jsonResponse(true, 'Store Balance retrieved successfully.', new StoreBalanceResource($storeBalance), 200);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}

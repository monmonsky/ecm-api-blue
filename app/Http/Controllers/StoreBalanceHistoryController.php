<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelpers;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\StoreBalanceHistoryResource;
use App\Models\Store;
use App\Repositories\StoreBalanceHistoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StoreBalanceHistoryController extends Controller
{
    private StoreBalanceHistoryRepository $storeBalanceHistoryRepository;

    public function __construct(StoreBalanceHistoryRepository $storeBalanceHistoryRepository)
    {
        $this->storeBalanceHistoryRepository = $storeBalanceHistoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $storeBalanceHistorie = $this->storeBalanceHistoryRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelpers::jsonResponse(true, 'Store balance histories retrieved successfully.', StoreBalanceHistoryResource::collection($storeBalanceHistorie), 200);
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
            $storeBalanceHistorie = $this->storeBalanceHistoryRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelpers::jsonResponse(true, 'Users retrieved successfully.', PaginateResource::make($storeBalanceHistorie, StoreBalanceHistoryResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse(false, $e->getMessage(), null, 500);
        }
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

            $storeBalanceHistory = $this->storeBalanceHistoryRepository->getById($id);

            if (!$storeBalanceHistory) {
                return ResponseHelpers::jsonResponse(true, 'User not found.', null, 404);
            }

            return ResponseHelpers::jsonResponse(true, 'User retrieved successfully.', StoreBalanceHistoryResource::make($storeBalanceHistory), 200);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelpers;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\StoreUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\StoreResource;
use App\Interfaces\StoreRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    private StoreRepositoryInterface $storeRepository;

    public function __construct(StoreRepositoryInterface $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $stores = $this->storeRepository->getAll(
                $request->search,
                $request->is_verified,
                $request->limit,
                true
            );

            return ResponseHelpers::jsonResponse(true, 'Store retrieved successfully.', StoreResource::collection($stores), 200);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'is_verified' => 'nullable|boolean',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $stores = $this->storeRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['is_verified'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelpers::jsonResponse(true, 'Store retrieved successfully.', PaginateResource::make($stores, StoreResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $request->validated();
        try {
            $user = $this->storeRepository->create($request->all());

            return ResponseHelpers::jsonResponse(true, 'Store created successfully.', new StoreResource($user), 201);
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

            $store = $this->storeRepository->getById($id);

            if (!$store) {
                return ResponseHelpers::jsonResponse(true, 'Store not found.', null, 404);
            }

            return ResponseHelpers::jsonResponse(true, 'Store retrieved successfully.', new StoreResource($store), 200);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function updateVerifiedStatus(Request $request, string $id)
    {
        try {

            if (! Str::isUuid($id)) {
                return ResponseHelpers::jsonResponse(false, 'Invalid UUID.', null, 422);
            }

            $store = $this->storeRepository->getById($id);
            if (!$store) {
                return ResponseHelpers::jsonResponse(true, 'Store not found.', null, 404);
            }

            $store = $this->storeRepository->updateVerifiedStatus($id, true);

            return ResponseHelpers::jsonResponse(true, 'Store verification status updated successfully.', new StoreResource($store), 200);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateRequest $request, string $id)
    {
        $request->validated();
        try {

            if (! Str::isUuid($id)) {
                return ResponseHelpers::jsonResponse(false, 'Invalid UUID.', null, 422);
            }

            $store = $this->storeRepository->getById($id);

            if (!$store) {
                return ResponseHelpers::jsonResponse(true, 'Store not found.', null, 404);
            }

            $store = $this->storeRepository->update($id, $request->all());

            return ResponseHelpers::jsonResponse(true, 'Store updated successfully.', new StoreResource($store), 200);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if (! Str::isUuid($id)) {
                return ResponseHelpers::jsonResponse(false, 'Invalid UUID.', null, 422);
            }

            $store = $this->storeRepository->getById($id);

            if (!$store) {
                return ResponseHelpers::jsonResponse(true, 'Store not found.', null, 404);
            }

            $this->storeRepository->delete($id);

            return ResponseHelpers::jsonResponse(true, 'Store deleted successfully.', null, 204);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}

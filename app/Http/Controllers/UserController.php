<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelpers;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Str;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $users = $this->userRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelpers::jsonResponse(true, 'Users retrieved successfully.', UserResource::collection($users), 200);
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
            $users = $this->userRepository->getAllPaginated(
              $request['search'] ?? null,
              $request['row_per_page']  
            );

            return ResponseHelpers::jsonResponse(true, 'Users retrieved successfully.', PaginateResource::make($users, UserResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $request->validated();
        try {
            $user = $this->userRepository->create($request->all());

            return ResponseHelpers::jsonResponse(true, 'User created successfully.', new UserResource($user), 201);
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

            $user = $this->userRepository->getById($id);

            if(!$user) {
                return ResponseHelpers::jsonResponse(true, 'User not found.', null, 404);
            }

            return ResponseHelpers::jsonResponse(true, 'Users retrieved successfully.', UserResource::make($user), 200);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $request->validated();
        try {

            if (! Str::isUuid($id)) {
                return ResponseHelpers::jsonResponse(false, 'Invalid UUID.', null, 422);
            }

            $user = $this->userRepository->getById($id);

            if(!$user) {
                return ResponseHelpers::jsonResponse(true, 'User not found.', null, 404);
            }

            $user = $this->userRepository->update($id, $request->all());

            return ResponseHelpers::jsonResponse(true, 'User updated successfully.', new UserResource($user), 200);
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
            
            $user = $this->userRepository->getById($id);

            if(!$user) {
                return ResponseHelpers::jsonResponse(true, 'User not found.', null, 404);
            }

            $user = $this->userRepository->delete($id);

            return ResponseHelpers::jsonResponse(true, 'User deleted successfully.', new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelpers::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}

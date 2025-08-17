<?php

namespace App\Repositories;

use App\Interfaces\StoreRepositoryInterface;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class StoreRepository implements StoreRepositoryInterface
{
    public function getAll(?string $search = null, ?bool $isVerified, ?int $limit = null, bool $execute = true)
    {
        $query = Store::where(function ($query) use ($search, $isVerified) {
            if ($search) {
                $query->search($search);
            }

            if ($isVerified !== null) {
                $query->where('is_verified', $isVerified);
            }
        });

        if ($limit) {
            $query->take($limit);
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(?string $search = null, ?bool $isVerified, ?int $rowsPerPage = null)
    {
        $query = $this->getAll($search, $isVerified, null, false);

        return $query->paginate($rowsPerPage);
    }

    public function getById(string $id)
    {
        $query = Store::where('id', $id);

        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $store = new Store();
            $store->user_id = $data['user_id'];
            $store->name = $data['name'];
            $store->logo = $data['logo']->store('assets/store', 'public');
            $store->about = $data['about'];
            $store->phone = $data['phone'];
            $store->address_id = $data['address_id'];
            $store->city = $data['city'];
            $store->address = $data['address'];
            $store->postal_code = $data['postal_code'];
            $store->save();

            $store->storeBalance()->create(['balance' => 0]);

            DB::commit();

            return $store;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to create store: ' . $e->getMessage());
        }
    }

    public function updateVerifiedStatus(string $id, bool $isVerified)
    {
        DB::beginTransaction();

        try {
            $store = $this->getById($id);
            if (!$store) {
                throw new \Exception('Store not found.');
            }

            $store->is_verified = $isVerified;
            $store->save();

            DB::commit();

            return $store;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to update store verification status: ' . $e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $store = Store::find($id);
            $store->name = $data['name'];
            if (isset($data['logo'])) {
                $store->logo = $data['logo']->store('assets/store', 'public');
            }
            $store->about = $data['about'];
            $store->phone = $data['phone'];
            $store->address_id = $data['address_id'];
            $store->city = $data['city'];
            $store->address = $data['address'];
            $store->postal_code = $data['postal_code'];
            $store->save();

            DB::commit();

            return $store;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to update store: ' . $e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $store = Store::find($id);
            $store->delete();

            DB::commit();

            return $store;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to delete store: ' . $e->getMessage());
        }
    }
}

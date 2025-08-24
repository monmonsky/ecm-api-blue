<?php

namespace App\Repositories;

use App\Interfaces\StoreBalanceRepositoryInterface;
use App\Models\StoreBalance;
use Illuminate\Support\Facades\DB;

class StoreBalanceRepository implements StoreBalanceRepositoryInterface
{
    public function getAll(?string $search = null, ?int $limit = null, bool $execute = true)
    {
        $query = StoreBalance::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        })->with(['storeBalanceHistories']);

        if ($limit) {
            $query->take($limit);
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(?string $search = null, ?int $rowsPerPage = null)
    {
        $query = $this->getAll($search, null, false);

        return $query->paginate($rowsPerPage);
    }

    public function getById(string $id)
    {
        $query = StoreBalance::where('id', $id)->with(['storeBalanceHistories']);;

        return $query->first();
    }

    public function credit(string $id, string $amount)
    {
        DB::beginTransaction();

        try {

            $storeBalance = StoreBalance::find($id);

            if (!$storeBalance) {
                throw new \Exception('Store Balance not found.');
            }

            $storeBalance->balance = bcadd($storeBalance->balance, $amount, 2);
            $storeBalance->save();

            DB::commit();

            return $storeBalance;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to credit store balance: ' . $e->getMessage());
        }
    }

    public function debit(string $id, string $amount)
    {
        DB::beginTransaction();

        try {

            $storeBalance = $this->getById($id);

            if (!$storeBalance) {
                throw new \Exception('Store Balance not found.');
            }

            if (bccomp($storeBalance->balance, $amount, 2) < 0) {
                throw new \Exception('Insufficient balance.');
            }

            $storeBalance->balance = bcsub($storeBalance->balance, $amount, 2);
            $storeBalance->save();

            DB::commit();

            return $storeBalance;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to credit store balance: ' . $e->getMessage());
        }
    }
}

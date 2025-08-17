<?php

namespace App\Interfaces;

interface StoreRepositoryInterface
{
    public function getAll(?string $search, ?bool $isVerified, ?int $limit, bool $execute);

    public function getAllPaginated(?string $search, ?bool $isVerified, ?int $rowsPerPage);

    public function getById(string $id);

    public function create(array $data);

    public function updateVerifiedStatus(string $id, bool $isVerified);

    public function update(string $id, array $data);

    public function delete(string $id);
}

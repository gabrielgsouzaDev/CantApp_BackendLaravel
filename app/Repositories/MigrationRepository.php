<?php

namespace App\Repositories;

use App\Models\Migration;

class MigrationRepository
{
    protected $model;

    public function __construct(Migration $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
}

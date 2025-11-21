<?php

namespace App\Repositories;

use App\Models\FailedJob;

class FailedJobRepository
{
    protected $model;

    public function __construct(FailedJob $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function delete($id)
    {
        $job = $this->model->find($id);
        if ($job) {
            return $job->delete();
        }
        return false;
    }
}

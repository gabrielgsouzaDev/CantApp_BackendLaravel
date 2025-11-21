<?php

namespace App\Repositories;

use App\Models\JobBatch;

class JobBatchRepository
{
    protected $model;

    public function __construct(JobBatch $model)
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

    public function update($id, array $data)
    {
        $batch = $this->model->find($id);
        if ($batch) {
            $batch->update($data);
            return $batch;
        }
        return null;
    }

    public function delete($id)
    {
        $batch = $this->model->find($id);
        if ($batch) {
            return $batch->delete();
        }
        return false;
    }
}

<?php

namespace App\Repositories;

use App\Models\Job;

class JobRepository
{
    protected $model;

    public function __construct(Job $model)
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
        $job = $this->model->find($id);
        if ($job) {
            $job->update($data);
            return $job;
        }
        return null;
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

<?php

namespace App\Repositories;

use App\Models\Cache;

class CacheRepository
{
    protected $model;

    public function __construct(Cache $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($key)
    {
        return $this->model->find($key);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($key, array $data)
    {
        $cache = $this->model->find($key);
        if ($cache) {
            $cache->update($data);
            return $cache;
        }
        return null;
    }

    public function delete($key)
    {
        $cache = $this->model->find($key);
        if ($cache) {
            return $cache->delete();
        }
        return false;
    }
}

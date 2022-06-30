<?php

namespace HasManySelectField\Controllers;


use App\Http\Controllers\Controller;
use HasManySelectField\Repositories\ItemRepository;

class ItemsController extends Controller
{
    /**
     * @var ItemRepository
     */
    private $repository;

    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->getItems();
    }

    public function search()
    {
        return $this->repository->search();
    }

    public function detach()
    {
        return $this->repository->detachItem(request('itemId'));
    }

    public function attach()
    {
        $this->repository->attachItem(request('itemId'));

        return response()->json($this->repository->getItem(request('itemId')));
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27/12/20
 * Time: 02:16 ص
 */

namespace App\Services;


use App\Repositories\ProductsRepository;

class ProductService
{
    /**
     * @var $productRepository
     */
    protected $productRepository;

    /**
     * ProductService constructor.
     *
     * @param ProductsRepository $productRepository
     */
    public function __construct(ProductsRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get all post.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->productRepository->all();
    }

    /**
     * Get post by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->productRepository->find($id);
    }

    /**
     * Get post by name.
     *
     * @param $name
     * @return String
     */
    public function findProductsByName($name)
    {
        return $this->productRepository->findProductsByName($name);
    }
}
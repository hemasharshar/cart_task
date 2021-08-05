<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27/12/20
 * Time: 02:16 ص
 */

namespace App\Services;


use App\Repositories\UserRepository;

class UserService
{
    /**
     * @var $productRepository
     */
    protected $userRepository;

    /**
     * ProductService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get post by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->userRepository->find($id);
    }
}
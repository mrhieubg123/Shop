<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use App\Repositories\BrandRepository;
use App\Repositories\ProductRepository;



class HomeController extends Controller
{
    protected $categoryRepository;
    protected $brandRepository;
    protected $productRepository;
    public function __construct(CategoryRepository $categoryRepository,BrandRepository $brandRepository,ProductRepository $productRepository){
        $this->categoryRepository = $categoryRepository;
        $this->brandRepository = $brandRepository;
        $this->productRepository=$productRepository;
    }
    

    public function index() {
  		$categories=$this->categoryRepository->getAll();
  		$brands=$this->brandRepository->getAll();
  		$products=$this->productRepository->getList();
        return view('home',compact('categories','brands','products'));
    }
    public function login() {
    	return view('product.shop_login');
    }
}

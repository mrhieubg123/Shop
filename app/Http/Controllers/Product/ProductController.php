<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\BrandRepository;

class ProductController extends Controller
{
	protected $categoryRepository;
    protected $brandRepository;
    protected $productRepository;
    public function __construct(CategoryRepository $categoryRepository,BrandRepository $brandRepository,ProductRepository $productRepository){
        $this->categoryRepository = $categoryRepository;
        $this->brandRepository = $brandRepository;
        $this->productRepository=$productRepository;
    }
    //
    public function details($id){
        // todo
        $categories=$this->categoryRepository->getAll();
  		$brands=$this->brandRepository->getAll();
        $product=$this->productRepository->getById($id);
        return view('product.details',compact('product','categories','brands'));
    }
    public function addToCart($id){
    	$product=$this->productRepository->getById($id);
    	$cart=\Session::get('cart');
        if(!isset($cart[$product->id]))
            $cart[$product->id]['qty']=0;
    	$cart[$product->id]=array(
    		"id"	=>$product->id,
    		"name"	=>$product->name,
    		"price"	=>$product->price,
    		"img"	=>$product->imageproducts->first()->image_url,
    		"qty" 	=>$cart[$product->id]['qty']+1,
    	);
    	\Session::put('cart',$cart);
        return redirect()->back();
    }
    public function clearCart(){
        \Session::flush();
        //$request->session()->flush();
        return redirect()->back();
    }
    public function updateCart($id,$qty){
    	$cart=\Session::get('cart');
    	if ($cart[$id]['qty']+$qty > 0) {
            $cart[$id]['qty'] += $qty;
        } else {
            unset($cart[$id]);
        }
        \Session::put('cart',$cart);
        return redirect()->back();
    }
    public function updateToCart(Request $request,$id){
        $qty=$request->input('quantity');
        $product=$this->productRepository->getById($id);
        $cart=\Session::get('cart');
        if(!isset($cart[$product->id]))
            $cart[$product->id]['qty']=0;
        $cart[$product->id]=array(
            "id"    =>$product->id,
            "name"  =>$product->name,
            "price" =>$product->price,
            "img"   =>$product->imageproducts->first()->image_url,
            "qty"   =>$cart[$product->id]['qty'],
        );
        if ($cart[$id]['qty']+$qty > 0) {
            $cart[$id]['qty'] += $qty;
        } else {
            unset($cart[$id]);
        }
        \Session::put('cart',$cart);
        return redirect()->back();
    }

    public function cart(){
        // todo
        return view('product.cart');
    }
}

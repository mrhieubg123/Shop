<?php
#app/Http/Controller/ShopAccount.php
namespace App\Http\Controllers;

use App\Models\ShopOrder;
use App\Models\ShopOrderStatus;
use App\Models\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Repositories\CategoryRepository;
use App\Repositories\BrandRepository;
use App\Repositories\LoveRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductOrderRepository;

class ShopAccount extends Controller
{
    public function __construct()
    {
        // parent::__construct();
    }

    public function index()
    {
        $user = Auth::user();
        $id   = $user->id;
        return view('member.index')->with(array(
            'title'       => trans('account.my_profile'),
            'user'        => $user,
            'layout_page' => 'shop_profile',
        ));
    }

    public function changePassword()
    {
        $user = Auth::user();
        $id   = $user->id;
        return view('member.change_password')->with(array(
            'title'       => trans('account.change_password'),
            'user'        => $user,
            'layout_page' => 'shop_profile',
        ));
    }

    public function postChangePassword(Request $request)
    {
        $user         = Auth::user();
        $id           = $user->id;
        $dataUser     = User::find($id);
        $password     = $request->get('password');
        $password_old = $request->get('password_old');
        if (trim($password_old) == '') {
            return redirect()->back()->with(['password_old_error' => trans('account.password_old_required')]);
        } else {
            if (!\Hash::check($password_old, $dataUser->password)) {
                return redirect()->back()->with(['password_old_error' => trans('account.password_old_notcorrect')]);
            }
        }
        $messages = [
            'required' => trans('validation.required'),
        ];
        $v = Validator::make($request->all(), [
            'password_old' => 'required',
            'password'     => 'required|string|min:6|confirmed',
        ], $messages);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }

        $dataUser->update(['password' => bcrypt($password)]);
        return redirect()->route('member.index')->with(['message' => trans('account.update_success')]);
    }

    public function changeInfomation()
    {
        $user     = Auth::user();
        $id       = $user->id;
        $dataUser = User::find($id);
        return view('member.change_infomation')->with(array(
            'title'       => trans('account.change_infomation'),
            'dataUser'    => $dataUser,
            'layout_page' => 'shop_profile',
        ));
    }

    public function postChangeInfomation(Request $request)
    {
        $user     = Auth::user();
        $id       = $user->id;
        $dataUser = User::find($id);

        $messages = [
            'required' => trans('validation.required'),
        ];
        $v = Validator::make($request->all(), [
            'name'     => 'required',
            'phone'    => 'required|regex:/^0[^0][0-9\-]{7,13}$/',
            'address1' => 'required',
            'address2' => 'required',
        ], $messages);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }

        $dataUser->update($request->all());
        return redirect()->route('member.index')->with(['message' => trans('account.update_success')]);
    }

    /**
     * [profile description]
     * @return [type] [description]
     */
    public function orderList()
    {
        $user        = Auth::user();
        $id          = $user->id;
        $orders      = ShopOrder::with('orderTotal')->where('user_id', $id)->sort()->get();
        $statusOrder = ShopOrderStatus::pluck('name', 'id')->all();
        return view('member.order_list')->with(array(
            'title'       => trans('account.order_list'),
            'user'        => $user,
            'orders'      => $orders,
            'statusOrder' => $statusOrder,
            'layout_page' => 'shop_profile',
        ));
    }
    public function wishList(){
        if (Auth::user()) {
            $categoryRepository=new CategoryRepository();
            $brandRepository=new BrandRepository();
            $loveRepository=new LoveRepository();
            $categories=$categoryRepository->getAll();
            $brands=$brandRepository->getAll();
            $wishlist=$loveRepository->getList(Auth::id());
            return view('product.wishlist',compact('wishlist','brands','categories'));
        }
        return view('product.shop_login',
            array(
                'title' => trans('language.login'),
            )
        );
    }
    public function addToWishList($id){
        if (Auth::user()) {
            $loveRepository=new LoveRepository();
            $wishlist=$loveRepository->getProduct(Auth::id(),$id)->first();
            if(isset($wishlist)){
                if($wishlist->loved==1)
                    $b=array('loved' =>0 , );
                else $b=array('loved' =>1 , );
                $loveRepository->update($wishlist->id,$b);
            }
            else{
                $b=array(
                    'user_id'=>Auth::id(),
                    'product_id'=>$id,
                    'loved'=>1,);
                $loveRepository->store($b);
            }
            return redirect()->back();
        }
        return view('product.shop_login',
            array(
                'title' => trans('language.login'),
            )
        );
    }
    public function checkout(Request $request){
        if(Auth::user()){
            $v = Validator::make($request->all(), [
                'toname'     => 'required',
                'phone'    => 'required|regex:/^0[^0][0-9\-]{7,13}$/',
                'address1' => 'required',
            ]);
            if ($v->fails()) {
                return redirect()->back()->withErrors($v->errors());
            }
            $cart=\Session::get('cart');
            $total=0;
            foreach ($cart as $item) {
                $total+=$item['price']*$item['qty'];
            }
            if($total==0){
                \Session::flash('cartnull','Giỏ hàng trống');
                return redirect()->back();
            }
            $order=\Session::get('order');
            $order=array(
                'user_id'       =>Auth::id(),
                'customer_name' =>$request->toname,
                'customer_phone'=>$request->phone,
                'address'       =>$request->address1,
                'order_total'   =>$total,
                'status'        =>0,
            );
            \Session::put('order',$order);
            return view('member.shop_checkout',compact('order'));
        }
        return view('product.shop_login',
            array(
                'title' => trans('language.login'),
            )
        );
    }
    public function checkoutConfirm(){
        if(Auth::user()){
            $order=new OrderRepository();
            $orderProduct=new ProductOrderRepository();
            $c=\Session::get('order');
            $order->store($c);
            foreach ($order->getAll() as $item) {
                $orderId=$item->id;
            }
            foreach (\Session::get('cart') as $cart) {
                $d=array(
                    'order_id'=>$orderId,
                    'product_id'=>$cart['id'],
                    'quantity'=>$cart['qty'],
                );
                $orderProduct->store($d);
            }
            return redirect()->route('home');
        }
        return view('product.shop_login',
            array(
                'title' => trans('language.login'),
            )
        );
    }
}

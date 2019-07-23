<?php
#app/Http/Controller/ShopAccount.php
namespace App\Http\Controllers;

use App\Models\ShopOrder;
use App\Models\ShopOrderStatus;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShopAccount extends GeneralController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $user = Auth::user();
        $id   = $user->id;
        return view(SITE_THEME . '.account.index')->with(array(
            'title'       => trans('account.my_profile'),
            'user'        => $user,
            'layout_page' => 'shop_profile',
        ));
    }

    public function changePassword()
    {
        $user = Auth::user();
        $id   = $user->id;
        return view(SITE_THEME . '.account.change_password')->with(array(
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
        return view(SITE_THEME . '.account.change_infomation')->with(array(
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
        return view(SITE_THEME . '.account.order_list')->with(array(
            'title'       => trans('account.order_list'),
            'user'        => $user,
            'orders'      => $orders,
            'statusOrder' => $statusOrder,
            'layout_page' => 'shop_profile',
        ));
    }

}

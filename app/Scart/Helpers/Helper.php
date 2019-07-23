<?php
namespace App\Scart;

use App\Models\Config;
use App\Models\ConfigGlobal;
use App\Models\Language;
use App\Models\Layout;
use App\Models\LayoutUrl;
use App\Models\ShopCurrency;
use Illuminate\Support\Facades\Mail;
use Log;

class Helper
{
    public static function strToUrl($str)
    {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );

        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return strtolower(preg_replace(
            array('/[\'\/~`\!@#\$%\^&\*\(\)\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', '/[\s-]+|[-\s]+|[--]+/', '/^[-\s_]|[-_\s]$/'),
            array('', '-', ''),
            strtolower($str)));
    }

    //Currency
    public static function currencyRender(float $money, $currency = null, $rate = null, $space_between_symbol = false, $useSymbol = true)
    {
        return ShopCurrency::render($money, $currency, $rate, $space_between_symbol, $useSymbol);
    }

    public static function currencyOnlyRender(float $money, $currency, $space_between_symbol = false, $include_symbol = true)
    {
        return ShopCurrency::onlyRender($money, $currency, $space_between_symbol, $include_symbol);
    }
    public static function currencySumCart($details, float $rate = null)
    {
        return ShopCurrency::sumCart($details, $rate);
    }
    public static function currencyValue(float $money, $rate = null)
    {
        return ShopCurrency::getValue($money, $rate);
    }
    public static function currencyCode()
    {
        return ShopCurrency::getCode();
    }
    public static function currencyRate()
    {
        return ShopCurrency::getRate();
    }
    public static function currencyFormat(float $money)
    {
        return ShopCurrency::format($money);
    }
    public static function getCurrency()
    {
        return ShopCurrency::getCurrency();
    }

    private static $currencies = null;
    public static function currencies()
    {
        if (self::$currencies !== null) {
            return self::$currencies;
        }
        self::$currencies = ShopCurrency::getAll();
        return self::$currencies;
    }

    //End currency

    private static $languages = null;
    public static function languages()
    {
        if (self::$languages !== null) {
            return self::$languages;
        }
        self::$languages = Language::where('status', 1)->get()->keyBy('code');
        return self::$languages;
    }

    private static $layouts = null;
    public static function layouts()
    {
        if (self::$layouts !== null) {
            return self::$layouts;
        }
        self::$layouts = Layout::getLayout();
        return self::$layouts;
    }

    private static $layoutsUrl = null;
    public static function layoutsUrl()
    {
        if (self::$layoutsUrl !== null) {
            return self::$layoutsUrl;
        }
        self::$layoutsUrl = LayoutUrl::getAllUrl();
        return self::$layoutsUrl;
    }

    //Value config
    private static $configs = null;
    public static function configs()
    {
        if (self::$configs !== null) {
            return self::$configs;
        }
        self::$configs = Config::pluck('value', 'key')->all();
        return self::$configs;
    }
    private static $configsGlobal = null;
    public static function configsGlobal()
    {
        if (self::$configsGlobal !== null) {
            return self::$configsGlobal;
        }
        self::$configsGlobal = ConfigGlobal::first();
        return self::$configsGlobal;
    }
    //End config

    //Extensions
    public static function getExtensionsGroup($group, $onlyActive = true)
    {
        return Config::getExtensionsGroup($group, $onlyActive);
    }
    //End Extensions

    public static function processImageThumb($pathRoot, $pathFile, $widthThumb = 250, $heightThumb = null, $statusWatermark = false, $fileWatermark = '')
    {
        if (!file_exists($pathRoot . '/thumb/' . $pathFile)) {
            //Insert watermark
            if ($statusWatermark) {
                \Image::make($pathRoot . '/' . $pathFile)
                    ->insert($fileWatermark, 'bottom-right', 10, 10)
                    ->save($pathRoot . '/' . $pathFile);
            }

            //thumbnail
            $image_thumb = \Image::make($pathRoot . '/' . $pathFile);
            $image_thumb->resize($widthThumb, $heightThumb, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_thumb->save($pathRoot . '/thumb/' . $pathFile);
        }
    }

    public static function getListCart($instance = 'default')
    {
        $cart                = \Cart::instance($instance);
        $arrCart['count']    = $cart->count();
        $arrCart['subtotal'] = \Helper::currencyRender($cart->subtotal());
        $arrCart['items']    = [];
        if ($cart->count()) {
            foreach ($cart->content() as $key => $item) {
                $product            = \App\Models\ShopProduct::find($item->id);
                $arrCart['items'][] = [
                    'id'        => $item->id,
                    'qty'       => $item->qty,
                    'image'     => asset($product->getThumb()),
                    'price'     => $product->getPrice(),
                    'showPrice' => $product->showPrice(),
                    'url'       => $product->getUrl(),
                    'rowId'     => $item->rowId,
                    'name'      => $product->getName(),
                ];
            }
        }

        return $arrCart;
    }

/**
 * [sendMail description]
 * @param  [type] $view           [description]
 * @param  array  $data           [description]
 * @param  array  $config         [description]
 * @param  array  $fileAttach     [description]
 * @param  array  $fileAttachData [description]
 * @return [type]                 [description]
 */
    public static function sendMail($view, $data = array(), $config = array(), $fileAttach = array(), $fileAttachData = array())
    {
        if (!empty(self::configs()['email_action_mode'])) {
            try {
                Mail::send(new \App\Mail\SendMail($view, $data, $config, $fileAttach, $fileAttachData));
            } catch (\Exception $e) {
                Log::error("Sendmail view:" . $view . PHP_EOL . $e->getMessage());
            }

        } else {
            return false;
        }
    }

}

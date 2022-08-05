<?php

namespace App\Support;

use KuCoin\SDK\PrivateApi\Account;
use KuCoin\SDK\Exceptions\HttpException;
use KuCoin\SDK\Exceptions\BusinessException;
use KuCoin\SDK\Auth;
use KuCoin\SDK\PrivateApi\Order;
use KuCoin\SDK\PublicApi\Symbol;

use App\Exceptions\PriceOracleFailureException;
use App\Interfaces\PriceOracleInterface;
use App\Models\Token;

class KucoinOrder
{
    /**
     * Market Buy order
     *
     * @param Token $token
     * @param float $quantity
     */
    public function marketBuy(Token $token, float $quantity)
    {
      $auth = new Auth(config('app.kucoin.api.key'), config('app.kucoin.api.secret'), config('app.kucoin.api.passphrase'), Auth::API_KEY_VERSION_V2);

      $api = new Order($auth);
      $order = [
        'clientOid' => uniqid(),
        'type'      => 'market',
        'side'      => 'buy',
        'symbol'    => 'VET-USDT',
        'remark'    => 'My bot trade',
        'size'      => $quantity,
      ];
      $data = $api->create($order);

      return $data;
    }

    /**
     * Market Buy order
     *
     * @param Token $token
     * @param float $quantity
     */
    public function marketSell(Token $token, float $quantity)
    {
      $auth = new Auth(config('app.kucoin.api.key'), config('app.kucoin.api.secret'), config('app.kucoin.api.passphrase'), Auth::API_KEY_VERSION_V2);

      $api = new Order($auth);
      $order = [
        'clientOid' => uniqid(),
        'type'      => 'market',
        'side'      => 'sell',
        'symbol'    => 'VET-USDT',
        'remark'    => 'My bot trade',
        'size'      => $quantity,
      ];
      $data = $api->create($order);

      return $data;
    }



      // $auth = new Auth('xxx', 'xxx', 'xxx', Auth::API_KEY_VERSION_V2);
      // $api = new Account($auth);
      // try {
      //     $result = $api->getList(['type' => 'main']);
      //     var_dump($result);
      // } catch (HttpException $e) {
      //     var_dump($e->getMessage());
      // } catch (BusinessException $e) {
      //     var_dump($e->getMessage());
      // }



      // $auth = new Auth('xxx', 'xxx', 'xxx', Auth::API_KEY_VERSION_V2);
      // $api = new Order($auth);
      // $order = [
      //   'clientOid' => uniqid(),
      //   'type'      => 'market',
      //   'side'      => 'sell',
      //   'symbol'    => 'VET-USDT',
      //   'remark'    => 'My bot trade 3',
      //   'size'      => 500,
      // ];
      // $data = $api->create($order);
      // var_dump($data['orderId']);



      // try {
      //   $api = new Symbol();
      //   $data = $api->getTicker('VET-USDT');
      // } catch (\Exception $e) {
      //   throw new PriceOracleFailureException($e->getMessage());
      // }

      // if(! $data['price'] > 0) {
      //   throw new PriceOracleFailureException('Invalid response in Price data');
      // }

      // return $data['price'];
    // }
}

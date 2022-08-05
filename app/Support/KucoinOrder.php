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

class KucoinOrder implements PriceOracleInterface
{

    // private $url = 'https://api.kucoin.com';

    // Nasty hacky throw it in code ...

    /**
     *
     * @return float
     * @throws PriceOracleFailureException
     */
    public function getPrice(): float
    {

      // $api = new Time();
      // $timestamp = $api->timestamp();
      // var_dump($timestamp);


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



      try {
        $api = new Symbol();
        $data = $api->getTicker('VET-USDT');
      } catch (\Exception $e) {
        throw new PriceOracleFailureException($e->getMessage());
      }

      if(! $data['price'] > 0) {
        throw new PriceOracleFailureException('Invalid response in Price data');
      }

      return $data['price'];
    }
}

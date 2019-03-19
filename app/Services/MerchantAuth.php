<?php
namespace App\Services;

use App\Tools\JsonMessage;
use Illuminate\Support\Facades\Hash;
use App\Repository\Dao\MerchantRepository;

class MerchantAuth
{
    protected $merchant;
    protected $loggedOut = false;
    private $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }

    /**
     * 商家登陆
     * @param $data
     * @return MerchantRepository|array|\Illuminate\Database\Eloquent\Model|null|object|string
     */
    public function login($data) {
        if(!$this->validateLogin($data)) {
            return JsonMessage::LOGIN_FAILED;
        }

        $merchant = $this->merchantRepository->getByColumn($data['account_name'], "account_name");
        if (empty($merchant)) {
            return JsonMessage::USER_NOT_FOUND;
        }
        else if(!Hash::check($data['password'], $merchant->password)) {
            return JsonMessage::PASSWORD_ERROR;
        }

        session()->put([
            'merchant_id'           =>  $merchant->id,
            'merchant_account_name' =>  $merchant->account_name
        ]);

        $this->merchant = $merchant;
        return $merchant;
    }

    /**
     * 验证商家登陆信息
     * @param $data
     * @return bool
     */
    public function validateLogin($data) {
        if (empty($data['account_name']) or empty($data['password'])) {
            return false;
        }

        return true;
    }

    /**
     * 商家退出
     * @return boolean
     */
    public function logout() {
        session()->flush();
        $this->loggedOut = true;
        return true;
    }

    /**
     * 获取已登陆的商家
     * @return null
     */
    public function merchant() {
        if ($this->loggedOut) {
            return null;
        }

        if (! is_null($this->merchant)) {
            return $this->merchant;
        }

        $merchantId = $this->id();
        if (! is_null($merchantId)) {
            $this->merchant = $this->merchantRepository->getById($merchantId);
        }

        return $this->merchant;
    }

    /**
     * 商家注册添加
     * @param $data
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function register($data) {
        $data['password'] = isset($data['password'])?$data['password'] : Hash::make($data['password']);
        $merchant = $this->merchantRepository->create($data);
        event('register_shopper');

        session()->put([
            'merchant_id'           =>  $merchant->id,
            'merchant_account_name'  =>  $merchant->account_name
        ]);

        return $merchant;
    }

    /**
     * 获取已登陆商家id
     * @return mixed
     */
    public function id() {
        return session()->get('merchant_id');
    }

    /**
     * 检查商家是否已登陆
     * @return bool
     */
    public function check() {
        return !is_null($this->merchant());
    }
}
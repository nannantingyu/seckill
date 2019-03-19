<?php
namespace App\Repository\Dao;
use App\Models\Merchant;
use App\Repository\BaseRepository;

class MerchantRepository extends BaseRepository
{
    function model()
    {
        return Merchant::class;
    }
}
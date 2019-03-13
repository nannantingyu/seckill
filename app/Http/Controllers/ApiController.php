<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/22
 * Time: 5:30 PM
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\StoreApiClient;
use Laravel\Passport\ClientRepository;
use Auth;

class ApiController extends Controller
{
    private $clientRepository;
    public function __construct(ClientRepository $clientRepository)
    {
        $this->middleware('auth');
        $this->clientRepository = $clientRepository;
    }

    /**
     * show create api client page
     * @param Request $request
     * @return view
     */
    public function apiClient(Request $request) {
        return view("api_client");
    }

    /**
     * add api client
     * @param StoreApiClient $request
     * @return redirect
     */
    public function storeApiClient(StoreApiClient $request) {
        $validated = $request->validated();
        $client = $this->clientRepository->create(Auth::user()->id, $validated['name'], $validated['redirect']);
        return redirect()->route('api_client_success')->with([
            'api_client_name'=>$client['name'],
            'api_client_id'=>$client['id'],
            'api_client_secret'=>$client['secret']
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function apiClientSuccess() {
        return view('api_client_success');
    }

    /**
     * Get all clients for user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function userClients() {
        return $this->clientRepository->forUser(Auth::user()->id);
    }
}
<?php namespace App\Http\Controllers\library;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Fotolia_Api;
class FotoliaController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Main Page Controller
    |--------------------------------------------------------------------------
    |
    |
    */

    public function __construct()
    {
        $this->fotoliaApi = new Fotolia_Api(config()->get('fotolia.key'));
    }

    public function index(Request $request)
    {
        $words = $request->input('words');
        $data = [];
        if (!empty($words))
        {
            $parameters = array(
                'words' => $words,
                'language_id' => Fotolia_Api::LANGUAGE_ID_EN_US,
                'limit' => 50,
            );
            $data = $this->fotoliaApi->getSearchResults($parameters);
            unset($data['nb_results']);
        }
        return response()->json($data); 
    }

    public function getMedia(Request $request)
    {
        $id = $request->input('id');
        $data = [];
        if (!empty($id))
        {
            $data = $this->fotoliaApi->getBulkMediaData(explode(',',$id));
        }
        return response()->json($data);
    }

    public function buyMedia(Request $request)
    {
        $id = $request->input('id');
        $licenseName = $request->input('license_name');

        $data = [
            'id' => $id,
            'name' => rand(0, 1) ? 'Fotolia_61068842_XS.jpg' : 'Fotolia_73046861_XS.jpg',
            'extension' => 'jpg',
        ];

        $pretendMode = config()->get('fotolia.pretend');
        if ($pretendMode) return response()->json($data);

        $data = [];

        if (!empty($id) AND !empty($licenseName))
        {
            $username = config()->get('fotolia.username');
            $password = config()->get('fotolia.password');
            $this->fotoliaApi->loginUser($username, $password);
            $data = $this->fotoliaApi->getMedia($id, $license_name);

            $api->downloadMedia($data['url'],  config()->get('fotolia.storage_path') .'/'. $data['name']);
            $api->logoutUser();

            unset($data['url']);
            $data['id'] = $id;
        }
        return response()->json($data);
    }

}

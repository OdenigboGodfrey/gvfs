<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class PostController extends Controller
{
    private $out;
    private $_500 = 500;
    private $_422 = 422;
    private $date = '';
    /** display positive message to user compulsory **/
    private $positive = 1;
    /** display message to user not compulsory **/
    private $neutral = 0;
    /** display negative message to user compulsory **/
    private $negative = -1;
    /** display error message to user compulsory **/
    private $error = -2;

    public function __construct()
    {
        $this->out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $this->date = Carbon::now();
    }

    protected function validator(array $data, $fields)
    {
        $validator =  Validator::make($data, $fields);
        if ($validator->fails()) {
            return \validator_result(true, $validator->errors()->all());
        }

        return \validator_result(false);
    }

    public function create_post(Request $request) {
        $validator = $this->validator($request->all(),[
            'title' => 'required|string',
            'post_image' => 'required',
            'location' => 'required|string',
            'post_type' => 'required|string',
            'posted_on' => 'required',
        ]);

        if ($validator['failed']) {
            return \prepare_json(false, ['messages' => $validator['messages']],'',$status_code=$this->_422);
        }
        try {
            $data = $request->all();
            $user = auth()->guard('api-users')->user();

            $post = Post::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'location' => $data['location'],
                'post_type' => $data['post_type'],
                'post_image' => $data['post_image'],
                'posted_on' => $this->out->toDateString(),
                'latitude' => $data['latitude'] ?? NULL,
                'longitude' => $data['longitude'] ?? NULL,
                'weight' => $data['weight'] ?? 1,
                'post_id' => $data['post_id'] ?? NULL,
                'sponsored' => $data['sponsored'] ?? $this->negative,
            ]);


        }
        catch (ModelNotFoundException $ex) {
            return \prepare_json(false, [], \get_api_string('emptor_not_found'), $this->_500);
        }
        catch (\Exception $ex) {
            return \prepare_json(false, [],\get_api_string('error_occured'), $this->_500);
        }
    }
}

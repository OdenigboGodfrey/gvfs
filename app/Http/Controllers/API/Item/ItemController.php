<?php

namespace App\Http\Controllers\API\Item;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Utilities\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Image as Manipulator;

class ItemController extends Controller
{
    public function create_item(Request $request) {
        /**
        user_types: 0=>customer,1=>client
         **/
        $validator = Utility::validator($request->all(),[
            'title' => 'required|string|max:50',
            'item_type' => 'required|string',
            'file_type' => 'string',
            'size' => 'numeric',
            'item_id' => 'numeric',
        ]);

        if ($validator['failed']) {
            return \prepare_json(Utility::$negative, ['messages' => $validator['messages']],'',$status_code=Utility::$_422);
        }

        try {
            $data = $request->all();

            if (array_key_exists('item_id', $data) ) {
                /** check to make sure the parent item is not a file**/
                $item = Item::where('id',$data['item_id'])->first();
                if ($item->item_type == get_api_string('file')) {
                    return prepare_json(Utility::$negative, [], \get_api_string('invalid_action'));
                }
            }

            if ($data['item_type'] == get_api_string('file') && !$request->hasFile('file')) {
                return prepare_json(Utility::$negative, [], \get_api_string('no_files'));
            }


            $item = Item::create([
                'title' => $data['title'],
                'item_type' => $data['item_type'],
                'item_id' => $data['item_id'] ?? NULL,
                'file_type' => $data['item_id'] ?? NULL,
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');

                $file_name = $file->getClientOriginalName();

                //upload item images
                $destinationPathX500 = 'uploads/gvfs/';
                $path = public_path($destinationPathX500);

                File::makeDirectory($path, $mode=0777, true, true);

                $res = $request->file('file')->move($path, $file_name);

            }

            if ($data['item_type'] == get_api_string('folder')) {
                $item['contents'] = $item->get_children($item->id);
            }
            $item['parent'] = $item->get_parent($item->item_id);
            return prepare_json(Utility::$positive, ['item' => $item], \get_api_string('generic_ok'),Utility::$_201);
        }
        catch (\Exception $ex) {
            return \prepare_json(Utility::$error, [],\get_api_string('error_occurred').$ex->getMessage(), Utility::$_500);
        }
    }
}

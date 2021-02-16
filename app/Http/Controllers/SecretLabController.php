<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SecretLab;
use App\Http\Resources\SecretLabResource;
use App\Models\SecretLabChange;

class SecretLabController extends Controller
{
    public function index() {
        $data = SecretLab::all();

        return response(['success' => true, 'data' => new SecretLabResource($data), 'message' => 'Retrieved successfully'], 200);
    }

    public function store(Request $request) {
        $data = $request->all();
        $validator = Validator::make($data, [
            'key' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors(), 'success' => false]);
        }

        $secretlab = SecretLab::where('key', $request->key)->first();

        if($secretlab) {
            $secretlab->value = $request->value;
            $secretlab->save();

            return response(['message' => 'Updated successfully', 'success' => true], 200);
        }
        else {
            SecretLab::create([
                'key' => $request->key,
                'value' => $request->value
            ]);

            return response(['message' => 'Created successfully', 'success' => true], 200);
        }
    }

    public function show(Request $request) {
        $data = SecretLab::where('key', $request->key)->first();
        $timestamp = $request->query('timestamp');

        if($timestamp) {
            if($data) {
                $history = SecretLabChange::where('key', $request->key)->get();
                $queryDate = date('Y-m-d H:i:s', $timestamp);

                if(count($history) > 0) {
                    $result = SecretLabChange::where([
                        ['key', '=', $request->key],
                        ['original_created_at', '<=', $queryDate],
                        ['created_at', '>=', $queryDate]
                    ])
                    ->orderByDesc('created_at')
                    ->first();
    
                    if(isset($result)) {
                        return response(['success' => true, 'data' => $result->old_value , 'message' => 'Retrieved successfully'], 200);
                    }
                    else {
                        $result = SecretLabChange::where([
                            ['key', '=', $request->key],
                            ['original_created_at', '<=', $queryDate],
                            ['created_at', '<=', $queryDate]
                        ])
                        ->orderByDesc('created_at')
                        ->first();
    
                        if(isset($result)) {
                            return response(['success' => true, 'data' => $result->updated_value , 'message' => 'Retrieved successfully'], 200);
                        }
                    }
                    
                    return response(['success' => false, 'message' => 'Data not exist']);
                }
                else {
                    $result = SecretLab::where([
                        ['key','=', $request->key],
                        ['created_at', '<=', $queryDate]
                    ])
                    ->first();
    
                    if(isset($result)) {
                        return response(['success' => true, 'data' => $result->value , 'message' => 'Retrieved successfully'], 200);
                    }
    
                    return response(['success' => false, 'message' => 'Data not exist']);
                }
            }
            else {
                return response(['success' => false, 'message' => 'Data not exist']);
            }
        }
        else {
            if($data) {
                return response(['success' => true, 'data' => new SecretLabResource($data), 'message' => 'Retrieved successfully'], 200);
            }
            else {
                return response(['success' => false, 'message' => 'Data not exist']);
            }
        }
    }
}

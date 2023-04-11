<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function store(Request $request)
    {

        Log::info("Start saving");

        request()->validate([
            'name' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|integer'
        ]);

        Log::info("Validation successful");

        if (file_exists(public_path() . '/storage/products.json')) {
            Log::info("Json file exists");
            // get the json file from storage
            $contents = Storage::disk('public')->get("products.json");
            Log::info($contents);
            $data = json_decode($contents, true);
            // get the current time
            $current_time = Carbon::now();
            // calculate total of the products
            $total = $request->quantity * $request->price;
            // Add your new data to the existing data
            $newData = [
                'name' => $request->name,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'date' => $current_time->toDateTimeString(),
                'total' => $total,
            ];
            $data[] = $newData;

            // Encode the updated data to JSON format and write it to the file
            $jsonData = json_encode($data);
            file_put_contents(base_path('public/storage/products.json'), stripslashes($jsonData));

            Log::info("Success");

            return;
        } else {

            Log::info("Fresh saving of data");
            // get current time
            $current_time = Carbon::now();
            // calculate total
            $total = $request->quantity * $request->price;
            // make an array of the requested data from form
            $data[] = [
                'name' => $request->name,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'date' => $current_time->toDateTimeString(),
                'total' => $total,
            ];

            Log::info($data);
            // Encode the data to JSON format and create a file then write there
            $json = json_encode($data, JSON_PRETTY_PRINT);
            Log::info("Else 1");
            file_put_contents(base_path('public/storage/products.json'), stripslashes($json));

            Log::info("success");

            return;
        }
    }
}

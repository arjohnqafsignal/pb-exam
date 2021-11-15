<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Pizza;
use App\Product;
use Illuminate\Support\Facades\Request;

class OrderController extends Controller
{
    public function index()
    {
        return OrderResource::collection(Pizza::with('order')->get());
    }

    public function filter(Request $request)
    {
        $records = Pizza::with('order')->when($request->post('keyword'), function($query) use ($request) {
            if($request->post('filter'))
            {
                $filter = $request->post('filter');
                switch($filter)
                {
                    case 'size':
                        $query->where('size', $request->post('keyword'));
                        break;

                    case 'crust':
                        $query->where('size', $request->post('keyword'));
                        break;

                    case 'type':
                        $query->where('size',  $request->post('keyword'));
                        break;

                    case 'total_toppings_used':
                        $query->where('total_toppings_used',  $request->post('keyword'));
                        break;

                    default:
                        $query->where('size',  $request->post('keyword'));
                        break;
                }
            }

        })->get();

        return OrderResource::collection($records);
    }
}

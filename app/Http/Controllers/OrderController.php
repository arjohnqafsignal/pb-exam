<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Order;
use App\Pizza;
use App\Topping;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $order = new Order();
        $order->order_number = $request->orderNumber;
        $order->save();

        $pizzas = $request->pizzas;
        //return response()->json($pizzas);
        if(count($pizzas) > 0)
        {
            foreach($pizzas as $piz)
            {
                $pizza = new Pizza();

                $pizza->order_id = $order->id;
                $pizza->pizza_number = $piz['pizzaNumber'];
                $pizza->size = $piz['size'];
                $pizza->crust = $piz['crust'];
                $pizza->type = $piz['pizzaType'];

                $pizza->total_toppings_used = $this->computeToppings($piz['toppings']);
                $pizza->save();

                if(count($piz['toppings']) > 0){
                    foreach($piz['toppings'] as $top)
                    {

                        $topping = new Topping();
                        $topping->pizza_id = $pizza->id;
                        $topping->area = $top['area'];
                        $topping->topping = implode("|",$top['toppingsRaw']);
                        $topping->save();
                    }
                }
            }
        }


        if($order->id)
        {
            return response()->json($order->id);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
                        $query->where('crust', $request->post('keyword'));
                        break;

                    case 'type':
                        $query->where('type',  $request->post('keyword'));
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

    private function computeToppings($toppings)
    {
        if(count($toppings) > 0)
        {
            $total = 0;

            foreach($toppings as $topping){
                $total += count($topping['toppingsRaw']);
            }

            return $total;
        }
        else
        {
            return 0;
        }
    }
}

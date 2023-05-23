<?php

namespace App\Http\Controllers;

use App\Models\Shipping;

class ShippingController extends Controller
{
    /**
     * Soft delete Shipping
     * @param $id
     * @return void
     */
    public function Delete($id){
        $shipping_delete = Shipping::where('id', $id)->get();
        if ($shipping_delete){
            foreach ($shipping_delete as $shipping) {
                $shipping->status = 'deleted';
                $shipping->deleted_at = now();
                $shipping->save();
            }
        }
    }

    /**
     * Update Shipping
     * @param $id
     * @return void
     */
    public function Update($id){
        $shipping = Shipping::find($id);
        if ($shipping) {
            $shipping->status = 'delivered';
            $shipping->updated_at = now();
            $shipping->save();
        }
    }
}

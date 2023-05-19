<?php

namespace App\Console\Commands;

use App\Models\Shipping;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;

class ShippingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:shipping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to get orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = $this->resilencyCall('orders');
        $zones = $this->resilencyCall('zones');
        $zone_vector = [];

        foreach ($zones as $zone) {
            $zone_vector[$zone['id']]['name'] = $zone['name'];
            foreach ($zone['polygon_coordinates'] as $point) {
                $zone_vector[$zone['id']]['x'][] = $point[1];
                $zone_vector[$zone['id']]['y'][] = $point[0];
            }
        }

        $id_shipping_list = Shipping::all()->pluck('id_shipping');
        if ($data) {
            foreach ($data as $order) {
                if (!in_array($order['id'], $id_shipping_list->toArray())) {
                    Shipping::create([
                        'id_shipping' => $order['id'],
                        'buyer_name' => $order['buyer_name'],
                        'description' => $order['description'],
                        'photo_url' => $order['photo_url'],
                        'address' => $order['address'],
                        'longitude' => $order['longitude'],
                        'latitude' => $order['latitude'],
                        'zone' => $this->getZone($order['longitude'], $order['latitude'], $zone_vector),
                        'created_at' => $order['created_at'],
                    ]);
                }
            }
        }
    }

    public function getZone($longitude, $latitude, $zone_vector) {
       foreach ($zone_vector as $zone) {
            if ($this->is_in_polygon($zone['x'], $zone['y'], $longitude, $latitude )) {
                return $zone['name'];
            }
       }
       return "Invalid Zone";
    }

    function is_in_polygon($vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
        $points_polygon = count($vertices_x) - 1;
        $i = $j = $c = 0;
        for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
            if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
                ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) )
                $c = !$c;
        }
        return $c;
    }

    function resilencyCall($endpoint) {
        $status_code = 0;
        $count = 0;
        $data = [];
        $page = 1;

        $baseUrl = env('API_ENDPOINT');
        $accessToken = env('ACCESS_TOKEN');
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer '.$accessToken
        ];
        do {
            do{
                try {
                    $request = new Request('GET', $baseUrl.'/'.$endpoint.'?page='.$page, $headers);
                    $res = $client->sendAsync($request)->wait();
                    $responseData  = json_decode($res->getBody(), true);
                    $total = $responseData['total'];
                    $per_page = $responseData['per_page'];
                    $data = array_merge($data, $responseData['data']);
                    $status_code = $res->getStatusCode();
                    $page++;
                } catch (\Exception $exception) {
                    $count++;
                }
            }  while ($status_code != 200 && $count < 100);
        } while ($page <= ($total/$per_page));
        return $data;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Shipping;
use App\Models\Zone;
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
        $shipping = $this->resilencyCall('orders');
        $zones = Zone::all();
        if ($zones) {
            $zones = [];
            $zonesApi = $this->resilencyCall('zones');
            foreach ($zonesApi as $zoneApi) {
                if (!Zone::where("id_zone", $zoneApi['id'])->exists()) {
                    $zones[] = Zone::create([
                        'id_zone' => $zoneApi['id'],
                        'name' => $zoneApi['name'],
                        'points' => json_encode($zoneApi['polygon_coordinates']),
                    ]);
                }
            }
        }

        $zone_vector = [];
        foreach ($zones as $zone) {
            $zone_vector[$zone['id']]['id'] = $zone['id'];
            $points = json_decode($zone['points']);
            foreach ($points as $point) {
                $zone_vector[$zone['id']]['x'][] = $point[1];
                $zone_vector[$zone['id']]['y'][] = $point[0];
            }
        }

        if ($shipping) {
            foreach ($shipping as $order) {
                if (!Shipping::where("id_shipping", $order['id'])->exists()) {
                    Shipping::create([
                        'id_shipping' => $order['id'],
                        'buyer_name' => $order['buyer_name'],
                        'description' => $order['description'],
                        'photo_url' => $order['photo_url'],
                        'address' => $order['address'],
                        'longitude' => $order['longitude'],
                        'latitude' => $order['latitude'],
                        'zone_id' => $this->getZone($order['longitude'], $order['latitude'], $zone_vector),
                        'created_at' => $order['created_at'],
                    ]);
                }
            }
        }
    }

    /**
     * Get the corresponding zone
     * @param $longitude
     * @param $latitude
     * @param $zone_vector
     * @return int|string|null
     */
    public function getZone($longitude, $latitude, $zone_vector) {
       foreach ($zone_vector as $key => $zone) {
            if ($this->is_in_polygon($zone['x'], $zone['y'], $longitude, $latitude )) {
                return $key;
            }
       }
       return null;
    }

    /**
     * Find polygon by points
     * more info: https://itecnote.com/tecnote/php-find-point-in-polygon-php/
     * @param $vertices_x
     * @param $vertices_y
     * @param $longitude_x
     * @param $latitude_y
     * @return bool|int
     */
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

    /**
     * General call to endpoints
     * @param $endpoint
     * @return array
     * @throws \Exception
     */
    function resilencyCall($endpoint) {
        $retries = env('RETRIES');
        $data = [];
        $page = 1;

        $baseUrl = env('API_ENDPOINT');
        $accessToken = env('ACCESS_TOKEN');
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer '.$accessToken
        ];
        $maxDBIdShipping = Shipping::max('id_shipping') ?? 1;
        do {
            $responseData = retry($retries, function () use($client, $baseUrl, $endpoint, $page, $headers) {
                $request = new Request('GET', $baseUrl.'/'.$endpoint.'?page='.$page, $headers);
                $res = $client->sendAsync($request)->wait();
                $status_code = $res->getStatusCode();
                if ($status_code != 200) {
                    throw new \Exception('The API failed to retrieve the data');
                }
                return json_decode($res->getBody(), true);
            }, 100);
            $oldestAPIShippingId = $responseData['data'][count($responseData['data'])-1]['id'];
            $last_page = $responseData['last_page'];
            $data = array_merge($data, $responseData['data']);
            $page++;
        } while (
            ($endpoint == 'orders' && $oldestAPIShippingId > $maxDBIdShipping)
            || ($endpoint == 'zones' && $page <= $last_page)
        );
        return $data;
    }
}

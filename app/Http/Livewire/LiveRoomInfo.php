<?php

namespace App\Http\Livewire;

use App\Models\Inhouse;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LiveRoomInfo extends Component
{
    public $viewBy = 'type';
    public $dateToday;

    public $typesId;
    public $typesIdForCheckbox = [];
    public $roomStatuses = [0, 1, 2];

    public $departureCount;
    public $vacantCount;
    public $occupyCount;

    protected $listeners = [
        'checkInCreated' => '$refresh',
        'inhouseEdited' => '$refresh',
        'roomTransferred' => '$refresh',
        'roomCheckedOut' => '$refresh',
    ];

    public function updateRoomTimers()
    {
        $inhouses = Inhouse::where('checkout_payment_done', false)->get();

        foreach ($inhouses as $key => $value) {
            # code...
        }
    }

    public function mount()
    {
        $this->dateToday = today()->toDateString();
        $this->roomTypes = RoomType::select('id', 'room_type_name')->withCount('rooms')->get();
        $this->typesId = $this->roomTypes->pluck('id')->toArray();
        $this->typesIdForCheckbox = $this->typesId;
    }

    public function render()
    {
        $this->loadStatusCounts();
        $roomTypes = implode(', ', count($this->typesIdForCheckbox) > 0 ? $this->typesIdForCheckbox : [0]);
        $roomStatuses = implode(', ', count($this->roomStatuses) > 0 ? $this->roomStatuses : [0]) . ', '. 3;

        $results = DB::select(
            "SELECT * FROM (

			SELECT
			r.id as room_id,
			x.id as inhouse_id,
			r.room_no,
			x.customer_name,
			x.checkout_payment_status,
			room_type_name,
			x.total_time,
			x.remaining_in_minutes,
			x.total_in_minutes,
			CASE
			  WHEN status IS NULL THEN 0
			  ELSE status
			END status

			FROM
			  rooms r
			  LEFT JOIN (
				SELECT
				  inhouses.id,
				  room_id,
				  customer_name,
				  checkout_payment_done as checkout_payment_status,
				  CASE
					WHEN checkout_payment_done = true THEN 2
					ELSE 1
				END as 'status',
				  CASE
						WHEN checkout_payment_done = true then TIME_FORMAT(TIMEDIFF(departure, now()), '%H:%i')
						ELSE TIME_FORMAT(TIMEDIFF(now(), arrival), '%H:%i')
					END as 'total_time',
					CASE
						WHEN checkout_payment_done = true then round(time_to_sec(TIMEDIFF(departure, now())) / 60)
						ELSE null
					END as 'remaining_in_minutes',
					round(time_to_sec(TIMEDIFF(now(), arrival)) / 60) 'total_in_minutes'
				FROM
				  inhouses
				  LEFT JOIN customers on customers.id = inhouses.customer_id
				WHERE
				  checked_out = 0
			  ) x ON r.id = x.room_id

			  LEFT JOIN room_types ON r.room_type_id = room_types.id
			WHERE
			  room_types.id IN ($roomTypes)
			  ) y
			  WHERE y.status in ($roomStatuses)"
        );

        $roomsCollection = collect($results);

        switch ($this->viewBy) {
            case 'all':
                $rooms = $roomsCollection->sortBy('room_no');
                break;
            case 'status':
                $rooms = $roomsCollection->groupBy(function ($room) {
                    switch ($room->status) {
                        case 1:
                            return 'Departure';
                            break;
                        case 2:
                            return 'Occupy';
                            break;
                        default:
                            return 'Vacant';
                            break;
                    }
                    return $room->status;
                })->sortKeys();
                break;
            default:
                $rooms = $roomsCollection->groupBy(function ($room) {
                    return $room->room_type_name;
                })->sortKeys();
                break;
        }

        return view('livewire.live-room-info', [
            'rooms' => $rooms,
            'roomTypesWithCounts' => collect(DB::select(
                "SELECT rt.id, rt.room_type_name, count(rt.room_type_name) as count
					from rooms r
					join room_types rt on rt.id = r.room_type_id
					where
					r.id not in (
						select room_id from inhouses
						where checked_out = false
					)
					group by rt.id, rt.room_type_name order by id
				"
            )),
        ]);
    }

        public function loadStatusCounts()
        {
            $roomTypes = implode(', ', $this->typesId);
            $countResults = DB::select(
                "SELECT status,  COUNT(*) as count FROM (
        			SELECT r.room_no,
        			CASE
        				WHEN status IS NULL THEN 0
        				ELSE status
        			END status
        		FROM rooms r
        		LEFT JOIN (
        				SELECT room_id,
						CASE
							WHEN time_to_sec(TIMEDIFF(departure, now())) / 60 < 15 THEN 1
							ELSE 2
						END AS 'status'
        				FROM inhouses
        				WHERE checked_out = 0
        			) x
        			ON r.id = x.room_id
        			LEFT JOIN
        				room_types ON r.room_type_id = room_types.id
        			WHERE room_types.id IN ($roomTypes)
        		) y
        		GROUP BY y.status"
            );

            $counts = collect($countResults)->groupBy(function ($counts) {
                switch ($counts->status) {
                    case 1:
                        return 'Departure';
                        break;
                    case 2:
                        return 'Occupy';
                        break;
                    default:
                        return 'Vacant';
                        break;
                }
            });

            $this->departureCount = isset($counts['Departure']) ? $counts['Departure']->first()->count : 0;
            $this->vacantCount = isset($counts['Vacant']) ? $counts['Vacant']->first()->count : 0;
            $this->occupyCount = isset($counts['Occupy']) ? $counts['Occupy']->first()->count : 0;
        }
}

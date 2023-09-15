<?php


namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VepostTracking;
use Carbon\Carbon;
use Inertia\Inertia;

class CustomerPackageController extends Controller
{
    //

    public function packagesSentToday(Request $request)
    {
        $model = new VepostTracking;
        if ($request->search) {
            $model = $model->where("file_name", "LIKE", "%" . $request->search . "%");
        }
        $username = $request->user()->username;
        $user = auth()->user();
        $controlstring = $user->controlstring;

        $vendor_trackings = VepostTracking::where('sender_username', $username)->orderBy('ltime_send_end', 'desc')->whereDate('ltime_send_end', Carbon::now())->paginate(10);
        return Inertia::render(
            'customers/Packages',
            [
                'controlstring' => $controlstring,
                'headText' => 'Packages Sent',
                'packages' => $vendor_trackings,
                'url' => '/customer/sent/today'
            ]
        );
    }


    public function packagesViewedToday(Request $request)
    {
        $model = new VepostTracking;
        if ($request->search) {
            $model = $model->where("file_name", "LIKE", "%" . $request->search . "%");
        }
        $username = $request->user()->username;
        $user = auth()->user();
        $controlstring = $user->controlstring;

        $vendor_trackings = VepostTracking::whereNotNull('time_post_opened')->where(function ($query) use ($username) {
            $query->where('receiver_username', $username)
                ->orWhere('sender_username', $username);
        })
            ->whereDate('time_post_opened', Carbon::now())->orderBy('time_post_opened', 'desc')->paginate(10);
        return Inertia::render(
            'customers/Packages',
            [   
                'controlstring' => $controlstring,
                'headText' => 'Packages Viewed',
                'packages' => $vendor_trackings,
                'url' => '/customer/viewed/today'
            ]
        );
    }


    public function packagesRecievedToday(Request $request)
    {
        $model = new VepostTracking;
        if ($request->search) {
            $model =  $model->where("file_name", "LIKE", "%" . $request->search . "%");
        }
        $username = $request->user()->username;
        $user = auth()->user();
        $controlstring = $user->controlstring;

        $vendor_trackings = VepostTracking::where('receiver_username', $username)->orderBy('ltime_recv_end', 'desc')->whereDate('ltime_recv_end', Carbon::now())->paginate(10);
        return Inertia::render(
            'customers/Packages',
            [
                'controlstring' => $controlstring,
                'headText' => 'Packages Received',
                'packages' => $vendor_trackings,
                'url' => '/customer/received/today'
            ]
        );
    }
}

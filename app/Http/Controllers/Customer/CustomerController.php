<?php

namespace App\Http\Controllers\Customer;

use App\Enums\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;

class CustomerController extends Controller
{

    public function __invoke(Request $request)
    {
        
        // Get the username of the currently logged-in user
        $username = auth()->user()->username;
        $user = auth()->user();
        $controlstring = $user->controlstring;

        // Count only the packages related to the logged-in user
        $total_packages = DB::table('vepost_tracking')
                            ->where('sender_username', $username)->orWhere('receiver_username', $username)
                            ->count();
    
        $viewed_packages = DB::table('vepost_tracking')
                            ->where('time_post_opened', '<>', null) // Ensure that time_post_opened is not null
                            ->where(function($query) use ($username) {
                                $query->where('sender_username', $username)
                                      ->orWhere('receiver_username', $username);
                            })
                            ->count();
        

        $send_packages = DB::table('vepost_tracking')->where('sender_username', $username)->count();

        $received_packages = DB::table('vepost_tracking')->where('receiver_username', $username)->count();
    
                            
    
        return Inertia::render('customerViews/customerDashboard', [
            'sendPackages' => $send_packages,
            'receivedPackages' => $received_packages,
            'totalPackages' => $total_packages,
            'viewedOnce' => $viewed_packages,
            'username'  => $username,
            'controlstring' => $controlstring,
            
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = new User;
        $user = auth()->user();
        $controlstring = $user->controlstring;
        if (isset($request->search)) {
            $user = $user->where('username', 'LIKE', '%' . $request->search . '%');
        }
        
        return Inertia::render('customers/Index', [
            'customers' => $user->where('user_type', UserType::TYPE_CUSTOMER)->orderBy('created_at')
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($customer) => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'username' => $customer->username,
                    'phone' => $customer->phone,
                    'controlstring' => $controlstring,
                ]),
        ]);
    }
    public function Individual(Request $request)
    {
        $user = new User;
        $user = auth()->user();
        $controlstring = $user->controlstring;
        if (isset($request->search)) {
            $user = $user->where('username', 'LIKE', '%' . $request->search . '%');
        }
        
        return Inertia::render('customers/Individual', [
            'customers' => $user->where('user_type', UserType::TYPE_CUSTOMER)->where('controlstring', 'LIKE', '_1%')
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($customer) => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'username' => $customer->username,
                    'phone' => $customer->phone,
                    'controlstring' => $controlstring,
                ]),
        ]);
    }
    
    public function organisation(Request $request)
    {
        $user = new User;
        $user = auth()->user();
        $controlstring = $user->controlstring;
        if (isset($request->search)) {
            $user = $user->where('username', 'LIKE', '%' . $request->search . '%');
        }
        
        return Inertia::render('customers/Organisation', [
            'customers' => $user->where('user_type', UserType::TYPE_CUSTOMER)->where('controlstring', 'LIKE', '_0%')
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($customer) => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'username' => $customer->username,
                    'phone' => $customer->phone,
                    'controlstring' => $controlstring,
                ]),
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Show the page with info.
     */
    public function edit(Request $request, $id)
{   
    $customer = User::findOrFail($id);
    
    $packagesQuery = DB::table('vepost_tracking')
                       ->where('sender_username', $customer->username)
                       ->orderBy('mpID', 'desc');
                       
    if ($request->input('search')) {
        $packagesQuery = $packagesQuery->where('mpID', 'LIKE', '%' . $request->input('search') . '%')
                                        ->orWhere('file_name', 'LIKE','%' . $request->input('search') . '%');
    }
    
    $packages = $packagesQuery->get();
        return Inertia::render('customers/Edit', [
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'username' => $customer->username,
                'phone' => $customer->phone,
            ],
            'packages' => $packages->map(function ($package) {
                return [
                    'id' => $package->id,
                    's_name' => $package->sender_displayname,
                    'fileName' => $package->file_name,
                    'fileSizeTransfer' => $package->file_size_transfer,
                    'senderOS' => $package->sender_OS,
                    'senderDeviceName' => $package->sender_device_name,
                    'r_name' => $package->receiver_displayname,
                    'receiverOS' => $package->receiver_OS,
                    'receiverDeviceName' => $package->receiver_device_name,
                    'senttime' => $package->time_send_start,
                    'mpID' => $package->mpID,
                ];
            })
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $customer = User::find($id);

        if (!$customer || $customer->user_type !== UserType::TYPE_CUSTOMER) {
            return redirect()->route('home')->with('error', 'Customer not found.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->phone = $request->input('phone');
        $customer->save();

        return redirect()->route('customer.edit', ['id' => $customer->id])->with('success', 'Customer updated successfully.');
    }
}

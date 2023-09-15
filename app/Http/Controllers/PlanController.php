<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription as ModelsSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptionItem = !empty(auth()->user()->subscription('default')) ? auth()->user()->subscription('default')->items->first() : '';

        $stripePrice = '';
        $user = auth()->user();
        $controlstring = $user->controlstring;

        if (!empty($subscriptionItem))
            $stripePrice = $subscriptionItem->stripe_price;

        return inertia::render('customerViews/customerPricing', [
            'controlstring' => $controlstring,
            'activePrice' => $stripePrice,
            'plans' => DB::table('plans')->where('type', 'Personal')->get()->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'type' => $plan->type,
                    'name' => $plan->name,
                    'code' => $plan->code,
                    'price' => $plan->price,
                    'icon' => $plan->icon,
                    'slug' => $plan->slug,
                    'description' => $plan->description,
                    'stripe_plan' => $plan->stripe_plan,
                    'message' => $plan->message,
                    'options' => json_decode($plan->options, true),

                    //   'subscription_type' => $plan->subscription_type,
                ];
            }),
        ]);
    }
    /**
     * Display a Pricing Business
     */
    public function indexBusiness()
    {
        $subscriptionItem = !empty(auth()->user()->subscription('default')) ? auth()->user()->subscription('default')->items->first() : '';

        $stripePrice = '';
        $user = auth()->user();
        $controlstring = $user->controlstring;
        if (!empty($subscriptionItem))
            $stripePrice = $subscriptionItem->stripe_price;

        return inertia::render('customerViews/customerPricing', [
            'activePrice' => $stripePrice,
            'controlstring' => $controlstring
            ,
            'plans' => DB::table('plans')->where('type', 'Business')->get()->map(function ($plan) use ($controlstring) {

                return [


                    'id' => $plan->id,
                    'type' => $plan->type,
                    'name' => $plan->name,
                    'code' => $plan->code,
                    'price' => $plan->price,
                    'icon' => $plan->icon,
                    'slug' => $plan->slug,
                    'description' => $plan->description,
                    'stripe_plan' => $plan->stripe_plan,
                    'message' => $plan->message,
                    'options' => json_decode($plan->options, true),

                    //  'subscription_type' => $plan->subscription_type,
    
                ];
            }),
        ]);
    }


    /**
     * Stripe Payments 
     * Show the form for creating a new resource.
     */
    public function create($slug, $planType)
    {
        $intent = [];
        $subscribe = false;

        $plan = DB::table('plans')
            ->select('id', 'type', 'name', 'code', 'price', 'icon', 'slug', 'description', 'stripe_plan', 'message', 'options')
            ->where('slug', $slug)
            ->first();

        if (empty($plan)) {
            return to_route('pricing');
        }

        // Check if the user is authenticated and subscribed
        if (auth()->check() === true) {
            $subscribe = auth()->user()->subscribed('default');
            if ($subscribe === false) {
                $intent = auth()->user()->createSetupIntent();
            }
        }

        // Check the planType to decide which Stripe Plan ID to use
        $envKey = strtoupper($planType) . '_STRIPE_PLAN_' . strtoupper(str_replace('-', '_', $slug));
        $stripePlanId = env($envKey, '');
        $user = auth()->user();
        $controlstring = $user->controlstring;
        Log::info('Generated envKey:', ['envKey' => $envKey]);
        Log::info('Stripe Plan ID:', ['stripePlanId' => $stripePlanId]);


        // Use the specific Stripe Plan ID for the plan
        if (!empty($stripePlanId)) {
            $plan->stripe_plan = $stripePlanId;
        }

        return inertia::render('customerViews/customerCheckout', [
            'SKey' => env('STRIPE_KEY'),
            'intentToken' => $intent,
            'isUserSubscribe' => $subscribe,
            'controlstring' => $controlstring,
            'plan' => [
                'id' => $plan->id,
                'type' => $plan->type,
                'name' => $plan->name,
                'code' => $plan->code,
                'price' => $plan->price,
                'icon' => $plan->icon,
                'slug' => $plan->slug,
                'description' => $plan->description,
                'message' => $plan->message,
                'options' => json_decode($plan->options, true),
                'stripePlan' => $plan->stripe_plan,
                'planType' => $planType,

            ]
        ]);
    }

    /**
     * Linking to the Stripe (Creating Substription)
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (empty($request->slug))
            return response()->error(false, 400);
        if (empty($request->payment))
            return response()->error(false, 400);

        $user = auth()->user();

        $plan = DB::table('plans')
            ->select('id', 'stripe_plan', 'type', 'slug')
            ->where('slug', $request->slug)
            ->first();

        if (!$plan) {
            return response()->error('Plan not found', 400);
        }

        // Determine if the plan is yearly or monthly.
        $planType = $request->planType ?? 'monthly';

        $stripePlanId = "";


        if ($planType === 'yearly') {
            $envKey = strtoupper($planType) . '_STRIPE_PLAN_' . strtoupper(str_replace('-', '_', $request->slug));
            $stripePlanId = env($envKey, '');
        } else {
            $stripePlanId = $plan->stripe_plan;
        }

        if (!$stripePlanId) {
            return response()->error('Stripe plan ID not found', 400);
        }

        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($request->payment);
            $user->newSubscription('default', $stripePlanId)->create($request->payment);

            $controlstring = $user->controlstring;
            if ($plan->type === 'Business') {
                $controlstring[1] = '1';
            } else if ($plan->type === 'Personal') {
                $controlstring[1] = '0';
            }

            $plan_slug = $plan->slug;
            $parts = explode('-', $plan_slug);
            $plan_type = end($parts);

            if ($plan_type === 'basic') {
                $controlstring[2] = '2';
            } elseif ($plan_type === 'premium') {
                $controlstring[2] = '3';
            } elseif ($plan_type === 'starter') {
                $controlstring[2] = '1';
            }

            $user->update(['controlstring' => $controlstring]);
            request()->session()->flash('success', "You subscribed for the {$plan_slug} plan!");

            return response()->success([
                'data1' => '',
                'data2' => '',
                'data3' => '',
                'data4' => auth()->user()->subscribed('default')
            ]);

        } catch (IncompletePayment $exception) {
            return response()->success([
                'data1' => $exception->payment->id,
                'data2' => '',
                'data3' => $exception->payment->status,
                'data4' => ''
            ]);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 400);
        }
    }
    /**
     * Display the specified resource.
     */
    public function editAll()
    {
        $personalPlans = DB::table('plans')->where('type', 'Personal')->get();
        $businessPlans = DB::table('plans')->where('type', 'Business')->get();

        return Inertia::render('adminviews/EditPlans', [
            'personalPlans' => $personalPlans->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'type' => $plan->type,
                    'name' => $plan->name,
                    'code' => $plan->code,
                    'price' => $plan->price,
                    'icon' => $plan->icon,
                    'slug' => $plan->slug,
                    'description' => $plan->description,
                    'stripe_plan' => $plan->stripe_plan,
                    'message' => $plan->message,
                    'options' => json_decode($plan->options, true),
                ];
            })->toArray(),
            'businessPlans' => $businessPlans->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'type' => $plan->type,
                    'name' => $plan->name,
                    'code' => $plan->code,
                    'price' => $plan->price,
                    'icon' => $plan->icon,
                    'slug' => $plan->slug,
                    'description' => $plan->description,
                    'stripe_plan' => $plan->stripe_plan,
                    'message' => $plan->message,
                    'options' => json_decode($plan->options, true),
                ];
            })->toArray(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($planId)
    {
        $plan = DB::table('plans')->where('id', $planId)->first();

        if (!$plan) {
            // Handle the error, e.g., redirect with an error message.
            return redirect()->route('admin.plans.index')->withErrors('Plan not found.');
        }

        return inertia('adminviews/EditPlan', ['plan' => $plan]);
    }

    public function update(Request $request, $planId)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
// Regex
// 123456.78  (6 digits before the decimal and 2 after)
// 123.45     (3 digits before the decimal and 2 after)
// 123456     (6 digits, no decimal)
// 123456.7   (6 digits before the decimal and 1 after)

        'price' => 'required|numeric', // Up to 6 digits before the decimal and 2 after
        'options' => 'required|string',
    ]);

    // Find the plan by id
    $plan = DB::table('plans')->find($planId);

    if (!$plan) {
        return redirect()->back()->with('error', 'Plan not found.');
    }

    // Update the plan
    DB::table('plans')
        ->where('id', $planId)
        ->update($validatedData);

    return redirect('/admin/plans/edit')->with('success', 'Plan updated successfully.');
}

    /**
     * Update the specified resource in storage.
     */
    public function upgradeSubscription(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'slug' => 'required',
            'planType' => 'sometimes|string'
        ]);

        $user = $request->user();
        // $controlstring = $user->controlstring;
        // $controlstring[1] = '0';
        // $controlstring[2] = '0';
        // Check if the user is already subscribed to the provided plan


        // Get the plan from the database
        $plan = DB::table('plans')
            ->select('id', 'stripe_plan', 'type', 'slug')
            ->where('slug', $validatedData['slug'])
            ->first();

        if (!$plan) {
            return response()->error('Plan not found', 400);
        }

        // Determine if the plan is yearly or monthly.
        $planType = $validatedData['planType'] ?? 'monthly';

        $stripePlanId = ($planType === 'yearly')
            ? env(strtoupper($planType) . '_STRIPE_PLAN_' . strtoupper(str_replace('-', '_', $validatedData['slug'])), '')
            : $plan->stripe_plan;

        if (!$stripePlanId) {
            return response()->error('Stripe plan ID not found', 400);
        }

        try {


            // Swap to the new plan and prorate the costs
            $user->subscription('default')->prorate()->swap($stripePlanId);

            // Handle your controlstring logic here (similar to your create method)
            $controlstring = $user->controlstring;
            if ($plan->type === 'Business') {
                $controlstring[1] = '1';
            } else if ($plan->type === 'Personal') {
                $controlstring[1] = '0';
            }

            $plan_slug = $plan->slug;
            $parts = explode('-', $plan_slug);
            $plan_type = end($parts);

            if ($plan_type === 'basic') {
                $controlstring[2] = '2';
            } elseif ($plan_type === 'premium') {
                $controlstring[2] = '3';
            } elseif ($plan_type === 'starter') {
                $controlstring[2] = '1';
            }

            $user->update(['controlstring' => $controlstring]);
            request()->session()->flash('success', "You upgraded for the {$plan_slug} plan!");
            return Redirect::to('customer/pricing');


        } catch (\Stripe\Exception\CardException $e) {
            // Handle card failures
            return response()->error($e->getError()->message, 400);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 400);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancelSubscription(Request $request)
    {
        $request->validate(['plan' => 'required']);
        request()->user()->subscription('default', $request->stripePlan)->cancel();

        $subscription = ModelsSubscription::where('user_id', request()->user()->id)->first();
        $subscription_id = $subscription->id;
        $plan = $subscription->plan; // fetch the associated plan

        $controlstring = $request->user()->controlstring;
        $controlstring[1] = '0';
        $controlstring[2] = '0';

        $user = $request->user();
        $user->update(['controlstring' => $controlstring]);

        $subscription->delete();
        DB::table('subscription_items')->where('subscription_id', $subscription_id)->delete();

        request()->session()->flash('success', "You unsubscribed from the plan!"); // use the plan's name

        return Redirect::to('customer/pricing');
    }
}
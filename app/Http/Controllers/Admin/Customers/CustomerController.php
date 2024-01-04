<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\Customer;
use App\Models\IndividualCustomer as Individual;
use App\Models\EntityCustomer as Entity;
use App\Models\File;
use App\Models\Phone;
use App\Models\Comment;
use App\Models\Activity;
use App\Models\ShareHolder;
use Auth;
use Exception;

class CustomerController extends Controller
{
    public function index(){
        $this->authorize('view-customers');
        return view('admin.customers.all', [
            'main_menu' => $this->getAdminMenu()
        ]);
    }

    public function edit($id){
        $this->authorize('create-transactions');
        $customer = Customer::with('customerable')->find($id);
        if($customer->customerable_type == 'App\\Models\\IndividualCustomer'){
            return view('admin.customers.individual', [
                'main_menu' => $this->getAdminMenu(),
                'type' => 'edit',
                'customer' => $customer,
                'countries' => $this->getCountries()
            ]);
        }else{
            return view('admin.customers.entity', [
                'main_menu' => $this->getAdminMenu(),
                'type' => 'edit',
                'customer' => $customer,
                'countries' => $this->getCountries(),
                'activities' => Activity::all()
            ]);
        }

    }

    public function destroy($id){
        $this->authorize('create-transactions');
        try{
            $customer = Customer::find($id);
            $customer->delete();
            return ['success' => true];
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function addInfo(Request $request, $id){
        $this->authorize('create-transactions');
        $customer = Customer::find($id);
        if($request->has('file')){
            $file = $request->file('file');
            $path = $file->store('public/customers_files');
            $customer->files()->save(
                new File([
                    'title' => $file->getClientOriginalName(),
                    'src' => substr($path, 23),
                    'type' => $file->getClientMimeType()
                ])
            );
        }elseif($request->has('phone')){
            $newPhone = new Phone(['phone' => $request->phone]);
            $customer->phones()->save( $newPhone );
            return $newPhone;
        }elseif($request->has('comment')){
            $newComment =  new Comment([
                'user_id' => Auth::id(),
                'comment' => $request->comment
            ]);
            $customer->comments()->save($newComment);
            return [ 'id' => $newComment->id, 'userName' => Auth::user()->name];
        }elseif($request->has('share_holder_name', 'share_holder_share')){
            if($customer->customerable_type == 'App\\Models\\IndividualCustomer'){
                throw new Exception('Indiviual customers can\'t has share holder');
            }
            $newShareHolder = new ShareHolder([
                'name' => $request->share_holder_name,
                'share' => $request->share_holder_share
            ]);
            $customer->customerable->share_holders()->save($newShareHolder);
            return $newShareHolder;
        }
    }

    public function getInfo(Request $request, $id){
        $this->authorize('view-customers');
        return Customer::with([
            'phones',
            'comments.user:id,name', 
            'files', 
            'customerable.country',
            'customerable' => function(MorphTo $morphTo){
                $morphTo->morphWith([
                    Entity::class => ['activity', 'share_holders']
                ]);
            }
        ])->find($id);
    }

    public function deleteInfo(Request $request, $id = null){
        $this->authorize('create-transactions');
        if($request->has('commentId')){
            Comment::find($request->commentId)->delete();
        }elseif($request->has('phoneId')){
            Phone::find($request->phoneId)->delete();
        }elseif($request->has('fileId')){
            File::find($request->fileId)->delete();
        }elseif($request->has('shareHolderId')){
            ShareHolder::find($request->shareHolderId)->delete();
        }
    }

    public function details($id){
        $this->authorize('view-customers');
        return view('admin.customers.details', [
            'main_menu' => $this->getAdminMenu(),
            'customer_id' => $id,
            'currencies' => $this->getCurrencies(),
            'payment_methods' => $this->getPaymentMethods(),
        ]);
    }
}

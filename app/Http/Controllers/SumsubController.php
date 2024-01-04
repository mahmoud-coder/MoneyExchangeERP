<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Customer;
use App\Models\IndividualCustomer;
use App\Models\Country;
use App\Models\Option;
use Exception;
use DB;
use Auth;

define("BASE_URL", "https://api.sumsub.com");

class SumsubController extends Controller
{
    private function sendRequest($url, $method = 'GET', $payload = ''){
        $ts = time();
        $signature = hash_hmac('sha256', $ts.$method.$url.$payload, config('app.sumsub_secret_key'));
        $response = Http::withHeaders([
            'X-App-Token'      => config('app.sumsub_app_token'),
            'X-App-Access-Ts'  => $ts,
            'X-App-Access-Sig' => $signature,
        ])->{ strtolower($method) }(BASE_URL . $url);
        return $response;
    }

    private function apiHealth(){
        return $this->sendRequest('/resources/status/api');
    }

    private function applicantData($id){
        return $this->sendRequest("/resources/applicants/$id/one");
    }

    public function person_applicant_reviewed(Request $request){
        if(! Option::get('sumsub-customer-create') ) return;
    
        try{
            if($request->reviewResult['reviewAnswer'] == 'GREEN'){
                $applicant = $this->applicantData( $request->applicantId );

                $id_card = null;
                if(isset( $applicant['info']['idDocs'] )):
                foreach($applicant['info']['idDocs'] as $doc){
                    if($doc['idDocType'] == 'ID_CARD'){
                        $id_card = $doc['number'];
                        break;
                    }
                }
                endif;
                $country_id = isset($applicant['info']['country']) ? Country::where('symbol', $applicant['info']['country'])->first()->id : null;
                $applicant_info = [
                    'name' => $applicant['info']['firstName'] ?? null,
                    'surname' => $applicant['info']['lastName'] ?? null,
                    'email' => $applicant['email'] ?? null,
                    'birthday' => $applicant['info']['dob'] ?? null,
                    'country_id' => $country_id,
                    'id_card' => $id_card
                ];
                
                try{
                    DB::beginTransaction();
                    $customer = new Customer;
                    $customer->creator_type = 'sumsub';
                    $customer->creator_id = null;
                    IndividualCustomer::create($applicant_info)->customer()->save($customer);
                    DB::commit();
                }catch(Exception $e){
                    DB::rollBack();
                    return ['success'=> false,'message'=> $e->getMessage()];
                }
            }
        }catch(Exception $e){
            return ['success'=> false,'message'=> $e->getMessage()];
        }
    }
}
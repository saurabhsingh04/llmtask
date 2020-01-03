<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use App\Http\Exceptions\BadRequestException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as Validation;
/**
 * Orders services functions
 */
class ValidationService
{
    /**
     * Validate validator
     *
     * @param Validator $validator
     * @return void
     * @throws Exception
     */
    protected function validate(Validation $validator)
    {   
        if($validator->fails())
        {
            throw new BadRequestException('INVALID_REQUEST', 400, $validator->messages()->get('*'));
        }
    }
    /**
     * Validate list order request
     *
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function listOrderValidate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'integer|min:1',
            'limit' => 'integer|min:1',
        ]);
        return $this->validate($validator);
    }
    /**
     * Validate create order request
     *
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function createOrderValidate(Request $request)
    {
        $validator = Validator::make(
            $request->all(), 
            [
            'origin' => 'required|array|between:2,2',
            'destination' => 'required|array|between:2,2',
            'origin.0' => ['string','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'destination.0' => ['string','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'origin.1' => ['string','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'destination.1' => ['string','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            ],
            [
                'origin.between' => 'Please provide latitude and longitude only for origin',
                'destination.between' => 'Please provide latitude and longitude only for destination',
                'origin.0.regex' => 'Please provide the valid origin latitude',
                'origin.1.regex' => 'Please provide the valid origin longitude',
                'origin.0.string' => 'Origin latitude must be string',
                'origin.1.string' => 'Origin longitude must be string',
                'destination.0.string' => 'Destination latitude must be string',
                'destination.1.string' => 'Destination longitude must be string',
                'destination.0.regex' => 'Please provide the valid destination latitude',
                'destination.1.regex' => 'Please provide the valid destnation longitude'
            ]
        );
        return $this->validate($validator);
    }
    /**
     * Validate update order request
     *
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function updateOrderValidate(Request $request)
    {
        $validator = Validator::make(array_merge($request->all(),['id'=>$request->route('id')]), [
            'status' => 'required|string|in:TAKEN',
            'id' => 'required|integer|min:1'
        ]);
        return $this->validate($validator);
    }
}
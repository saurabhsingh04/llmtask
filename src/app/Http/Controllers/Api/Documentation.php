<?php
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="LLM Task Api",
 *      description="LLM Task API",
 *      @OA\Contact(
 *          email="saurabh.singh04@nagarro.com"
 *      )
 * )
 */

 /**
 * @OA\Tag(
 *     name="Orders",
 *     description="Order related APIs"
 * )
 * 
 */

/**
 * @OA\Get(
 *      path="/orders",
 *      operationId="list",
 *      tags={"Orders"},
 *      summary="Get list of orders",
 *      description="Returns list of orders",
 *      @OA\Parameter(
 *            name="page",
 *            in="query",
 *            description="Page",
 *            required=false
 *        ),
 *        @OA\Parameter(
 *            name="limit",
 *            in="query",
 *            description="maximum number of results to return",
 *            required=false
 *        ),
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent (
 *              type = "array",
 *              @OA\Items(
 *                   @OA\Property(property="id", type="integer"),
 *                   @OA\Property(property="distance", type="integer"),
 *                   @OA\Property(property="status", type="string")
 *              )
 *           )
 *       ),
 *       @OA\Response(response=400, description="INVALID_REQUEST"),
 *     )
 *
 * Returns list of orders
 */
/**
 * @OA\Post(
 *      path="/orders",
 *      operationId="create",
 *      tags={"Orders"},
 *      summary="Create a new order",
 *      description="Returns order",
 *      @OA\RequestBody(
 *         request="new order",
 *         description="Create order",
 *         required=true,
 *         @OA\JsonContent (
 *            @OA\Property(property="origin", type="array", @OA\items(type="string")),
 *            @OA\Property(property="destination", type="array",  @OA\items(type="string"))
 *         )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent (
 *                   @OA\Property(property="id", type="integer"),
 *                   @OA\Property(property="distance", type="integer"),
 *                   @OA\Property(property="status", type="string")
 *           )
 *       ),
 *       @OA\Response(response=400, description="INVALID_REQUEST"),
 *       @OA\Response(response=422, description="DISTANCE_API_ERROR")
 *     )
 *
 * Returns list of orders
 */
/**
 * @OA\Patch(
 *      path="/orders/{id}",
 *      operationId="update status",
 *      tags={"Orders"},
 *      summary="Mark order as taken",
 *      description="Returns status",
 *      @OA\Parameter(
 *            name="id",
 *            in="path",
 *            description="order id",
 *            required=true,
 *            @OA\Schema(type="integer")
 *        ),
 *        @OA\RequestBody(
 *            request="status",
 *            description="order status",
 *            required=true,
 *            @OA\JsonContent (
 *                   @OA\Property(property="status", type="string"),
 *            )
 *        ),
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent (
 *                   @OA\Property(property="status", type="string"),
 *           )
 *       ),
 *       @OA\Response(response=400, description="INVALID_REQUEST"),
 *       @OA\Response(response=404, description="NOT_FOUND"),
 *       @OA\Response(response=409, description="ALREADY_TAKEN")
 *     )
 *
 * Returns list of orders
 */
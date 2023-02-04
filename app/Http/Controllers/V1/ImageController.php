<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepo;
use Illuminate\Http\Request;
use App\Traits\ApiResponsor;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
	protected $imageRepo;

	public function __construct(ImageRepo $imageRepo)
	{
		$this->imageRepo = $imageRepo;
	}

	public function index(Request $request): JsonResponse
	{
		$results = $this->imageRepo->upload($request);
		return response()->json($results, $results['code']);
	}
}

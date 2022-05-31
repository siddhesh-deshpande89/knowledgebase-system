<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(title="KnowledgeBase API", description="Knowledgebase System API Documentation",version="1.0.1",
 *     @OA\Contact(email="siddhesh.deshpande89@gmail.com",name="Siddhesh")
 * )
 * @OA\Server(
 *      url="http://localhost/api",
 *      description="Knowledgebase System"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

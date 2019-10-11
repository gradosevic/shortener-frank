<?php

namespace MuhamedDidovic\Shortener\Controllers;

use Cache;
use MuhamedDidovic\Shortener\Link;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class LinkStatsController extends BaseController
{
    public function show(Request $request)
    {

        $code = $request->get('code');
        //todo: check cache
        $link = Cache::remember("stats.{$code}", 10, function () use ($code) {
            return Link::byCode($code)->first();
        });

        if ($link === null) {
            return response(null, 404);
        }

        return $this->linkResponse($link, [
            'requested_count' => (int) $link->requested_count,
            'used_count' => (int) $link->used_count,
            'last_requested' => $link->last_requested,
            'last_used' => $link->last_used ? $link->last_used : null,
        ]);
    }
}

<?php

namespace Modules\Employee\Http\Services;

class LeavePermissionService {
    public function create_leave_permission($params)
    {
        $name = $params[0];
        $division = $params[1];
        $time = $params[2];
        $need = $params[3];

        $name = explode(':', $name)[1];
        $division = explode(':', $division)[1];
        $time = explode(':', $time)[1];
        $need = explode(':', $need)[1];

        $url = route('print.leave-permission', [
            'name' => $name,
            'division' => $division,
            'time' => $time,
            'need' => $need
        ]);
        return $url;
    }
}
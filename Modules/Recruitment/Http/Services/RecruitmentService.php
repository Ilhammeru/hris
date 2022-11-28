<?php

/**
* @author Ilham Gumilang <gumilang.dev@gmail.com>
* date 20221109
*/

namespace Modules\Recruitment\Http\Services;

use Modules\Recruitment\Entities\RecruitmentSetting;

class RecruitmentService {

    /**
     * Function to accept applicant to the next page
     * @param object data
     * @return array
     */
    public function acceptApplicant($data)
    {
        $current_progress = $data->progress_recruitment;
        $next_progress = $current_progress + 1;

        $next_data = RecruitmentSetting::find($next_progress);
        
    }
}
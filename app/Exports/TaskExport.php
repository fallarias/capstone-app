<?php

namespace App\Exports;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;


class TaskExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $taskId;
    
    public function __construct($taskId)
    {
        $this->taskId = $taskId;
    }

    public function collection()
    {
        return Task::where('task_id', $this->taskId)->get();
    }
}

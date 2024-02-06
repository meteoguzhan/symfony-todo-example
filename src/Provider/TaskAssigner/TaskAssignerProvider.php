<?php
namespace App\Provider\TaskAssigner;
use App\Strategy\TaskAssigner\TaskDeveloperAssignerStrategy;
use RuntimeException;

class TaskAssignerProvider
{
    private array $developers;

    public function __construct(array $developers)
    {
        $this->developers = $developers;
    }

    public function assignTasks(array $tasks, int $weeklyHours): array
    {
        $assignments = [];
        foreach ($tasks as $task) {
            $assigned = false;
            foreach ($this->developers as $developer) {
                $taskDeveloperAssignerStrategy = new TaskDeveloperAssignerStrategy($developer);
                $result = $taskDeveloperAssignerStrategy->assignTask($task, $weeklyHours);
                if ($result !== null) {
                    $assignments[$developer->getId()][] = $result;
                    $assigned = true;
                    break;
                }
            }

            if (!$assigned) {
                throw new RuntimeException('Task developers could not be assigned.');
            }
        }

        uksort($assignments, 'strnatcmp');
        $totalWeeksPerDeveloper = $this->calculateTotalWeeksForDevelopers($assignments, $weeklyHours);

        return [
            'assignments' => $assignments,
            'totalWeekPerDeveloper' => $totalWeeksPerDeveloper
        ];
    }

    private function calculateTotalWeeksForDevelopers(array $assignments, int $weeklyHours): array
    {
        $totalWeeksPerDeveloper = [];

        foreach ($assignments as $developerId => $developerTasks) {
            $developerTotalDuration = array_sum(array_column($developerTasks, 'duration'));
            $totalWeeksPerDeveloper[$developerId] = ceil($developerTotalDuration / $weeklyHours);
        }

        return $totalWeeksPerDeveloper;
    }
}


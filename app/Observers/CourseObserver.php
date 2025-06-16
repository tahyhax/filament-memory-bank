<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Course;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class CourseObserver
{
    /**
     * Handle the Course "created" event.
     */
    public function created(Course $course): void
    {
        $this->notifyAdminUsers(
            Notification::make()
                ->title('New Course Created')
                ->success()
                ->body("Course \"{$course->title}\" has been created successfully.")
                ->actions([
                    Action::make('view')
                        ->button()
                        ->label('View Course')
                        ->url("/admin/courses/{$course->id}/edit")
                        ->markAsRead(),
                ])
        );
    }

    /**
     * Handle the Course "updated" event.
     */
    public function updated(Course $course): void
    {
        // Only notify for significant updates (title, description, difficulty)
        if ($course->wasChanged(['title', 'description', 'difficulty_level'])) {
            $changes = [];
            
            if ($course->wasChanged('title')) {
                $changes[] = 'title';
            }
            if ($course->wasChanged('description')) {
                $changes[] = 'description';
            }
            if ($course->wasChanged('difficulty_level')) {
                $changes[] = 'difficulty level';
            }

            $changedFields = implode(', ', $changes);

            $this->notifyAdminUsers(
                Notification::make()
                    ->title('Course Updated')
                    ->info()
                    ->body("Course \"{$course->title}\" has been updated. Changed: {$changedFields}.")
                    ->actions([
                        Action::make('view')
                            ->button()
                            ->label('View Changes')
                            ->url("/admin/courses/{$course->id}/edit")
                            ->markAsRead(),
                    ])
            );
        }
    }

    /**
     * Handle the Course "deleted" event.
     */
    public function deleted(Course $course): void
    {
        $this->notifyAdminUsers(
            Notification::make()
                ->title('Course Deleted')
                ->warning()
                ->body("Course \"{$course->title}\" has been deleted from the system.")
                ->actions([
                    Action::make('viewAll')
                        ->button()
                        ->label('View All Courses')
                        ->url('/admin/courses')
                        ->markAsRead(),
                ])
        );
    }

    /**
     * Handle the Course "restored" event.
     */
    public function restored(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "force deleted" event.
     */
    public function forceDeleted(Course $course): void
    {
        //
    }

    /**
     * Send notification to all admin users.
     */
    private function notifyAdminUsers(Notification $notification): void
    {
        // Get all users with super_admin role
        $adminUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'super_admin');
        })->get();

        // If no admin users found, notify the first user
        if ($adminUsers->isEmpty()) {
            $firstUser = User::first();
            if ($firstUser) {
                $notification->sendToDatabase($firstUser);
            }
        } else {
            foreach ($adminUsers as $admin) {
                $notification->sendToDatabase($admin);
            }
        }
    }
}

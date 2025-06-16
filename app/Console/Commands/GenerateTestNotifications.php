<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;

class GenerateTestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'notifications:generate {--user= : User ID to send notifications to}';

    /**
     * The console command description.
     */
    protected $description = 'Generate test notifications for development purposes';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $userId = $this->option('user');
        
        if ($userId) {
            $user = User::find((int) $userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return;
            }
        } else {
            $user = User::first();
            if (!$user) {
                $this->error('No users found in the database.');
                return;
            }
        }

        $this->info("Generating test notifications for user: {$user->email}");

        // Success notification
        Notification::make()
            ->title('Course Created Successfully')
            ->success()
            ->body('New course "Advanced Laravel" has been created and is ready for enrollment.')
            ->actions([
                Action::make('view')
                    ->button()
                    ->url('/admin/courses')
                    ->markAsRead(),
                Action::make('edit')
                    ->button()
                    ->color('warning')
                    ->url('/admin/courses/create'),
            ])
            ->sendToDatabase($user);

        // Warning notification
        Notification::make()
            ->title('Course Capacity Almost Full')
            ->warning()
            ->body('Course "PHP Fundamentals" is at 90% capacity. Only 2 spots remaining.')
            ->actions([
                Action::make('viewCourse')
                    ->button()
                    ->label('View Course')
                    ->url('/admin/courses')
                    ->markAsRead(),
            ])
            ->sendToDatabase($user);

        // Error notification
        Notification::make()
            ->title('System Backup Failed')
            ->danger()
            ->body('The scheduled database backup failed. Please check the system logs.')
            ->actions([
                Action::make('checkLogs')
                    ->button()
                    ->label('Check Logs')
                    ->color('danger'),
                Action::make('retryBackup')
                    ->button()
                    ->label('Retry Backup')
                    ->color('warning'),
            ])
            ->sendToDatabase($user);

        // Info notification
        Notification::make()
            ->title('New Credit Available')
            ->info()
            ->body('A new credit "Database Design" has been added to the system.')
            ->actions([
                Action::make('viewCredits')
                    ->button()
                    ->label('View Credits')
                    ->url('/admin/credits')
                    ->markAsRead(),
            ])
            ->sendToDatabase($user);

        // Regular notification without actions
        Notification::make()
            ->title('System Maintenance Scheduled')
            ->body('System maintenance is scheduled for tonight at 2:00 AM. Expected downtime: 30 minutes.')
            ->sendToDatabase($user);

        // Course relationship notification
        Notification::make()
            ->title('New Course Relationship Added')
            ->success()
            ->body('Course "Advanced PHP" now has a prerequisite relationship with "PHP Fundamentals".')
            ->actions([
                Action::make('viewRelationships')
                    ->button()
                    ->label('View Relationships')
                    ->url('/admin/courses')
                    ->markAsRead(),
            ])
            ->sendToDatabase($user);

        // Specialty notification
        Notification::make()
            ->title('Specialty Requirements Updated')
            ->warning()
            ->body('The "Web Development" specialty requirements have been updated. Please review the changes.')
            ->actions([
                Action::make('reviewChanges')
                    ->button()
                    ->label('Review Changes')
                    ->url('/admin/specialties')
                    ->markAsRead(),
                Action::make('dismiss')
                    ->button()
                    ->label('Dismiss')
                    ->color('gray')
                    ->markAsRead(),
            ])
            ->sendToDatabase($user);

        $this->info('✅ 7 test notifications generated successfully!');
        $this->info('💡 You can view them in the Filament admin panel notifications.');
    }
}

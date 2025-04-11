<?php



use Illuminate\Support\Facades\Schedule;


Schedule::command('app:send-event-reminders')->daily();
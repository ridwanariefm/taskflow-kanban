<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\KanbanBoard;

// Route ini mengarahkan halaman utama (/) langsung ke Kanban Board
Route::get('/', KanbanBoard::class);
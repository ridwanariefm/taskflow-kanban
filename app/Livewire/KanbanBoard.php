<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Board;
use App\Models\Card;
use Illuminate\Support\Facades\Storage;

class KanbanBoard extends Component
{
    use WithFileUploads;

    public $newCardTitle = [];
    public $newCardImages = [];
    
    // Variabel untuk Edit masih dibutuhkan
    public $editingCardId = null;
    public $editingTitle = '';
    public $image;

    public function mount()
    {
        // Cek jika data kosong, buat data dummy
        if (Board::count() == 0) {
            $board = Board::create(['user_id' => 1, 'title' => 'My First Project']);
            $col1 = $board->columns()->create(['title' => 'To Do', 'position' => 1]);
            $board->columns()->create(['title' => 'In Progress', 'position' => 2]);
            $board->columns()->create(['title' => 'Done', 'position' => 3]);
            
            $col1->cards()->create(['title' => 'Welcome Task!', 'position' => 1]);
        }
    }

    public function render()
    {
        $board = Board::with(['columns.cards'])->first();
        return view('livewire.kanban-board', [
            'columns' => $board->columns
        ]);
    }

    public function addCard($columnId)
    {
        $this->validate([
            'newCardTitle.' . $columnId => 'required|string|max:50',
            'newCardImages.' . $columnId => 'nullable|image|max:1024',
        ]);

        $card = new Card();
        $card->column_id = $columnId;
        $card->title = $this->newCardTitle[$columnId];
        $card->position = 9999;

        if (isset($this->newCardImages[$columnId])) {
            $path = $this->newCardImages[$columnId]->store('cards', 'public');
            $card->image_path = $path;
        }

        $card->save();

        // === TAMBAHKAN BAGIAN RESET INI (PENTING) ===
        
        // 1. Kosongkan textbox judul untuk kolom ini
        $this->newCardTitle[$columnId] = ''; 
        
        // 2. Kosongkan input file/gambar untuk kolom ini
        unset($this->newCardImages[$columnId]);
    }

    public function updateCardOrder($orderedIds, $newColumnId)
    {
        foreach ($orderedIds as $index => $cardId) {
            if($card = Card::find($cardId)) {
                $card->column_id = $newColumnId;
                $card->position = $index + 1;
                $card->save();
            }
        }
    }

    public function editCard($cardId)
    {
        $this->editingCardId = $cardId;
        $this->editingTitle = Card::find($cardId)->title;
        $this->image = null;
    }

    public function cancelEdit()
    {
        $this->editingCardId = null;
        $this->editingTitle = '';
        $this->image = null;
    }

    public function updateCard()
    {
        $this->validate([
            'editingTitle' => 'required|string|max:50',
            'image' => 'nullable|image|max:1024',
        ]);

        $card = Card::find($this->editingCardId);
        $card->title = $this->editingTitle;

        if ($this->image) {
            // Hapus gambar lama
            if ($card->image_path) {
                Storage::disk('public')->delete($card->image_path);
            }
            // Upload gambar baru
            $path = $this->image->store('cards', 'public');
            $card->image_path = $path;
        }

        $card->save();
        $this->cancelEdit();
    }

    // === BAGIAN INI YANG DIUBAH UNTUK SWEETALERT ===
    
    // Nama fungsinya kita sesuaikan dengan panggilan JS: 'deleteCard'
    public function deleteCard($cardId)
    {
        $card = Card::find($cardId);
        
        if ($card) {
            // Hapus gambar fisik dari storage
            if ($card->image_path) {
                Storage::disk('public')->delete($card->image_path);
            }
            // Hapus data dari database
            $card->delete();
        }
    }

    public function clearAllTasks()
    {
        // 1. Ambil semua kartu yang memiliki gambar
        $cardsWithImages = Card::whereNotNull('image_path')->get();

        // 2. Hapus file fisik satu per satu
        foreach ($cardsWithImages as $card) {
            Storage::disk('public')->delete($card->image_path);
        }

        // 3. Hapus SEMUA data di tabel cards
        // Menggunakan truncate() lebih cepat dan me-reset ID auto-increment,
        // tapi jika error foreign key, gunakan Card::query()->delete();
        Card::query()->delete(); 
    }
}
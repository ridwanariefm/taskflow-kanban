<div class="h-screen flex flex-col overflow-hidden">
    {{-- Header --}}
    <div
        class="px-8 py-6 flex justify-between items-center bg-white/50 backdrop-blur-sm border-b border-white/20 sticky top-0 z-10">
        <div>
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
                TaskFlow
            </h1>
            <p class="text-sm text-gray-500 font-medium">Drag & Drop Project Management</p>
        </div>
        {{-- Bagian Kanan Header --}}
        <div class="flex items-center gap-3">
            {{-- Tombol Delete All Baru --}}
            <button onclick="confirmClearAll()"
                class="flex items-center gap-2 px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors cursor-pointer border border-red-200"
                title="Hapus Semua Task">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16M10 11v6m4-6v6" />
                </svg>
                Reset Board
            </button>

            <div class="h-6 w-px bg-gray-300 mx-1"></div> {{-- Pemisah --}}

            {{-- Label Lama --}}
            <span
                class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider">
                Laravel 12
            </span>
            <span class="px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-xs font-bold uppercase tracking-wider">
                Livewire 3
            </span>
        </div>
    </div>

    {{-- Board Area --}}
    <div class="flex-1 overflow-x-auto overflow-y-hidden">
        <div class="h-full flex px-8 pb-8 pt-4 gap-6 items-start">

            @foreach($columns as $column)
                    {{-- Kolom --}}
                    <div
                        class="w-80 flex-shrink-0 flex flex-col max-h-full bg-white/60 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl shadow-indigo-100/20">

                        {{-- Header Kolom --}}
                        <div class="p-4 flex justify-between items-center border-b border-gray-100">
                            <h3 class="font-bold text-gray-700 flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full 
                                                                                                                                                                        {{ $column['title'] == 'To Do' ? 'bg-red-400' :
                ($column['title'] == 'In Progress' ? 'bg-yellow-400' : 'bg-green-400') }}">
                                </div>
                                {{ $column['title'] }}
                            </h3>
                            <span class="bg-white text-gray-500 text-xs font-bold px-2 py-1 rounded-lg border shadow-sm">
                                {{ count($column['cards']) }}
                            </span>
                        </div>

                        {{-- Scroll Area Kartu --}}
                        <div class="flex-1 overflow-y-auto p-3 custom-scrollbar">
                            <div class="kanban-cards min-h-[100px] space-y-3 pb-2" data-column-id="{{ $column['id'] }}">
                                @foreach($column['cards'] as $card)
                                    <div wire:key="card-{{ $card['id'] }}"
                                        class="group relative bg-white p-4 rounded-xl shadow-sm border border-transparent hover:border-indigo-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 ease-out cursor-grab active:cursor-grabbing"
                                        data-card-id="{{ $card['id'] }}">
                                        {{-- === TAMPILAN GAMBAR (Cover) === --}}
                                        @if($card['image_path'])
                                            <div class="mb-3 rounded-lg overflow-hidden h-32 w-full bg-gray-100">
                                                <img src="{{ asset('storage/' . $card['image_path']) }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        @endif

                                        @if($editingCardId === $card['id'])
                                            {{-- === MODE EDIT (Form Upload) === --}}
                                            <form wire:submit.prevent="updateCard" class="relative z-20">
                                                <textarea wire:model="editingTitle"
                                                    class="w-full text-sm border-2 border-indigo-400 rounded-lg p-2 focus:ring-0 focus:outline-none resize-none bg-indigo-50 mb-2"
                                                    rows="2" autofocus></textarea>

                                                {{-- Input File --}}
                                                <div class="mb-2">
                                                    <label class="block text-xs font-medium text-gray-500 mb-1">Ganti Cover
                                                        Image</label>
                                                    <input type="file" wire:model="image" onchange="checkFileSize(this)"
                                                        class="block w-full text-xs text-gray-500
                                                                                                                                                                                                                                                                                                                                  file:mr-2 file:py-1 file:px-2
                                                                                                                                                                                                                                                                                                                                  file:rounded-full file:border-0
                                                                                                                                                                                                                                                                                                                                  file:text-xs file:font-semibold
                                                                                                                                                                                                                                                                                                                                  file:bg-indigo-50 file:text-indigo-700
                                                                                                                                                                                                                                                                                                                                  hover:file:bg-indigo-100
                                                                                                                                                                                                                                                                                                                                " />

                                                    {{-- Loading Indicator saat upload --}}
                                                    <div wire:loading wire:target="image" class="text-xs text-blue-500 mt-1">
                                                        Sedang upload...
                                                    </div>
                                                </div>

                                                <div class="flex gap-2 justify-end">
                                                    <button type="button" wire:click="cancelEdit"
                                                        class="px-3 py-1 text-xs font-medium text-gray-500 hover:text-gray-700 cursor-pointer">Cancel</button>
                                                    <button type="submit"
                                                        class="px-3 py-1 text-xs font-bold bg-indigo-600 text-white rounded-md hover:bg-indigo-700 shadow-lg shadow-indigo-200 cursor-pointer">Save</button>
                                                </div>
                                            </form>
                                        @else
                                            {{-- === MODE TAMPIL === --}}
                                            <div class="flex justify-between items-start gap-2">
                                                <p class="text-sm font-medium text-gray-700 leading-relaxed">
                                                    {{ $card['title'] }}
                                                </p>
                                            </div>

                                            {{-- Tombol Aksi (Muncul saat Hover) --}}
                                            <div
                                                class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-white/90 backdrop-blur rounded-lg p-1 shadow-sm border">
                                                <button wire:click="editCard({{ $card['id'] }})"
                                                    class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors cursor-pointer"
                                                    title="Edit">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                                <button type="button" onclick="confirmDelete({{ $card['id'] }})" {{-- Panggil JS --}}
                                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors cursor-pointer"
                                                    title="Hapus">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Footer (Input Tambah) --}}
                        {{-- GANTI BAGIAN FOOTER MENJADI SEPERTI INI --}}
                        <div class="p-3 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl">
                            <form wire:submit.prevent="addCard({{ $column['id'] }})">

                                {{-- Preview Gambar --}}
                                @if(isset($newCardImages[$column['id']]))
                                    <div class="mb-2 relative w-fit group-preview">
                                        <img src="{{ $newCardImages[$column['id']]->temporaryUrl() }}"
                                            class="h-12 w-12 rounded-lg object-cover border border-indigo-200 shadow-sm">
                                        <button type="button" wire:click="$set('newCardImages.{{ $column['id'] }}', null)"
                                            class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full p-0.5 shadow-sm hover:bg-red-600 transition cursor-pointer"
                                            title="Batal upload">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif

                                <div class="relative group flex items-center">
                                    <input {{--===PERBAIKAN UTAMA DI SINI===--}} {{-- Kita tambahkan count() agar key-nya unik
                                        setiap kali kartu bertambah --}}
                                        wire:key="input-title-{{ $column['id'] }}-{{ count($column['cards']) }}" type="text"
                                        wire:model="newCardTitle.{{ $column['id'] }}" placeholder="Add new task..."
                                        class="w-full pl-3 pr-20 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm placeholder-gray-400">
                                    <div class="absolute right-2 top-1.5 flex items-center gap-1">
                                        <label for="img-input-{{ $column['id'] }}"
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md cursor-pointer transition-colors"
                                            title="Attach Image">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                            </svg>
                                        </label>
                                        <input {{-- Key untuk input file juga boleh disamakan logikanya --}}
                                            wire:key="input-file-{{ $column['id'] }}-{{ count($column['cards']) }}"
                                            id="img-input-{{ $column['id'] }}" type="file" onchange="checkFileSize(this)"
                                            wire:model="newCardImages.{{ $column['id'] }}" class="hidden" accept="image/*">
                                        <button type="submit"
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div wire:loading wire:target="newCardImages.{{ $column['id'] }}"
                                    class="text-[10px] text-indigo-500 mt-1 pl-1 font-medium flex items-center gap-1">
                                    <svg class="animate-spin h-3 w-3 text-indigo-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Uploading...
                                </div>
                            </form>
                        </div>
                    </div>
            @endforeach
        </div>
    </div>

    {{-- === MODAL KONFIRMASI HAPUS (Alpine.js) === --}}
    <div x-data="{ open: @entangle('confirmingCardId') }" x-show="open" x-cloak style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full p-6 border border-gray-100">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4 animate-pulse">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="text-center">
                    <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Hapus Tugas?</h3>
                    <p class="text-sm text-gray-500 mt-2">
                        Apakah kamu yakin ingin menghapus tugas ini? <br>
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="mt-6 flex justify-center gap-3">
                    <button wire:click="cancelDelete"
                        class="px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer transition-colors">
                        Batal
                    </button>
                    <button wire:click="destroyCard"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 shadow-lg shadow-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 cursor-pointer transition-colors">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:navigated', () => { initSortable(); });
        document.addEventListener('DOMContentLoaded', () => { initSortable(); });

        function initSortable() {
            const containers = document.querySelectorAll('.kanban-cards');
            if (containers.length === 0) return;

            containers.forEach(container => {
                if (container.classList.contains('sortable-loaded')) return;

                new Sortable(container, {
                    group: 'shared',
                    animation: 200,
                    delay: 0,
                    delayOnTouchOnly: true,
                    ghostClass: 'sortable-ghost',
                    dragClass: 'sortable-drag',

                    onEnd: function (evt) {
                        const newColumnEl = evt.to;
                        const newColumnId = newColumnEl.getAttribute('data-column-id');
                        const orderedIds = Array.from(newColumnEl.children)
                            .filter(child => child.hasAttribute('data-card-id'))
                            .map(child => child.getAttribute('data-card-id'));
                        @this.updateCardOrder(orderedIds, newColumnId);
                    }
                });
                container.classList.add('sortable-loaded');
            });
        }
    </script>

    <style>
        .sortable-ghost {
            background: #f0fdf4;
            border: 2px dashed #4ade80;
            opacity: 0.5;
            box-shadow: none;
        }

        .sortable-drag {
            cursor: grabbing;
            opacity: 1;
            background: white;
            transform: rotate(2deg);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>

    <script>
        function confirmDelete(cardId) {
            Swal.fire({
                title: 'Hapus Tugas?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteCard', cardId);

                    Swal.fire({
                        title: 'Terhapus!',
                        text: 'Tugas berhasil dihapus.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            })
        }

        // === FUNGSI CEK UKURAN FILE ===
        function checkFileSize(input) {
            if (input.files && input.files[0]) {
                const maxBytes = 1024 * 1024; // 1MB (1024KB)
                const fileSize = input.files[0].size;

                if (fileSize > maxBytes) {
                    // 1. Munculkan Alert Error
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar!',
                        text: 'Ukuran maksimal gambar adalah 1MB (1024KB).',
                        confirmButtonColor: '#d33'
                    });

                    // 2. Reset Input File (Agar tidak jadi di-upload)
                    input.value = '';

                    // 3. (Opsional) Reset property Livewire jika perlu, 
                    // tapi biasanya input.value = '' sudah cukup mencegah upload visual.
                }
            }
        }

        function confirmClearAll() {
            Swal.fire({
                title: 'RESET PROJECT?',
                text: "Semua tugas dan gambar akan dihapus permanen. Tindakan ini TIDAK BISA dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', // Merah Gelap
                cancelButtonColor: '#6b7280', // Abu-abu
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal',
                focusCancel: true // Fokus ke tombol batal biar aman
            }).then((result) => {
                if (result.isConfirmed) {
                    // Panggil Backend
                    @this.call('clearAllTasks');

                    Swal.fire({
                        title: 'Bersih!',
                        text: 'Semua tugas telah dihapus.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            })
        }
    </script>
</div>
<div class="container-fluid vh-100">
    <div class="row h-100">
        {{-- Sidebar --}}
        <div class="col-md-3 bg-light border-end p-0 d-flex flex-column" style="max-height: 100vh;">
            {{-- Header Sidebar --}}
            <div class="p-3 border-bottom bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">📝 Catatan Saya</h5>
                    <button wire:click="createNote" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg"></i> Baru
                    </button>
                </div>

                {{-- Search Bar --}}
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text"
                           wire:model.live.debounce.300ms="searchQuery"
                           class="form-control border-start-0"
                           placeholder="Cari catatan...">
                </div>
            </div>

            {{-- List Notes --}}
            <div class="flex-grow-1 overflow-auto">
                @forelse($notes as $note)
                    <div wire:click="selectNote({{ $note->id }})"
                         class="note-item p-3 border-bottom {{ $selectedNote && $selectedNote->id == $note->id ? 'bg-primary bg-opacity-10 border-start border-primary border-3' : '' }}"
                         style="cursor: pointer; transition: all 0.2s;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1 overflow-hidden">
                                <h6 class="mb-1 fw-bold text-truncate">
                                    @if($note->is_favorite)
                                        <span class="text-warning">⭐</span>
                                    @endif
                                    {{ $note->title ?: 'Tanpa Judul' }}
                                </h6>
                                <p class="mb-1 text-muted small text-truncate" style="line-height: 1.4;">
                                    {{ Str::limit(strip_tags($note->content), 60) }}
                                </p>
                                <small class="text-muted">
                                    {{ $note->updated_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-5 text-muted">
                        @if($searchQuery)
                            <i class="bi bi-search fs-1 mb-3 d-block"></i>
                            <p class="mb-0">Tidak ada catatan ditemukan</p>
                            <small>Coba kata kunci lain</small>
                        @else
                            <i class="bi bi-journal-text fs-1 mb-3 d-block"></i>
                            <p class="mb-0">Belum ada catatan</p>
                            <small>Klik tombol "Baru" untuk membuat catatan</small>
                        @endif
                    </div>
                @endforelse
            </div>

            {{-- Footer Info --}}
            <div class="p-2 border-top bg-white text-center">
                <small class="text-muted">
                    Total: {{ count($notes) }} catatan
                </small>
            </div>
        </div>

        {{-- Main Content Area --}}
        <div class="col-md-9 p-0 d-flex flex-column" style="max-height: 100vh;">
            @if($selectedNote)
                {{-- Toolbar --}}
                <div class="p-3 border-bottom bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <button wire:click="toggleFavorite"
                                    class="btn {{ $selectedNote->is_favorite ? 'btn-warning' : 'btn-outline-warning' }} btn-sm">
                                {{ $selectedNote->is_favorite ? '⭐' : '☆' }}
                                {{ $selectedNote->is_favorite ? 'Favorit' : 'Tandai Favorit' }}
                            </button>
                            <button wire:click="deleteNote"
                                    class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus catatan ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i>
                            Terakhir diubah: {{ $selectedNote->updated_at->format('d M Y, H:i') }}
                        </small>
                    </div>
                </div>

                {{-- Editor Area --}}
                <div class="flex-grow-1 overflow-auto p-4 bg-white">
                    {{-- Title Input --}}
                    <div class="mb-4">
                        <input type="text"
                               wire:model.blur="noteTitle"
                               wire:change="updateNote"
                               class="form-control form-control-lg border-0 fw-bold ps-0"
                               placeholder="Judul Catatan"
                               style="font-size: 2rem; box-shadow: none;">
                    </div>

                    {{-- Content Textarea --}}
                    <div>
                        <textarea wire:model.blur="noteContent"
                                  wire:change="updateNote"
                                  class="form-control border-0 ps-0"
                                  placeholder="Mulai menulis catatan Anda di sini..."
                                  rows="25"
                                  style="resize: none; font-size: 1.1rem; line-height: 1.8; box-shadow: none;"></textarea>
                    </div>
                </div>
            @else
                {{-- Empty State --}}
                <div class="d-flex flex-column justify-content-center align-items-center h-100 text-muted bg-light">
                    <i class="bi bi-journal-text mb-4" style="font-size: 5rem;"></i>
                    <h4 class="mb-2">Selamat Datang di Notes App</h4>
                    <p class="text-center mb-4" style="max-width: 400px;">
                        Pilih catatan dari sidebar di sebelah kiri atau klik tombol "Baru" untuk membuat catatan baru
                    </p>
                    <button wire:click="createNote" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Buat Catatan Baru
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

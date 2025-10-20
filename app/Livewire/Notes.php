<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Note;

class Notes extends Component
{
    public $notes;
    public $selectedNote;
    public $noteTitle = '';
    public $noteContent = '';
    public $searchQuery = '';

    public function mount()
    {
        $this->loadNotes();
    }

    public function loadNotes()
    {
        $query = Note::query();
        
        if ($this->searchQuery) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('content', 'like', '%' . $this->searchQuery . '%');
            });
        }
        
        $this->notes = $query->orderBy('pinned', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function selectNote($id)
    {
        $this->selectedNote = Note::find($id);
        if ($this->selectedNote) {
            $this->noteTitle = $this->selectedNote->title ?? '';
            $this->noteContent = $this->selectedNote->content ?? '';
        }
    }

    public function updateNote()
    {
        if ($this->selectedNote) {
            $this->selectedNote->update([
                'title' => $this->noteTitle ?: 'Tanpa Judul',
                'content' => $this->noteContent
            ]);
            $this->loadNotes();
            $this->dispatch('note-saved');
        }
    }

    public function togglePinned()
    {
        if ($this->selectedNote) {
            $this->selectedNote->update([
                'pinned' => !$this->selectedNote->pinned
            ]);
            $this->loadNotes();
            $this->selectNote($this->selectedNote->id);
        }
    }

    public function deleteNote()
    {
        if ($this->selectedNote) {
            $this->selectedNote->delete();
            $this->selectedNote = null;
            $this->noteTitle = '';
            $this->noteContent = '';
            $this->loadNotes();
        }
    }

    public function createNote()
    {
        $note = Note::create([
            'title' => 'Catatan Baru',
            'content' => ''
        ]);
        $this->selectNote($note->id);
        $this->loadNotes();
    }

    public function updatedSearchQuery()
    {
        $this->loadNotes();
    }

    public function render()
    {
        return view('livewire.notes');
    }
}
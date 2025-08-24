<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kuis;
use Livewire\WithPagination;

class KuisList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage(); // reset pagination on search update
    }

    public function editKuis($id)
    {
        return redirect('/kuis' . '/' . $id . '/edit');
    }

    public function render()
    {
        $quizzes = Kuis::where('nama', 'like', '%' . $this->search . '%')
                        ->orderBy('dibuat_pada', 'desc')
                        ->paginate(10);

        return view('livewire.kuis-list', [
            'quizzes' => $quizzes
        ]);
    }
}


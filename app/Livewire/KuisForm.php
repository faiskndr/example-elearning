<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Kuis;
use App\Models\KuisSoal;
use App\Models\KuisSoalOpsi;
use Illuminate\Support\Facades\DB;

class KuisForm extends Component
{
    public $nama;
    public $questions = [];

    public function mount($id = null)
    {
        $this->addQuestion($id);
    }

    public function addQuestion($kuisId = null)
    {
        if (!is_null($kuisId)) {
            $this->questions = KuisSoal::where('kuis_id', $kuisId)
                ->get()
                ->map(function ($soal) {
                    return [
                        'soal' => $soal->soal,
                        'poin' => $soal->poin,
                        'options' => $soal->soalOpsiRelations->map(function ($opsi) {
                            return ['jawaban' => $opsi->jawaban, 'is_kunci_jawaban' => $opsi->is_kunci_jawaban ? true : false];
                        })->toArray()
                    ];
                })->toArray();
                // dd($this->questions);
        } else {
            $this->questions[] = [
                'soal' => '',
                'poin' => 1,
                'options' => [
                    ['jawaban' => '', 'is_kunci_jawaban' => false],
                    ['jawaban' => '', 'is_kunci_jawaban' => false],
            ],
        ];
        }
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function addOption($questionIndex)
    {
        $this->questions[$questionIndex]['options'][] = ['jawaban' => '', 'is_kunci_jawaban' => false];
    }

    public function removeOption($questionIndex, $optionIndex)
    {
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
        $this->questions[$questionIndex]['options'] = array_values($this->questions[$questionIndex]['options']);
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'questions.*.soal' => 'required|string',
            'questions.*.poin' => 'required|integer|min:1',
            'questions.*.options.*.jawaban' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $quiz = Kuis::create([
                'nama' => $this->nama,
            ]);
            
            foreach ($this->questions as $question) {
                $soal = KuisSoal::create([
                    'kuis_id' => $quiz->kuis_id,
                    'soal' => $question['soal'],
                    'poin' => $question['poin'],
                ]);

                foreach ($question['options'] as $option) {
                    KuisSoalOpsi::create([
                        'kuis_soal_id' => $soal->kuis_soal_id,
                        'jawaban' => $option['jawaban'],
                        'is_kunci_jawaban' => $option['is_kunci_jawaban'] ? 1 : 0,
                    ]);
                }
            }

            DB::commit();
            session()->flash('success', 'Kuis berhasil dibuat!');
            $this->reset(['nama', 'questions']);
            $this->mount(); // reset form
            return redirect('/kuis');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menyimpan kuis: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.kuis-form');
    }
}

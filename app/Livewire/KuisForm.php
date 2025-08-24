<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Kuis;
use App\Models\KuisSoal;
use App\Models\KuisSoalOpsi;
use Illuminate\Support\Facades\DB;

class KuisForm extends Component
{
    public $kuis_id;
    public $nama;
    public $questions = [];

    public function mount($id = null)
    {
        $this->addQuestion($id);
    }

    public function addQuestion($kuisId = null)
    {
        if (!is_null($kuisId)) {
            $this->kuis_id = $kuisId;
            $this->nama = Kuis::find($kuisId)->nama;
            $this->questions = KuisSoal::where('kuis_id', $kuisId)
                ->get()
                ->map(function ($soal) {
                    return [
                        'kuis_soal_id' => $soal->kuis_soal_id,
                        'soal' => $soal->soal,
                        'poin' => $soal->poin,
                        'options' => $soal->soalOpsiRelations->map(function ($opsi) {
                            return [
                                'kuis_soal_opsi_id' => $opsi->kuis_soal_opsi_id,
                                'jawaban' => $opsi->jawaban, 
                                'is_kunci_jawaban' => $opsi->is_kunci_jawaban ? true : false
                            ];
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
            'kuis_id' => 'nullable|integer',
            'nama' => 'required|string|max:255',
            'questions.*.soal' => 'required|string',
            'questions.*.poin' => 'required|integer|min:1',
            'questions.*.options.*.jawaban' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $isUbah = !is_null($this->kuis_id);
            
            if ($isUbah) {
                $quiz = Kuis::find($this->kuis_id);
                $quiz->nama = $this->nama;
                $quiz->save();
            } else {
                $quiz = Kuis::create([
                    'nama' => $this->nama,
                ]);
            }
            
            $soalList = [];
            foreach ($this->questions as $question) {
                $soalList[] = [
                    'kuis_soal_id' => $question['kuis_soal_id'] ?? null,
                    'kuis_id' => $quiz->kuis_id,
                    'soal' => $question['soal'],
                    'poin' => $question['poin'],
                ];
            }
            
            KuisSoal::upsert($soalList, 'kuis_soal_id');
            $kuisSoalId = KuisSoal::where('kuis_id', $quiz->kuis_id)
                ->pluck('kuis_soal_id')
                ->toArray();
            
            $kuisSoalOpsiList = [];
            foreach ($kuisSoalId as $key => $value) {
                foreach ($this->questions[$key]['options'] as $option) {
                    $kuisSoalOpsiList[] = [
                        'kuis_soal_opsi_id' => $option['kuis_soal_opsi_id'] ?? null,
                        'kuis_soal_id' => $value,
                        'jawaban' => $option['jawaban'],
                        'is_kunci_jawaban' => $option['is_kunci_jawaban'] ? 1 : 0,
                    ];
                }
            }
        
            KuisSoalOpsi::upsert($kuisSoalOpsiList, 'kuis_soal_opsi_id');

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

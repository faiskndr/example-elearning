<div class="container mx-auto space-y-6">
    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded">
            {{ session('success') }}
        </div>
    @elseif (session()->has('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div>
        <label class="block font-bold mb-4">Nama Kuis</label>
        <input type="text" wire:model="nama" class="border p-2 rounded w-full" />
        @error('nama') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    @foreach ($questions as $qIndex => $question)
        <div class="border p-4 rounded bg-gray-50">
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-semibold">Soal #{{ $qIndex + 1 }}</h3>
                <button type="button" wire:click="removeQuestion({{ $qIndex }})" class="text-red-600">Hapus</button>
            </div>

            <input type="text" wire:model="questions.{{ $qIndex }}.soal" placeholder="Pertanyaan"
                   class="border p-2 rounded w-full mb-2" />
            @error("questions.$qIndex.soal") <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

            <input type="number" wire:model="questions.{{ $qIndex }}.poin" placeholder="Poin"
                   class="border p-2 rounded w-32 mb-2" min="1" />

            <div class="mt-2 space-y-2">
                @foreach ($question['options'] as $oIndex => $option)
                    <div class="flex items-center space-x-2">
                        <input type="text" wire:model="questions.{{ $qIndex }}.options.{{ $oIndex }}.jawaban"
                               class="border p-2 rounded w-full" placeholder="Opsi Jawaban" />
                        <label>
                            <input type="checkbox" wire:model="questions.{{ $qIndex }}.options.{{ $oIndex }}.is_kunci_jawaban" />
                            Benar
                        </label>
                        <button type="button" wire:click="removeOption({{ $qIndex }}, {{ $oIndex }})"
                                class="text-red-600">✖</button>
                    </div>
                @endforeach
                <button type="button" wire:click="addOption({{ $qIndex }})"
                        class="text-blue-600 text-sm mt-2">+ Tambah Opsi</button>
            </div>
        </div>
    @endforeach

    <button type="button" wire:click="addQuestion" class="bg-blue-600 text-white px-4 py-2 rounded">
        + Tambah Pertanyaan
    </button>

    <button type="button" wire:click="save" class="bg-green-600 text-white px-4 py-2 rounded">
        Simpan Kuis
    </button>
</div>

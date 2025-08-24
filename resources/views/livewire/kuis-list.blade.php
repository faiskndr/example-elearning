<div class="container mx-auto">
    <div class="flex justify-between py-4">
        <h1 class="text-2xl font-bold mb-4">Daftar Kuis</h1>
        <a href="{{ url('/kuis/form') }}" class="text-white text-center p-2 bg-sky-600">Tambah Kuis</a>
    </div>
    <input type="text" wire:model.debounce.300ms="search" placeholder="Cari kuis..." 
           class="border rounded p-2 mb-4 w-full" />

    <table class="w-full table-auto border-collapse border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Nama Kuis</th>
                <th class="border px-4 py-2">Tanggal Dibuat</th>
                <th class="border px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quizzes as $index => $quiz)
                <tr>
                    <td class="border px-4 py-2">{{ $quizzes->firstItem() + $index }}</td>
                    <td class="border px-4 py-2">{{ $quiz->nama }}</td>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($quiz->dibuat_pada)->format('d M Y') }}</td>
                    <td class="border px-4 py-2">
                        <button wire:click="editKuis({{ $quiz->kuis_id }})">edit</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center py-4">Tidak ada kuis ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $quizzes->links() }}
    </div>
</div>

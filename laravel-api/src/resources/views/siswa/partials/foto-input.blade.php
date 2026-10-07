@php $fotoAwal = $fotoUrlAwal ?? null; @endphp

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
        Foto Siswa <span class="text-gray-400 font-normal">(opsional)</span>
    </label>
    <div class="flex items-center gap-4">
        <div id="fotoPreviewWrap" class="w-20 h-20 rounded-2xl bg-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center shrink-0">
            <img id="fotoPreview" src="{{ $fotoAwal }}" class="w-full h-full object-cover {{ $fotoAwal ? '' : 'hidden' }}" alt="Preview foto">
            <svg id="fotoPlaceholder" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-300 {{ $fotoAwal ? 'hidden' : '' }}">
                <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <input type="file" name="foto" id="fotoInput" accept="image/*" class="hidden" onchange="previewFoto(this)">
            <div class="flex flex-wrap gap-2">
                <label for="fotoInput" class="cursor-pointer inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                        <path fill-rule="evenodd" d="M9.25 13.25a.75.75 0 001.5 0V4.636l2.955 3.129a.75.75 0 001.09-1.03l-4.25-4.5a.75.75 0 00-1.09 0l-4.25 4.5a.75.75 0 101.09 1.03L9.25 4.636v8.614z" clip-rule="evenodd"/>
                        <path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z"/>
                    </svg>
                    Pilih File
                </label>
                <button type="button" onclick="openCamera()" id="cameraBtn"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5">
                        <path d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C3.099 7.57 2.25 8.501 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.073-.849-2.005-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                        <circle cx="12" cy="13" r="3.5"/>
                    </svg>
                    Ambil dari Kamera
                </button>
            </div>
            @error('foto') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            <p class="text-gray-400 text-xs mt-1.5">JPG/PNG, maks 2MB. Tampil di kartu RFID &amp; daftar siswa.</p>
            @if($allowHapus ?? false)
            <label class="flex items-center gap-2 mt-2 cursor-pointer select-none">
                <input type="checkbox" name="hapus_foto" value="1" id="hapusFotoCheck" onchange="if(this.checked){document.getElementById('fotoInput').value='';previewFoto(null,true);}"
                       class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                <span class="text-xs text-red-600 font-medium">Hapus foto saat ini</span>
            </label>
            @endif
        </div>
    </div>
</div>

{{-- Modal Ambil Foto dari Kamera --}}
<div id="cameraModal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm font-semibold text-gray-700">Ambil Foto</p>
            <button type="button" onclick="closeCamera()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L10 8.94l3.47-3.47a.75.75 0 111.06 1.06L11.06 10l3.47 3.47a.75.75 0 11-1.06 1.06L10 11.06l-3.47 3.47a.75.75 0 01-1.06-1.06L8.94 10 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
        <div class="relative bg-black" style="aspect-ratio:1/1;">
            <video id="cameraVideo" autoplay playsinline muted class="w-full h-full object-cover"></video>
            <canvas id="cameraCanvas" class="hidden"></canvas>
            <p id="cameraError" class="hidden absolute inset-0 flex items-center justify-center text-center text-red-300 text-xs p-6 bg-black/60"></p>
        </div>
        <div class="p-4 flex gap-3">
            <button type="button" onclick="closeCamera()" class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button type="button" onclick="capturePhoto()" id="captureBtn" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors">
                Ambil Foto
            </button>
        </div>
    </div>
</div>

<script>
let _cameraStream = null;

function previewFoto(input, cleared = false) {
    const preview     = document.getElementById('fotoPreview');
    const placeholder = document.getElementById('fotoPlaceholder');
    if (cleared) {
        preview.src = '';
        preview.classList.add('hidden');
        placeholder.classList.remove('hidden');
        return;
    }
    if (input.files && input.files[0]) {
        document.getElementById('hapusFotoCheck')?.removeAttribute('checked');
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

async function openCamera() {
    const modal      = document.getElementById('cameraModal');
    const video       = document.getElementById('cameraVideo');
    const errorEl     = document.getElementById('cameraError');
    const captureBtn  = document.getElementById('captureBtn');

    modal.classList.replace('hidden', 'flex');
    document.body.style.overflow = 'hidden';
    errorEl.classList.add('hidden');
    video.classList.remove('hidden');
    captureBtn.disabled = false;
    captureBtn.classList.remove('opacity-50');

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        errorEl.textContent = 'Kamera tidak didukung di browser ini. Pastikan halaman diakses lewat HTTPS atau localhost.';
        errorEl.classList.remove('hidden');
        video.classList.add('hidden');
        captureBtn.disabled = true;
        captureBtn.classList.add('opacity-50');
        return;
    }

    try {
        _cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
        video.srcObject = _cameraStream;
    } catch (e) {
        errorEl.textContent = 'Tidak bisa mengakses kamera: ' + (e.message || 'izin ditolak.');
        errorEl.classList.remove('hidden');
        video.classList.add('hidden');
        captureBtn.disabled = true;
        captureBtn.classList.add('opacity-50');
    }
}

function closeCamera() {
    const modal = document.getElementById('cameraModal');
    modal.classList.replace('flex', 'hidden');
    document.body.style.overflow = '';
    if (_cameraStream) {
        _cameraStream.getTracks().forEach(t => t.stop());
        _cameraStream = null;
    }
}

function capturePhoto() {
    const video  = document.getElementById('cameraVideo');
    const canvas = document.getElementById('cameraCanvas');
    if (!video.videoWidth) return;

    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);

    canvas.toBlob(blob => {
        const file = new File([blob], 'foto-kamera-' + Date.now() + '.jpg', { type: 'image/jpeg' });
        const dt   = new DataTransfer();
        dt.items.add(file);

        const input = document.getElementById('fotoInput');
        input.files = dt.files;
        previewFoto(input);
        closeCamera();
    }, 'image/jpeg', 0.9);
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && !document.getElementById('cameraModal').classList.contains('hidden')) {
        closeCamera();
    }
});
</script>

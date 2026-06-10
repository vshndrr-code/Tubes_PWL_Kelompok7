@extends('layouts.app')

@push('head')
<style>
    @keyframes soft-enter {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @media (prefers-reduced-motion: no-preference) {
        .ui-reveal { animation: soft-enter .42s ease-out both; }
        .ui-button {
            transition: transform .18s ease, box-shadow .18s ease,
                        border-color .18s ease, background-color .18s ease, color .18s ease;
        }
        .ui-button:hover { transform: translateY(-2px); }
    }

    .color-swatch {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 3px solid transparent;
        cursor: pointer;
        transition: transform 0.15s, border-color 0.15s;
        outline: none;
    }
    .color-swatch:hover { transform: scale(1.2); }
    .color-swatch.selected { border-color: #1e293b; transform: scale(1.2); }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#f6f7f9] text-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">

        {{-- Header --}}
        <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Edit Label</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Edit Tag</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Perbarui nama atau warna tag "{{ $tag->name }}".
                </p>
            </div>
            <a href="{{ route('tags.index') }}"
                class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2 focus:ring-offset-[#f6f7f9]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5" />
                    <path d="m12 19-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
            {{-- Form --}}
            <form action="{{ route('tags.update', $tag) }}" method="POST"
                class="ui-reveal rounded-lg border border-slate-200 bg-white shadow-sm">
                @csrf
                @method('PUT')

                <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Form Tag</p>
                    <h2 class="mt-1 text-lg font-semibold text-slate-950">Perbarui detail tag</h2>
                </div>

                <div class="space-y-6 p-5 sm:p-6">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="text-sm font-semibold text-slate-700">Nama Tag <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $tag->name) }}"
                            class="mt-2 h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('name') border-red-400 ring-red-100 @enderror"
                            required placeholder="Nama tag">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Color Picker --}}
                    <div>
                        <label for="color" class="text-sm font-semibold text-slate-700">Warna Tag</label>
                        <p class="mt-1 text-xs text-slate-500">Pilih warna dari palet, atau klik lingkaran untuk membuka color picker.</p>

                        @php
                            $palette = [
                                '#EF4444', '#F97316', '#EAB308', '#22C55E',
                                '#10B981', '#06B6D4', '#3B82F6', '#6366F1',
                                '#8B5CF6', '#A855F7', '#EC4899', '#64748B',
                                '#0F172A', '#78716C', '#D97706', '#059669',
                            ];
                            $currentColor = old('color', $tag->color ?? '#6366F1');
                        @endphp

                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($palette as $hex)
                                <button type="button"
                                    class="color-swatch {{ $currentColor === $hex ? 'selected' : '' }}"
                                    style="background-color: {{ $hex }}"
                                    data-color="{{ $hex }}"
                                    onclick="selectColor('{{ $hex }}')"
                                    title="{{ $hex }}"></button>
                            @endforeach
                        </div>

                        <div class="mt-3 flex items-center gap-3">
                            <div class="group relative h-9 w-9 cursor-pointer" title="Klik untuk pilih warna">
                                <input type="color" id="color-picker"
                                    value="{{ $currentColor }}"
                                    class="absolute inset-0 h-full w-full cursor-pointer rounded-full border border-transparent opacity-0"
                                    oninput="onColorPickerInput(this.value)">
                                <div id="color-preview"
                                    class="pointer-events-none h-9 w-9 rounded-full border-2 border-slate-200 shadow-sm transition"
                                    style="background-color: {{ $currentColor }}"></div>
                            </div>
                            <input type="text" name="color" id="color"
                                value="{{ $currentColor }}"
                                class="h-10 w-36 rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-mono text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('color') border-red-400 @enderror"
                                placeholder="#6366F1" maxlength="7"
                                oninput="onColorInput(this.value)">
                            <span class="text-xs text-slate-500">Klik lingkaran atau ketik kode hex</span>
                        </div>
                        @error('color')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                    <a href="{{ route('tags.index') }}"
                        class="ui-button inline-flex h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
                        Batal
                    </a>
                    <button type="submit"
                        class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-slate-950 px-5 text-sm font-semibold text-white shadow-sm shadow-slate-900/10 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400">
                        Perbarui Tag
                    </button>
                </div>
            </form>

            {{-- Preview Sidebar --}}
            <aside class="space-y-5">
                <section class="ui-reveal overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="bg-slate-950 p-5 text-white">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Tag Saat Ini</p>
                        <h3 class="mt-2 text-lg font-semibold">{{ $tag->name }}</h3>
                        <p class="mt-1 text-sm text-slate-400">Digunakan di <strong class="text-white">{{ $tag->transactions_count ?? 0 }}</strong> transaksi</p>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400 mb-2">Badge</p>
                            <span id="preview-badge"
                                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold text-white"
                                style="background-color: {{ $currentColor }}">
                                #<span id="preview-name">{{ $tag->name }}</span>
                            </span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400 mb-2">Chip</p>
                            <span id="preview-chip"
                                class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-semibold text-white"
                                style="background-color: {{ $currentColor }}">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.707 9.293l-7-7A1 1 0 0010 2H4a2 2 0 00-2 2v6a1 1 0 00.293.707l7 7a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" clip-rule="evenodd" />
                                </svg>
                                <span id="preview-chip-name">{{ $tag->name }}</span>
                            </span>
                        </div>

                        <div class="rounded-lg bg-slate-50 px-3 py-2 ring-1 ring-slate-200 border-t border-slate-100">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Warna Aktif</p>
                            <div class="mt-1.5 flex items-center gap-2">
                                <span id="color-dot" class="h-4 w-4 rounded-full shadow-sm ring-1 ring-slate-200"
                                    style="background-color: {{ $currentColor }}"></span>
                                <span id="color-hex-label" class="font-mono text-xs text-slate-600">{{ $currentColor }}</span>
                            </div>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>

<script>
    const colorInput   = document.getElementById('color');
    const colorPicker  = document.getElementById('color-picker');
    const colorPreview = document.getElementById('color-preview');
    const previewBadge = document.getElementById('preview-badge');
    const previewChip  = document.getElementById('preview-chip');
    const previewName  = document.getElementById('preview-name');
    const previewChipName = document.getElementById('preview-chip-name');
    const nameInput    = document.getElementById('name');
    const colorDot     = document.getElementById('color-dot');
    const colorHexLabel = document.getElementById('color-hex-label');

    function isValidHex(hex) {
        return /^#[0-9A-Fa-f]{6}$/.test(hex);
    }

    function selectColor(hex) {
        colorInput.value = hex;
        colorPicker.value = hex;
        updateColorUI(hex);
        document.querySelectorAll('.color-swatch').forEach(s => {
            s.classList.toggle('selected', s.dataset.color === hex);
        });
    }

    function onColorInput(val) {
        if (isValidHex(val)) {
            colorPicker.value = val;
            updateColorUI(val);
            document.querySelectorAll('.color-swatch').forEach(s => {
                s.classList.toggle('selected', s.dataset.color === val);
            });
        }
    }

    function onColorPickerInput(val) {
        colorInput.value = val;
        updateColorUI(val);
        document.querySelectorAll('.color-swatch').forEach(s => {
            s.classList.toggle('selected', s.dataset.color === val);
        });
    }

    function updateColorUI(hex) {
        colorPreview.style.backgroundColor = hex;
        previewBadge.style.backgroundColor = hex;
        previewChip.style.backgroundColor  = hex;
        if (colorDot) colorDot.style.backgroundColor = hex;
        if (colorHexLabel) colorHexLabel.textContent = hex;
    }

    nameInput.addEventListener('input', function () {
        const val = this.value.trim() || 'nama-tag';
        previewName.textContent = val;
        previewChipName.textContent = val;
    });

    // Initialize
    const initColor = colorInput.value || '#6366F1';
    updateColorUI(initColor);
    document.querySelectorAll('.color-swatch').forEach(s => {
        s.classList.toggle('selected', s.dataset.color === initColor);
    });
</script>
@endsection

$file = "d:\tubespwl\Tubes_PWL_Kelompok7\resources\views\savings_goals\index.blade.php"
$content = Get-Content $file -Raw

# Replace the broken dropdown container
$oldBlock = @'
                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <select x-model="status"
                                        class="h-10 rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                                        <option value="all">Semua status</option>
                                        <option value="active">Aktif</option>
                                        <option value="completed">Selesai</option>
                                        <option value="cancelled">Dibatalkan</option>
                                    </select>

                                    <select x-model="accountId"
                                        class="h-10 rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                                        <option value="all">Semua akun</option>
'@

$newBlock = @'
                                <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:flex-wrap">
                                    <select x-model="status"
                                        class="h-10 w-full min-w-[150px] shrink-0 rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                                        <option value="all">Semua status</option>
                                        <option value="active">Aktif</option>
                                        <option value="completed">Selesai</option>
                                        <option value="cancelled">Dibatalkan</option>
                                    </select>

                                    <select x-model="accountId"
                                        class="h-10 w-full min-w-[150px] shrink-0 rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                                        <option value="all">Semua akun</option>
'@

if ($content.Contains($oldBlock)) {
    $content = $content.Replace($oldBlock, $newBlock)
    Set-Content -Path $file -Value $content -NoNewline
    Write-Host "SUCCESS: Dropdown container diperbaiki"
} else {
    Write-Host "ERROR: Old block not found"
}

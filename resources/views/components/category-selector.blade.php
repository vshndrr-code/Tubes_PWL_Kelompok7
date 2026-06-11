@props(['categories', 'selectedCategoryId' => null, 'name' => 'category_id', 'allowNull' => false])

<div x-data="categorySelectorData()" class="w-full">
    <!-- Hidden input for form -->
    <input type="hidden" name="{{ $name }}" x-model="selectedId" @change="updateSelectedCategory()">

    <!-- Button to open modal -->
    <button type="button" @click="open = true" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 transition-colors text-left bg-white hover:bg-gray-50">
        <span x-show="selectedId || allowNull" x-text="selectedCategoryName" class="text-gray-900"></span>
        <span x-show="!selectedId && !allowNull" class="text-gray-500">Pilih Kategori</span>
    </button>

    <!-- Modal -->
    <div x-show="open" class="fixed inset-0 bg-black/50 z-50 flex items-end sm:items-center justify-center" @click.self="open = false">
        <div class="bg-white w-full sm:w-[500px] sm:rounded-2xl rounded-t-3xl shadow-xl overflow-hidden" @click.stop>
            <!-- Header -->
            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Select category</h3>
                <button type="button" @click="open = false" class="text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Search -->
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="relative">
                    <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" x-model="search" placeholder="Search" class="w-full pl-10 pr-4 py-2.5 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 transition placeholder-gray-500">
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex border-b border-gray-200 px-4 sm:px-6">
                <button type="button" @click="activeTab = 'expense'" :class="activeTab === 'expense' ? 'text-green-600 border-b-2 border-green-600' : 'text-gray-500'" class="py-3 px-4 font-medium transition">
                    EXPENSE
                </button>
                <button type="button" @click="activeTab = 'income'" :class="activeTab === 'income' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500'" class="py-3 px-4 font-medium transition">
                    INCOME
                </button>
            </div>

            <!-- Categories List -->
            <div class="overflow-y-auto max-h-[50vh] sm:max-h-[60vh]">
                <div x-show="allowNull">
                    <button type="button" @click="selectCategory({id: '', name: 'Tanpa Kategori'})" :class="selectedId === '' || selectedId === null ? 'bg-green-50 border-l-4 border-green-600' : 'hover:bg-gray-50'" class="w-full flex items-center gap-3 px-4 sm:px-6 py-3.5 border-b border-gray-100 transition text-left">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0 text-slate-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-slate-900 font-medium">Tanpa Kategori (Semua Kategori)</p>
                        </div>
                        <div x-show="selectedId === '' || selectedId === null" class="text-green-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </div>

                <template x-for="category in filteredCategories" :key="category.id">
                    <button type="button" @click="selectCategory(category)" :class="selectedId == category.id ? 'bg-green-50 border-l-4 border-green-600' : 'hover:bg-gray-50'" class="w-full flex items-center gap-3 px-4 sm:px-6 py-3.5 border-b border-gray-100 transition text-left">
                        <!-- Icon -->
                        <div :style="{ backgroundColor: category.color || (category.type === 'income' ? '#10b981' : '#ef4444') }" class="w-12 h-12 sm:w-14 sm:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                            <template x-if="category.icon">
                                <i :class="'fa-solid fa-' + category.icon + ' text-white text-lg sm:text-xl'"></i>
                            </template>
                            <template x-if="!category.icon">
                                <span class="text-lg sm:text-2xl" x-text="getCategoryEmoji(category.name)"></span>
                            </template>
                        </div>
                        <!-- Category Name -->
                        <div class="flex-1">
                            <p x-text="category.name" class="text-gray-900 font-medium"></p>
                        </div>
                        <!-- Checkmark -->
                        <div x-show="selectedId == category.id" class="text-green-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </template>

                <!-- Empty State -->
                <div x-show="filteredCategories.length === 0" class="p-8 text-center text-gray-500">
                    <p>No categories found</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-4 sm:p-6 border-t border-gray-200 bg-gray-50 flex gap-3">
                <button type="button" @click="open = false" class="flex-1 px-4 py-2.5 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 transition font-medium">
                    Cancel
                </button>
                <button type="button" @click="confirmSelection()" class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium" x-show="selectedId">
                    Select
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function categorySelectorData() {
    return {
        open: false,
        activeTab: 'expense',
        search: '',
        selectedId: @json($selectedCategoryId),
        selectedCategoryName: 'Pilih Kategori',
        categories: @json($categories),
        allowNull: @json($allowNull),

        get filteredCategories() {
            return this.categories.filter(cat => {
                const matchesType = cat.type === this.activeTab;
                const matchesSearch = cat.name.toLowerCase().includes(this.search.toLowerCase());
                return matchesType && matchesSearch;
            });
        },

        getCategoryEmoji(categoryName) {
            const emojiMap = {
                'Salary': '💰',
                'Other Income': '🎁',
                'Incoming transfer': '➡️',
                'Collect Interest': '%',
                'Makan & Minuman': '🍽️',
                'Kopi & Snack': '☕',
                'Restoran': '🍗',
                'Transportasi': '🚗',
                'Bensin': '⛽',
                'Taksi & Ojek': '🚕',
                'Belanja Barang': '🛍️',
                'Pakaian & Fashion': '👔',
                'Elektronik': '💻',
                'Listrik & Air': '⚡',
                'Internet & Telepon': '📡',
                'Sewa Rumah': '🏠',
                'Hiburan & Film': '🎬',
                'Gaming': '🎮',
                'Buku & Musik': '📚',
                'Kesehatan': '❤️',
                'Gym & Olahraga': '🏋️',
                'Obat & Vitamin': '💊',
                'Pendidikan': '🎓',
                'Kursus & Workshop': '👨‍🏫',
                'Asuransi': '🛡️',
                'Cicilan': '💳',
                'Hadiah & Ucapan': '🎀',
                'Donasi & Zakat': '🤝',
                'Tabungan': '🐷',
                'Lain-lain': '•••',
            };
            return emojiMap[categoryName] || '💰';
        },

        selectCategory(category) {
            this.selectedId = category.id;
            this.selectedCategoryName = category.name;
            this.open = false;
        },

        confirmSelection() {
            this.open = false;
        },

        updateSelectedCategory() {
            const cat = this.categories.find(c => c.id == this.selectedId);
            if (cat) {
                this.selectedCategoryName = cat.name;
            } else if (this.allowNull && (this.selectedId === '' || this.selectedId === null)) {
                this.selectedCategoryName = 'Tanpa Kategori';
            } else {
                this.selectedCategoryName = 'Pilih Kategori';
            }
        },

        init() {
            if (this.selectedId) {
                this.updateSelectedCategory();
            } else if (this.allowNull) {
                this.selectedCategoryName = 'Tanpa Kategori';
            }
        }
    }
}
</script>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">📝 New Admission Application</h2>
            <a href="{{ route('admission.admin') }}" class="text-sm text-gray-500 font-bold">View applications →</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))<div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>@endif
            @if($errors->any())<div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg text-sm"><ul class="list-disc ml-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

            <form action="{{ route('admission.apply.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-6">
                @csrf

                <div>
                    <h3 class="font-bold text-gray-700 border-b pb-2 mb-4">Applicant</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Full Name *</label><input name="full_name" value="{{ old('full_name') }}" class="w-full border-gray-300 rounded-md" required></div>
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Gender *</label>
                            <select name="gender" class="w-full border-gray-300 rounded-md" required><option value="">Select</option><option>Male</option><option>Female</option></select>
                        </div>
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Address *</label><input name="address" value="{{ old('address') }}" class="w-full border-gray-300 rounded-md" required></div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Date of Birth *</label>
                            <input type="date" name="date_of_birth" id="dob" value="{{ old('date_of_birth') }}" class="w-full border-gray-300 rounded-md" required>
                        </div>
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Age</label><input id="age" readonly class="w-full border-gray-200 bg-gray-50 rounded-md" placeholder="auto from DOB"></div>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-gray-700 border-b pb-2 mb-4">Placement</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Section *</label>
                            <select name="section" id="section" class="w-full border-gray-300 rounded-md" required>
                                <option value="">Select section…</option>
                                @foreach($sections as $s)<option value="{{ $s }}" {{ old('section')===$s ? 'selected' : '' }}>{{ $s }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Class *</label>
                            <select name="desired_class" id="desired_class" class="w-full border-gray-300 rounded-md" required>
                                <option value="">Select section first…</option>
                                @foreach($classes as $c)<option value="{{ $c->name }}" data-section="{{ $c->section }}">{{ $c->name }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-gray-700 border-b pb-2 mb-4">Parent / Guardian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Name *</label><input name="parent_name" value="{{ old('parent_name') }}" class="w-full border-gray-300 rounded-md" required></div>
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Phone *</label><input name="parent_phone" value="{{ old('parent_phone') }}" class="w-full border-gray-300 rounded-md" required></div>
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email *</label><input type="email" name="parent_email" value="{{ old('parent_email') }}" class="w-full border-gray-300 rounded-md" required></div>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-gray-700 border-b pb-2 mb-4">Documents</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Passport Photo *</label><input type="file" name="passport" accept="image/*" required></div>
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Birth Certificate * (all applicants)</label><input type="file" name="birth_certificate" required></div>
                        <div id="fslcWrap"><label class="block text-xs font-bold text-gray-500 uppercase mb-1">First School Leaving Cert. <span class="text-gray-400">(secondary)</span></label><input type="file" name="fslc"></div>
                        <div id="jwaecWrap"><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Junior WAEC <span class="text-gray-400">(senior secondary)</span></label><input type="file" name="junior_waec"></div>
                        <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Indigene Letter <span class="text-gray-400">(optional)</span></label><input type="file" name="indigene_letter"></div>
                    </div>
                </div>

                <div class="flex justify-end pt-3 border-t">
                    <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700">Submit Application</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const section = document.getElementById('section');
        const classSel = document.getElementById('desired_class');
        const opts = [...classSel.options];
        function syncClasses() {
            const sec = section.value;
            classSel.value = '';
            opts.forEach(o => { if (o.value) o.hidden = sec && o.dataset.section !== sec; });
            document.getElementById('fslcWrap').style.opacity = (sec && sec !== 'Primary') ? 1 : 0.5;
            document.getElementById('jwaecWrap').style.opacity = (sec === 'Senior Secondary') ? 1 : 0.5;
        }
        section.addEventListener('change', syncClasses);
        const dob = document.getElementById('dob');
        dob.addEventListener('change', function () {
            if (!this.value) return;
            const d = new Date(this.value), n = new Date();
            let a = n.getFullYear() - d.getFullYear();
            if (n < new Date(n.getFullYear(), d.getMonth(), d.getDate())) a--;
            document.getElementById('age').value = a >= 0 ? a + ' years' : '';
        });
        syncClasses();
    </script>
</x-app-layout>

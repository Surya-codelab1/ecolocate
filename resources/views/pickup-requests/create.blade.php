<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Pickup | EcoLocate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .app-bg { background: linear-gradient(135deg, #ecfdf5 0%, #f0fdfa 45%, #e0f2fe 100%); }
    </style>
</head>
<body class="app-bg min-h-screen">

    <div class="max-w-lg mx-auto py-10 px-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-1">Request a Pickup</h2>
            <p class="text-sm text-gray-500 mb-5">at <strong>{{ $facility->facility_name }}</strong></p>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-5">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('pickup-requests.store', $facility->id) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Device to Recycle</label>
                    <select name="device_id"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                        <option value="">Select device</option>
                        @foreach($devices as $device)
                            <option value="{{ $device->id }}" @selected(old('device_id') == $device->id)>
                                {{ $device->brand }} {{ $device->model_name }} ({{ $device->category }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Pickup Address</label>
                    <textarea name="pickup_address" rows="2"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none"
                        placeholder="Your full address">{{ old('pickup_address') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1.5">Preferred Date</label>
                        <input type="date" name="preferred_date" value="{{ old('preferred_date') }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1.5">Preferred Time</label>
                        <input type="text" name="preferred_time" value="{{ old('preferred_time') }}" placeholder="e.g. 10 AM - 12 PM"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Additional Note (optional)</label>
                    <textarea name="additional_note" rows="2"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none">{{ old('additional_note') }}</textarea>
                </div>

                <button type="submit"
                    class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-3 rounded-xl transition">
                    Submit Pickup Request
                </button>
            </form>
        </div>
    </div>

</body>
</html>
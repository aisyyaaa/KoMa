<form method="POST" action="{{ route('platform.verifications.verify') }}">
    @csrf
    <input type="hidden" name="seller_id" value="{{ $seller->id }}">
    <div class="flex space-x-2">
        <button name="action" value="accept" class="px-3 py-2 bg-green-600 text-white rounded">Accept</button>
        <button name="action" value="reject" class="px-3 py-2 bg-red-600 text-white rounded">Reject</button>
    </div>
    <div class="mt-2">
        <label class="block text-sm">Reason (optional)</label>
        <textarea name="reason" class="w-full border rounded p-2"></textarea>
    </div>
</form>

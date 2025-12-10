<a href="{{ route('platform.sellers.show', $seller) }}" class="text-blue-600 mr-2">View</a>
<form action="{{ route('platform.sellers.destroy', $seller) }}" method="POST" style="display:inline">
    @csrf @method('DELETE')
    <button class="text-red-600" onclick="return confirm('Delete seller?')">Delete</button>
</form>

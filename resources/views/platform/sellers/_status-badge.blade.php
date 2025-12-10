@if(strtoupper($status) === 'ACTIVE')
    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">Active</span>
@elseif(strtoupper($status) === 'PENDING')
    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">Pending</span>
@else
    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-sm">Rejected</span>
@endif

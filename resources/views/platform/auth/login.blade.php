@extends('layouts.platform')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
	<h2 class="text-2xl font-semibold mb-4">Platform Login</h2>

	@if($errors->any())
		<div class="mb-4 text-sm text-red-600">
			{{ $errors->first() }}
		</div>
	@endif

	<form method="POST" action="{{ route('platform.auth.login.post') }}">
		@csrf
		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Email</label>
			<input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
		</div>
		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Password</label>
			<input type="password" name="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
		</div>
		<div class="flex items-center justify-between">
			<button type="submit" class="px-4 py-2 bg-koma-primary text-white rounded">Log in</button>
			<a href="{{ route('landingpage.index') }}" class="text-sm text-gray-600">Back to site</a>
		</div>
	</form>
</div>
@endsection

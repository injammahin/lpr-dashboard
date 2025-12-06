<!-- resources/views/components/auth/session-status.blade.php -->
@include('auth-session-status')

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
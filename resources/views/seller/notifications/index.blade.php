@extends('seller.layouts.app')

@section('title', 'Notifications - Seller')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="mb-1">Notifications</h2>
            <p class="text-muted mb-0">Updates about admin approvals and request decisions.</p>
        </div>
        @if(($sellerUnreadNotificationsCount ?? 0) > 0)
            <form action="{{ route('seller.notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-check2-all"></i> Mark all as read
                </button>
            </form>
        @endif
    </div>

    <div class="card">
        <div class="card-body p-0">
            @if($notifications->count() > 0)
                <ul class="list-group list-group-flush">
                    @foreach($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $title = $data['title'] ?? 'Notification';
                            $message = $data['message'] ?? '';
                            $actionUrl = $data['action_url'] ?? null;
                            $actionLabel = $data['action_label'] ?? 'View details';
                        @endphp
                        <li class="list-group-item {{ is_null($notification->read_at) ? 'list-group-item-light' : '' }}">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="fw-semibold">{{ $title }}</div>
                                    @if($message)
                                        <div class="text-muted small mt-1">{{ $message }}</div>
                                    @endif
                                    <div class="text-muted small mt-2">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="text-end">
                                    @if(is_null($notification->read_at))
                                        <span class="badge bg-danger-subtle text-danger">New</span>
                                    @endif
                                    @if($actionUrl)
                                        <div class="mt-2">
                                            <a href="{{ route('seller.notifications.open', $notification->id) }}" class="btn btn-sm btn-outline-secondary">{{ $actionLabel }}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-bell" style="font-size: 1.5rem;"></i>
                    <div class="mt-2">No notifications yet.</div>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</div>
@endsection

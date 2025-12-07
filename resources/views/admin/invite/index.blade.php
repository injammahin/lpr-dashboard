{{-- resources/views/admin/invite/index.blade.php --}}
<x-admin-layout>

    <style>
        /* --- Custom Stunning CSS for Invite Page --- */

        .invite-card {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
            border: 1px solid #e5e7eb;
            transition: 0.3s ease-in-out;
        }

        .invite-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 35px rgba(0, 0, 0, 0.12);
        }

        .invite-title {
            font-size: 30px;
            font-weight: 700;
            color: #1f2937;
            letter-spacing: -0.5px;
            margin-bottom: 25px;
        }

        .invite-input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            font-size: 15px;
            transition: 0.2s;
        }

        .invite-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
            outline: none;
        }

        .invite-btn {
            padding: 12px 25px;
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.25s ease-in-out;
            box-shadow: 0 4px 10px rgba(29, 78, 216, 0.25);
        }

        .invite-btn:hover {
            background: linear-gradient(90deg, #1d4ed8, #1e40af);
            transform: scale(1.03);
        }

        .invite-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .invite-table thead th {
            text-align: left;
            padding: 12px;
            font-size: 14px;
            color: #6b7280;
            font-weight: 600;
        }

        .invite-row {
            background: white;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.06);
            border-radius: 12px;
            transition: 0.25s ease;
        }

        .invite-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .invite-row td {
            padding: 15px;
            font-size: 15px;
            color: #374151;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-accepted {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
    </style>


    <!-- CONTENT WRAPPER -->
    <div class="invite-card">

        <h1 class="invite-title">✉️ Invite Users</h1>

        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-800 border border-green-300 rounded-lg mb-4 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Invite Form -->
        <form method="POST" action="{{ route('admin.invite.send') }}" class="flex gap-3 mb-6">
            @csrf

            <input type="email" name="email" class="invite-input" placeholder="Enter email to invite">

            <button class="invite-btn">
                Send Invite
            </button>
        </form>

        <!-- Invite Table -->
        <table class="invite-table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Invited On</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($invites as $row)
                    <tr class="invite-row">
                        <td>{{ $row->email }}</td>

                        <td>
                            <span class="status-badge {{ $row->accepted ? 'status-accepted' : 'status-pending' }}">
                                {{ $row->accepted ? 'Accepted' : 'Pending' }}
                            </span>
                        </td>

                        <td>{{ $row->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</x-admin-layout>
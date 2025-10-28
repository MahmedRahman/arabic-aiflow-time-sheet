@extends('layouts.app')

@section('title', 'إدارة العملاء')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users me-2"></i>إدارة العملاء</h2>
    <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إضافة عميل جديد
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($clients->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الهاتف</th>
                            <th>الشركة</th>
                            <th>عدد المشاريع</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-primary text-white rounded-circle me-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        {{ substr($client->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $client->name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->phone ?? 'غير محدد' }}</td>
                            <td>{{ $client->company ?? 'غير محدد' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $client->projects_count ?? 0 }}</span>
                            </td>
                            <td>{{ $client->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العميل؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $clients->links() }}
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h4>لا توجد عملاء حالياً</h4>
                <p>ابدأ بإضافة عميل جديد</p>
                <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>إضافة عميل جديد
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.base')

@section('title', 'Сообщения')

@section('content')
    <div class="container">
        <h1 class="mb-4">Сообщения</h1>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Email</th>
                <th>Имя</th>
                <th>Сообщение</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($messages as $message)
                <tr>
                    <td>{{ $message->email ?? 'Без email' }}</td>
                    <td>{{ $message->name ?? 'Анонимно' }}</td>
                    <td>{{ $message->message ?? '—' }}</td>
                    <td>{{ $message->created_at ?? '—' }}</td>
                    <td>
                        <a href="{{"https://e.mail.ru/compose/?to={$message->email}"}}" target="_blank" rel="noopener noreferrer" class="btn btn-success btn-sm">
                            <i class="bi bi-pencil"></i> Ответить
                        </a>
                        <form action="{{ route('messages.destroy', $message->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить эту продажу?');">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@extends('app')

@section('content')

<form action="{{ route('abonnements.store', $forfait->id) }}" method="POST">
    @csrf

    <button type="submit" class="btn btn-success">
        S'abonner
    </button>
</form>

            <a href="{{ route('paydunya.payment', ['id_forfait' => $forfait->id_forfait]) }}" class="btn btn-success">
                S'abonner à ce forfait
            </a>

@endsection
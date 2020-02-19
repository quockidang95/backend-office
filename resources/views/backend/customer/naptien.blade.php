<p>{{$user->name}}</p>

<form action="{{URL::to('/submitnaptien/' . $user->id)}}" method="post">
    @csrf
    <input type="number" name="tien">

    <button type="submit">Nap tien</button>
</form>

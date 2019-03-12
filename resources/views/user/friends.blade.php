@extends('layouts.app')

@section('content')
    <div class="container friends-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Invitations</h4>
                    </div>
                    <div class="card-body py-4">
                        @if(sizeof($invites) < 1)
                            <p>You have no invites...</p>
                        @endif
                        @foreach($invites as $user)
                            <div class="card card-friend">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {{ $user->name }}
                                        </div>
                                        <div class="col-md-8">
                                            <button class="btn btn-primary accept-friend float-right" data-user-id="{{$user->id}}">
                                                Accept Invite
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Your Friends</h4>
                    </div>
                    <div class="card-body">
                        @if(sizeof($friends) < 1)
                            <p>You have no friends...</p>
                        @endif
                        @foreach($friends as $user)
                            <div class="card card-friend">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{ $user->name }}
                                        </div>
                                        <div class="col-md-6">
                                            <a class="btn btn-primary float-right" href="{{ url('/profile/'.$user->id) }}">
                                                View Profile
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Find new friends</h4>
                    </div>
                    <div class="card-body">
                        @foreach($nonfriends as $user)
                            <div class="card card-friend">
                                <div class="card-body">
                                    @if(sizeof($nonfriends) < 1)
                                        <p>There are no people of this platform yet</p>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-4">
                                            {{ $user->name }}
                                        </div>
                                        <div class="col-md-8">
                                            <a class="btn btn-primary float-right" href="{{ url('/profile/'.$user->id) }}">
                                                View Profile
                                            </a>
                                            <button class="btn btn-dark add-friend float-right mr-4" data-user-id="{{$user->id}}">
                                                Send Invite
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        jQuery(document).ready(function(){
            $('.add-friend').click(function(e){
                e.preventDefault();
                let friendElement = e.target;
                $(friendElement).prop('disabled',true);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('/user/add_friend') }}",
                    method: 'post',
                    data: {
                        friend_id: $(friendElement).data("user-id"),
                    },
                    success: function(result){
                        console.log(result);
                        $(friendElement).text("Invite sent!");
                    }});
            });
            $('.accept-friend').click(function(e){
                e.preventDefault();
                let friendElement = e.target;
                $(friendElement).prop('disabled',true);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('/user/accept_friend') }}",
                    method: 'post',
                    data: {
                        friend_id: $(friendElement).data("user-id"),
                    },
                    success: function(result){
                        console.log("hi there");
                        $(friendElement).text("Invite Accepted");
                    }});
            });
        });
    </script>
@endsection
